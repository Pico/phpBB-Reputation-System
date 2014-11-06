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
class memberlist_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth           $auth       Auth object
	* @param \phpbb\config\config       $config     Config object
	* @param \phpbb\controller\helper   $helper     Controller helper object
	* @return \pico\reputation\event\memberlist_listener
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $helper)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;
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
			'core.memberlist_prepare_profile_data'	=> 'prepare_user_reputation_data',
		);
	}

	/**
	* Display user reputation on user profile page
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function prepare_user_reputation_data($event)
	{
		$data = $event['data'];
		$template_data = $event['template_data'];

		$template_data = array_merge($template_data, array(
			'USER_REPUTATION' => $data['user_reputation'],

			'U_VIEW_USER_REPUTATION'	=> $this->helper->route('reputation_details_controller', array('uid' => $data['user_id'])),
			'U_RATE_USER'				=> $this->helper->route('reputation_user_rating_controller', array('uid' => $data['user_id'])),
			'U_REPUTATION_REFERER'		=> $this->helper->get_current_url(),

			'S_RATE_USER'		=> ($this->config['rs_user_rating'] && $this->auth->acl_get('u_rs_rate')) ? true : false,
			'S_VIEW_REPUTATION'	=> ($this->auth->acl_get('u_rs_view')) ? true : false,
		));

		$event['template_data'] = $template_data;
	}
}
