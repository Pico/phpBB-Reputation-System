<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\controller;

class details
{
	/** @var \phpbb\auth\auth */
	protected $auth;

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

	/** @ reputation display */
	protected $reputation_display;

	/**  @ reputation helper */
	protected $reputation_helper;

	/** @ reputation power */
	protected $reputation_power;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var string */
	protected $reputations_table;

	/** @var bool*/
	private $is_ajax;

	/**
	* Constructor
	* 
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $controller_helper
	* @param \phpbb\db\driver\driver $db
	* @param \phpbb\request\request $request
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param string $reputation_display Reputation display service
	* @param string $reputation_helper Reputation helper service
	* @param string $reputation_power Reputation power service
	* @param string $phpbb_root_path Root path
	* @param string $php_ext PHP extension
	* @param string $reputation_table Name of the table uses to store reputations
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $controller_helper,  \phpbb\db\driver\driver $db,\phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $reputation_display, $reputation_helper, $reputation_power, $phpbb_root_path, $php_ext, $reputations_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->controller_helper = $controller_helper;
		$this->template = $template;
		$this->user = $user;
		$this->reputation_display = $reputation_display;
		$this->reputation_helper = $reputation_helper;
		$this->reputation_power = $reputation_power;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->reputations_table = $reputations_table;

		if (!function_exists('get_user_avatar') || !function_exists('get_user_rank'))
		{
			include($phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
		}

		$this->user->add_lang_ext('pico88/reputation', 'reputation_system');

		$this->is_ajax = $request->is_ajax();

		if (!$this->config['rs_enable'])
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_DISABLED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}
	}

	/**
	* Main controller to be accessed with the URL /reputation/{uid}/{sort_key}/{sort_dir}/{page}
	* (where {uid} is the placeholder for a value)
	* (where {sort_key} is the placeholder for a string)
	* (where {sort_dir} is the placeholder for a string)
	* (where {page} is the placeholder for a value)
	*
	* @param int	$uid		User ID taken from the URL
	* @param string	$sort_key	Sort key: id|username|time|point|action|post (default: id - get from routing.yml)
	* @param string	$sort_dir	Sort direction: dsc|asc (descending|ascending) (default: dsc - get from routing.yml)
	* @param int	$page		Page number taken from the URL (default: 1 - get from routing.yml)
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function main($uid, $sort_key, $sort_dir, $page)
	{
		// Check user permissions - if user can not view reputation details, throw the error
		if (!$this->auth->acl_get('u_rs_view'))
		{
			$meta_info = append_sid("{$this->phpbb_root_path}index.$this->php_ext", "");
			$message = $user->lang['RS_VIEW_DISALLOWED'] . '<br /><br />' . $this->user->lang('RETURN_INDEX', '<a href="' . append_sid("{$this->phpbb_root_path}index.$this->php_ext", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		// Select user data
		$sql = 'SELECT *
			FROM ' . USERS_TABLE . "
			WHERE user_type <> 2
				AND user_id = $uid";
		$result = $this->db->sql_query($sql);
		$user_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);        

		// Check if the current user exists - if not, throw the error
		if (empty($user_row))
		{
			$meta_info = append_sid("{$this->phpbb_root_path}index.$this->php_ext", "");
			$message = $this->user->lang['RS_NO_USER_ID'] . '<br /><br />' . $this->user->lang('RETURN_INDEX', '<a href="' . append_sid("{$this->phpbb_root_path}index.$this->php_ext", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		// Count reputation rows for the current user
		$sql = 'SELECT COUNT(rep_id) AS total_reps
			FROM ' . $this->reputations_table . "
			WHERE rep_to = $uid";
		$result = $this->db->sql_query($sql);
		$total_reps = (int) $this->db->sql_fetchfield('total_reps');
		$this->db->sql_freeresult($result);

		// Sort keys
		$sort_key_sql = array(
			'id'		=> 'r.rep_id',
			'username'	=> 'u.username_clean',
			'time'		=> 'r.time',
			'point'		=> 'r.point',
			'action'	=> 'r.action',
			'post'		=> 'r.post_id',
		);

		// Sql order depends on sort key
		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'dsc') ? 'DESC' : 'ASC');

		// Start vaule - it is based on page
		$start = ($page - 1) * $this->config['rs_per_page'];

		$sql_array = array(
			'SELECT'	=> 'r.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_reputation, p.post_id AS real_post_id, p.forum_id, p.post_subject',
			'FROM'		=> array($this->reputations_table => 'r'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_from = u.user_id',
				),
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'p.post_id = r.post_id',
				),
			),
			'WHERE'		=> 'r.rep_to = ' . $uid,
			'ORDER_BY'	=> $order_by . ', r.rep_id ASC'
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, $this->config['rs_per_page'], $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->reputation_display->row($row);
		}
		$this->db->sql_freeresult($result);

		// User reputation rank
		$rank_title = $rank_img = $rank_img_src = '';
		get_user_rank($user_row['user_rank'], $user_row['user_posts'], $rank_title, $rank_img, $rank_img_src);

		// User avatar
		$avatar_img = get_user_avatar($user_row['user_avatar'], $user_row['user_avatar_type'], $user_row['user_avatar_width'], $user_row['user_avatar_height']);

		// Reputation stats
		$positive_count = $negative_count = 0;
		$positive_sum = $negative_sum = 0;
		$positive_week = $negative_week = 0;
		$positive_month = $negative_month = 0;
		$positive_6months = $negative_6months = 0;
		$post_count = $user_count = 0;

		$last_week = time() - 604800;
		$last_month = time() - 2678400;
		$last_6months = time() - 16070400;

		$sql = 'SELECT action, time, point
			FROM ' . $this->reputations_table . "
			WHERE rep_to = $uid";
		$result = $this->db->sql_query($sql);

		while ($reputation_vote = $this->db->sql_fetchrow($result))
		{
			if ($reputation_vote['point'] > 0)
			{
				$positive_count++;
				$positive_sum += $reputation_vote['point'];
				if ($reputation_vote['time'] >= $last_week)
				{
					$positive_week++;
				}
				if ($reputation_vote['time'] >= $last_month)
				{
					$positive_month++;
				}
				if ($reputation_vote['time'] >= $last_6months)
				{
					$positive_6months++;
				}
			}
			else if ($reputation_vote['point'] < 0)
			{
				$negative_count++;
				$negative_sum += $reputation_vote['point'];
				if ($reputation_vote['time'] >= $last_week)
				{
					$negative_week++;
				}
				if ($reputation_vote['time'] >= $last_month)
				{
					$negative_month++;
				}
				if ($reputation_vote['time'] >= $last_6months)
				{
					$negative_6months++;
				}
			}

			if ($reputation_vote['action'] == 1)
			{
				$post_count += $reputation_vote['point'];
			}
			else if ($reputation_vote['action'] == 2)
			{
				$user_count += $reputation_vote['point'];
			}
		}
		$this->db->sql_freeresult($result);

		// User reputation power
		if ($this->config['rs_enable_power'])
		{
			$used_power = $this->reputation_power->used($user_row['user_id']);
			$user_max_voting_power = $this->reputation_power->get($user_row['user_posts'], $user_row['user_regdate'], $user_row['user_reputation'], $user_row['user_warnings']);
			$user_power_explain = $this->reputation_power->explain();
			$voting_power_left = '';

			if ($this->config['rs_power_renewal'])
			{
				$voting_power_left = $user_max_voting_power - $used_power;

				if ($voting_power_left <= 0)
				{
					$voting_power_left = 0;
				}
			}

			$this->template->assign_vars(array(
				'S_RS_POWER_EXPLAIN'		=> $this->config['rs_power_explain'] ? true : false,
				'RS_POWER'					=> $user_max_voting_power,
				'RS_POWER_LEFT'				=> $this->config['rs_power_renewal'] ? $this->user->lang('RS_VOTE_POWER_LEFT', $voting_power_left, $user_max_voting_power) : '',
				'RS_CFG_TOTAL_POSTS'		=> $this->config['rs_total_posts'] ? true : false,
				'RS_CFG_MEMBERSHIP_DAYS'	=> $this->config['rs_membership_days'] ? true : false,
				'RS_CFG_REP_POINT'			=> $this->config['rs_power_rep_point'] ? true : false,
				'RS_CFG_LOOSE_WARN'			=> $this->config['rs_power_lose_warn'] ? true : false,
			));

			$this->template->assign_vars($user_power_explain);
		}

		// Generate pagination
		$pagination_url = $this->controller_helper->url('reputation/' . $uid . '/' . $sort_key . '/' . $sort_dir . '/%d');
		phpbb_generate_template_pagination($this->template, $pagination_url, 'pagination', '/%d', $total_reps, $this->config['rs_per_page'], $start);

		$this->template->assign_vars(array(
			'USER_ID'			=> $user_row['user_id'],
			'USERNAME'			=> get_username_string('username', $user_row['user_id'], $user_row['username'], $user_row['user_colour']),
			'USERNAME_FULL'		=> get_username_string('full', $user_row['user_id'], $user_row['username'], $user_row['user_colour']),
			'REPUTATIONS'		=> ($user_row['user_reputation']),
			'AVATAR_IMG'		=> $avatar_img,
			'RANK_TITLE'		=> $rank_title,
			'RANK_IMG'			=> $rank_img,
			'REPUTATION_BOX'	=> ($user_row['user_reputation'] == 0) ? 'neutral' : (($user_row['user_reputation'] > 0) ? 'positive' : 'negative'),

			'PAGE_NUMBER'		=> phpbb_on_page($this->template, $this->user, $pagination_url, $total_reps, $this->config['rs_per_page'], $start),
			'TOTAL_REPS'		=> $this->user->lang('LIST_REPUTATIONS', $total_reps),

			'U_SORT_USERNAME'	=> $this->controller_helper->url('reputation/' . $uid . '/username/' . (($sort_key == 'username' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_TIME'		=> $this->controller_helper->url('reputation/' . $uid . '/time/' . (($sort_key == 'time' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_POINTS'		=> $this->controller_helper->url('reputation/' . $uid . '/point/' . (($sort_key == 'point' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_ACTION'		=> $this->controller_helper->url('reputation/' . $uid . '/action/' . (($sort_key == 'action' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_POSTS'		=> $this->controller_helper->url('reputation/' . $uid . '/post/' . (($sort_key == 'post' && $sort_dir == 'asc') ? 'dsc' : 'asc')),

			'POST_COUNT'		=> $post_count,
			'USER_COUNT'		=> $user_count,
			'POSITIVE_COUNT'	=> $positive_count,
			'POSITIVE_SUM'		=> $positive_sum,
			'POSITIVE_WEEK'		=> $positive_week,
			'POSITIVE_MONTH'	=> $positive_month,
			'POSITIVE_6MONTHS'	=> $positive_6months,
			'NEGATIVE_COUNT'	=> $negative_count,
			'NEGATIVE_SUM'		=> $negative_sum,
			'NEGATIVE_WEEK'		=> $negative_week,
			'NEGATIVE_MONTH'	=> $negative_month,
			'NEGATIVE_6MONTHS'	=> $negative_6months,

			'S_RS_POST_RATING' 	=> $this->config['rs_post_rating'] ? true : false,
			'S_RS_USER_RATING' 	=> $this->config['rs_user_rating'] ? true : false,
			'S_RS_AVATAR'		=> $this->config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_RS_NEGATIVE'		=> $this->config['rs_negative_point'] ? true : false,
			'S_RS_POWER_ENABLE'	=> $this->config['rs_enable_power'] ? true : false,
			'S_TRUNCATE'		=> $this->auth->acl_gets('m_rs_moderate') ? true : false,
		 ));

		return $this->controller_helper->render('details.html', $this->user->lang['RS_DETAILS']);
	}

	/**
	* Post details controller to be accessed with the URL /reputation/details/post/{post_id}/{sort_key}/{sort_dir}
	* (where {post_id} is the placeholder for a value)
	* (where {sort_key} is the placeholder for a string)
	* (where {sort_dir} is the placeholder for a string)
	*
	* @param int	$post_id	Post ID taken from the URL
	* @param string	$sort_key	Sort key: id|username|time|point (default: id - get from routing.yml)
	* @param string	$sort_dir	Sort direction: dsc|asc (descending|ascending) (default: dsc - get from routing.yml)
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function postdetails($post_id, $sort_key, $sort_dir)
	{
		if (!$this->auth->acl_get('u_rs_view'))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_VIEW_DISALLOWED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		$sql_array = array(
			'SELECT'	=> 'p.poster_id, p.post_subject, u.username, u.user_colour',
			'FROM'		=> array(
				POSTS_TABLE => 'p',
				USERS_TABLE => 'u'
			),
			'WHERE'		=> 'p.post_id = ' . $post_id . '
				AND p.poster_id = u.user_id',
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$post_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		//We couldn't find this post. May be it was deleted while user voted?
		if (empty($post_row))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_NO_POST']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		$sort_key_sql = array(
			'username'	=> 'u.username_clean',
			'time'		=> 'r.time',
			'point'		=> 'r.point',
			'id'		=> 'r.rep_id'
		);

		// Sql order depends on sort key
		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'dsc') ? 'DESC' : 'ASC');

		$sql_array = array(
			'SELECT'	=> 'r.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_reputation',
			'FROM'		=> array($this->reputations_table => 'r'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_from = u.user_id',
				),
			),
			'WHERE'		=> 'r.post_id = ' . $post_id,
			'ORDER_BY'	=> $order_by
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->reputation_display->row($row, $this->is_ajax);
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'POST_ID'			=> $post_id,
			'POST_SUBJECT'		=> $post_row['post_subject'],
			'POST_AUTHOR'		=> get_username_string('full', $post_row['poster_id'], $post_row['username'], $post_row['user_colour']),

			'U_SORT_USERNAME'	=> $this->reputation_helper->generate_url('reputation/details/post/' . $post_id . '/username/' . (($sort_key == 'username' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),
			'U_SORT_TIME'		=> $this->reputation_helper->generate_url('reputation/details/post/' . $post_id . '/time/' . (($sort_key == 'time' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),
			'U_SORT_POINTS'		=> $this->reputation_helper->generate_url('reputation/details/post/' . $post_id . '/point/' . (($sort_key == 'point' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),

			'S_RS_AVATAR'		=> $this->config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_TRUNCATE'		=> $this->auth->acl_gets('m_rs_moderate') ? true : false,
			'S_IS_AJAX'			=> $this->is_ajax,
		));

		return $this->controller_helper->render('postdetails.html');
	}

	/**
	* User details controller to be accessed with the URL /reputation/details/user/{uid}/{sort_key}/{sort_dir}
	* (where {uid} is the placeholder for a value)
	* (where {sort_key} is the placeholder for a string)
	* (where {sort_dir} is the placeholder for a string)
	*
	* @param int	$uid		User ID taken from the URL
	* @param string	$sort_key	Sort key: id|username|time|point|action|post (default: id - get from routing.yml)
	* @param string	$sort_dir	Sort direction: dsc|asc (descending|ascending) (default: dsc - get from routing.yml)
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function userdetails($uid, $sort_key, $sort_dir)
	{
		if (!$this->auth->acl_get('u_rs_view'))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_VIEW_DISALLOWED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		$sql = 'SELECT user_id, username, user_colour
			FROM ' . USERS_TABLE . "
			WHERE user_type <> 2
				AND user_id = $uid";
		$result = $this->db->sql_query($sql);
		$user_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (empty($user_row))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_NO_USER_ID']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		$sort_key_sql = array(
			'username'	=> 'u.username_clean',
			'time'		=> 'r.time',
			'point'		=> 'r.point',
			'action'	=> 'r.action',
			'post'		=> 'r.post_id',
			'id'		=> 'r.rep_id'
		);

		// Sql order depends on sort key
		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'dsc') ? 'DESC' : 'ASC');

		$sql_array = array(
			'SELECT'	=> 'r.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_reputation, p.post_id AS real_post_id, p.forum_id, p.post_subject',
			'FROM'		=> array($this->reputations_table => 'r'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_from = u.user_id',
				),
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'p.post_id = r.post_id',
				),
			),
			'WHERE'		=> 'r.rep_to = ' . $uid,
			'ORDER_BY'	=> $order_by
		);

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->reputation_display->row($row, $this->is_ajax);
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'USER_ID'			=> $uid,

			'U_USER_DETAILS'	=> $this->reputation_helper->generate_url('reputation/' . $uid, $this->is_ajax),
			'U_SORT_USERNAME'	=> $this->reputation_helper->generate_url('reputation/details/user/' . $uid . '/username/' . (($sort_key == 'username' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),
			'U_SORT_TIME'		=> $this->reputation_helper->generate_url('reputation/details/user/' . $uid . '/time/' . (($sort_key == 'time' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),
			'U_SORT_POINTS'		=> $this->reputation_helper->generate_url('reputation/details/user/' . $uid . '/point/' . (($sort_key == 'point' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),
			'U_SORT_ACTION'		=> $this->reputation_helper->generate_url('reputation/details/user/' . $uid . '/action/' . (($sort_key == 'action' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),
			'U_SORT_POSTS'		=> $this->reputation_helper->generate_url('reputation/details/user/' . $uid . '/post/' . (($sort_key == 'post' && $sort_dir == 'asc') ? 'dsc' : 'asc'), $this->is_ajax),

			'L_RS_USER_REPUTATION'	=> $this->user->lang('RS_USER_REPUTATION', get_username_string('username', $user_row['user_id'], $user_row['username'], $user_row['user_colour'])),

			'S_RS_AVATAR'		=> $this->config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_TRUNCATE'		=> $this->auth->acl_gets('m_rs_moderate') ? true : false,
			'S_IS_AJAX'			=> $this->is_ajax,
		));

		return $this->controller_helper->render('userdetails.html');
	}

}