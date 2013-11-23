<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\mcp;

class reputation_module
{
	var $p_master;
	var $u_action;

	function mcp_reputation(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $request;
		global $config, $phpbb_root_path, $phpEx, $phpbb_container;

		$reputation_table = $phpbb_container->getParameter('tables.reputations');

		$user->add_lang_ext('pico88/reputation', 'reputation_system');

		$this->page_title = 'RS_TITLE';

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

		switch ($mode)
		{
			case 'front':
				$this->tpl_name = 'mcp_front';

				$sql = 'SELECT user_id, username, user_colour, user_reputation
					FROM ' . USERS_TABLE . '
					WHERE user_reputation > 0
					ORDER BY user_reputation DESC';
				$result = $db->sql_query_limit($sql, 5);

				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('best', array(
						'USERNAME_FULL'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
						'U_DETAILS'			=> $phpbb_container->get('controller.helper')->url('reputation/' . $row['user_id']),
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
						'U_DETAILS'			=> $phpbb_container->get('controller.helper')->url('reputation/' . $row['user_id']),
						'REPUTATION'		=> $row['user_reputation'],
					));
				}
				$db->sql_freeresult($result);

				$sql = $db->sql_build_query('SELECT', array(
					'SELECT'	=> 'u.username as username_rep_from, u.user_colour as user_colour_rep_from, ut.username as username_rep_to, ut.user_colour as user_colour_rep_to, ut.user_reputation, r.*, p.post_id AS real_post_id, p.forum_id, p.post_subject',
					'FROM'		=> array($reputation_table => 'r'),
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
				$result = $db->sql_query_limit($sql, 10);

				while ($row = $db->sql_fetchrow($result))
				{
					$phpbb_container->get('reputation.display')->table_row($row);
				}
				$db->sql_freeresult($result);

				$template->assign_vars(array(
					'COMMENT'	=> $config['rs_enable_comment'] ? true : false,
				));
			break;

			case 'list':
				$start = $request->variable('start', 0);
				$sk = $request->variable('sk', '');

				$search_user_from = $request->variable('search_from', '', true);
				$search_user_to = $request->variable('search_to', '', true);

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

					if (!$row_from)
					{
						trigger_error('NO_USER');
					}
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

					if (!$row_to)
					{
						trigger_error('NO_USER');
					}
				}

				$sort_days = $total = 0;
				$sort_key = $sort_dir = '';
				$sort_by_sql = $sort_order_sql = array();

				reputation_sorting($sort_days, $sort_key, $sort_dir, $sort_by_sql, $sort_order_sql, $total, $row_from['user_id'], $row_to['user_id'], $reputation_table);

				$sql_array = array(
					'SELECT'	=> 'r.rep_id',
					'FROM'		=> array($reputation_table => 'r'),
				);

				if ($sk == 'a')
				{
					$sql_array['LEFT_JOIN'][] = array('FROM' => array(USERS_TABLE => 'u'), 'ON' => 'r.rep_from = u.user_id');
				}
				if ($sk == 'b')
				{
					$sql_array['LEFT_JOIN'][] = array('FROM' => array(USERS_TABLE => 'ru'), 'ON' => 'r.rep_to = ru.user_id');
				}

				$sql_where = array();

				if ($sort_days)
				{
					$sql_where[] = 'r.time >= ' . (time() - ($sort_days * 86400));
				}
				if (!empty($search_user_from) && $row_from['user_id'])
				{
					$sql_where[] = 'r.rep_from = ' . $row_from['user_id'];
				}
				if (!empty($search_user_to) && $row_to['user_id'])
				{
					$sql_where[] = 'r.rep_to = ' . $row_to['user_id'];
				}
				if (!$config['rs_negative_point'])
				{
					$sql_where[] = 'point > 0';
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
					$display = $phpbb_container->get('reputation.display');
					$sql = $db->sql_build_query('SELECT', array(
						'SELECT'	=> 'r.*, u.username as username_rep_from, u.user_colour as user_colour_rep_from, ru.username as username_rep_to, ru.user_colour as user_colour_rep_to, ru.user_reputation, p.post_id AS real_post_id, p.post_subject',
						'FROM'		=> array($reputation_table => 'r'),
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
						$phpbb_container->get('reputation.display')->table_row($row);
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
					'S_SEARCH_FROM'			=> $search_user_from,
					'S_SEARCH_TO'			=> $search_user_to,

					'U_MCP_ACTION'			=> $this->u_action,

					'PAGE_NUMBER'			=> phpbb_on_page($template, $user, $pagination_url, $total, $config['rs_per_page'], $start),
					'TOTAL_REPS'			=> $user->lang('LIST_REPUTATIONS', $total),
				));

				$this->tpl_name = 'mcp_list';
			break;

			case 'give_point':
				$user->add_lang_ext('pico88/reputation', 'info_acp_reputation');

				add_form_key('mcp_reputation');

				$username = $request->variable('username', '', true);
				$comment = $request->variable('comment', '', true);
				$points = $request->variable('points', '');

				$rs_power = '';
				$reputationpower = $phpbb_container->get('reputation.power')->get($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings']);
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

				if ($request->is_set_post('submit'))
				{
					$redirect = append_sid("{$phpbb_root_path}mcp.$phpEx", "i=$id&amp;mode=$mode");

					if (!check_form_key('mcp_reputation') || !is_numeric($points) || ($points > $reputationpower) || ($points < -$reputationpower))
					{
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
						trigger_error($user->lang['RS_NO_USER_ID']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					if ($user_row['user_type'] == USER_IGNORE)
					{
						trigger_error($user->lang['RS_USER_ANONYMOUS']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					if ($user_row['user_id'] == $user->data['user_id'])
					{
						trigger_error($user->lang['RS_SELF']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					if ($user->check_ban($user_row['user_id'], false, false, true))
					{
						trigger_error($user->lang['RS_USER_BANNED']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
					}

					$phpbb_container->get('reputation.manager')->give_point($user_row['user_id'], 0, $comment, $points, 'user');

					trigger_error($user->lang['RS_SENT']. '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>'));
				}

				$this->page_title = 'ACP_REPUTATION_GIVE';

				$template->assign_vars(array(
					'U_FIND_USERNAME'	=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=mcp_reputation&amp;field=username&amp;select_single=true'),
					'S_MCP_ACTION'		=> $this->u_action,
				));

				$this->tpl_name = 'mcp_give';
			break;
		}
	}
}

function reputation_sorting(&$sort_days, &$sort_key, &$sort_dir, &$sort_by_sql, &$sort_order_sql, &$total, &$user_from, &$user_to, $reputation_table)
{
	global $db, $user, $auth, $template;

	$sort_days = request_var('st', 0);
	$min_time = ($sort_days) ? time() - ($sort_days * 86400) : 0;

	$default_key = 'c';
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
		FROM ' . $reputation_table . " r
		$sql_where";

	$sort_key = request_var('sk', $default_key);
	$sort_dir = request_var('sd', $default_dir);

	$limit_days = array(0 => $user->lang['ALL_REPUTATIONS'], 1 => $user->lang['1_DAY'], 7 => $user->lang['7_DAYS'], 14 => $user->lang['2_WEEKS'], 30 => $user->lang['1_MONTH'], 90 => $user->lang['3_MONTHS'], 180 => $user->lang['6_MONTHS'], 365 => $user->lang['1_YEAR']);
	$sort_by_text = array(
		'a'	=> $user->lang['RS_FROM'],
		'b'	=> $user->lang['RS_TO_USER'],
		'c'	=> $user->lang['RS_TIME'],
		'd'	=> $user->lang['RS_POINT'],
		'e'	=> $user->lang['RS_ACTION'],
		'f'	=> $user->lang['POST']
	);
	$sort_by_sql = array(
		'a'	=> 'u.username_clean',
		'b'	=> 'ru.username_clean',
		'c'	=> 'r.rep_id',
		'd'	=> 'r.point',
		'e'	=> 'r.action',
		'f'	=> 'r.post_id'
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