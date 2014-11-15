<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\controller;

class details_controller
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\request\request */
	protected $request;

	/* @var \phpbb\symfony_request */
	protected $symfony_request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \pico\reputation\core\reputation_helper */
	protected $reputation_helper;

	/** @var \pico\reputation\core\reputation_manager */
	protected $reputation_manager;

	/** @ \pico\reputation\core\reputation_power */
	protected $reputation_power;

	/** @var string The table we use to store our reputations */
	protected $reputations_table;

	/** @var string The database table the reputation types are stored */
	protected $reputation_types_table;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth						Auth object
	* @param \phpbb\config\config $config				Config object
	* @param \phpbb\controller\helper					Controller helper object
	* @param \phpbb\db\driver\driver $db				Database object
	* @param \phpbb\pagination $pagination				Pagination object
	* @param \phpbb\request\request $request			Request object
	* @param \phpbb\symfony_request $symfony_request	Symfony Request object
	* @param \phpbb\template\template $template			Template object
	* @param \phpbb\user $user							User object
	* @param \pico\reputation\core\reputation_helper	Reputation helper object
	* @param \pico\reputation\core\reputation_manager	Reputation manager object
	* @param \pico\reputation\core\reputation_power		Reputation power object
	* @param string $reputations_table					Name of the table used to store reputations data
	* @param string $reputation_types_table				Name of the table used to store reputation types data
	* @param string $root_path							phpBB root path
	* @param string $php_ext							phpEx
	* @return \pico\reputation\controller\details_controller
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\db\driver\driver_interface $db, \phpbb\pagination $pagination, \phpbb\request\request $request, \phpbb\symfony_request $symfony_request, \phpbb\template\template $template, \phpbb\user $user, \pico\reputation\core\reputation_helper $reputation_helper, \pico\reputation\core\reputation_manager $reputation_manager, \pico\reputation\core\reputation_power $reputation_power, $reputations_table, $reputation_types_table, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->symfony_request = $symfony_request;
		$this->template = $template;
		$this->user = $user;
		$this->reputation_helper = $reputation_helper;
		$this->reputation_manager = $reputation_manager;
		$this->reputation_power = $reputation_power;
		$this->reputations_table = $reputations_table;
		$this->reputation_types_table = $reputation_types_table;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Main reputation details controller 
	*
	* @param int $uid			User ID taken from the URL
	* @param string $sort_key	Sort key: id|username|time|point|action (default: id)
	* @param string $sort_dir	Sort direction: dsc|asc (descending|ascending) (default: dsc)
	* @param int $page			Page number taken from the URL
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	* @access public
	*/
	public function details($uid, $sort_key, $sort_dir, $page)
	{
		$this->user->add_lang_ext('pico/reputation', array('reputation_system', 'reputation_rating'));

		// Check user permissions - if user can not view reputation details, throw the error
		if (!$this->auth->acl_get('u_rs_view'))
		{
			$meta_info = append_sid("{$this->root_path}index.$this->php_ext", "");
			$message = $user->lang['RS_VIEW_DISALLOWED'] . '<br /><br />' . $this->user->lang('RETURN_INDEX', '<a href="' . append_sid("{$this->root_path}index.$this->php_ext", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		// User data
		$sql = 'SELECT *
			FROM ' . USERS_TABLE . "
			WHERE user_type <> 2
				AND user_id = $uid";
		$result = $this->db->sql_query($sql);
		$user_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);        

		// Check if an user exists - if not, throw the error and return to the index page
		if (empty($user_row))
		{
			$meta_info = append_sid("{$this->root_path}index.$this->php_ext", "");
			$message = $this->user->lang['RS_NO_USER_ID'] . '<br /><br />' . $this->user->lang('RETURN_INDEX', '<a href="' . append_sid("{$this->root_path}index.$this->php_ext", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		// Count reputation rows for the current user
		$sql = 'SELECT COUNT(reputation_id) AS total_reps
			FROM ' . $this->reputations_table . "
			WHERE user_id_to = $uid";
		$result = $this->db->sql_query($sql);
		$total_reps = (int) $this->db->sql_fetchfield('total_reps');
		$this->db->sql_freeresult($result);

		// Sort keys
		$sort_key_sql = array(
			'username'	=> 'u.username_clean',
			'time'		=> 'r.reputation_time',
			'point'		=> 'r.reputation_points',
			'action'	=> 'rt.reputation_type_name',
			'id'		=> 'r.reputation_id'
		);

		// Sql order depends on sort key
		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'dsc') ? 'DESC' : 'ASC');

		// Start value - it is based on page
		$start = ($page - 1) * $this->config['rs_per_page'];

		$post_type_id = (int) $this->reputation_manager->get_reputation_type_id('post');

		$sql_array = array(
			'SELECT'	=> 'r.*, rt.reputation_type_name, u.group_id, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, p.post_id, p.forum_id, p.post_subject',
			'FROM'	=> array(
				$this->reputations_table => 'r',
				$this->reputation_types_table => 'rt',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.user_id_from = u.user_id ',
				),
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'p.post_id = r.reputation_item_id
						AND r.reputation_type_id = ' . $post_type_id,
				),
			),
			'WHERE'	=> 'r.user_id_to = ' . $uid . '
				AND r.reputation_type_id = rt.reputation_type_id',
			'ORDER_BY'	=> $order_by
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, $this->config['rs_per_page'], $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('reputation', array(
				'ID'			=> $row['reputation_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id_from'], $row['username'], $row['user_colour']),
				'ACTION'		=> $this->user->lang('RS_' . strtoupper($row['reputation_type_name']) . '_RATING'),
				'AVATAR'		=> phpbb_get_user_avatar($row),
				'TIME'			=> $this->user->format_date($row['reputation_time']),
				'COMMENT'		=> $row['reputation_comment'],
				'POINTS'		=> $row['reputation_points'],
				'POINTS_CLASS'	=> $this->reputation_helper->reputation_class($row['reputation_points']),
				'POINTS_TITLE'	=> $this->user->lang('RS_POINTS_TITLE', $row['reputation_points']),

				'U_DELETE'	=> $this->helper->route('reputation_delete_controller', array('rid' => $row['reputation_id'])),

				'S_COMMENT'	=> !empty($row['reputation_comment']),
				'S_DELETE'	=> ($this->auth->acl_get('m_rs_moderate') || ($row['user_id_from'] == $this->user->data['user_id'] && $this->auth->acl_get('u_rs_delete'))) ? true : false,
			));

			// Generate post url
			$this->reputation_manager->generate_post_link($row);
		}
		$this->db->sql_freeresult($result);

		// User reputation rank
		if (!function_exists('phpbb_get_user_rank'))
		{
			include($this->root_path . 'includes/functions_display.' . $this->php_ext);
		}
		$user_rank_data = phpbb_get_user_rank($user_row, $user_row['user_posts']);

		// Reputation statistics
		$positive_count = $negative_count = 0;
		$positive_sum = $negative_sum = 0;
		$positive_week = $negative_week = 0;
		$positive_month = $negative_month = 0;
		$positive_6months = $negative_6months = 0;
		$post_count = $user_count = 0;

		$last_week = time() - 604800;
		$last_month = time() - 2678400;
		$last_6months = time() - 16070400;

		$user_type_id = (int) $this->reputation_manager->get_reputation_type_id('user');

		$sql = 'SELECT reputation_time, reputation_type_id, reputation_points
			FROM ' . $this->reputations_table . "
			WHERE user_id_to = $uid";
		$result = $this->db->sql_query($sql);

		while ($reputation_vote = $this->db->sql_fetchrow($result))
		{
			if ($reputation_vote['reputation_points'] > 0)
			{
				$positive_count++;
				$positive_sum += $reputation_vote['reputation_points'];
				if ($reputation_vote['reputation_time'] >= $last_week)
				{
					$positive_week++;
				}
				if ($reputation_vote['reputation_time'] >= $last_month)
				{
					$positive_month++;
				}
				if ($reputation_vote['reputation_time'] >= $last_6months)
				{
					$positive_6months++;
				}
			}
			else if ($reputation_vote['reputation_points'] < 0)
			{
				$negative_count++;
				$negative_sum += $reputation_vote['reputation_points'];
				if ($reputation_vote['reputation_time'] >= $last_week)
				{
					$negative_week++;
				}
				if ($reputation_vote['reputation_time'] >= $last_month)
				{
					$negative_month++;
				}
				if ($reputation_vote['reputation_time'] >= $last_6months)
				{
					$negative_6months++;
				}
			}

			if ($reputation_vote['reputation_type_id'] == $post_type_id)
			{
				$post_count += $reputation_vote['reputation_points'];
			}
			else if ($reputation_vote['reputation_type_id'] == $user_type_id)
			{
				$user_count += $reputation_vote['reputation_points'];
			}
		}
		$this->db->sql_freeresult($result);

		// User reputation power
		if ($this->config['rs_enable_power'])
		{
			$used_power = $this->reputation_power->used($user_row['user_id']);
			$user_max_voting_power = $this->reputation_power->get($user_row['user_posts'], $user_row['user_regdate'], $user_row['user_reputation'], $user_row['user_warnings'], $user_row['group_id']);
			$user_power_explain = $this->reputation_power->explain();
			$voting_power_left = '';

			if ($this->config['rs_power_renewal'])
			{
				$voting_power_left = $user_max_voting_power - $used_power;

				if ($voting_power_left < 0)
				{
					$voting_power_left = 0;
				}
			}

			$this->template->assign_vars(array(
				'S_RS_POWER_EXPLAIN'		=> $this->config['rs_power_explain'] ? true : false,
				'S_RS_GROUP_POWER'			=> (isset($user_power_explain['GROUP_VOTING_POWER'])) ? true : false,

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
		$this->pagination->generate_template_pagination(
			array(
				'routes'	=> 'reputation_details_controller',
				'params'	=> array(
					'uid'		=> $uid,
					'sort_key'	=> $sort_key,
					'sort_dir'	=> $sort_dir,
				),
			),
			'pagination',
			'page',
			$total_reps,
			$this->config['rs_per_page'],
			$start
		);

		$this->template->assign_vars(array(
			'USER_ID'			=> $user_row['user_id'],
			'USERNAME'			=> get_username_string('username', $user_row['user_id'], $user_row['username'], $user_row['user_colour'], true),
			'USERNAME_FULL'		=> get_username_string('full', $user_row['user_id'], $user_row['username'], $user_row['user_colour']),
			'REPUTATION'		=> ($user_row['user_reputation']),
			'AVATAR_IMG'		=> phpbb_get_user_avatar($user_row),
			'RANK_IMG'			=> $user_rank_data['img'],
			'RANK_IMG_SRC'		=> $user_rank_data['img_src'],
			'RANK_TITLE'		=> $user_rank_data['title'],
			'REPUTATION_CLASS'	=> $this->reputation_helper->reputation_class($user_row['user_reputation']),

			'PAGE_NUMBER'		=> $this->pagination->on_page($total_reps, $this->config['rs_per_page'], $start),
			'TOTAL_REPS'		=> $this->user->lang('LIST_REPUTATIONS', $total_reps),

			'U_SORT_USERNAME'	=> $this->helper->route('reputation_details_controller', array('uid' => $uid, 'sort_key' => 'username', 'sort_dir' => ($sort_key == 'username' && $sort_dir == 'asc') ? 'dsc' : 'asc',)),
			'U_SORT_TIME'		=> $this->helper->route('reputation_details_controller', array('uid' => $uid, 'sort_key' => 'time', 'sort_dir' => ($sort_key == 'time' && $sort_dir == 'asc') ? 'dsc' : 'asc',)),
			'U_SORT_POINT'		=> $this->helper->route('reputation_details_controller', array('uid' => $uid, 'sort_key' => 'point', 'sort_dir' => ($sort_key == 'point' && $sort_dir == 'asc') ? 'dsc' : 'asc',)),
			'U_SORT_ACTION'		=> $this->helper->route('reputation_details_controller', array('uid' => $uid, 'sort_key' => 'action', 'sort_dir' => ($sort_key == 'action' && $sort_dir == 'asc') ? 'dsc' : 'asc',)),

			'U_CLEAR'			=> $this->helper->route('reputation_clear_user_controller', array('uid' =>  $uid,)),

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
			'S_CLEAR'			=> $this->auth->acl_gets('m_rs_moderate') ? true : false,
		 ));

		return $this->helper->render('details.html', $this->user->lang('RS_DETAILS'));
	}

	/**
	* Post details controller
	*
	* @param int $post_id		Post ID taken from the URL
	* @param string $sort_key	Sort key: id|username|time|point
	* @param string $sort_dir	Sort direction: dsc|asc (descending|ascending)
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	* @access public
	*/
	public function postdetails($post_id, $sort_key, $sort_dir)
	{
		$this->user->add_lang_ext('pico/reputation', array('reputation_system', 'reputation_rating'));

		$is_ajax = $this->request->is_ajax();
		$referer = $this->symfony_request->get('_referer');

		if (empty($this->config['rs_enable']))
		{
			if ($is_ajax)
			{
				$json_response = new \phpbb\json_response();
				$json_data = array(
					'error_msg' => $this->user->lang('RS_DISABLED'),
				);
				$json_response->send($json_data);
			}

			redirect(append_sid("{$this->root_path}index.$this->php_ext"));
		}

		$sql_array = array(
			'SELECT'	=> 'p.forum_id, p.poster_id, p.post_subject, u.username, u.user_colour',
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

		//We couldn't find the post. It might be deleted while user tried voting?
		if (empty($post_row))
		{
			$message = $this->user->lang('RS_NO_POST');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if (!$this->auth->acl_get('u_rs_view'))
		{
			$message = $this->user->lang('RS_VIEW_DISALLOWED');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $post_row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$reputation_type_id = (int) $this->reputation_manager->get_reputation_type_id('post');

		$sort_key_sql = array(
			'username'	=> 'u.username_clean',
			'time'		=> 'r.reputation_time',
			'point'		=> 'r.reputation_points',
			'id'		=> 'r.reputation_id'
		);

		// Sql order depends on sort key
		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'dsc') ? 'DESC' : 'ASC');

		$sql_array = array(
			'SELECT'	=> 'r.* , u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height',
			'FROM'		=> array($this->reputations_table => 'r'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'u.user_id = r.user_id_from',
				),
			),
			'WHERE'		=> 'r.reputation_item_id = ' . $post_id . '
						AND r.reputation_type_id = ' . $reputation_type_id,
			'ORDER_BY'	=> $order_by
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('reputation', array(
				'ID'			=> $row['reputation_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id_from'], $row['username'], $row['user_colour']),
				'AVATAR'		=> phpbb_get_user_avatar($row),
				'TIME'			=> $this->user->format_date($row['reputation_time']),
				'COMMENT'		=> $row['reputation_comment'],
				'POINTS'		=> $row['reputation_points'],
				'POINTS_CLASS'	=> $this->reputation_helper->reputation_class($row['reputation_points']),
				'POINTS_TITLE'	=> $this->user->lang('RS_POINTS_TITLE', $row['reputation_points']),

				'U_DELETE'	=> $this->helper->route('reputation_delete_controller', array('rid' => $row['reputation_id'])),

				'S_COMMENT'	=> !empty($row['reputation_comment']),
				'S_DELETE'	=> ($this->auth->acl_get('m_rs_moderate') || ($row['user_id_from'] == $this->user->data['user_id'] && $this->auth->acl_get('u_rs_delete'))) ? true : false,
			));
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'POST_ID'		=> $post_id,
			'POST_SUBJECT'	=> $post_row['post_subject'],
			'POST_AUTHOR'	=> get_username_string('full', $post_row['poster_id'], $post_row['username'], $post_row['user_colour']),

			'U_SORT_USERNAME'	=> $this->helper->route('reputation_post_details_controller', array('post_id' => $post_id, 'sort_key' => 'username', 'sort_dir' => ($sort_key == 'username' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_TIME'		=> $this->helper->route('reputation_post_details_controller', array('post_id' => $post_id, 'sort_key' => 'time', 'sort_dir' => ($sort_key == 'time' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_POINT'		=> $this->helper->route('reputation_post_details_controller', array('post_id' => $post_id, 'sort_key' => 'point', 'sort_dir' => ($sort_key == 'point' && $sort_dir == 'asc') ? 'dsc' : 'asc')),

			'U_CLEAR'				=> $this->helper->route('reputation_clear_post_controller', array('post_id' =>  $post_id)),
			'U_REPUTATION_REFERER'	=> $referer,

			'S_RS_AVATAR'		=> $this->config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_RS_POINTS_IMG'	=> $this->config['rs_point_type'] ? true : false,
			'S_CLEAR'			=> $this->auth->acl_gets('m_rs_moderate') ? true : false,
			'S_IS_AJAX'			=> $is_ajax ? true : false,
		));

		return $this->helper->render('postdetails.html');
	}

	/**
	* User details controller
	*
	* @param int $uid			User ID taken from the URL
	* @param string $sort_key	Sort key: id|username|time|point|action (default: id)
	* @param string $sort_dir	Sort direction: dsc|asc (descending|ascending) (default: dsc)
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	* @access public
	*/
	public function userdetails($uid, $sort_key, $sort_dir)
	{
		$this->user->add_lang_ext('pico/reputation', array('reputation_system', 'reputation_rating'));

		$is_ajax = $this->request->is_ajax();
		$referer = $this->symfony_request->get('_referer');

		if (empty($this->config['rs_enable']))
		{
			if ($is_ajax)
			{
				$json_response = new \phpbb\json_response();
				$json_data = array(
					'error_msg' => $this->user->lang('RS_DISABLED'),
				);
				$json_response->send($json_data);
			}

			redirect(append_sid("{$this->root_path}index.$this->php_ext"));
		}

		$sql = 'SELECT user_id, username, user_colour
			FROM ' . USERS_TABLE . '
			WHERE user_type <> 2
				AND user_id =' . (int) $uid;
		$result = $this->db->sql_query($sql);
		$user_row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (empty($user_row))
		{
			$message = $this->user->lang('RS_NO_USER_ID');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if (!$this->auth->acl_get('u_rs_view'))
		{
			$message = $this->user->lang('RS_VIEW_DISALLOWED');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$sort_key_sql = array(
			'username'	=> 'u.username_clean',
			'time'		=> 'r.reputation_time',
			'point'		=> 'r.reputation_points',
			'action'	=> 'rt.reputation_type_name',
			'id'		=> 'r.reputation_id'
		);

		// Sql order depends on sort key
		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'dsc') ? 'DESC' : 'ASC');

		$reputation_type_id = (int) $this->reputation_manager->get_reputation_type_id('post');

		$sql_array = array(
			'SELECT'	=> 'r.*, rt.reputation_type_name, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, p.post_id, p.forum_id, p.post_subject',
			'FROM'		=> array(
				$this->reputations_table => 'r',
				$this->reputation_types_table => 'rt',
			),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'u.user_id = r.user_id_from',
				),
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'p.post_id = r.reputation_item_id
						AND r.reputation_type_id = ' . $reputation_type_id,
				),
			),
			'WHERE'		=> 'r.user_id_to = ' . $uid . '
				AND r.reputation_type_id = rt.reputation_type_id',
			'ORDER_BY'	=> $order_by
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('reputation', array(
				'ID'			=> $row['reputation_id'],
				'USERNAME'		=> get_username_string('full', $row['user_id_from'], $row['username'], $row['user_colour']),
				'ACTION'		=> $this->user->lang('RS_' . strtoupper($row['reputation_type_name']) . '_RATING'),
				'AVATAR'		=> phpbb_get_user_avatar($row),
				'TIME'			=> $this->user->format_date($row['reputation_time']),
				'COMMENT'		=> $row['reputation_comment'],
				'POINTS'		=> $row['reputation_points'],
				'POINTS_CLASS'	=> $this->reputation_helper->reputation_class($row['reputation_points']),
				'POINTS_TITLE'	=> $this->user->lang('RS_POINTS_TITLE', $row['reputation_points']),

				'U_DELETE'	=> $this->helper->route('reputation_delete_controller', array('rid' => $row['reputation_id'])),

				'S_COMMENT'	=> !empty($row['reputation_comment']),
				'S_DELETE'	=> ($this->auth->acl_get('m_rs_moderate') || ($row['user_id_from'] == $this->user->data['user_id'] && $this->auth->acl_get('u_rs_delete'))) ? true : false,
			));

			// Generate post url
			$this->reputation_manager->generate_post_link($row);
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'USER_ID'			=> $uid,

			'U_USER_DETAILS'	=> $this->helper->route('reputation_details_controller', array('uid' => $uid)),
			'U_SORT_USERNAME'	=> $this->helper->route('reputation_user_details_controller', array('uid' => $uid, 'sort_key' => 'username', 'sort_dir' => ($sort_key == 'username' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_TIME'		=> $this->helper->route('reputation_user_details_controller', array('uid' => $uid, 'sort_key' => 'time', 'sort_dir' => ($sort_key == 'time' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_POINT'		=> $this->helper->route('reputation_user_details_controller', array('uid' => $uid, 'sort_key' => 'point', 'sort_dir' => ($sort_key == 'point' && $sort_dir == 'asc') ? 'dsc' : 'asc')),
			'U_SORT_ACTION'		=> $this->helper->route('reputation_user_details_controller', array('uid' => $uid, 'sort_key' => 'action', 'sort_dir' => ($sort_key == 'action' && $sort_dir == 'asc') ? 'dsc' : 'asc')),

			'U_CLEAR'				=> $this->helper->route('reputation_clear_user_controller', array('uid' =>  $uid)),
			'U_REPUTATION_REFERER'	=> $referer,

			'L_RS_USER_REPUTATION'	=> $this->user->lang('RS_USER_REPUTATION', get_username_string('username', $user_row['user_id'], $user_row['username'], $user_row['user_colour'])),

			'S_RS_AVATAR'		=> $this->config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_RS_POINTS_IMG'	=> $this->config['rs_point_type'] ? true : false,
			'S_CLEAR'			=> $this->auth->acl_gets('m_rs_moderate') ? true : false,
			'S_IS_AJAX'			=> $is_ajax ? true : false,
		));

		return $this->helper->render('userdetails.html');
	}
}