<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @ var string */
	protected $reputation_display;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $reputations_table;

	/**
	* Constructor
	* 
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\cache\driver\driver_interface $cache
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $controller_helper
	* @param \phpbb\db\driver\driver $db
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param string $reputation_display Reputation display service
	* @param string $phpbb_root_path Root path
	* @param string $reputation_table Name of the table uses to store reputations
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\driver\driver_interface $cache, \phpbb\config\config $config, \phpbb\controller\helper $controller_helper, \phpbb\db\driver\driver $db, \phpbb\template\template $template, \phpbb\user $user, $reputation_display, $phpbb_root_path, $reputations_table)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->controller_helper = $controller_helper;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->reputation_display = $reputation_display;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->reputations_table = $reputations_table;
	}

	/**
	* Get subscribed events
	*
	* @return array
	* @static
	*/
	static public function getSubscribedEvents()
	{
		return array(
			// Event loaded on each page
			'core.common'								=> 'common_setup',
			'core.user_setup'							=> 'load_language_on_setup',

			// ACP events
			'core.acp_manage_forums_request_data'		=> 'forum_request',
			'core.acp_manage_forums_initialise_data'	=> 'forum_initialise',
			'core.acp_manage_forums_display_form'		=> 'forums_display',
			'core.permissions'							=> 'permissions_add_reputation',

			// Index event
			'core.index_modify_page_title'				=> 'index_reputation_toplist',

			// Memberlist event
			'core.memberlist_prepare_profile_data'		=> 'memberlist_add_user_reputation',

			// Viewtopic events
			'core.viewtopic_get_post_data'				=> 'viewtopic_modify_sql_array',
			'core.viewtopic_post_rowset_data'			=> 'viewtopic_post_rowset_add_reputation_data',
			'core.viewtopic_cache_guest_data'			=> 'viewtopic_cache_add_reputation_data',
			'core.viewtopic_cache_user_data'			=> 'viewtopic_cache_add_reputation_data',
			'core.viewtopic_modify_post_row'			=> 'viewtopic_postrow_add_reputation',
			'core.viewtopic_modify_page_title'			=> 'viewtopic_add_reputation',
		);
	}

	/**
	*
	*/
	public function common_setup()
	{
		$this->template->assign_vars(array(
			'S_REPUTATION'	=> $this->config['rs_enable'] ? true : false,
		));
	}

	/**
	*
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
				'ext_name' => 'pico88/reputation',
				'lang_set' => 'reputation_common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	*
	*/
	public function index_reputation_toplist()
	{
		if ($this->config['rs_enable'] && $this->config['rs_enable_toplist'] && $this->config['rs_toplist_num'])
		{
			$this->user->add_lang_ext('pico88/reputation', 'reputation_system');

			$reputation_toplist = '';
			$sql = 'SELECT user_id, username, user_colour, user_reputation
				FROM ' . USERS_TABLE . '
				WHERE user_id <> ' . ANONYMOUS . '
					AND user_reputation > 0
				ORDER BY user_reputation DESC';
			$result = $this->db->sql_query_limit($sql, $this->config['rs_toplist_num']);

			while ($row = $this->db->sql_fetchrow($result))
			{
				$direction = $this->config['rs_toplist_direction'] ? '<br />' : ', ';
				$reputation_toplist .= (($reputation_toplist != '') ? $direction : '') . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']) . ' (' . $row['user_reputation'] . ')';
			}
			$this->db->sql_freeresult($result);

			$this->template->assign_vars(array(
				'S_RS_TOPLIST'	=> true,
				'RS_TOPLIST'	=> $this->config['rs_toplist_direction'] ? '<br />' . $reputation_toplist : $reputation_toplist
			));
		}
	}

	/**
	*
	*/
	public function forum_request($event)
	{
		$forum_data = $event['forum_data'];
		$forum_data += array('enable_reputation' => request_var('enable_reputation', 0));
		$event['forum_data'] = $forum_data;
	}

	/**
	*
	*/
	public function forum_initialise($event)
	{
		$forum_data = $event['forum_data'];
		$forum_data += array('enable_reputation' => false);
		$event['forum_data'] = $forum_data;
	}

	/**
	*
	*/
	public function forums_display($event)
	{
		$template_data = $event['template_data'];
		$template_data += array('S_ENABLE_REPUTATION' => $event['forum_data']['enable_reputation']);
		$event['template_data'] = $template_data;
	}

	/**
	*
	*/
	public function permissions_add_reputation($event)
	{
		$categories = $event['categories'];
		$categories = array_merge($categories, array('reputation' => 'ACL_CAT_REPUTATION'));
		$event['categories'] = $categories;

		$permissions = $event['permissions'];
		$permissions = array_merge($permissions, array(
			// Admin Permissions
			'a_reputation'		=> array('lang' => 'ACL_A_REPUTATION', 'cat' => 'misc'),

			// Forum Permissions
			'f_rs_give'				=> array('lang' => 'ACL_F_RS_GIVE', 'cat' => 'reputation'),
			'f_rs_give_negative'	=> array('lang' => 'ACL_F_RS_GIVE_NEGATIVE', 'cat' => 'reputation'),

			// Moderator Permissions
			'm_rs_moderate'		=> array('lang' => 'ACL_M_RS_MODERATE', 'cat' => 'reputation'),
			'm_rs_give'			=> array('lang' => 'ACL_M_RS_GIVE', 'cat' => 'reputation'),

			// User Permissions
			'u_rs_give'				=> array('lang' => 'ACL_U_RS_GIVE', 'cat' => 'reputation'),
			'u_rs_give_negative'	=> array('lang' => 'ACL_U_RS_GIVE_NEGATIVE', 'cat' => 'reputation'),
			'u_rs_view'				=> array('lang' => 'ACL_U_RS_VIEW', 'cat' => 'reputation'),
			'u_rs_ratepost'			=> array('lang' => 'ACL_U_RS_RATEPOST', 'cat' => 'reputation'),
			'u_rs_delete'			=> array('lang' => 'ACL_U_RS_DELETE', 'cat' => 'reputation'),
		));
		$event['permissions'] = $permissions;
	}

	/**
	*
	*/
	public function memberlist_add_user_reputation($event)
	{
		$template_data = $event['template_data'];

		$template_data = array_merge($template_data, array(
			'REPUTATION' => $event['data']['user_reputation'],

			'U_VIEW_REP_LIST' => ($this->auth->acl_get('u_rs_view')) ? $this->controller_helper->url('reputation/' . $event['data']['user_id']) : '',
			'U_RATE_USER' => $this->controller_helper->url('reputation/rate/user/' . $event['data']['user_id']),

			'S_RATE_USER' => ($this->config['rs_user_rating'] && $this->auth->acl_get('u_rs_give')) ? true : false,
		));

		$event['template_data'] = $template_data;
	}

	/**
	*
	*/
	public function viewtopic_modify_sql_array($event)
	{
		if (!$this->config['rs_enable'] && !$this->config['rs_post_rating'])
		{
			return;
		}

		$sql_ary = $event['sql_ary'];

		$sql_ary['LEFT_JOIN'] = array_merge($sql_ary['LEFT_JOIN'], array(
			array(
				'FROM'	=> array($this->reputations_table => 'r'),
				'ON'	=> 'r.rep_from = ' . $this->user->data['user_id'] . ' AND r.post_id = p.post_id'
			)
		));
		$sql_ary['SELECT'] .= ', r.rep_id AS rated, r.point AS voting_points';

		$event['sql_ary'] = $sql_ary;
	}

	/**
	*
	*/
	public function viewtopic_post_rowset_add_reputation_data($event)
	{
		if (!$this->config['rs_enable'] && !$this->config['rs_post_rating'])
		{
			return;
		}

		$rowset_data = $event['rowset_data'];
		$row = $event['row'];

		$rowset_data = array_merge($rowset_data, array(
			'post_reputation'	=> $row['post_reputation'],
			'rated' 			=> (isset($row['rated'])) ? true : false,
			'post_vote_class'	=> (isset($row['rated'])) ? ($row['voting_points'] > 0 ? 'rated_good' : 'rated_bad') : '',
			'voting_points'		=> (isset($row['voting_points'])) ? $row['voting_points'] : 0,
		));
		$event['rowset_data'] = $rowset_data;
	}

	/**
	*
	*/
	public function viewtopic_cache_add_reputation_data($event)
	{
		if (!$this->config['rs_enable'] && !$this->config['rs_post_rating'])
		{
			return;
		}

		$user_cache_data = $event['user_cache_data'];

		$user_cache_data = array_merge($user_cache_data, array(
			'reputation'		=> $event['row']['user_reputation'],
		));
		$event['user_cache_data'] = $user_cache_data;
	}

	/**
	*
	*/
	public function viewtopic_postrow_add_reputation($event)
	{
		if (!$this->config['rs_enable'] && !$this->config['rs_post_rating'])
		{
			return;
		}

		$row = $event['row'];
		$user_poster_data = $event['user_poster_data'];

		$rs_box_color = $this->reputation_display->vote_class($row['post_reputation']);

		//Own post? Rated_good? Rated_bad?
		if ($this->user->data['user_id'] == $event['row']['user_id'])
		{
			$post_vote_class = 'own';
		}
		else
		{
			$post_vote_class = $row['post_vote_class'];
		}

		$post_row = $event['post_row'];
		$post_row = array_merge($post_row, array(
			'U_RATE_POST_POSITIVE'		=> $this->controller_helper->url('reputation/rate/post/positive/' . $event['row']['post_id']),
			'U_RATE_POST_NEGATIVE'		=> $this->controller_helper->url('reputation/rate/post/negative/' . $event['row']['post_id']),
			'U_VIEW_POST_REPUTATION'	=> $this->controller_helper->url('reputation/details/post/' . $event['row']['post_id']),
			'U_VIEW_USER_REPUTATION'	=> $this->controller_helper->url('reputation/details/user/' . $event['row']['user_id']),

			'S_VIEW_REPUTATION'		=> ($this->auth->acl_get('u_rs_view')) ? true : false,
			'S_GIVE_REPUTATION'		=> ($this->auth->acl_get('f_rs_give', $event['row']['forum_id']) && $this->auth->acl_get('u_rs_ratepost') && $event['row']['user_id'] != ANONYMOUS) ? true : false,
			'S_GIVE_NEGATIVE'		=> ($this->auth->acl_get('f_rs_give_negative', $event['row']['forum_id']) && $this->config['rs_negative_point']) ? true : false,

			'POST_REPUTATION'		=> $row['post_reputation'],
			'RS_BOX_COLOR'			=> $rs_box_color,
			'USER_REPUTATION'		=> $user_poster_data['reputation'],
			'RS_VOTE_CLASS'			=> $post_vote_class,
		));
		$event['post_row'] = $post_row;
	}

	/**
	*
	*/
	public function viewtopic_add_reputation($event)
	{
		if (!$this->config['rs_enable'] && !$this->config['rs_post_rating'])
		{
			return;
		}

		$topic_data = $event['topic_data'];

		$this->template->assign_vars(array(
			'S_FORUM_REPUTATION'	=> ($topic_data['enable_reputation'] && $topic_data['topic_type'] != POST_GLOBAL && $this->config['rs_post_rating']) ? true : false,
		));
	}
}
