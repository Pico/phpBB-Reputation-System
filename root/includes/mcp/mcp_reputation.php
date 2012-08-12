<?php
/**
*
* @package	Reputation System
* @author	Pico88 (Pico) (http://www.modsteam.tk)
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
* @package mcp
*/
class mcp_reputation
{
	var $p_master;
	var $u_action;

	function mcp_reputation(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($id, $mode)
	{
		global $auth, $db, $user, $template;
		global $config, $phpbb_root_path, $phpEx, $action;

		$user->add_lang('mods/reputation_system');

		$this->page_title = 'RS_TITLE';

		if ($action == 'delete')
		{
			$rep_id_list = request_var('rep_id_list', array(0));

			if (!sizeof($rep_id_list))
			{
				trigger_error('NO_REPUTATION_SELECTED');
			}

			mcp_reputation_delete($rep_id_list);
		}

		switch ($mode)
		{
			case 'front':
				$sql = 'SELECT user_id, username, user_colour, user_reputation
					FROM ' . USERS_TABLE . '
					WHERE user_reputation > 0
					ORDER BY user_reputation DESC';
				$result = $db->sql_query_limit($sql, 5);
				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('best', array(
						'USERNAME_FULL'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
						'USERNAME'			=> $row['username'],
						'USERNAME_COLOUR'	=> ($row['user_colour']) ? '#' . $row['user_colour'] : '',
						'U_USER'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $row['user_id']),
						'U_DETAILS'			=> append_sid("{$phpbb_root_path}reputation.$phpEx", 'mode=details&amp;u=' . $row['user_id']),
						'REPUTATION'		=> $row['user_reputation'],
					));
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT user_id, username, user_colour, user_reputation
					FROM ' . USERS_TABLE . '
					WHERE user_reputation < 0
					ORDER BY user_reputation ASC';
				$result = $db->sql_query_limit($sql, 5);
				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('worst', array(
						'USERNAME_FULL'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
						'USERNAME'			=> $row['username'],
						'USERNAME_COLOUR'	=> ($row['user_colour']) ? '#' . $row['user_colour'] : '',
						'U_USER'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $row['user_id']),
						'U_DETAILS'			=> append_sid("{$phpbb_root_path}reputation.$phpEx", 'mode=details&amp;u=' . $row['user_id']),
						'REPUTATION'		=> $row['user_reputation'],
					));
				}
				$db->sql_freeresult($result);

				$sql = $db->sql_build_query('SELECT', array(
					'SELECT'	=> 'u.username as username_rep_from, u.user_colour as user_colour_rep_from, ut.username as username_rep_to, ut.user_colour as user_colour_rep_to, ut.user_reputation, r.*, p.post_subject',
					'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
					'LEFT_JOIN' => array(
						array(
							'FROM'	=> array(USERS_TABLE => 'u'),
							'ON'	=> 'r.rep_from = u.user_id',
						),
						array(
							'FROM'	=> array(USERS_TABLE => 'ut'),
							'ON'	=> 'r.rep_to = ut.user_id',
						),
						array(
							'FROM'	=> array(POSTS_TABLE => 'p'),
							'ON'	=> 'p.post_id = r.post_id',
						),
					),
					'ORDER_BY'	=> 'r.time DESC',
				));
				$result = $db->sql_query_limit($sql, 5);

				while ($row = $db->sql_fetchrow($result))
				{
					$row['bbcode_options'] = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
					(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) +
					(($row['enable_urls']) ? OPTION_FLAG_LINKS : 0);

					$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : $user->lang['RS_NA'];
					$time = $user->format_date($row['time']);
					$user_from = get_username_string('full', $row['rep_from'], $row['username_rep_from'], $row['user_colour_rep_from']);
					$user_to = get_username_string('full', $row['rep_to'], $row['username_rep_to'], $row['user_colour_rep_to']);

					$post_subject = (empty($row['post_subject'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'];
					$post_link = (!empty($row['post_subject'])) ? '<a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : $post_subject;

					$action = ($row['warning'] == 2) ? $user->lang['RS_BAN'] : (($row['warning'] == 1) ? $user->lang['RS_WARNING'] : (!empty($row['user']) ? $user->lang['RS_USER_RATING'] : $user->lang['RS_POST_RATING'] . '<br />' . $post_link));

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
						'USERNAME_TO'		=> $user_to,
						'ACTION'			=> $action,
						'TIME'				=> $time,
						'COMMENT' 			=> $comment,
						'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
						'POINT_CLASS'		=> $config['rs_point_type'] ? 'zero' : $point_class,
					));
				}
				$db->sql_freeresult($result);

				$template->assign_vars(array(
					'COMMENT'	=> $config['rs_enable_comment'] ? true : false,
				));
				
				$this->tpl_name = 'reputation/mcp_front';
			break;

			case 'list':
				$start = request_var('start', 0);
				$sk = request_var('sk', '');

				$search_user_from = utf8_normalize_nfc(request_var('search_from', '', true));
				$search_user_to = utf8_normalize_nfc(request_var('search_to', '', true));

				$row_from = $row_to = '';
				if (!empty($search_user_from))
				{
					$username_clean = $db->sql_escape(utf8_clean_string($search_user_from));
					$sql = 'SELECT user_id
						FROM ' . USERS_TABLE . "
						WHERE username_clean = '$username_clean'";
					$result = $db->sql_query($sql);
					$row_from = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
				}

				if (!empty($search_user_to))
				{
					$username_clean = $db->sql_escape(utf8_clean_string($search_user_to));
					$sql = 'SELECT user_id
						FROM ' . USERS_TABLE . "
						WHERE username_clean = '$username_clean'";
					$result = $db->sql_query($sql);
					$row_to = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
				}

				$sort_days = $total = 0;
				$sort_key = $sort_dir = '';
				$sort_by_sql = $sort_order_sql = array();
				reputation_sorting($sort_days, $sort_key, $sort_dir, $sort_by_sql, $sort_order_sql, $total, $row_from['user_id'], $row_to['user_id']);

				$sql_array = array(
					'SELECT'	=> 'r.rep_id',
					'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
				);

				if ($sk == 'rf')
				{
					$sql_array['LEFT_JOIN'][] = array('FROM' => array(USERS_TABLE => 'u'), 'ON' => 'r.rep_from = u.user_id');
				}
				if ($sk == 'ru')
				{
					$sql_array['LEFT_JOIN'][] = array('FROM' => array(USERS_TABLE => 'ru'), 'ON' => 'r.rep_to = ru.user_id');
				}
				$sql_where = array();
				if ($sort_days)
				{
					$sql_where[] = 'r.time >= ' . (time() - ($sort_days * 86400));
				}
				if (!empty($search_user_from) && !empty($row_from))
				{
					$sql_where[] = 'r.rep_from = ' . $row_from['user_id'];
				}
				if (!empty($search_user_to) && !empty($row_to))
				{
					$sql_where[] = 'r.rep_to = ' . $row_to['user_id'];
				}
				$sql_array['WHERE'] = implode(' AND ', $sql_where);

				$sql_array['ORDER_BY'] = $sort_order_sql;

				$sql = $db->sql_build_query('SELECT', $sql_array);
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
						'SELECT'	=> 'u.username as username_rep_from, u.user_colour as user_colour_rep_from, ru.username as username_rep_to, ru.user_colour as user_colour_rep_to, ru.user_reputation, r.*, p.post_subject',
						'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
						'LEFT_JOIN' => array(
							array(
								'FROM'	=> array(USERS_TABLE => 'u'),
								'ON'	=> 'r.rep_from = u.user_id',
							),
							array(
								'FROM'	=> array(USERS_TABLE => 'ru'),
								'ON'	=> 'r.rep_to = ru.user_id',
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
						$row['bbcode_options'] = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
						(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) +
						(($row['enable_urls']) ? OPTION_FLAG_LINKS : 0);

						$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : $user->lang['RS_NA'];
						$time = $user->format_date($row['time']);
						$user_from = get_username_string('full', $row['rep_from'], $row['username_rep_from'], $row['user_colour_rep_from']);
						$user_to = get_username_string('full', $row['rep_to'], $row['username_rep_to'], $row['user_colour_rep_to']);

						$post_subject = (empty($row['post_subject'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'] . ' [#p' . $row['post_id'] . ']';
						$post_link = (!empty($row['post_subject'])) ? '<a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : $post_subject;

						$action = ($row['warning'] == 2) ? $user->lang['RS_BAN'] : (($row['warning'] == 1) ? $user->lang['RS_WARNING'] : (!empty($row['user']) ? $user->lang['RS_USER_RATING'] : $user->lang['RS_POST_RATING'] . '<br />' . $post_link));

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
							'USERNAME_TO'		=> $user_to,
							'ACTION'			=> $action,
							'TIME'				=> $time,
							'COMMENT'			=> $comment,
							'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
							'POINT_CLASS'		=> $config['rs_point_type'] ? 'zero' : $point_class,
							'REP_ID'			=> $row['rep_id'])
						);
					}
					$db->sql_freeresult($result);
					unset($reputation_ids, $row);
				}

				// Now display the page
				$template->assign_vars(array(
					'S_MCP_ACTION'			=> $this->u_action,
					'S_SEARCH_FROM'			=> $search_user_from,
					'S_SEARCH_TO'			=> $search_user_to,

					'COMMENT'				=> $config['rs_enable_comment'] ? true : false,
					'PAGINATION'			=> generate_pagination($this->u_action . "&amp;st=$sort_days&amp;sk=$sort_key&amp;sd=$sort_dir&amp;search_from=$search_user_from&amp;search_to=$search_user_to", $total, $config['rs_per_page'], $start),
					'PAGE_NUMBER'			=> on_page($total, $config['rs_per_page'], $start),
					'TOTAL'					=> $total,
					'TOTAL_REPS'			=> ($total == 1) ? $user->lang['LIST_REPUTATION'] : sprintf($user->lang['LIST_REPUTATIONS'], $total),
					)
				);

				$this->tpl_name = 'reputation/mcp_list';
			break;

			case 'give_point':
				$user->add_lang('mods/info_acp_reputation');

				add_form_key('mcp_reputation');

				$submit = (isset($_POST['submit'])) ? true : false;
				$username = utf8_normalize_nfc(request_var('username', '', true));
				$notify = request_var('notify_user', '');
				$comment = utf8_normalize_nfc(request_var('comment', '', true));
				$rep_power = request_var('rep_power', '');

				include($phpbb_root_path . '/includes/functions_reputation.' . $phpEx);
				$reputation = new reputation();

				$user_reputation_stats = $reputation->get_reputation_stats($user->data['user_id']);
				$rs_power = '';
				$reputationpower = $reputation->get_rep_power($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings'], $user_reputation_stats['bancounts']);
				$startpower = $config['rs_negative_point'] ? -$reputationpower : 1;

				for($i = $reputationpower; $i >= $startpower; $i--) //from + to -
				//for($i = $startpower; $i <= $reputationpower; ++$i) //from - to +
				{
					if ($i == 0)
					{
						$rs_power = '';
					}
					if ($i > 0)
					{
						$rs_power = '<option value="' . $i . '">' . $user->lang['RS_POSITIVE'] . ' (+' . $i . ') </option>';
					}
					if ($i < 0 && $auth->acl_get('u_rs_give_negative'))
					{
						$rs_power = '<option value="' . $i . '">' . $user->lang['RS_NEGATIVE'] . ' (' . $i . ') </option>';
					}

					$template->assign_block_vars('reputation', array(
							'REPUTATION_POWER'	=> $rs_power)
					);
				}

				if ($submit)
				{
					if (!check_form_key('mcp_reputation') || !is_numeric($rep_power) || ($rep_power > $reputationpower) || ($rep_power < -$reputationpower))
					{
						$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=reputation&amp;mode=give_point");
						trigger_error($user->lang['FORM_INVALID']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					$sql = 'SELECT *
						FROM ' . USERS_TABLE . "
						WHERE username_clean = '" . $db->sql_escape(utf8_clean_string($username)) . "'";
					$result = $db->sql_query($sql);
					$user_row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					if (!$user_row)
					{
						$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=reputation&amp;mode=give_point");
						trigger_error($user->lang['RS_NO_USER_ID']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					if ($user_row['user_type'] == USER_IGNORE)
					{
						$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=reputation&amp;mode=give_point");
						trigger_error($user->lang['RS_USER_ANONYMOUS']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					if ($user_row['user_id'] == $user->data['user_id'])
					{
						$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=reputation&amp;mode=give_point");
						trigger_error($user->lang['RS_SELF']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					if ($user->check_ban($user_row['user_id'], false, false, true))
					{
						$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=reputation&amp;mode=give_point");
						trigger_error($user->lang['RS_USER_BANNED']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					$reputation->give_point($user_row['user_id'], 0, $comment, $notify, $rep_power, 'user');

					$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=reputation&amp;mode=give_point");
					trigger_error($user->lang['RS_SENT']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
				}

				$this->page_title = 'ACP_REPUTATION_GIVE';

				$template->assign_vars(array(
						'U_FIND_USERNAME'	=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=mcp_reputation&amp;field=username&amp;select_single=true'),
						'S_MCP_ACTION'		=> $this->u_action,
					)
				);

				$this->tpl_name = 'reputation/mcp_give';
			break;
		}
	}
}

function mcp_reputation_delete($rep_id_list)
{
	global $phpEx, $phpbb_root_path;
	global $db, $user;

	include($phpbb_root_path . '/includes/functions_reputation.' . $phpEx);
	$reputation = new reputation();

	$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=reputation&amp;mode=list");

	$s_hidden_fields = build_hidden_fields(array(
		'i'					=> 'reputation',
		'mode'				=> 'list',
		'rep_id_list'		=> $rep_id_list,
		'action'			=> 'delete',
		'redirect'			=> $redirect)
	);
	$success_msg = '';

	if (confirm_box(true))
	{
		foreach ($rep_id_list as $rep_id)
		{
			$sql = 'SELECT rep_to, post_id
				FROM ' . REPUTATIONS_TABLE . '
				WHERE rep_id=' . $rep_id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$reputation->delete($rep_id, $row['post_id']);

			$sql = 'SELECT username
				FROM ' . USERS_TABLE . ' 
				WHERE user_id = ' . $row['rep_to'];
			$result = $db->sql_query($sql);
			$user_row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			add_log('mod', '', '', 'LOG_USER_REP_DELETE', $user_row['username']);

			$success_msg = 'RS_POINT' . ((sizeof($rep_id_list) == 1) ? '' : 'S') .'_DELETED';
		}
	}
	else
	{
		confirm_box(false, $user->lang['RS_DELETE_POINT' . ((sizeof($rep_id_list) == 1) ? '' : 'S') . '_CONFIRM'], $s_hidden_fields);
	}

	$redirect = request_var('redirect', "index.$phpEx");
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

function reputation_sorting(&$sort_days, &$sort_key, &$sort_dir, &$sort_by_sql, &$sort_order_sql, &$total, &$user_from, &$user_to)
{
	global $db, $user, $auth, $template;

	$sort_days = request_var('st', 0);
	$min_time = ($sort_days) ? time() - ($sort_days * 86400) : 0;

	$default_key = 't';
	$default_dir = 'd';

	$sql_where = '';
	$sql_where_array = array();
	if ($min_time)
	{
		$sql_where_array[] = 'r.time >=' . $min_time;
	}
	if ($user_from)
	{
		$sql_where_array[] = 'r.rep_from = ' . $user_from;
	}
	if ($user_to)
	{
		$sql_where_array[] = 'r.rep_to = ' . $user_to;
	}
	$sql_where_array = implode(' AND ', $sql_where_array);
	if ($sql_where_array)
	{
		$sql_where = 'WHERE ' . $sql_where_array;
	}

	$sql = 'SELECT COUNT(r.rep_id) AS total
		FROM ' . REPUTATIONS_TABLE . " r
		$sql_where";

	$sort_key = request_var('sk', $default_key);
	$sort_dir = request_var('sd', $default_dir);

	$limit_days = array(0 => $user->lang['ALL_REPUTATIONS'], 1 => $user->lang['1_DAY'], 7 => $user->lang['7_DAYS'], 14 => $user->lang['2_WEEKS'], 30 => $user->lang['1_MONTH'], 90 => $user->lang['3_MONTHS'], 180 => $user->lang['6_MONTHS'], 365 => $user->lang['1_YEAR']);
	$sort_by_text = array('rf' => $user->lang['RS_FROM'], 'ru' => $user->lang['RS_TO_USER'], 't' => $user->lang['RS_TIME'], 'p' => $user->lang['RS_POINT']);
	$sort_by_sql = array('rf' => 'u.username_clean', 'ru' => 'ru.username_clean', 't' => 'r.time', 'p' => 'r.point');

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