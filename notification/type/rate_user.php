<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\notification\type;

/**
* Reputation notifications class
* This class handles notifications for Reputation System
*
* @package notifications
*/
class rate_user extends \phpbb\notification\type\base
{
	/** @var \phpbb\controller\helper */
	protected $helper;

/**
	* Notification Type Boardrules Constructor
	*
	* @param \phpbb\user_loader $user_loader
	* @param \phpbb\db\driver\driver_interface $db
	* @param \phpbb\cache\driver\driver_interface $cache
	* @param \phpbb\user $user
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $helper
	* @param string $phpbb_root_path
	* @param string $php_ext
	* @param string $notification_types_table
	* @param string $notifications_table
	* @param string $user_notifications_table
	* @return \phpbb\boardrules\notification\boardrules
	*/
	public function __construct(\phpbb\user_loader $user_loader, \phpbb\db\driver\driver_interface $db, \phpbb\cache\driver\driver_interface $cache, $user, \phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $helper, $phpbb_root_path, $php_ext, $notification_types_table, $notifications_table, $user_notifications_table)
	{
		$this->user_loader = $user_loader;
		$this->db = $db;
		$this->cache = $cache;
		$this->user = $user;
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->notification_types_table = $notification_types_table;
		$this->notifications_table = $notifications_table;
		$this->user_notifications_table = $user_notifications_table;
	}

	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'pico.reputation.notification.type.rate_user';
	}

	/**
	* Language key used to output the text
	*
	* @var string
	*/
	protected $language_key = 'NOTIFICATION_RATE_USER';

	/**
	* Notification option data (for outputting to the user)
	*
	* @var bool|array False if the service should use it's default data
	* 					Array of data (including keys 'id', 'lang', and 'group')
	*/
	public static $notification_option = array(
		'id'	=> 'pico.reputation.notification',
		'lang'	=> 'NOTIFICATION_TYPE_REPUTATION',
		'group'	=> 'NOTIFICATION_GROUP_MISCELLANEOUS',
	);

	/**
	* Is this type available to the current user (defines whether or not it will be shown in the UCP Edit notification options)
	*
	* @return bool True/False whether or not this is available to the user
	*/
	public function is_available()
	{
		return true;
	}

	/**
	* Get the id of the notification
	*
	* @param array $data The data for the reputation
	*/
	public static function get_item_id($data)
	{
		return (int) $data['reputation_id'];
	}

	/**
	* Get the id of the parent
	*
	* @param array $data The data for the reputation
	*/
	public static function get_item_parent_id($data)
	{
		return 0;
	}

	/**
	* Find the users who will receive notifications
	*
	* @param array $data The type specific data for the updated
	* @param array $options Options for finding users for notification
	*
	* @return array
	*/
	public function find_users_for_notification($data, $options = array())
	{
		$options = array_merge(array(
			'ignore_users'	=> array(),
		), $options);
		$users = array((int) $data['user_id_to']);

		$notify_users = $this->check_user_notification_options($users, $options);

		// Try to find the users who already have been notified about replies and have not read the topic since and just update their notifications
		$sql = 'SELECT n.*
			FROM ' . $this->notifications_table . ' n, ' . $this->notification_types_table . ' nt
			WHERE n.notification_type_id = ' . (int) $this->notification_type_id . '
				AND n.item_parent_id = ' . (int) self::get_item_parent_id($data) . '
				AND n.notification_read = 0
				AND n.user_id = ' . $data['user_id_to'] . '
				AND nt.notification_type_id = n.notification_type_id
				AND nt.notification_type_enabled = 1';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$row_data = unserialize($row['notification_data']);

			// Do not create a new notification
			unset($notify_users[$row['user_id']]);

			$notification = $this->notification_manager->get_item_type_class($this->get_type(), $row);
			$update_responders = $notification->add_voting_users($data);
			if (!empty($update_responders))
			{
				$sql = 'UPDATE ' . $this->notifications_table . '
					SET ' . $this->db->sql_build_array('UPDATE', $update_responders) . '
					WHERE notification_id = ' . $row['notification_id'];
				$this->db->sql_query($sql);
			}
		}
		$this->db->sql_freeresult($result);

		return $notify_users;
	}

	/**
	* Users needed to query before this notification can be displayed
	*
	* @return array Array of user_ids
	*/
	public function users_to_query()
	{
		$voting_users = $this->get_data('voting_users');

		$users = array(
			$this->get_data('user_id_from'),
		);

		if (is_array($voting_users))
		{
			foreach ($voting_users as $voting_user)
			{
				$users[] = $voting_user['user_id_from'];
			}
		}

		return $users;
	}

	/**
	* Get the user's avatar
	*/
	public function get_avatar()
	{
		return $this->user_loader->get_avatar($this->get_data('user_id_from'));
	}

	/**
	* Get the HTML formatted title of this notification
	*
	* @return string
	*/
	public function get_title()
	{
		$voting_users = $this->get_data('voting_users');
		$usernames = array();

		if (!is_array($voting_users))
		{
			$voting_users = array();
		}

		$voting_users = array_merge(array(array(
			'user_id_from'	=> $this->get_data('user_id_from'),
		)), $voting_users);

		$voting_users_cnt = sizeof($voting_users);
		$voting_users = $this->trim_user_ary($voting_users);
		$trimmed_voting_users_cnt = $voting_users_cnt - sizeof($voting_users);

		foreach ($voting_users as $voting_user)
		{
			$usernames[] = $this->user_loader->get_username($voting_user['user_id_from'], 'no_profile');
		}

		if ($trimmed_voting_users_cnt > 20)
		{
			$usernames[] = $this->user->lang('NOTIFICATION_MANY_OTHERS');
		}
		else if ($trimmed_voting_users_cnt)
		{
			$usernames[] = $this->user->lang('NOTIFICATION_X_OTHERS', $trimmed_voting_users_cnt);
		}

		return $this->user->lang(
			$this->language_key,
			phpbb_generate_string_list($usernames, $this->user),
			$trimmed_voting_users_cnt
		);
	}

	/**
	* Get the url to this item
	*
	* @return string URL
	*/
	public function get_url()
	{
		return $this->helper->route('reputation_details_controller', array('uid' => $this->user->data['user_id']));
	}

	/**
	* Trim the user array passed down to 3 users if the array contains
	* more than 4 users.
	*
	* @param array $users Array of users
	* @return array Trimmed array of user_ids
	*/
	public function trim_user_ary($users)
	{
		if (sizeof($users) > 4)
		{
			array_splice($users, 3);
		}
		return $users;
	}

	/**
	* Get email template
	*
	* @return string|bool
	*/
	public function get_email_template()
	{
		return false;
	}

	/**
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()
	{
		return array();
	}

	/**
	* Function for preparing the data for insertion in an SQL query
	* (The service handles insertion)
	*
	* @param array $data The data for the reputation
	* @param array $pre_create_data Data from pre_create_insert_array()
	*
	* @return array Array of data ready to be inserted into the database
	*/
	public function create_insert_array($data, $pre_create_data = array())
	{
		$this->set_data('user_id_from', $data['user_id_from']);

		return parent::create_insert_array($data, $pre_create_data);
	}

	/**
	* Add voting users to the notification
	*
	* @param mixed $user
	*/
	public function add_voting_users($user)
	{
		if ($this->get_data('user_id_from') == $user['user_id_from'])
		{
			return array();
		}

		$voting_users = $this->get_data('voting_users');

		$voting_users = ($voting_users === null) ? array() : $voting_users;

		if (sizeof($voting_users) > 25)
		{
			return array();
		}

		foreach ($voting_users as $voting_user)
		{
			if ($voting_user['user_id_from'] == $user['user_id_from'])
			{
				return array();
			}
		}

		$voting_users[] = array(
			'user_id_from'	=> $user['user_id_from'],
		);

		$this->set_data('voting_users', $voting_users);

		$serialized_data = serialize($this->get_data(false));

		if (utf8_strlen($serialized_data) >= 4000)
		{
			return array();
		}

		return array('notification_data' => $serialized_data);
	}
}
