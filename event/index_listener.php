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
class index_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth           $auth       Auth object
	* @param \phpbb\config\config       $config     Config object
	* @param \phpbb\db\driver\driver    $db         Database object
	* @param \phpbb\controller\helper   $helper     Controller helper object
	* @param \phpbb\template\template   $template   Template object
	* @param \phpbb\user                $user       User object
	* @return \pico\reputation\event\index_listener
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
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
			'core.index_modify_page_title'	=> 'reputation_toplist',
		);
	}

	/**
	* Display reputation toplist
	*
	* @return null
	* @access public
	*/
	public function reputation_toplist()
	{
		if ($this->config['rs_enable'] && $this->config['rs_enable_toplist'] && $this->config['rs_toplist_num'])
		{
			$this->user->add_lang_ext('pico/reputation', 'reputation_toplist');

			$sql = 'SELECT user_id, username, user_colour, user_reputation
				FROM ' . USERS_TABLE . '
				WHERE user_reputation > 0
				ORDER BY user_reputation DESC';
			$result = $this->db->sql_query_limit($sql, $this->config['rs_toplist_num']);

			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->template->assign_block_vars('toplist', array(
					'USERNAME_FULL'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
					'USER_REPUTATION'	=> $row['user_reputation'],

					'U_VIEW_USER_REPUTATION'	=> $this->helper->route('reputation_details_controller', array('uid' => $row['user_id'])),

					'S_DIRECTION'	=> ($this->config['rs_toplist_direction']) ? true : false,
				));
			}
			$this->db->sql_freeresult($result);

			$this->template->assign_vars(array(
				'S_RS_TOPLIST'		=> true,
				'S_VIEW_REPUTATION'	=> ($this->auth->acl_get('u_rs_view')) ? true : false,
			));
		}
	}
}
