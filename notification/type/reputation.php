<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace pico88\reputation\notification\type;

/**
* Reputation notifications class
* This class handles notifications for reputations
*
* @package notifications
*/
class reputation extends \phpbb\notification\type\base
{
	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'reputation';
	}

	/**
	* Notification option data (for outputting to the user)
	*
	* @var bool|array False if the service should use it's default data
	* 					Array of data (including keys 'id', 'lang', and 'group')
	*/
	public static $notification_option = array(
		'lang'	=> 'NOTIFICATION_TYPE_REPUTATION',
	);

	/**
	* Is available
	*/
	public function is_available()
	{
		return ($this->config['rs_enable'] && $this->config['rs_notification']);
	}

	/**
	* Get post id
	*/
	public static function get_item_id($data)
	{
		return $data['post_id'];
	}

	/**
	* Get parent id - it's not used
	*/
	public static function get_item_parent_id($data)
	{
		// No parent
		return 0;
	}

	/**
	* Find the users who want to receive notifications
	*
	* @return array
	*/
	public function find_users_for_notification($data, $options = array())
	{
		$options = array_merge(array(
			'ignore_users'		=> array(),
		), $options);

		return $this->check_user_notification_options(array($data['user_to']), $options);
	}

	/**
	* Get the user's avatar
	*/
	public function get_avatar()
	{
		return $this->user_loader->get_avatar($this->get_data('user_from'));
	}

	/**
	* Get the HTML formatted title of this notification
	*
	* @return string
	*/
	public function get_title()
	{
		$username = $this->get_data('username_from');
		$points = $this->get_data('points');

		return $this->user->lang('NOTIFICATION_REPUTATION', (int) $points, $username);
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
		$user_data = $this->user_loader->get_user($this->get_data('user_from'));

		return array(
			'AUTHOR_NAME'				=> htmlspecialchars_decode($user_data['username']),
			//'SUBJECT'					=> htmlspecialchars_decode(censor_text($this->get_data('message_subject'))),

			'U_VIEW_MESSAGE'			=> generate_board_url() . '/ucp.' . $this->php_ext . "?i=pm&mode=view&p={$this->item_id}",
		);
	}

	/**
	* Get the url to this item
	*
	* @return string URL
	*/
	public function get_url()
	{
		return append_sid($this->phpbb_root_path . 'ucp.' . $this->php_ext, "i=reputation&amp;mode=list");
	}

	/**
	* Users needed to query before this notification can be displayed
	*
	* @return array Array of user_ids
	*/
	public function users_to_query()
	{
		return array();
	}

	/**
	* Function for preparing the data for insertion in an SQL query
	* (The service handles insertion)
	*
	* @param array $post Data from submit_post
	* @param array $pre_create_data Data from pre_create_insert_array()
	*
	* @return array Array of data ready to be inserted into the database
	*/
	public function create_insert_array($data, $pre_create_data = array())
	{
		$this->set_data('user_from', $data['user_from']);
		$this->set_data('username_from', $data['username_from']);
		$this->set_data('points', $data['points']);

		return parent::create_insert_array($data, $pre_create_data);
	}
}
