<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\ucp;

class reputation_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $auth, $db, $user, $template, $request;
		global $config, $phpbb_root_path, $phpEx, $action, $phpbb_container;

		$user->add_lang_ext('pico88/reputation', 'reputation_system');

		$start = request_var('start', 0);

		$this->page_title = 'RS_TITLE';

		$reputation_table = $phpbb_container->getParameter('tables.reputations');

		if (!function_exists('get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
		}

		switch ($mode)
		{
			case 'front':
				$this->tpl_name = 'ucp_front';

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
					FROM ' . $reputation_table . "
					WHERE rep_to = {$user->data['user_id']}";
				$result = $db->sql_query($sql);

				while ($reputation_vote = $db->sql_fetchrow($result))
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
				$db->sql_freeresult($result);

				$sql_array = array(
					'SELECT'	=> 'u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, r.*, p.post_id AS real_post_id, p.forum_id, p.post_subject',
					'FROM'		=> array($reputation_table => 'r'),
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
					'WHERE' 	=> 'r.rep_to = ' . $user->data['user_id'],
					'ORDER_BY'	=> 'r.time DESC',
				);
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query_limit($sql, 5);

				while ($row = $db->sql_fetchrow($result))
				{
					$phpbb_container->get('reputation.display')->row($row);
				}
				$db->sql_freeresult($result);

				$rs_rank_title = $rs_rank_img = $rs_rank_img_src = $rs_rank_color = '';
				if ($config['rs_ranks'])
				{
					$reputation->get_rs_rank($user->data['user_reputation'], $rs_rank_title, $rs_rank_img, $rs_rank_img_src, $rs_rank_color);
				}

				if ($config['rs_enable_power'])
				{
					$reputation_power = $phpbb_container->get('reputation.power');

					$user_max_voting_power = $reputation_power->get($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['user_warnings']);
					$user_power_explain = $reputation_power->explain();

					$voting_power_left = '';
					if ($config['rs_power_renewal'])
					{
						$voting_power_left = $user_max_voting_power - $user_reputation_stats['renewal_time'];
						if ($voting_power_left <= 0) $voting_power_left = 0; 
					}

					$template->assign_vars(array(
						'RS_POWER_EXPLAIN'			=> $config['rs_power_explain'] ? true : false,
						'RS_POWER'					=> $user_max_voting_power,
						'RS_POWER_LEFT'				=> $config['rs_power_renewal'] ? sprintf($user->lang['RS_VOTE_POWER_LEFT'], $voting_power_left, $user_max_voting_power) : '',
						'RS_CFG_TOTAL_POSTS'		=> $config['rs_total_posts'] ? true : false,
						'RS_CFG_MEMBERSHIP_DAYS'	=> $config['rs_membership_days'] ? true : false,
						'RS_CFG_REP_POINT'			=> $config['rs_power_rep_point'] ? true : false,
						'RS_CFG_LOOSE_WARN'			=> $config['rs_power_lose_warn'] ? true : false,
					));

					$template->assign_vars($user_power_explain);
				}

				$template->assign_vars(array(
					'REPUTATIONS'		=> ($user->data['user_reputation']) ? $user->data['user_reputation'] : 0,
					'RS_RANK_TITLE'		=> $rs_rank_title,
					'RS_RANK_IMG'		=> $rs_rank_img,
					'REPUTATION_BOX'	=> $config['rs_ranks'] ? $rs_rank_color : (($user->data['user_reputation'] == 0) ? 'zero' : (($user->data['user_reputation'] > 0) ? 'positive' : 'negative')),

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

					'S_RS_POST_RATING' 	=> $config['rs_post_rating'] ? true : false,
					'S_RS_USER_RATING' 	=> $config['rs_user_rating'] ? true : false,
					'S_RS_COMMENT'		=> $config['rs_enable_comment'] ? true : false,
					'S_RS_NEGATIVE'		=> $config['rs_negative_point'] ? true : false,
				));
			break;

			case 'list':
				$this->tpl_name = 'ucp_list';

				if ($request->is_set('catchup'))
				{
					$sql = 'UPDATE ' . USERS_TABLE . "
						SET user_rep_last = " . time() . "
						WHERE user_id = {$user->data['user_id']}";
					$db->sql_query($sql);

					$url = $this->u_action;
					meta_refresh(0, $url);
				}

				$sort_days = $total = 0;
				$sort_key = $sort_dir = '';
				$sort_by_sql = $sort_order_sql = array();
				reputation_sorting($sort_days, $sort_key, $sort_dir, $sort_by_sql, $sort_order_sql, $total, 'list', $reputation_table);

				$limit_time_sql = ($sort_days) ? 'AND r.time >= ' . (time() - ($sort_days * 86400)) : '';

				$sort_order_u = ($sort_order_sql[0] == 'u') ? ' LEFT JOIN ' . USERS_TABLE . ' u ON r.rep_from = u.user_id' : '';

				$sql = 'SELECT r.rep_id
					FROM ' . $reputation_table . ' r
					' . $sort_order_u . '
					WHERE r.rep_to = ' . $user->data['user_id'] . '
						' . $limit_time_sql . '
					ORDER BY ' . $sort_order_sql;
				$result = $db->sql_query_limit($sql, $config['rs_per_page'], $start);

				$reputation_ids = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$reputation_ids[] = $row['rep_id'];
				}
				$db->sql_freeresult($result);

				if (sizeof($reputation_ids))
				{
					$sql = $db->sql_build_query('SELECT', array(
						'SELECT'	=> 'r.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, p.post_id AS real_post_id, p.forum_id, p.post_subject',
						'FROM'		=> array($reputation_table => 'r'),
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
						'WHERE'		=> $db->sql_in_set('r.rep_id', $reputation_ids),
						'ORDER_BY'	=> $sort_order_sql,
					));
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$phpbb_container->get('reputation.display')->row($row);
					}
					$db->sql_freeresult($result);
					unset($reputation_ids, $row);
				}

				// Generate pagination
				$pagination_url = $this->u_action . "&amp;st=$sort_days&amp;sk=$sort_key&amp;sd=$sort_dir";
				$pagination_url .= (!empty($search_user_from)) ? "&amp;search_from=$search_user_from" : '';
				$pagination_url .= (!empty($search_user_to)) ? "&amp;search_to=$search_user_to" : '';
				phpbb_generate_template_pagination($template, $pagination_url, 'pagination', 'start', $total, $config['rs_per_page'], $start);

				// Now display the page
				$template->assign_vars(array(
					'S_COMMENT'				=> $config['rs_enable_comment'] ? true : false,

					'PAGE_NUMBER'			=> phpbb_on_page($template, $user, $pagination_url, $total, $config['rs_per_page'], $start),
					'TOTAL_REPS'			=> $user->lang('LIST_REPUTATIONS', $total),

					'S_NOTIFICATION'		=> ($config['rs_notification'] && $user->data['user_rs_notification']) ? true : false,
				));
			break;

			case 'given':
				$this->tpl_name = 'ucp_given';

				if ($request->is_set('delete'))
				{
					$rep_id_list = $request->variable('rep_id_list', array(0));

					if (!sizeof($rep_id_list))
					{
						trigger_error('NO_REPUTATION_SELECTED');
					}

					$success_msg = '';
					$redirect = $this->u_action;

					if (confirm_box(true))
					{
						foreach ($rep_id_list as $rep_id)
						{
							//$reputation->delete($rep_id);

							$success_msg = 'RS_POINTS_DELETED';
						}
					}
					else
					{
						confirm_box(false, $user->lang('RS_DELETE_POINTS_CONFIRM', sizeof($rep_id_list)), build_hidden_fields(array(
							'i'					=> $id,
							'mode'				=> $mode,
							'rep_id_list'		=> $rep_id_list,
							'delete'			=> true,
							'redirect'			=> $redirect))
						);
					}

					$redirect = reapply_sid($redirect);

					if (!$success_msg)
					{
						redirect($redirect);
					}
					else
					{
						meta_refresh(3, $redirect);
						trigger_error($user->lang($success_msg, sizeof($rep_id_list)) . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], "<a href=\"$redirect\">", '</a>'));
					}
				}

				$sort_days = $total = 0;
				$sort_key = $sort_dir = '';
				$sort_by_sql = $sort_order_sql = array();
				reputation_sorting($sort_days, $sort_key, $sort_dir, $sort_by_sql, $sort_order_sql, $total, 'given', $reputation_table);

				$limit_time_sql = ($sort_days) ? 'AND r.time >= ' . (time() - ($sort_days * 86400)) : '';

				$sort_order_u = ($sort_order_sql[0] == 'u') ? ' LEFT JOIN ' . USERS_TABLE . ' u ON r.rep_from = u.user_id' : '';

				$sql = 'SELECT r.rep_id
					FROM ' . $reputation_table . ' r
					' . $sort_order_u . '
					WHERE r.rep_from = ' . $user->data['user_id'] . '
						' .$limit_time_sql . '
					ORDER BY ' . $sort_order_sql;
				$result = $db->sql_query_limit($sql, $config['rs_per_page'], $start);

				$reputation_ids = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$reputation_ids[] = $row['rep_id'];
				}
				$db->sql_freeresult($result);

				if (sizeof($reputation_ids))
				{
					$sql = $db->sql_build_query('SELECT', array(
						'SELECT'	=> 'r.*, u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, p.post_id AS real_post_id, p.forum_id, p.post_subject',
						'FROM'		=> array($reputation_table => 'r'),
						'LEFT_JOIN' => array(
							array(
								'FROM'	=> array(USERS_TABLE => 'u'),
								'ON'	=> 'r.rep_to = u.user_id',
							),
							array(
								'FROM'	=> array(POSTS_TABLE => 'p'),
								'ON'	=> 'p.post_id = r.post_id',
							),
						),
						'WHERE'		=> $db->sql_in_set('r.rep_id', $reputation_ids),
						'ORDER_BY'	=> $sort_order_sql,
					));
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						$phpbb_container->get('reputation.display')->row($row);
					}
					$db->sql_freeresult($result);
					unset($reputation_ids, $row);
				}

				// Generate pagination
				$pagination_url = $this->u_action . "&amp;st=$sort_days&amp;sk=$sort_key&amp;sd=$sort_dir";
				$pagination_url .= (!empty($search_user_from)) ? "&amp;search_from=$search_user_from" : '';
				$pagination_url .= (!empty($search_user_to)) ? "&amp;search_to=$search_user_to" : '';
				phpbb_generate_template_pagination($template, $pagination_url, 'pagination', 'start', $total, $config['rs_per_page'], $start);

				// Now display the page
				$template->assign_vars(array(
					'U_ACTION'				=> $this->u_action,

					'S_COMMENT'				=> $config['rs_enable_comment'] ? true : false,
					'S_DELETE_LINK'			=> ($auth->acl_get('u_rs_delete')) ? true : false,

					'PAGE_NUMBER'			=> phpbb_on_page($template, $user, $pagination_url, $total, $config['rs_per_page'], $start),
					'TOTAL_REPS'			=> $user->lang('LIST_REPUTATIONS', $total),
				));
			break;
		}

		$template->assign_vars(array(
			'S_UCP_ACTION'		=> $this->u_action,
		));
	}
}

function reputation_sorting(&$sort_days, &$sort_key, &$sort_dir, &$sort_by_sql, &$sort_order_sql, &$total, $mode, $reputation_table)
{
	global $config, $db, $user, $auth, $template;

	$sort_days = request_var('st', 0);
	$min_time = ($sort_days) ? time() - ($sort_days * 86400) : 0;

	$default_key = 'b';
	$default_dir = 'd';

	$where_sql = ($mode == 'list') ? "WHERE r.rep_to = {$user->data['user_id']}" : "WHERE r.rep_from = {$user->data['user_id']}";
	$where_sql .= ($min_time) ? " AND r.time >= $min_time" : '';

	$sql = 'SELECT COUNT(r.rep_id) AS total
		FROM ' . $reputation_table . " r
		$where_sql";

	$sort_key = request_var('sk', $default_key);
	$sort_dir = request_var('sd', $default_dir);
	$sort_dir_text = array('a' => $user->lang['ASCENDING'], 'd' => $user->lang['DESCENDING']);

	$limit_days = array(0 => $user->lang['ALL_REPUTATIONS'], 1 => $user->lang['1_DAY'], 7 => $user->lang['7_DAYS'], 14 => $user->lang['2_WEEKS'], 30 => $user->lang['1_MONTH'], 90 => $user->lang['3_MONTHS'], 180 => $user->lang['6_MONTHS'], 365 => $user->lang['1_YEAR']);
	$sort_by_text = array(
		'a'	=> $user->lang['USERNAME'],
		'b'	=> $user->lang['RS_TIME'],
		'c'	=> $user->lang['RS_POINT'],
		'd'	=> $user->lang['RS_ACTION'],
		'e'	=> $user->lang['POST']
	);
	$sort_by_sql = array(
		'a'	=> 'u.username_clean',
		'b'	=> 'r.time',
		'c'	=> 'r.point',
		'd'	=> 'r.action',
		'e'	=> 'r.post_id'
	);

	if (!isset($sort_by_sql[$sort_key]))
	{
		$sort_key = $default_key;
	}

	$sort_order_sql = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

	$s_limit_days = $s_sort_key = $s_sort_dir = $sort_url = '';
	gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $sort_url);

	$template->assign_vars(array(
		'S_SELECT_SORT_DIR'		=> $s_sort_dir,
		'S_SELECT_SORT_KEY'		=> $s_sort_key,
		'S_SELECT_SORT_DAYS'	=> $s_limit_days
	));

	$result = $db->sql_query($sql);
	$total = (int) $db->sql_fetchfield('total');
	$db->sql_freeresult($result);
}

?>