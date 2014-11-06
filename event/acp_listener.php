<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class acp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	* Constructor
	*
	* @param \phpbb\request\request     $request    Request object
	* @param \phpbb\template\template   $template   Template object
	* @return \pico\reputation\event\acp_listener
	* @access public
	*/
	public function __construct(\phpbb\request\request $request, \phpbb\template\template $template)
	{
		$this->request = $request;
		$this->template = $template;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_manage_forums_request_data'		=> 'forum_reputation_request',
			'core.acp_manage_forums_initialise_data'	=> 'forum_initialise_reputation',
			'core.acp_manage_forums_display_form'		=> 'forum_display_reputation',
			'core.acp_manage_group_request_data'		=> 'group_request_data',
			'core.acp_manage_group_initialise_data'		=> 'group_initialise_data',
			'core.acp_manage_group_display_form'		=> 'group_display_form',
			'core.permissions'							=> 'add_reputation_permissions',
		);
	}

	/**
	* Add reputation forum request data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function forum_reputation_request($event)
	{
		$forum_data = $event['forum_data'];
		$forum_data['reputation_enabled'] = $this->request->variable('reputation_enabled', 0);
		$event['forum_data'] = $forum_data;
	}

	/**
	* Initialise reputation data while creating a new forum
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function forum_initialise_reputation($event)
	{
		if($event['action'] == 'add')
		{
			$forum_data = $event['forum_data'];
			$forum_data = array_merge($forum_data, array(
				'reputation_enabled'	=> false,
			));
			$event['forum_data'] = $forum_data;
		}
	}

	/**
	* Assign reputation data to forum template
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function forum_display_reputation($event)
	{
		$template_data = $event['template_data'];
		$template_data['S_ENABLE_REPUTATION'] = $event['forum_data']['reputation_enabled'];
		$event['template_data'] = $template_data;
	}

	/**
	* Add reputation group request data
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function group_request_data($event)
	{
		$submit_ary = $event['submit_ary'];
		$submit_ary['reputation_power'] = $this->request->variable('group_reputation_power', 0);
		$event['submit_ary'] = $submit_ary;

		$validation_checks = $event['validation_checks'];
		$validation_checks['reputation_power'] = array('num', false, 0, 999);
		$event['validation_checks'] = $validation_checks;
	}

	/**
	* Add group test variable
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function group_initialise_data($event)
	{
		$test_variables = $event['test_variables'];
		$test_variables['reputation_power'] = 'int';
		$event['test_variables'] = $test_variables;
	}

	/**
	* Assign reputation data to group template
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function group_display_form($event)
	{
		$group_row = $event['group_row'];

		$this->template->assign_vars(array(
			'GROUP_REPUTATION_POWER' => (isset($group_row['group_reputation_power'])) ? $group_row['group_reputation_power'] : 0,
		));
	}

	/**
	* Add reputation permissions
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_reputation_permissions($event)
	{
		// Create reputation category
		$categories = $event['categories'];
		$categories['reputation'] = 'ACL_CAT_REPUTATION';
		$event['categories'] = $categories;

		// Assign permissions to categories
		$permissions = $event['permissions'];
		$permissions = array_merge($permissions, array(
			// Admin permissions
			'a_reputation'		=> array('lang' => 'ACL_A_REPUTATION', 'cat' => 'misc'),

			// Forum permissions
			'f_rs_rate'				=> array('lang' => 'ACL_F_RS_RATE', 'cat' => 'reputation'),
			'f_rs_rate_negative'	=> array('lang' => 'ACL_F_RS_RATE_NEGATIVE', 'cat' => 'reputation'),

			// Moderator permissions
			'm_rs_moderate'		=> array('lang' => 'ACL_M_RS_MODERATE', 'cat' => 'reputation'),
			'm_rs_rate'			=> array('lang' => 'ACL_M_RS_RATE', 'cat' => 'reputation'),

			// User permissions
			'u_rs_rate'				=> array('lang' => 'ACL_U_RS_RATE', 'cat' => 'reputation'),
			'u_rs_rate_negative'	=> array('lang' => 'ACL_U_RS_RATE_NEGATIVE', 'cat' => 'reputation'),
			'u_rs_view'				=> array('lang' => 'ACL_U_RS_VIEW', 'cat' => 'reputation'),
			'u_rs_rate_post'		=> array('lang' => 'ACL_U_RS_RATE_POST', 'cat' => 'reputation'),
			'u_rs_delete'			=> array('lang' => 'ACL_U_RS_DELETE', 'cat' => 'reputation'),
		));
		$event['permissions'] = $permissions;
	}
}
