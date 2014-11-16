<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\core;

/**
* Reputation manager
*
* This class consists all common methods for reputations
*/
class reputation_manager
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string The table we use to store our reputations */
	protected $reputations_table;

	/** @var string The database table the reputation types are stored */
	protected $reputation_types_table;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** @var int Reputation identifier */
	private $reputation_id;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth               $auth                   Auth object
	* @param \phpbb\cache\service           $cache                  Cache object
	* @param \phpbb\config\config           $config                 Config object
	* @param \phpbb\db\driver\driver        $db                     Database object
	* @param \phpbb\log\log\                $log                    Log object
	* @param \phpbb\template\template       $template               Template object
	* @param \phpbb\notification\manager    $notification_manager   Notification object
	* @param \phpbb\user                    $user                   User object
	* @param string                         $reputations_table      Name of the table used to store reputations data
	* @param string                         $reputation_types_table Name of the table used to store reputation types data
	* @param string                         $root_path              phpBB root path
	* @param string                         $php_ext                phpEx
	* @return \pico\reputation\core\reputation_manager
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\log\log $log, \phpbb\notification\manager $notification_manager, \phpbb\template\template $template, \phpbb\user $user, $reputations_table, $reputation_types_table, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->log = $log;
		$this->notification_manager = $notification_manager;
		$this->template = $template;
		$this->user = $user;
		$this->reputations_table = $reputations_table;
		$this->reputation_types_table = $reputation_types_table;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Get the reputation types
	*
	* @return array Reputation types
	* @access public
	*/
	public function get_reputation_types()
	{
		$reputation_type_ids = $this->cache->get('reputation_type_ids');

		if ($reputation_type_ids === false)
		{
			$reputation_type_ids = array();

			$sql = 'SELECT *
				FROM ' . $this->reputation_types_table;
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$reputation_type_ids[(int) $row['reputation_type_id']] = (string) $row['reputation_type_name'];
			}
			$this->db->sql_freeresult($result);

			$this->cache->put('reputation_type_ids', $reputation_type_ids);
		}

		return $reputation_type_ids;
	}

	/**
	* Get reputation type id from string
	*
	* @param string $type_string
	* @return int $type_id
	* @access public
	*/
	public function get_reputation_type_id($type_string)
	{
		$types = $this->get_reputation_types();

		$type_id = array_search($type_string, $types);

		if (empty($type_id))
		{
			throw new \pico\reputation\exception\invalid_argument(array('reputation_type', 'INVALID_TYPE'));
		}

		return $type_id;
	}

	/**
	* The main function for recording reputation vote.
	*
	* @param array $data Reputation data
	* @access public
	* @return null
	*/
	public function store_reputation($data)
	{
		$data['reputation_time'] = time();

		$fields = array(
			'user_id_from'			=> 'integer',
			'user_id_to'			=> 'integer',
			'reputation_time'		=> 'integer',
			'reputation_type'		=> 'string',
			'reputation_item_id'	=> 'integer',
			'reputation_points'		=> 'integer',
			'reputation_comment'	=> 'string', 
		);

		foreach ($fields as $field => $type)
		{
			if (!isset($data[$field]))
			{
				throw new \pico\reputation\exception\invalid_argument(array($field, 'FIELD_MISSING'));
			}

			$value = $data[$field];

			settype($value, $type);

			$data[$field] = $value;
		}

		// Get reputation type id
		$data['reputation_type_id'] = $this->get_reputation_type_id($data['reputation_type']);

		// Unset reputation type - it is not stored in DB
		unset($data['reputation_type']);

		$validate_unsigned = array(
			'user_id_from',
			'user_id_to',
			'reputation_time',
			'reputation_type_id',
			'reputation_item_id',
		);

		foreach ($validate_unsigned as $field)
		{
			if ($data[$field] < 0)
			{
				throw new \pico\reputation\exception\out_of_bounds($field);
			}
		}

		// Save reputation vote
		$sql = 'INSERT INTO ' . $this->reputations_table . ' ' . $this->db->sql_build_array('INSERT', $data);
		$this->db->sql_query($sql);

		unset($this->reputation_id);
		$this->reputation_id = $this->db->sql_nextid();

		// Update post reputation
		if ($data['reputation_type_id'] == $this->get_reputation_type_id('post'))
		{
			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation + {$data['reputation_points']}
				WHERE post_id = {$data['reputation_item_id']}";
			$this->db->sql_query($sql);
		}

		// Update user reputation
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = user_reputation + {$data['reputation_points']}
			WHERE user_id = {$data['user_id_to']}";
		$this->db->sql_query($sql);

		// Check max/min user points
		if ($this->config['rs_max_point'] || $this->config['rs_min_point'])
		{
			$this->check_max_min($data['user_id_to']);
		}
	}

	/**
	* Check user reputation
	* 
	* If it is higher than allowed, decrease it to maximum.
	* If it is lower than allowed, increase it to minimum.
	*
	* @param int $user_id User ID
	* @access public
	* @return null
	*/
	private function check_max_min($user_id)
	{
		$sql = 'SELECT SUM(reputation_points) AS points
			FROM ' . $this->reputations_table . '
			WHERE user_id_to = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);
		$points = $this->db->sql_fetchfield('points');
		$this->db->sql_freeresult($result);

		// Maximum user reputation
		if (($points > $this->config['rs_max_point']) && $this->config['rs_max_point'])
		{
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_reputation = {$this->config['rs_max_point']}
				WHERE user_id = $user_id";
			$this->db->sql_query($sql);
		}

		// Minimum user reputation
		if (($points < $this->config['rs_min_point']) && $this->config['rs_min_point'])
		{
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_reputation = {$this->config['rs_min_point']}
				WHERE user_id = $user_id";
			$this->db->sql_query($sql);
		}
	}

	/**
	* Notify user about reputation
	*
	* @param string $notification_type_name Notification type name
	* @param array $data Notification data
	* @access public
	* @return null
	*/
	public function add_notification($notification_type_name, $data)
	{
		$data = array_merge(
			array('reputation_id' => $this->reputation_id),
			$data
		);
		$this->notification_manager->add_notifications($notification_type_name, $data);
	}

	/**
	* Response method for displaying reputation messages
	*
	* @param string $message_lang Message user lang
	* @param array $json_data Json data for ajax request
	* @param string $redirect_link Redirect link
	* @param string $redirect_text Redirect text
	* @param bool $is_ajax Ajax request
	* @access public
	* @return string
	*/
	public function response($message_lang, $json_data, $redirect_link, $redirect_text, $is_ajax = false)
	{
		$redirect = $redirect_link;

		meta_refresh(3, $redirect);

		$message = $message_lang;

		if ($is_ajax)
		{
			$json_response = new \phpbb\json_response();
			$json_response->send($json_data);
		}

		$message .= '<br /><br />' . $this->user->lang($redirect_text, '<a href="' . $redirect . '">', '</a>');
		trigger_error($message);
	}

	/**
	* Return post reputation
	*
	* @param int $post_id Post ID
	* @access public
	* @return int post reputation
	*/
	public function get_post_reputation($post_id)
	{
		$sql = 'SELECT post_reputation
			FROM ' . POSTS_TABLE . "
			WHERE post_id = $post_id";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row['post_reputation'];
	}

	/**
	* Return user reputation
	*
	* @param int $user_id User ID
	* @access public
	* @return int user reputation
	*/
	public function get_user_reputation($user_id)
	{
		$sql = 'SELECT user_reputation
			FROM ' . USERS_TABLE . "
			WHERE user_id = $user_id";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row['user_reputation'];
	}

	/**
	* Prevent overrating one user by another user
	*
	* @param int $user_id User ID
	* @access public
	* @return bool
	*/
	public function prevent_rating($user_id)
	{
		if (!$this->config['rs_prevent_num'] || !$this->config['rs_prevent_perc'])
		{
			return false;
		}

		$total_reps = $same_user = 0;

		$post_type = (int) $this->get_reputation_type_id('post');
		$user_type = (int) $this->get_reputation_type_id('user');

		$sql = 'SELECT user_id_from
			FROM ' . $this->reputations_table . "
			WHERE user_id_to = {$user_id}
				AND (reputation_type_id = {$post_type} OR reputation_type_id = {$user_type})";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$total_reps++;

			if ($row['user_id_from'] == $this->user->data['user_id'])
			{
				$same_user++;
			}
		}
		$this->db->sql_freeresult($result);

		if (($total_reps >= $this->config['rs_prevent_num']) && ($same_user / $total_reps * 100 >= $this->config['rs_prevent_perc']))
		{
			return true;
		}

		return false;
	}

	/**
	* Generet post URL
	*
	* @param array $row Array with data
	* @access public
	* @return null
	*/
	public function generate_post_link($row)
	{
		$post_subject = $post_url = '';

		// Post was deleted
		if (!isset($row['post_subject']) && !isset($row['post_id']))
		{
			$post_subject = $this->user->lang('RS_POST_DELETE');
		}

		// Post exists
		if (isset($row['post_id']))
		{
			// Check forum read permission
			if ($this->auth->acl_get('f_read', $row['forum_id']))
			{
				$post_subject = $row['post_subject'] . ' [#p' . $row['post_id'] . ']';
				$post_url = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $row['post_id'] . '#p' . $row['post_id']);
			}
		}

		$this->template->assign_block_vars('reputation.post', array(
			'POST_SUBJECT'	=> $post_subject,
			'U_POST'		=> $post_url,
			'S_POST'		=> $row['reputation_type_id'] == $this->get_reputation_type_id('post'),
		));
	}

	/**
	* Delete single reputation
	*
	* @param array $data Reputation data
	* @access public
	* @return null
	*/
	public function delete_reputation($data)
	{
		// Required fields
		$fields = array(
			'user_id_from',
			'user_id_to',
			'reputation_item_id',
			'reputation_points',
			'reputation_type_name',
		);

		foreach ($fields as $field)
		{
			if (!isset($data[$field]))
			{
				throw new \pico\reputation\exception\invalid_argument(array($field, 'FIELD_MISSING'));
			}
		}

		if ($data['reputation_type_id'] == $this->get_reputation_type_id('post'))
		{
			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation - {$data['reputation_points']}
				WHERE post_id = {$data['reputation_item_id']}";
			$this->db->sql_query($sql);
		}

		$sql = 'DELETE FROM ' . $this->reputations_table . "
			WHERE reputation_id = {$data['reputation_id']}";
		$this->db->sql_query($sql);

		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = user_reputation - {$data['reputation_points']}
			WHERE user_id = {$data['user_id_to']}";
		$this->db->sql_query($sql);

		// Check max/min points
		if ($this->config['rs_max_point'] || $this->config['rs_min_point'])
		{
			$this->check_max_min($data['user_id_to']);
		}

		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_REPUTATION_DELETED', false, array(
			'user_id_from'	=> (isset($data['username_from'])) ? $data['username_from'] : $data['user_id_from'],
			'user_id_to'	=> (isset($data['username_to'])) ? $data['username_to'] : $data['user_id_to'],
			'points'		=> $data['reputation_points'],
			'type_name'		=> $data['reputation_type_name'],
			'item_id'		=> $data['reputation_item_id'],
		));
	}

	/**
	* Clear post reputation
	*
	* @param int $post_id Post id
	* @param array $data Reputation data
	* @access public
	* @return null
	*/
	public function clear_post_reputation($post_id, $data)
	{
		// Required fields
		$fields = array(
			'user_id_to',
			'reputation_item_id',
			'reputation_type_id',
			'post_reputation',
		);

		foreach ($fields as $field)
		{
			if (!isset($data[$field]))
			{
				throw new \pico\reputation\exception\invalid_argument(array($field, 'FIELD_MISSING'));
			}
		}

		$sql = 'UPDATE ' . POSTS_TABLE . "
			SET post_reputation = 0
			WHERE post_id = {$post_id}";
		$this->db->sql_query($sql);

		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = user_reputation - {$data['post_reputation']}
			WHERE user_id = {$data['user_id_to']}";
		$this->db->sql_query($sql);

		// Check max/min points
		if ($this->config['rs_max_point'] || $this->config['rs_min_point'])
		{
			$this->check_max_min($data['user_id_to']);
		}

		$sql = 'DELETE FROM ' . $this->reputations_table . "
			WHERE reputation_item_id = {$post_id}
				AND reputation_type_id = {$data['reputation_type_id']}";
		$this->db->sql_query($sql);

		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_POST_REPUTATION_CLEARED', false, array(
			'user_id_to'	=> (isset($data['username_to'])) ? $data['username_to'] : $data['user_id_to'],
			'post_subject'	=> (isset($data['post_subject'])) ? $data['post_subject'] : $data['reputation_item_id'],
		));
	}

	/**
	* Clear user reputation
	*
	* @param int $user_id User id
	* @param array $data Reputation data
	* @param arrat $post_ids Post IDs
	* @access public
	* @return null
	*/
	public function clear_user_reputation($user_id, $data, $post_ids)
	{
		// Required fields
		$fields = array(
			'user_id_to',
			'reputation_item_id',
		);

		foreach ($fields as $field)
		{
			if (!isset($data[$field]))
			{
				throw new \pico\reputation\exception\invalid_argument(array($field, 'FIELD_MISSING'));
			}
		}

		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = 0
			WHERE user_id = {$user_id}";
		$this->db->sql_query($sql);

		$sql = 'UPDATE ' . POSTS_TABLE . '
			SET post_reputation = 0
			WHERE ' . $this->db->sql_in_set('post_id', $post_ids, false, true);
		$this->db->sql_query($sql);

		$sql = 'DELETE FROM ' . $this->reputations_table . "
			WHERE user_id_to = {$user_id}";
		$this->db->sql_query($sql);

		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_USER_REPUTATION_CLEARED', false, array(
			'user_id_to'	=> (isset($data['username_to'])) ? $data['username_to'] : $data['user_id_to'],
		));
	}
}
