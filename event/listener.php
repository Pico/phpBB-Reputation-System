<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \pico\reputation\core\reputation_helper */
	protected $reputation_helper;

	/** @var string The table we use to store our reputations */
	protected $reputations_table;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth						Auth object
	* @param \phpbb\config\config $config				Config object
	* @param \phpbb\controller\helper					Controller helper object
	* @param \phpbb\db\driver\driver $db				Database object
	* @param \phpbb\request\request $request			Request object
	* @param \phpbb\template\template $template			Template object
	* @param \phpbb\user $user							User object
	* @param \pico\reputation\core\reputation_helper	Reputation helper object
	* @param string $reputations_table					Name of the table used to store reputations data
	* @return \pico\reputation\event\listener
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\db\driver\driver $db, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \pico\reputation\core\reputation_helper $reputation_helper,  $reputations_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->reputation_helper = $reputation_helper;
		$this->reputations_table = $reputations_table;
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
			'core.common'		=> 'reputation_status',
			'core.user_setup'	=> 'load_language_on_setup',

			// ACP events
			'core.acp_manage_forums_request_data'	=> 'forum_reputation_request',
			'core.acp_manage_forums_display_form'	=> 'forum_display_reputation',
			'core.acp_manage_group_request_data'	=> 'group_request_data',
			'core.acp_manage_group_initialise_data'	=> 'group_initialise_data',
			'core.acp_manage_group_display_form'	=> 'group_display_form',
			'core.permissions'						=> 'add_reputation_permissions',

			// Index event
			'core.index_modify_page_title'	=> 'index_reputation_toplist',

			// Memberlist event
			'core.memberlist_prepare_profile_data'	=> 'memberlist_add_user_reputation',

			// Viewtopic events
			'core.viewtopic_get_post_data'		=> 'viewtopic_modify_sql_array',
			'core.viewtopic_post_rowset_data'	=> 'viewtopic_post_rowset_add_reputation_data',
			'core.viewtopic_cache_guest_data'	=> 'viewtopic_cache_add_reputation_data',
			'core.viewtopic_cache_user_data'	=> 'viewtopic_cache_add_reputation_data',
			'core.viewtopic_modify_post_row'	=> 'viewtopic_postrow_add_reputation',
			'core.viewtopic_modify_page_title'	=> 'viewtopic_add_reputation',
		);
	}

	/**
	* Check if Reputation System is enabled or disabled
	*
	* @return null
	* @access public
	*/
	public function reputation_status()
	{
		$this->template->assign_vars(array(
			'S_REPUTATION'	=> $this->config['rs_enable'] ? true : false,
		));
	}

	/**
	* Load common board rules language files during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'pico/reputation',
			'lang_set' => 'reputation_common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
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

	/**
	* Display user reputation on user profile page
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function index_reputation_toplist()
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

					// ToDo
					'U_VIEW_USER_REPUTATION'	=> '',

					'S_DIRECTION'	=> $this->config['rs_toplist_direction'] ? true : false,
				));
			}
			$this->db->sql_freeresult($result);

			$this->template->assign_vars(array(
				'S_RS_TOPLIST'	=> true,
			));
		}
	}

	/**
	* Display user reputation on user profile page
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function memberlist_add_user_reputation($event)
	{
		$data = $event['data'];
		$template_data = $event['template_data'];

		$template_data = array_merge($template_data, array(
			'USER_REPUTATION' => $data['user_reputation'],

			'U_VIEW_USER_REPUTATION'	=> $this->helper->route('reputation_details_controller', array('uid' => $data['user_id'])),
			'U_RATE_USER'				=> $this->helper->route('reputation_main_controller', array('rate' => 'user', 'u' => $data['user_id'])),

			'S_RATE_USER'		=> ($this->config['rs_user_rating'] && $this->auth->acl_get('u_rs_rate')) ? true : false,
			'S_VIEW_REPUTATION'	=> ($this->auth->acl_get('u_rs_view')) ? true : false,
		));

		$event['template_data'] = $template_data;
	}

	/**
	* Modify sql array by adding reputations table
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_sql_array($event)
	{
		// Modify sql array when the extension and its post rating are enabled
		// Otherwise do nothing
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$sql_ary = $event['sql_ary'];

			$sql_ary['LEFT_JOIN'][] = array(
				'FROM'	=> array($this->reputations_table => 'r'),
				'ON'	=> 'r.reputation_item_id = p.post_id AND r.reputation_type_id = 1'
			);
			$sql_ary['SELECT'] .= ', r.user_id_from, r.reputation_points';

			$event['sql_ary'] = $sql_ary;
		}
	}

	/**
	* Add reputation data to rowset
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_post_rowset_add_reputation_data($event)
	{
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$rowset_data = $event['rowset_data'];
			$row = $event['row'];

			$rowset_data = array_merge($rowset_data, array(
				'post_reputation'	=> $row['post_reputation'],
				'user_voted_id'		=> $row['user_id_from'],
				'reputation_points'	=> $row['reputation_points'],
			));

			$event['rowset_data'] = $rowset_data;
		}
	}

	/**
	* Add guest user's and user's data to display their reputation
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_cache_add_reputation_data($event)
	{
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$user_cache_data = $event['user_cache_data'];
			$user_cache_data['reputation'] = $event['row']['user_reputation'];
			$event['user_cache_data'] = $user_cache_data;
		}
	}

	/**
	* Add postrow data for displaying reputation
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_postrow_add_reputation($event)
	{
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$row = $event['row'];
			$user_poster_data = $event['user_poster_data'];
			$post_row = $event['post_row'];
			$post_id = $row['post_id'];

			// Check post status and return its status as class
			//	Is it own post, rated good or rated bad?
			if ($this->user->data['user_id'] == $row['user_id'])
			{
				$post_vote_class = 'own';
			}
			else
			{
				$post_vote_class = ($row['user_voted_id'] == $this->user->data['user_id'])  ? (($row['reputation_points'] > 0) ? 'rated_good' : 'rated_bad') : '';
			}

			$post_row = array_merge($post_row, array(
				'S_VIEW_REPUTATION'		=> ($this->auth->acl_get('u_rs_view')) ? true : false,
				'S_RATE_POST'			=> ($this->auth->acl_get('f_rs_rate', $row['forum_id']) && $this->auth->acl_get('u_rs_rate_post') && $row['user_id'] != ANONYMOUS) ? true : false,
				'S_RATE_POST_NEGATIVE'	=> ($this->auth->acl_get('f_rs_rate_negative', $row['forum_id']) && $this->config['rs_negative_point']) ? true : false,

				'RS_RATE_POST_NEGATIVE'	=> ($row['user_voted_id'] == $this->user->data['user_id']) ? $this->user->lang('RS_POST_RATED') : $this->user->lang('RS_RATE_POST_NEGATIVE'),
				'RS_RATE_POST_POSITIVE'	=> ($row['user_voted_id'] == $this->user->data['user_id']) ? $this->user->lang('RS_POST_RATED') : $this->user->lang('RS_RATE_POST_POSITIVE'),

				'U_RATE_POST_POSITIVE'		=> $this->helper->route('reputation_main_controller', array('rate' => 'post', 'mode' => 'positive', 'p' => $post_id)),
				'U_RATE_POST_NEGATIVE'		=> $this->helper->route('reputation_main_controller', array('rate' => 'post', 'mode' => 'negative', 'p' => $post_id)),
				'U_VIEW_POST_REPUTATION'	=> $this->helper->route('reputation_main_controller', array('details' => 'post', 'p' => $post_id)),
				'U_VIEW_USER_REPUTATION'	=> $this->helper->route('reputation_main_controller', array('details' => 'user', 'u' => $row['user_id'])),

				'POST_REPUTATION'		=> $row['post_reputation'],
				'POST_REPUTATION_CLASS'	=> $this->reputation_helper->reputation_class($row['post_reputation']),
				'POST_VOTE_CLASS'		=> $post_vote_class,
				'USER_REPUTATION'		=> $user_poster_data['reputation'],
			));

			$event['post_row'] = $post_row;
		}
	}

	/**
	* Add global template var for reputation in forums
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_add_reputation($event)
	{
		if ($this->config['rs_enable'])
		{
			$topic_data = $event['topic_data'];

			// Author note
			//	Post rating is not allowed in the global announcements
			//	because there is no option to set proper permissions for such topics
			$this->template->assign_vars(array(
				'S_FORUM_REPUTATION'	=> ($topic_data['reputation_enabled'] && $this->config['rs_post_rating'] && ($topic_data['topic_type'] != POST_GLOBAL)) ? true : false,
			));
		}
	}
}
