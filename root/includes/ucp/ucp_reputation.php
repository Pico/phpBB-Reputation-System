<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package ucp
*/
class ucp_reputation
{
	var $p_master;
	var $u_action;

	function ucp_reputation(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($id, $mode)
	{
		global $auth, $db, $user, $template;
		global $config, $phpbb_root_path, $phpEx;

		$user->add_lang('mods/reputation_system');

		$start = request_var('start', 0);

		$this->page_title = 'RS_TITLE';

		switch ($mode)
		{
			case 'front':
				include($phpbb_root_path . '/includes/functions_reputation.' . $phpEx);
				$reputation = new reputation();

				$positive_count = $negative_count = 0;
				$positive_week = $negative_week  = 0;
				$positive_month = $negative_month = 0;
				$positive_6months = $negative_6months = 0;

				$last_week = time() - 604800;
				$last_month = time() - 2678400;
				$last_6months = time() - 16070400;

				$sql = 'SELECT time, point
					FROM ' . REPUTATIONS_TABLE . "
					WHERE rep_to = {$user->data['user_id']}";
				$result = $db->sql_query($sql);

				while ($reputation_vote = $db->sql_fetchrow($result))
				{
					if ($reputation_vote['point'] > 0)
					{
						$positive_count++;
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
				}

				$sql_array = array(
					'SELECT'	=> 'u.username as username_rep_from, u.user_colour as user_colour_rep_from, r.*, p.forum_id, p.post_subject',
					'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
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
					'WHERE' 	=> 'r.rep_to = ' . $user->data['user_id'] . '' . ($config['rs_negative_point'] ? '' : ' AND point > 0'),
					'ORDER_BY'	=> 'r.time DESC',
				);
				$sql = $db->sql_build_query('SELECT', $sql_array);
				$result = $db->sql_query_limit($sql, 5);

				while ($row = $db->sql_fetchrow($result))
				{
					$row['bbcode_options'] = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

					$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : $user->lang['RS_NA'];
					$time = $user->format_date($row['time']);
					$user_from = get_username_string('full', $row['rep_from'], $row['username_rep_from'], $row['user_colour_rep_from']);

					$post_subject = (empty($row['post_subject'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'];
					$post_link = (!empty($row['post_subject'])) ? ($auth->acl_get('f_read', $row['forum_id']) ? '<br /><a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : '') : '<br />' . $post_subject;

					if ($row['action'] == 1)
					{
						$action = $user->lang['RS_POST_RATING'] . '' . $post_link;
					}
					else if ($row['action'] == 2)
					{
						$action = $user->lang['RS_USER_RATING'];
					}
					else if ($row['action'] == 3)
					{
						$action = $user->lang['RS_WARNING'];
					}
					else if ($row['action'] == 4)
					{
						$action = $user->lang['RS_BAN'];
					}
					else if ($row['action'] == 5)
					{
						$action = $user->lang['RS_ONLYPOST_RATING'] . '' . $post_link;
					}

					if ($row['point'] < 0)
					{
						$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/neg.png" alt="" title="' . $user->lang['RS_POINTS'] . ': ' . $row['point'] . '" />';
						$point_class = 'negative';
					}

					if ($row['point'] > 0)
					{
						$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/pos.png" alt="" title="' . $user->lang['RS_POINTS'] . ': ' . $row['point'] . '" />';
						$point_class = 'positive';
					}

					$template->assign_block_vars('reputation', array(
						'USERNAME_FROM'		=> $user_from,
						'ACTION'			=> $action,
						'TIME'				=> $time,
						'COMMENT'			=> $comment,
						'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
						'POINT_CLASS'		=> $config['rs_point_type'] ? 'zero' : $point_class,
					));
				}
				$db->sql_freeresult($result);

				$rs_rank_title = $rs_rank_img = $rs_rank_img_src = $rs_rank_color = '';
				if ($config['rs_ranks']) $reputation->get_rs_rank($user->data['user_reputation'], $rs_rank_title, $rs_rank_img, $rs_rank_img_src, $rs_rank_color);

				if ($config['rs_enable_power'])
				{
					$user_reputation_stats = $reputation->get_reputation_stats($user->data['user_id']);
					$user_max_voting_power = $reputation->get_rep_power($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings'], $user_reputation_stats['bancounts']);
					$user_power_explain = $reputation->explain_power();
					$voting_power_left = '';
					if ($config['rs_power_renewal'])
					{
						$voting_power_left = $user_max_voting_power - $user_reputation_stats['renewal_time'];
						if ($voting_power_left <= 0) $voting_power_left = 0; 
					}

					$group_power = $reputation->get_group_power();

					$template->assign_vars(array(
						'RS_POWER_EXPLAIN'			=> $config['rs_power_explain'] ? true : false,
						'RS_POWER'					=> $user_max_voting_power,
						'RS_POWER_LEFT'				=> $config['rs_power_renewal'] ? sprintf($user->lang['RS_VOTE_POWER_LEFT'], $voting_power_left, $user_max_voting_power) : '',
						'RS_CFG_TOTAL_POSTS'		=> $config['rs_total_posts'] ? true : false,
						'RS_CFG_MEMBERSHIP_DAYS'	=> $config['rs_membership_days'] ? true : false,
						'RS_CFG_REP_POINT'			=> $config['rs_power_rep_point'] ? true : false,
						'RS_CFG_LOOSE_WARN'			=> $config['rs_power_loose_warn'] ? true : false,
						'RS_CFG_LOOSE_BAN'			=> $config['rs_power_loose_ban'] ? true : false,
						'RS_GROUP_POWER'			=> $group_power ? true : false,
					));

					$template->assign_vars($user_power_explain);
				}

				$template->assign_vars(array(
					'REPUTATIONS'		=> ($user->data['user_reputation']) ? $user->data['user_reputation'] : 0,
					'RS_RANK_TITLE'		=> $rs_rank_title,
					'RS_RANK_IMG'		=> $rs_rank_img,
					'REPUTATION_BOX'	=> $config['rs_ranks'] ? $rs_rank_color : (($user->data['user_reputation'] == 0) ? 'zero' : (($user->data['user_reputation'] > 0) ? 'positive' : 'negative')),
					'POSITIVE_COUNT'	=> $positive_count,
					'POSITIVE_WEEK'		=> $positive_week,
					'POSITIVE_MONTH'	=> $positive_month,
					'POSITIVE_6MONTHS'	=> $positive_6months,
					'NEGATIVE_COUNT'	=> $negative_count,
					'NEGATIVE_WEEK'		=> $negative_week,
					'NEGATIVE_MONTH'	=> $negative_month,
					'NEGATIVE_6MONTHS'	=> $negative_6months,

					'S_RS_COMMENT'			=> $config['rs_enable_comment'] ? true : false,
					'S_RS_NEGATIVE'		=> $config['rs_negative_point'] ? true : false,
				));

				$this->tpl_name = 'reputation/ucp_front';
			break;

			case 'list':
				if ($user->data['user_id'])
				{
					$sql = 'UPDATE ' . USERS_TABLE . "
						SET user_rep_new = 0
						WHERE user_id = {$user->data['user_id']}";
					$db->sql_query($sql);
				}

				if (isset($_POST['catchup']))
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
				reputation_sorting($sort_days, $sort_key, $sort_dir, $sort_by_sql, $sort_order_sql, $total, 'list');

				$limit_time_sql = ($sort_days) ? 'AND r.time >= ' . (time() - ($sort_days * 86400)) : '';
				$where_negative = $config['rs_negative_point'] ? '' : 'AND r.point > 0';

				$sort_order_u = ($sort_order_sql[0] == 'u') ? ' LEFT JOIN ' . USERS_TABLE . ' u ON r.rep_from = u.user_id' : '';

				$sql = 'SELECT r.rep_id
					FROM ' . REPUTATIONS_TABLE . ' r
					' . $sort_order_u . '
					WHERE r.rep_to = ' . $user->data['user_id'] . '
						' . $limit_time_sql . '
						' . $where_negative . '
					ORDER BY ' . $sort_order_sql;
				$result = $db->sql_query_limit($sql, $config['rs_per_page'], $start);

				$i = 0;
				$reputation_ids = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$reputation_ids[] = $row['rep_id'];
					$row_num[$row['rep_id']] = $i++;
				}
				$db->sql_freeresult($result);

				if (sizeof($reputation_ids))
				{
					$sql = $db->sql_build_query('SELECT', array(
						'SELECT'	=> 'r.*, u.username as username_rep_from, u.user_colour as user_colour_rep_from, p.post_subject',
						'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
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
						$row['bbcode_options'] = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

						$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : $user->lang['RS_NA'];
						$time = $user->format_date($row['time']);
						$user_from = get_username_string('full', $row['rep_from'], $row['username_rep_from'], $row['user_colour_rep_from']);

						$post_subject = (empty($row['post_subject'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'] . ' [#p' . $row['post_id'] . ']';
						$post_link = (!empty($row['post_subject'])) ? '<br /><a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : '<br />' . $post_subject;

						$new_rep = ($config['rs_notification'] && $user->data['user_rs_notification'] && ($row['time'] >= $user->data['user_rep_last'])) ? '<span class="new-repo">' . $user->lang['RS_NEW'] . '</span>' : '';

						if ($row['action'] == 1)
						{
							$action = $user->lang['RS_POST_RATING'] . '' . $post_link;
						}
						else if ($row['action'] == 2)
						{
							$action = $user->lang['RS_USER_RATING'];
						}
						else if ($row['action'] == 3)
						{
							$action = $user->lang['RS_WARNING'];
						}
						else if ($row['action'] == 4)
						{
							$action = $user->lang['RS_BAN'];
						}
						else if ($row['action'] == 5)
						{
							$action = $user->lang['RS_ONLYPOST_RATING'] . '' . $post_link;
							$short_action = $user->lang['RS_ONLYPOST_RATING'];
						}

						if ($row['point'] < 0)
						{
							$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/neg.png" alt="" title="' . $user->lang['RS_POINTS'] . ': ' . $row['point'] . '" />';
							$point_class = 'negative';
						}

						if ($row['point'] > 0)
						{
							$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/pos.png" alt="" title="' . $user->lang['RS_POINTS'] . ': ' . $row['point'] . '" />';
							$point_class = 'positive';
						}

						$template->assign_block_vars('reputation', array(
							'USERNAME_FROM'		=> $user_from,
							'ACTION'			=> $new_rep . ' ' . $action,
							'TIME'				=> $time,
							'COMMENT' 			=> $comment,
							'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
							'POINT_CLASS'		=> $config['rs_point_type'] ? 'zero' : $point_class,
							'REP_ID'			=> $row['rep_id'],
						));
					}
					$db->sql_freeresult($result);
					unset($reputation_ids, $row);
				}

				// Now display the page
				$template->assign_vars(array(
					'COMMENT'				=> $config['rs_enable_comment'] ? true : false,
					'PAGINATION'			=> generate_pagination($this->u_action . "&amp;st=$sort_days&amp;sk=$sort_key&amp;sd=$sort_dir", $total, $config['rs_per_page'], $start),
					'PAGE_NUMBER'			=> on_page($total, $config['rs_per_page'], $start),
					'TOTAL'					=> $total,
					'TOTAL_REPS'			=> ($total == 1) ? $user->lang['LIST_REPUTATION'] : sprintf($user->lang['LIST_REPUTATIONS'], $total),

					'S_NOTIFICATION'		=> ($config['rs_notification'] && $user->data['user_rs_notification']) ? true : false,
				));

				$this->tpl_name = 'reputation/ucp_list';
			break;

			case 'given':
				if (isset($_POST['action']))
				{
					$rep_id_list = request_var('rep_id_list', array(0));

					if (!sizeof($rep_id_list))
					{
						trigger_error('NO_REPUTATION_SELECTED');
					}

					ucp_reputation_delete($rep_id_list);
				}

				$sort_days = $total = 0;
				$sort_key = $sort_dir = '';
				$sort_by_sql = $sort_order_sql = array();
				reputation_sorting($sort_days, $sort_key, $sort_dir, $sort_by_sql, $sort_order_sql, $total, 'given');

				$limit_time_sql = ($sort_days) ? 'AND r.time >= ' . (time() - ($sort_days * 86400)) : '';
				$where_negative = $config['rs_negative_point'] ? '' : 'AND r.point > 0';

				$sort_order_u = ($sort_order_sql[0] == 'u') ? ' LEFT JOIN ' . USERS_TABLE . ' u ON r.rep_from = u.user_id' : '';

				$sql = 'SELECT r.rep_id
					FROM ' . REPUTATIONS_TABLE . ' r
					' . $sort_order_u . '
					WHERE r.rep_from = ' . $user->data['user_id'] . '
						' .$limit_time_sql . '
						' . $where_negative . '
					ORDER BY ' . $sort_order_sql;
				$result = $db->sql_query_limit($sql, $config['rs_per_page'], $start);

				$i = 0;
				$reputation_ids = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$reputation_ids[] = $row['rep_id'];
					$row_num[$row['rep_id']] = $i++;
				}
				$db->sql_freeresult($result);

				if (sizeof($reputation_ids))
				{
					$sql = $db->sql_build_query('SELECT', array(
						'SELECT'	=> 'u.username as username_rep_to, u.user_colour as user_colour_rep_to, r.*, p.forum_id, p.post_subject',
						'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
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
						$row['bbcode_options'] = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

						$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : $user->lang['RS_NA'];
						$time = $user->format_date($row['time']);
						$user_to = get_username_string('full', $row['rep_to'], $row['username_rep_to'], $row['user_colour_rep_to']);

						$post_subject = (empty($row['post_subject'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'] . ' [#p' . $row['post_id'] . ']';
						$post_link = (!empty($row['post_subject'])) ? ($auth->acl_get('f_read', $row['forum_id']) ? '<br /><a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : '') : '<br />' . $post_subject;

						if ($row['action'] == 1)
						{
							$action = $user->lang['RS_POST_RATING'] . '' . $post_link;
						}
						else if ($row['action'] == 2)
						{
							$action = $user->lang['RS_USER_RATING'];
						}
						else if ($row['action'] == 3)
						{
							$action = $user->lang['RS_WARNING'];
						}
						else if ($row['action'] == 4)
						{
							$action = $user->lang['RS_BAN'];
						}
						else if ($row['action'] == 5)
						{
							$action = $user->lang['RS_ONLYPOST_RATING'] . '' . $post_link;
						}

						if ($row['point'] < 0)
						{
							$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/neg.png" alt="" title="' . $user->lang['RS_POINTS'] . ': ' . $row['point'] . '" />';
							$point_class = 'negative';
						}

						if ($row['point'] > 0)
						{
							$point_img = '<img src="' . $phpbb_root_path . 'images/reputation/pos.png" alt="" title="' . $user->lang['RS_POINTS'] . ': ' . $row['point'] . '" />';
							$point_class = 'positive';
						}

						$template->assign_block_vars('reputation', array(
							'USERNAME_TO'		=> $user_to,
							'ACTION'			=> $action,
							'TIME'				=> $time,
							'COMMENT' 			=> $comment,
							'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
							'POINT_CLASS'		=> $config['rs_point_type'] ? 'zero' : $point_class,
							'REP_ID'			=> $row['rep_id'],
						));
					}
					$db->sql_freeresult($result);
					unset($reputation_ids, $row);
				}

				// Now display the page
				$template->assign_vars(array(
					'S_UCP_ACTION'			=> $this->u_action,

					'COMMENT'				=> $config['rs_enable_comment'] ? true : false,
					'DELETE_LINK'			=> ($auth->acl_get('u_rs_delete')) ? true : false,
					'PAGINATION'			=> generate_pagination($this->u_action . "&amp;st=$sort_days&amp;sk=$sort_key&amp;sd=$sort_dir", $total, $config['rs_per_page'], $start),
					'PAGE_NUMBER'			=> on_page($total, $config['rs_per_page'], $start),
					'TOTAL'					=> $total,
					'TOTAL_REPS'			=> ($total == 1) ? $user->lang['LIST_REPUTATION'] : sprintf($user->lang['LIST_REPUTATIONS'], $total),
				));

				$this->tpl_name = 'reputation/ucp_given';
			break;

			case 'setting':
				$submit		= (!empty($_POST['submit'])) ? true : false;
				$error = $data = array();

				$data = array(
					'notification'		=> request_var('notification', $user->data['user_rs_notification']),
					'default_power'		=> request_var('default_power', $user->data['user_rs_default_power']),
				);

				if ($config['rs_enable_power'])
				{
					include($phpbb_root_path . '/includes/functions_reputation.' . $phpEx);
					$reputation = new reputation();

					$user_reputation_stats = $reputation->get_reputation_stats($user->data['user_id']);
					$rs_power = '';
					$reputationpower = $reputation->get_rep_power($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings'], $user_reputation_stats['bancounts']);

					for($i = 0; $i <= $reputationpower; ++$i)
					{
						$point_lang = ($i == 1) ? $user->lang['RS_DEF_POINT'] : $user->lang['RS_DEF_POINTS'];
						$rs_power = '<option value="' . $i . '">' . $i . ' ' . $point_lang . '</option>';
						if ($i == $user->data['user_rs_default_power']) $rs_power = '<option value="' . $i . '" selected="selected">' . $i . ' ' . $point_lang . '</option>';
						if ($i == 0) $rs_power = '<option value="0" selected="selected">' . $user->lang['RS_EMPTY'] . '</option>';

						$template->assign_block_vars('reputation', array(
							'REPUTATION_POWER'	=> $rs_power
						));
					}
				}

				add_form_key('ucp_reputation');

				if ($submit)
				{
					$validate_array = array();

					if ($config['rs_enable_power']) $validate_array['default_power'] = array('num', true, 1, $reputationpower);

					$error = validate_data($data, $validate_array);

					if (!check_form_key('ucp_reputation'))
					{
						$error[] = 'FORM_INVALID';
					}

					if (!sizeof($error))
					{
						$sql_ary = array(
							'user_rs_notification'	=> $data['notification'],
							'user_rs_default_power'	=> $data['default_power'],
						);

						$sql = 'UPDATE ' . USERS_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE user_id = ' . $user->data['user_id'];
						$db->sql_query($sql);

						meta_refresh(3, $this->u_action);
						$message = $user->lang['PROFILE_UPDATED'] . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
						trigger_error($message);
					}

					// Replace "error" strings with their real, localised form
					$error = preg_replace('#^([A-Z_]+)$#e', "(!empty(\$user->lang['\\1'])) ? \$user->lang['\\1'] : '\\1'", $error);
				}

				$template->assign_vars(array(
					'ERROR'		=> (sizeof($error)) ? implode('<br />', $error) : '',

					'NOTIFICATION'		=> $data['notification'],
					'DEFAULT_POWER'		=> $data['default_power'],

					'S_NOTIFICATION'	=> ($config['rs_notification']) ? true : false,
					'S_POWER_ENABLE'	=> ($config['rs_enable_power']) ? true : false,
					'S_NEGATIVE_ENABLE'	=> ($config['rs_negative_point']) ? true : false,
				));

				$this->tpl_name = 'reputation/ucp_setting';
			break;
		}

		$template->assign_vars(array(
			'S_UCP_ACTION'		=> $this->u_action,
		));		
	}
}

function ucp_reputation_delete($rep_id_list)
{
	global $phpEx, $phpbb_root_path;
	global $db, $user;

	include($phpbb_root_path . '/includes/functions_reputation.' . $phpEx);
	$reputation = new reputation();

	$redirect = append_sid("{$phpbb_root_path}ucp.$phpEx", "i=reputation&amp;mode=given");

	$s_hidden_fields = build_hidden_fields(array(
			'i'					=> 'reputation',
			'mode'				=> 'given',
			'rep_id_list'		=> $rep_id_list,
			'action'			=> 'delete',
			'redirect'			=> $redirect)
	);
	$success_msg = '';

	if (confirm_box(true))
	{
		foreach ($rep_id_list as $rep_id)
		{
			$reputation->delete($rep_id);

			$success_msg = 'RS_POINT' . ((sizeof($rep_id_list) == 1) ? '' : 'S') .'_DELETED';
		}
	}
	else
	{
		confirm_box(false, $user->lang['RS_DELETE_POINT' . ((sizeof($rep_id_list) == 1) ? '' : 'S') . '_CONFIRM'], $s_hidden_fields);
	}

	$redirect = reapply_sid($redirect);

	if (!$success_msg)
	{
		redirect($redirect);
	}
	else
	{
		meta_refresh(3, $redirect);
		trigger_error($user->lang[$success_msg] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], "<a href=\"$redirect\">", '</a>'));
	}
}

function reputation_sorting(&$sort_days, &$sort_key, &$sort_dir, &$sort_by_sql, &$sort_order_sql, &$total, $mode)
{
	global $config, $db, $user, $auth, $template;

	$sort_days = request_var('st', 0);
	$min_time = ($sort_days) ? time() - ($sort_days * 86400) : 0;

	$where_sql = ($mode == 'list') ? "WHERE r.rep_to = {$user->data['user_id']}" : "WHERE r.rep_from = {$user->data['user_id']}";
	$where_sql .= $config['rs_negative_point'] ? '' : ' AND r.point > 0';
	$where_sql .= ($min_time) ? " AND r.time >= $min_time" : '';

	$sql = 'SELECT COUNT(r.rep_id) AS total
		FROM ' . REPUTATIONS_TABLE . " r
		$where_sql";

	$sort_key = request_var('sk', 'f');
	$sort_dir = request_var('sd', 'd');
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
		'e'	=> 'r.post_id',
		'f'	=> 'r.rep_id'
	);

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