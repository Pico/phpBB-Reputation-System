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
class mcp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

		/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \pico\reputation\core\reputation_maanger */
	protected $reputation_manager;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth						Auth object
	* @param \phpbb\config\config $config				Config object
	* @param \phpbb\request\request $request			Request object
	* @param \phpbb\template\template $template			Template object
	* @param \phpbb\user $user							User object
	* @param \pico\reputation\core\reputation_manager	Reputation manager object
	* @return \pico\reputation\event\listener
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \pico\reputation\core\reputation_manager $reputation_manager)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->reputation_manager = $reputation_manager;
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
			'core.mcp_warn_post_after'					=> 'warning_post',
			'core.mcp_warn_user_after'					=> 'warning_user',
			'core.modify_mcp_modules_display_option'	=> 'reputation_warning_options',
		);
	}

	/**
	* 
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function warning_post($event)
	{
		if($this->request->is_set_post('add_reputation'))
		{
			$data = array(
				'user_id_from'			=> $this->user->data['user_id'],
				'user_id_to'			=> $event['user_row']['poster_id'],
				'reputation_type'		=> 'warning',
				'reputation_item_id'	=> $event['post_id'],
				'reputation_points'		=> $this->request->variable('points', ''),
				'reputation_comment'	=> $this->request->variable('comment', '', true),
			);

			try
			{
				$this->reputation_manager->store_reputation($data);
			}
			catch (\pico\reputation\exception\base $e)
			{
				// Catch exception
				$error = $e->get_message($this->user);
			}
		}
	}

	/**
	* 
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function warning_user($event)
	{
		if($this->request->is_set_post('add_reputation'))
		{
			$data = array(
				'user_id_from'			=> $this->user->data['user_id'],
				'user_id_to'			=> $event['user_row']['user_id'],
				'reputation_type'		=> 'warning',
				'reputation_item_id'	=> 0,
				'reputation_points'		=> $this->request->variable('points', ''),
				'reputation_comment'	=> $this->request->variable('comment', '', true),
			);

			try
			{
				$this->reputation_manager->store_reputation($data);
			}
			catch (\pico\reputation\exception\base $e)
			{
				// Catch exception
				$error = $e->get_message($this->user);
			}
		}
	}

	/**
	* 
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function reputation_warning_options($event)
	{
		$mode = $event['mode'];

		if ($this->config['rs_warning'] && $this->auth->acl_get('m_rs_moderate') && ($mode == 'warn_post' || $mode == 'warn_user'))
		{
			$this->user->add_lang_ext('pico/reputation', 'reputation_warning');

			//Preparing HTML for voting by manual spending of user power
			for($i = 1; $i <= $this->config['rs_max_power_warning']; ++$i)
			{
				$this->template->assign_block_vars('power', array(
					'POINTS'	=> -$i,
					'TITLE'		=> $this->user->lang('MCP_RS_POINTS', $i),
				));
			}

			$this->template->assign_vars(array(
				'S_RS_WARNING'		=> true,
			));
		}
	}
}
