<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\core;

class manager
{
	protected $phpbb_root_path;
	protected $php_ext;
	protected $config;
	protected $db;
	protected $user;
	protected $auth;
	protected $template;
	protected $cache;
	protected $container;
	protected $reputation_table;

	private $power;

	/**
	* class constructor method
	*/
	public function __construct($phpbb_root_path, $php_ext, \phpbb\config\config $config, \phpbb\db\driver\driver $db, $user, $auth, \phpbb\template\template $template, \phpbb\cache\driver\driver_interface $cache, $container, $reputation_table)
	{
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->config = $config;
		$this->db = $db;
		$this->user = $user;
		$this->auth = $auth;
		$this->template = $template;
		$this->cache = $cache;
		$this->container = $container;
		$this->reputation_table = $reputation_table;
	}

	private function select_mode($mode)
	{
		if ($mode == 'post')
		{
			$action = 1;
		}
		else if ($mode == 'user')
		{
			$action = 2;
		}
		else if ($mode == 'onlypost')
		{
			$action = 3;
		}

		return $action;
	}

	/**
	* Main function for actual recording of voting points.
	* @param int $to user_id who gets the rating
	* @param int $post_id Option post id
	* @param string $comment
	* @param int $point Actual value set by the voting user
	* @param string $mode
	* @return bool
	*/
	public function give_point($to, $post_id = 0, $comment, $point, $mode = 'post')
	{
		// Firstly, select mode
		$action = $this->select_mode($mode);

		// Now we are ready to prepare vote data and save it
		$sql_data = array(
			'rep_from'			=> $this->user->data['user_id'],
			'rep_to'			=> $to,
			'time'				=> time(),
			'action'			=> $action,
			'post_id'			=> $post_id,
			'point'				=> $point,
			'comment'			=> $comment
		);

		// We can also add comment if it exists
		if ($this->config['rs_enable_comment'] && !empty($comment))
		{

			if (!class_exists('parse_message'))
			{
				// We have to use globals to save comment :( What a shame...
				global $phpbb_root_path, $phpEx;
				include($this->phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
			}

			$message_parser = new \parse_message();

			// Prepare comment for storage
			$allow_bbcode = $allow_urls = $allow_smilies = true;

			$message_parser->message = $comment;
			$message_parser->parse($allow_bbcode, $allow_urls, $allow_smilies, false, false, false, false, true, 'comment');

			$sql_data = array_merge($sql_data, array(
				'comment'			=> (string) $message_parser->message,
				'bbcode_uid'		=> (string) $message_parser->bbcode_uid,
				'bbcode_bitfield'	=> $message_parser->bbcode_bitfield,
			));
		}

		$this->db->sql_query('INSERT INTO ' . $this->reputation_table . ' ' . $this->db->sql_build_array('INSERT', $sql_data));

		// Update post reputation
		if ($post_id)
		{
			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation + $point
				WHERE post_id = $post_id";
			$this->db->sql_query($sql);
		}

		// Get some user data
		$sql = 'SELECT user_reputation, user_rep_new
			FROM ' . USERS_TABLE . " 
			WHERE user_id = $to";
		$result = $this->db->sql_query($sql);
		$user_data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// Notify user about the point
		$rep_last_time = !$user_data['user_rep_new'] ? ', user_rep_last = ' . time() . '' : '';
		$new_points = ', user_rep_new = user_rep_new + 1' . $rep_last_time;

		if ($mode == 'onlypost')
		{
			$point = 0;
		}

		// Caching user reputation
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = user_reputation + $point
				$new_points
			WHERE user_id = $to";
		$this->db->sql_query($sql);

		// Check max/min points
		if ($this->config['rs_max_point'] || $this->config['rs_min_point'])
		{
			$this->check_max_min($to);
		}

		if ($this->config['rs_notification'])
		{
			$notification_data = array(
				'user_to'			=> $to,
				'user_from'			=> $this->user->data['user_id'],
				'username_from'		=> $this->user->data['username'],
				'post_id'			=> $post_id,
				'points'			=> $point,
			);

			$phpbb_notifications = $this->container->get('notification_manager');

			$phpbb_notifications->add_notifications('reputation', $notification_data);
		}

		return true;
	}

	/** Function responsible for deleting reputation
	* @param int $id reputation ID
	* @return bool
	*/
	public function delete($id)
	{
		if (empty($id))
		{
			return false;
		}

		$sql_array = array(
			'SELECT'	=> 'r.rep_to, r.action, r.time, r.post_id, r.point, u.username, u.user_rep_last',
			'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_to = u.user_id',
				),
			),
			'WHERE'		=> 'r.rep_id = ' . $id
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row['post_id'])
		{
			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation - {$row['point']}
				WHERE post_id = {$row['post_id']}";
			$this->db->sql_query($sql);
		}

		$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_id = $id";
		$this->db->sql_query($sql);

		if ($row['action'] != 3)
		{
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_reputation = user_reputation - {$row['point']}
				WHERE user_id = {$row['rep_to']}";
			$this->db->sql_query($sql);

			// Check max/min points
			if ($this->config['rs_max_point'] || $this->config['rs_min_point'])
			{
				$this->check_max_min($row['rep_to']);
			}
		}

		// Update new status field
		if ($row['time'] >= $row['user_rep_last'])
		{
			$sql = 'SELECT COUNT(rep_id) AS new_reps
				FROM ' . $this->reputation_table . "
				WHERE rep_to = {$row['rep_to']}
					AND time >= {$row['user_rep_last']}";
			$result = $this->db->sql_query($sql);
			$new = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_rep_new = {$new['new_reps']}
				WHERE user_id = {$row['rep_to']}";
			$this->db->sql_query($sql);
		}

		add_log('mod', '', '', 'LOG_USER_REP_DELETE', $row['username']);

		return true;
	}

	/** Function responsible for clearing user or post reputation
	* @param string $mode user or post
	* @param int $id post or user ID
	* @param array $post_ids post IDs for user with post mode
	* @return bool
	*/
	public function clear_reputation($mode, $id, $post_ids = array())
	{
		if (empty($mode) || empty($id))
		{
			return;
		}

		if ($mode == 'post')
		{
			$sql = 'SELECT SUM(point) AS user_points, rep_to, action
				FROM ' . $this->reputation_table . "
				WHERE post_id = $id";
			$result = $this->db->sql_query($sql);
			$point = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = 0
				WHERE post_id = $id";
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM ' . $this->reputation_table . "
				WHERE post_id = $id";
			$this->db->sql_query($sql);

			if ($point['action'] != 5)
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_reputation = user_reputation - {$point['user_points']}
					WHERE user_id = {$point['rep_to']}";
				$this->db->sql_query($sql);

				// Check max/min points
				if ($this->config['rs_max_point'] || $this->config['rs_min_point'])
				{
					$this->check_max_min($point['rep_to']);
				}
			}

			$sql = 'SELECT  topic_id, forum_id, post_subject
				FROM ' . POSTS_TABLE . "
				WHERE post_id = $id";
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$log_forum = $row['forum_id'];
			$log_topic = $row['topic_id'];
			$log_clear_action = 'LOG_CLEAR_POST_REP';
			$log_clear_data = $row['post_subject'];
		}
		else if ($mode == 'user')
		{
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_reputation = 0
				WHERE user_id = $id";
			$this->db->sql_query($sql);

			$sql = 'UPDATE ' . POSTS_TABLE . '
				SET post_reputation = 0
				WHERE ' . $this->db->sql_in_set('post_id', $post_ids, false, true);
			$this->db->sql_query($sql);

			$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_to = $id";
			$this->db->sql_query($sql);

			$sql = 'SELECT  username
				FROM ' . USERS_TABLE . "
				WHERE user_id = $id";
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$log_topic = '';
			$log_forum = '';
			$log_clear_action = 'LOG_CLEAR_USER_REP';
			$log_clear_data = $row['username'];
		}
		else
		{
			return;
		}

		add_log('mod', $log_forum,  $log_topic, $log_clear_action, $log_clear_data);
	}

	/**
	* @param int $user_id user ID
	*/
	private function check_max_min($user_id)
	{
		$sql = 'SELECT SUM(point) AS points
			FROM ' . REPUTATIONS_TABLE . "
			WHERE action != 5
				AND rep_to = $user_id";
		$result = $this->db->sql_query($sql);
		$points = $this->db->sql_fetchfield('points');
		$this->db->sql_freeresult($result);

		// Choose mode
		$mode = ($points > 0) ? 'max' : 'min';

		// Max user reputation
		if ($mode == 'max' && $this->config['rs_max_point'])
		{
			if ($points > $this->config['rs_max_point'])
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_reputation = {$this->config['rs_max_point']}
					WHERE user_id = $user_id";
				$this->db->sql_query($sql);
			}
			else
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_reputation = $points
					WHERE user_id = $user_id";
				$this->db->sql_query($sql);
			}
		}

		// Min user reputation
		if ($mode == 'min' && $this->config['rs_min_point'])
		{
			if ($points < $this->config['rs_min_point'])
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_reputation = {$this->config['rs_min_point']}
					WHERE user_id = $user_id";
				$this->db->sql_query($sql);
			}
			else
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_reputation = $points
					WHERE user_id = $user_id";
				$this->db->sql_query($sql);
			}
		}
	}

	/** Return post reputation
	* @param $post_id ID of a post
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

	/** Return user reputation
	* @param $user_id user ID
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

	/** Prevent overrating one user by another user
	* @param $user_id user ID
	*/
	public function prevent_rating($user_id)
	{
		if (!$this->config['rs_prevent_num'] || !$this->config['rs_prevent_perc'])
		{
			return false;
		}

		$total_reps = $same_user = 0;

		$sql = 'SELECT rep_from
			FROM ' . $this->reputation_table . "
			WHERE rep_to = $user_id
				AND (action = 1 OR action = 2)";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$total_reps++;

			if ($row['rep_from'] == $this->user->data['user_id'])
			{
				$same_user++;
			}
		}
		$this->db->sql_freeresult($result);

		if (($total_reps >= $this->config['rs_prevent_num']) && ($same_user / $total_reps * 100 >= $this->config['rs_prevent_perc']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	*
	*/
	public function reputation_response($data, $ajax = true)
	{
		if ($ajax)
		{
			$json_response = new \phpbb\json_response();
			$json_response->send($data);
		}
		else
		{
			if (isset($data['error_msg'])) $response = $data['error_msg'];
			if (isset($data['success_msg'])) $response = $data['success_msg'];
			trigger_error($response);
			exit;
		}
	}
}
