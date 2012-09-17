<?php
/**
*
* @package	Reputation System
* @author	Pico88 (http://www.modsteam.tk)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . '/includes/functions_reputation.' . $phpEx);

$reputation = new reputation();

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup(array('mods/reputation_system', 'memberlist'));

$mode = request_var('mode', '');
$id = intval(request_var('id', ''));
$uid = intval(request_var('u', ''));
$post_id = intval(request_var('p', ''));
$point = request_var('point', 'positive');
$start = request_var('start', 0);
$ajax = request_var('ajax', 0);

if (!$config['rs_enable'])
{
	if ($ajax)
	{
		echo json_encode(array('error_msg' => $user->lang['RS_DISABLED']));
		return;
	}
	else
	{
		$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
		$message = $user->lang['RS_DISABLED'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
		meta_refresh(3, $meta_info);
		trigger_error($message);
	}
}

switch ($mode)
{
	case 'postdetails':

		$action	= request_var('action', '');

		if (!$auth->acl_get('u_rs_view'))
		{
			$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
			$message = $user->lang['RS_VIEW_DISALLOWED'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_VIEW_DISALLOWED']));
				return;
			}
			else
			{
				meta_refresh(3, $meta_info);
				trigger_error($message);
			}
		}

		if (empty($post_id) && ($action <> 'delete'))
		{
			$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
			$message = $user->lang['RS_NO_POST_ID'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_NO_POST_ID']));
				return;
			}
			else
			{
				meta_refresh(3, $meta_info);
				trigger_error($message);
			}
		}

		if ($action == 'delete')
		{
			$sql = 'SELECT rep_from, post_id 
				FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_id = $id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if ($auth->acl_gets('m_rs_moderate') ||($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete')))
			{
				$s_hidden_fields = build_hidden_fields(array(
					'id'		=> $id,
					'p'			=> $row['post_id'],
					'mode'		=> 'postdetails',
					'action'	=> 'delete')
				);

				if (confirm_box(true))
				{
					if ($reputation->delete($id))
					{
						$meta_info = append_sid("{$phpbb_root_path}reputation.$phpEx", "mode=postdetails&amp;p={$row['post_id']}");
						$message = $user->lang['RS_POINT_DELETED'] . '<br /><br />' . sprintf($user->lang['RS_RETURN_POSTDETAILS'], '<a href="' . append_sid("{$phpbb_root_path}reputation.$phpEx", "mode=postdetails&amp;p={$row['post_id']}") . '">', '</a>');

						meta_refresh(3, $meta_info);
						trigger_error($message);
						break;
					}
				}
				else
				{
					confirm_box(false, $user->lang['RS_DELETE_POINT_CONFIRM'], $s_hidden_fields);
				}
			}
		}

		$rs_per_page = ($config['rs_ajax_enable'] && $ajax) ? $config['rs_per_popup']	: $config['rs_per_page'];

		$default_key = 'b';

		$check_params = array(
			'p'		=> array('p', $post_id),
			'sk'	=> array('sk', $default_key),
			'sd'	=> array('sd', 'a')
		);

		$sort_key = request_var('sk', $default_key);
		$sort_dir = request_var('sd', 'a');

		$params[] = "mode=postdetails";
		$sort_params[] = "mode=postdetails";

		foreach ($check_params as $key => $call)
		{
			if (!isset($_REQUEST[$key]))
			{
				continue;
			}

			$param = call_user_func_array('request_var', $call);
			$param = urlencode($key) . '=' . ((is_string($param)) ? urlencode($param) : $param);
			$params[] = $param;

			if ($key != 'sk' && $key != 'sd')
			{
				$sort_params[] = $param;
			}
		}
		
		$sort_key_sql = array('a' => 'u.username_clean', 'b' => 'r.time');
		$pagination_url = append_sid("{$phpbb_root_path}reputation.$phpEx", implode('&amp;', $params));
		$sort_url = append_sid("{$phpbb_root_path}reputation.$phpEx", implode('&amp;', $sort_params));

		if (!isset($sort_key_sql[$sort_key]))
		{
			$sort_key = $default_ke;
		}

		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'a') ? 'DESC' : 'ASC');

		$sql = 'SELECT COUNT(rep_id) AS total_reps
			FROM ' . REPUTATIONS_TABLE . "
			WHERE post_id = $post_id";
		$result = $db->sql_query($sql);
		$total_reps = (int) $db->sql_fetchfield('total_reps');
		$db->sql_freeresult($result);

		$sql_array = array(
			'SELECT'	=> 'u.username, u.user_colour, u.user_reputation, r.*',
			'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_from = u.user_id',
				),
			),
			'WHERE'		=> 'r.post_id = ' . $post_id,
			'ORDER_BY'	=> $order_by . ', r.rep_id ASC'
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query_limit($sql, $rs_per_page, $start);

		while ($row = $db->sql_fetchrow($result))
		{
			$userid_to = $row['rep_to'];
			$row['bbcode_options'] = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) + (($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + (($row['enable_urls']) ? OPTION_FLAG_LINKS : 0);
			
			$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : $user->lang['RS_NA'];
			$time = $user->format_date($row['time']);
			$user_from = get_username_string('full', $row['rep_from'], $row['username'], $row['user_colour']);

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

			$delete_link = false;
			if($auth->acl_get('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete'))) $delete_link = '<a href="reputation.' . $phpEx . '?mode=postdetails&amp;id=' . $row['rep_id'] . '&amp;action=delete">' . $user->lang['DELETE'] . '</a>';

			$template->assign_block_vars('reputation', array(
				'USERNAME'			=> $user_from,
				'TIME'				=> $time,
				'COMMENT'			=> $comment,
				'DELETE'			=> $delete_link,
				'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
				'POINT_CLASS'		=> $config['rs_point_type'] ? 'zero' : $point_class,
			));
		}

		$sql_array = array(
			'SELECT'	=> 'u.user_id, u.username, u.user_colour, p.post_subject, p.post_reputation',
			'FROM'		=> array(USERS_TABLE => 'u'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'p.poster_id = u.user_id',
				),
			),
			'WHERE'		=> 'p.post_id = ' . $post_id
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		page_header($user->lang['RS_TITLE']);

		$template->assign_vars(array(
			'USERNAME'			=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
			'POST_SUBJECT'		=> $row['post_subject'],
			'POST_REPUTATION'	=> $row['post_reputation'],
			'POST_ID'			=> $post_id,
			'PAGINATION'		=> generate_pagination($pagination_url, $total_reps, $config['rs_per_page'], $start),
			'PAGE_NUMBER'		=> on_page($total_reps, $config['rs_per_page'], $start),
			'U_SORT_USERNAME'	=> $sort_url . '&amp;sk=a&amp;sd=' . (($sort_key == 'a' && $sort_dir == 'd') ? 'a' : 'd'),
			'U_SORT_TIME'		=> $sort_url . '&amp;sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'd') ? 'a' : 'd'),
			'DELETE_LINK'		=> ($auth->acl_get('m_rs_moderate') || $auth->acl_get('u_rs_delete')) ? true : false,
			'COMMENT'			=> $config['rs_enable_comment'] ? true : false,
			'USER'				=> true,
			'AJAX'				=> $ajax ? true : false,
			'MORE_DETAILS'		=> append_sid("{$phpbb_root_path}reputation.$phpEx", '&amp;mode=postdetails&amp;p=' . $post_id),
		));

		$template->set_filenames(array(
			'body' => 'reputation/postdetails.html')
		);

		page_footer();

	break;

	case 'details':

		$popup = request_var('popup', 0);

		if (!$auth->acl_get('u_rs_view'))
		{
			$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
			$message = $user->lang['RS_VIEW_DISALLOWED'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		$sql = 'SELECT user_id
			FROM ' . USERS_TABLE . "
			WHERE user_type <> 2
				AND user_id = $uid";
		$result = $db->sql_query($sql);
		$user_check = $db->sql_fetchfield('user_id');
		$db->sql_freeresult($result);

		if (empty($uid) || empty($user_check))
		{
			$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
			$message = $user->lang['RS_NO_USER_ID'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		$rs_per_page = ($config['rs_ajax_enable'] && $ajax) ? $config['rs_per_popup']	: $config['rs_per_page'];

		$default_key = 'b';

		$check_params = array(
			'u'		=> array('u', $uid),
			'sk'	=> array('sk', $default_key),
			'sd'	=> array('sd', 'a')
		);

		$sort_key = request_var('sk', $default_key);
		$sort_dir = request_var('sd', 'a');

		if ($popup)
		{
			$params[] = "mode=details&amp;popup=1";
			$sort_params[] = "mode=details&amp;popup=1";
		}
		else
		{
			$params[] = "mode=details";
			$sort_params[] = "mode=details";
		}

		foreach ($check_params as $key => $call)
		{
			if (!isset($_REQUEST[$key]))
			{
				continue;
			}

			$param = call_user_func_array('request_var', $call);
			$param = urlencode($key) . '=' . ((is_string($param)) ? urlencode($param) : $param);
			$params[] = $param;

			if ($key != 'sk' && $key != 'sd')
			{
				$sort_params[] = $param;
			}
		}

		$sort_key_sql = array('a' => 'u.username_clean', 'b' => 'r.time');
		$pagination_url = append_sid("{$phpbb_root_path}reputation.$phpEx", implode('&amp;', $params));
		$sort_url = append_sid("{$phpbb_root_path}reputation.$phpEx", implode('&amp;', $sort_params));

		if (!isset($sort_key_sql[$sort_key]))
		{
			$sort_key = $default_ke;
		}

		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'a') ? 'DESC' : 'ASC');

		$sql = 'SELECT COUNT(rep_id) AS total_reps
			FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_to = $uid";
		$result = $db->sql_query($sql);
		$total_reps = (int) $db->sql_fetchfield('total_reps');
		$db->sql_freeresult($result);

		$sql_array = array(
			'SELECT'	=> 'u.username, u.user_colour, u.user_reputation, r.*, p.post_subject, p.forum_id',
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
			'WHERE'		=> 'r.rep_to = ' . $uid,
			'ORDER_BY'	=> $order_by . ', r.rep_id ASC'
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query_limit($sql, $rs_per_page, $start);

		while ($row = $db->sql_fetchrow($result))
		{
			$row['bbcode_options'] = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) + (($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + (($row['enable_urls']) ? OPTION_FLAG_LINKS : 0);

			$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : $user->lang['RS_NA'];
			$time = $user->format_date($row['time']);
			$user_from = get_username_string('full', $row['rep_from'], $row['username'], $row['user_colour']);

			$post_subject = (empty($row['post_subject'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'] . ' [#p' . $row['post_id'] . ']';
			$post_link = (!empty($row['post_subject'])) ? ($auth->acl_get('f_read', $row['forum_id']) ? '<br /><a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : '') : '<br />' . $post_subject;

			if ($row['action'] == 1)
			{
				$action = $user->lang['RS_POST_RATING'] . '' . $post_link;
				$short_action = $user->lang['RS_POST_RATING'];
			}
			else if ($row['action'] == 2)
			{
				$action = $short_action = $user->lang['RS_USER_RATING'];
			}
			else if ($row['action'] == 3)
			{
				$action = $short_action = $user->lang['RS_WARNING'];
			}
			else if ($row['action'] == 4)
			{
				$action = $short_action = $user->lang['RS_BAN'];
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

			$delete_link = false;
			$popup_link = ($popup) ? '&amp;popup=1' : '';
			if($auth->acl_get('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete'))) $delete_link = '<a href="reputation.' . $phpEx . '?mode=delete' . $popup_link . '&amp;id=' . $row['rep_id'] . '">' . $user->lang['DELETE'] . '</a>';

			$template->assign_block_vars('reputation', array(
				'USERNAME'			=> $user_from,
				'ACTION'			=> $action,
				'SHORT_ACTION'		=> $short_action,
				'TIME'				=> $time,
				'COMMENT' 			=> $comment,
				'DELETE' 			=> $delete_link,
				'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
				'POINT_CLASS'		=> $config['rs_point_type'] ? 'zero' : $point_class,
			));
		}

		$sql = 'SELECT *
			FROM ' . USERS_TABLE . "
			WHERE user_id = $uid";
		$result = $db->sql_query($sql);
		$user_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!function_exists('get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		}

		$rank_title = $rank_img = $rank_img_src = $rs_rank_title = $rs_rank_img = $rs_rank_img_src = $rs_rank_color = '';
		get_user_rank($user_row['user_rank'], $user_row['user_posts'], $rank_title, $rank_img, $rank_img_src);
		if ($config['rs_ranks']) $reputation->get_rs_rank($user_row['user_reputation'], $rs_rank_title, $rs_rank_img, $rs_rank_img_src, $rs_rank_color);

		$avatar_img = get_user_avatar($user_row['user_avatar'], $user_row['user_avatar_type'], $user_row['user_avatar_width'], $user_row['user_avatar_height']);

		$positive_count = $negative_count = 0;
		$positive_week = $negative_week = 0;
		$positive_month = $negative_month = 0;
		$positive_6months = $negative_6months = 0;

		$last_week = time() - 604800;
		$last_month = time() - 2678400;
		$last_6months = time() - 16070400;

		$sql = 'SELECT time, point
			FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_to = $uid";
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

		if ($config['rs_enable_power'])
		{
			$user_reputation_stats = $reputation->get_reputation_stats($user_row['user_id']);
			$user_max_voting_power = $reputation->get_rep_power($user_row['user_posts'], $user_row['user_regdate'], $user_row['user_reputation'], $user_row['group_id'], $user_row['user_warnings'], $user_reputation_stats['bancounts']);
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

		page_header($user->lang['RS_DETAILS']);

		$template->assign_vars(array(
			'USERNAME'			=> get_username_string('username', $user_row['user_id'], $user_row['username'], $user_row['user_colour']),
			'USERNAME_FULL'		=> get_username_string('full', $user_row['user_id'], $user_row['username'], $user_row['user_colour']),
			'U_RATE_USER' 		=> ($config['rs_user_rating'] && $auth->acl_get('u_rs_give')) ? append_sid("{$phpbb_root_path}reputation.$phpEx", '&amp;mode=rateuser&amp;u=' . $user_row['user_id']) : '',
			'REPUTATIONS'		=> ($user_row['user_reputation']),
			'AVATAR_IMG'		=> $avatar_img,
			'RANK_TITLE'		=> $rank_title,
			'RANK_IMG'			=> $rank_img,
			'RS_RANK_TITLE'		=> $rs_rank_title,
			'RS_RANK_IMG'		=> $rs_rank_img,
			'REPUTATION_BOX'	=> $config['rs_ranks'] ? $rs_rank_color : (($user_row['user_reputation'] == 0) ? 'zero' : (($user_row['user_reputation'] > 0) ? 'positive' : 'negative')),
			'PAGINATION'		=> generate_pagination($pagination_url, $total_reps, $config['rs_per_page'], $start),
			'PAGE_NUMBER'		=> on_page($total_reps, $config['rs_per_page'], $start),
			'U_SORT_USERNAME'	=> $sort_url . '&amp;sk=a&amp;sd=' . (($sort_key == 'a' && $sort_dir == 'd') ? 'a' : 'd'),
			'U_SORT_TIME'		=> $sort_url . '&amp;sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'd') ? 'a' : 'd'),
			'U_SORT_POSTS'		=> $sort_url . '&amp;sk=c&amp;sd=' . (($sort_key == 'c' && $sort_dir == 'd') ? 'a' : 'd'),
			'DELETE_LINK'		=> ($auth->acl_get('m_rs_moderate') || $auth->acl_get('u_rs_delete')) ? true : false,
			'COMMENT'			=> $config['rs_enable_comment'] ? true : false,
			'REP_POWER_ENABLE'	=> $config['rs_enable_power'] ? true : false,
			'POSITIVE_COUNT'	=> $positive_count,
			'POSITIVE_WEEK'		=> $positive_week,
			'POSITIVE_MONTH'	=> $positive_month,
			'POSITIVE_6MONTHS'	=> $positive_6months,
			'NEGATIVE_COUNT'	=> $negative_count,
			'NEGATIVE_WEEK'		=> $negative_week,
			'NEGATIVE_MONTH'	=> $negative_month,
			'NEGATIVE_6MONTHS'	=> $negative_6months,
			'AJAX'				=> $ajax ? true : false,
			'POPUP'				=> $popup ? true : false,
			'MORE_DETAILS'		=> append_sid("{$phpbb_root_path}reputation.$phpEx", '&amp;mode=details&amp;popup=1&amp;u=' . $uid),
		 ));

		$template->set_filenames(array(
			'body' => 'reputation/details.html')
		);

		page_footer();

	break;

	case 'ratepost':

		//Let's check if reputation is enabled for this forum
		$sql_array = array(
			'SELECT'	=> 'f.enable_reputation',
			'FROM'		=> array(FORUMS_TABLE => 'f'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'f.forum_id = p.forum_id',
				),
			),
			'WHERE'		=> 'p.post_id = ' . $post_id,
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$reputation_enabled_for_this_forum = (int) $db->sql_fetchfield('enable_reputation');
		$db->sql_freeresult($result);

		//Fire error if it's disabled and exit
		if (!$config['rs_post_rating'] || !$config['rs_negative_point'] && $point == 'negative' || !$reputation_enabled_for_this_forum)
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_DISABLED']));
				return;
			}
			else
			{
				$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
				$message = $user->lang['RS_DISABLED'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
				meta_refresh(3, $meta_info);
				trigger_error($message);
			}
		}

		$notify = request_var('notify_user', '');
		$comment = utf8_normalize_nfc(request_var('comment', '', true));
		$rep_power = request_var('rep_power', '');

		$error = '';
		$redirect = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id;

		//If cancel was pressed, exit voting
		if (isset($_POST['cancel']))
		{
			//We won't get there if the template is correct, but just to be safe
			if (!$ajax) redirect($redirect);
			return;
		}

		$sql = 'SELECT u.*, p.*
			FROM ' . POSTS_TABLE . ' p, ' . USERS_TABLE . " u
			WHERE post_id = $post_id
				AND u.user_id = p.poster_id";
		$result = $db->sql_query($sql);
		$user_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);


		//We couldn't find this post. May be it was deleted while user voted?
		if (!$user_row)
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_NO_POST']));
				return;
			}
			else
			{
				$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
				$message = $user->lang['RS_NO_POST'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
				meta_refresh(3, $meta_info);
				trigger_error($message);
			}
		}

		//No anonymous voting is allowed
		if ($user_row['user_type'] == USER_IGNORE)
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_USER_ANONYMOUS']));
				return;
			}
			else
			{
				$message = $user->lang['RS_USER_ANONYMOUS'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		//You can not vote for your posts
		if ($user_row['user_id'] == $user->data['user_id'])
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_SELF']));
				return;
			}
			else
			{
				$message = $user->lang['RS_SELF'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		//Check if user votes for the same post for the second time
		$sql = 'SELECT rep_id, point
			FROM ' . REPUTATIONS_TABLE . "
			WHERE post_id = $post_id
				AND rep_from = {$user->data['user_id']}";
		$result = $db->sql_query($sql);
		$check_user = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($check_user)
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => sprintf($user->lang['RS_SAME_POST'], $check_user['point'])));
				return;
			}
			else
			{
				$message = sprintf($user->lang['RS_SAME_POST'], $check_user['point']) . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		//Check if user is allowed to vote
		if (!$auth->acl_get('f_rs_give', $user_row['forum_id']) || !$auth->acl_get('f_rs_give_negative', $user_row['forum_id']) && $point == 'negative' || !$auth->acl_get('u_rs_ratepost'))
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
				return;
			}
			else
			{
				$message = $user->lang['RS_USER_DISABLED'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		//Check if user reputation is enought to give negative points
		if ($config['rs_min_rep_negative'] && ($user->data['user_reputation'] < $config['rs_min_rep_negative']) && $point == 'negative')
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => sprintf($user->lang['RS_USER_NEGATIVE'], $config['rs_min_rep_negative'])));
				return;
			}
			else
			{
				$message = sprintf($user->lang['RS_USER_NEGATIVE'], $config['rs_min_rep_negative']) . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		// Anti-abuse behaviour
		if (!empty($config['rs_anti_time']) && !empty($config['rs_anti_post']))
		{
			$anti_time = time() - $config['rs_anti_time'] * 3600;
			$sql_and = (!$config['rs_anti_method']) ? 'AND rep_to = ' . $user_row['user_id'] : '';
			$sql = 'SELECT COUNT(rep_id) AS rep_per_day
				FROM ' . REPUTATIONS_TABLE . '
				WHERE rep_from = ' . $user->data['user_id'] . '
					' . $sql_and . '
					AND post_id != 0
					AND time > ' . $anti_time;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			if ($row['rep_per_day'] >= $config['rs_anti_post'])
			{
				if ($ajax)
				{
					echo json_encode(array('error_msg' => $user->lang['RS_ANTISPAM_INFO']));
					return;
				}
				else
				{
					$message = $user->lang['RS_ANTISPAM_INFO'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}
			unset($row);
		}

		// Disallow rating banned users
		if ($user->check_ban($user_row['poster_id'], false, false, true))
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_USER_BANNED']));
				return;
			}
			else
			{
				$message = $user->lang['RS_USER_BANNED'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		// Submit vote
		$submit = false;
		if (isset($_POST['submit']) && !$ajax)
		{
			$submit = true;
		}
		if (isset($_POST['ajax']) && $_POST['ajax'] == 1)
		{
			$submit = true;
		}

		// Force comment
		if ($submit && ($config['rs_force_comment'] == RS_COMMENT_BOTH || $config['rs_force_comment'] == RS_COMMENT_POST) && ((utf8_clean_string($comment) === '')))
		{
			$error = $user->lang['RS_NO_COMMENT'];
		}

		// Comment length
		if ($submit && $config['rs_comment_max_chars'] && (strlen($comment) > $config['rs_comment_max_chars']))
		{
			$error = sprintf($user->lang['RS_TOO_LONG_COMMENT'], strlen($comment), $config['rs_comment_max_chars']);
		}

		if (!$config['rs_enable_comment'] && !$config['rs_enable_power'])
		{
			$error = '';
			$submit = true;
			$rep_power = ($point == 'negative') ? -1 : 1;
		}

		if (!$config['rs_enable_power'])
		{
			$rep_power = ($point == 'negative') ? -1 : 1;
		}

		$voting_power_left = $max_voting_allowed = '';
		// Get reputation power
		if ($config['rs_enable_power'])
		{
			$voting_power_pulldown = '';

			//Get details on user voting: how much power he spent, how many bandays he had
			$user_reputation_stats = $reputation->get_reputation_stats($user->data['user_id']);

			//Calculate how much maximum power a user has
			$max_voting_power = $reputation->get_rep_power($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings'], $user_reputation_stats['bancounts']);

			$voting_power_left = $max_voting_power - $user_reputation_stats['renewal_time'];

			//Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			//If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $config['rs_power_renewal'])
			{
				$error_text = sprintf($user->lang['RS_NO_POWER_LEFT'], $max_voting_power);

				if ($ajax)
				{
					echo json_encode(array('error_msg' => $error_text));
					return;
				}
				else
				{
					$message = $error_text . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}

			//Preparing HTML for voting by manual spending of user power
			for($i = 1; $i <= $max_voting_allowed; ++$i)
			{
				if ($point == 'negative')
				{
					$voting_power_pulldown = '<option value="-' . $i . '">' . $user->lang['RS_NEGATIVE'] . ' (-' . $i . ') </option>';

					if ($i == $user->data['user_rs_default_power'] && isset($user->data['user_rs_default_power']))
					{
						$voting_power_pulldown = '<option value="-' . $i . '" selected="selected">' . $user->lang['RS_NEGATIVE'] . ' (-' . $i . ') </option>';
					}
				}
				else
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $user->lang['RS_POSITIVE'] . ' (+' . $i . ')</option>';

					if ($i == $user->data['user_rs_default_power'] && isset($user->data['user_rs_default_power']))
					{
						$voting_power_pulldown = '<option value="' . $i . '" selected="selected">' . $user->lang['RS_POSITIVE'] . ' (+' . $i . ') </option>';
					}
				}

				$template->assign_block_vars('reputation', array(
					'REPUTATION_POWER'	=> $voting_power_pulldown)
				);
			}
		}

		if ($submit && !$error)
		{
			$user_id = request_var('user_id', 0);
			$session_id = request_var('sess', '');

			if (($user_id != $user->data['user_id'] || $session_id != $user->session_id) && $config['rs_enable_comment'])
			{
				if ($ajax)
				{
					echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
					return;
				}
				else
				{
					$message = $user->lang['RS_USER_DISABLED'] . '<br /><br />' . sprintf($user->lang['RETURN_TOPIC'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", "p=$post_id") . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}

			//Prevent cheater to break the forum permissions to give negative points or give more points than they can 
			if (!$auth->acl_get('f_rs_give_negative', $user_row['forum_id']) && $rep_power < 0 || $rep_power < 0 && $config['rs_min_rep_negative'] && ($user->data['user_reputation'] < $config['rs_min_rep_negative']) || $config['rs_enable_power'] && (($rep_power > $max_voting_allowed) || ($rep_power < -$max_voting_allowed)))
			{
				if ($ajax)
				{
					echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
					return;
				}
				else
				{
					$message = $user->lang['RS_USER_DISABLED'] . '<br /><br />' . sprintf($user->lang['RETURN_TOPIC'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", "p=$post_id") . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}

			$post_rating_mode = ($reputation_enabled_for_this_forum == 1) ? 'post' : 'onlypost';
			if ($reputation->give_point($user_row['poster_id'], $post_id, $comment, $notify, $rep_power, $post_rating_mode))
			{
				if ($ajax)
				{
					// If it's an AJAX request, generate JSON reply
					$new_rating = $reputation->get_rating($post_id, $config['rs_post_display']);
					$new_user_reputation = $reputation->get_user_reputation($user_row['poster_id']);
					$new_reputation_rank = $config['rs_ranks'] ? $reputation->get_rs_new_rank($new_user_reputation) : '';
					$json_data = array(
						'post_id'				=> $post_id,
						'poster_id'				=> $user_row['poster_id'],
						'new_post_rating'		=> $new_rating,
						'new_user_reputation'	=> '<strong>' . $new_user_reputation . '</strong>',
						'new_reputation_rank'	=> $new_reputation_rank,
						'new_post_rating_class' => $reputation->get_vote_class($new_rating),
						'new_post_class'		=> ($rep_power > 0) ? 'rated_good' : 'rated_bad',
						'check_vote'			=> $config['rs_negative_point'] ? true : false,
						'what_to_fadeout'		=> ($rep_power > 0) ? '.rate-bad-icon a' : '.rate-good-icon a',
					);

					echo json_encode($json_data);
					return '';
					//Returned JSON data and stop the script.
				}
				else
				{
					$meta_info = append_sid("{$phpbb_root_path}viewtopic.$phpEx", "p=$post_id#p$post_id");
					$message = $user->lang['RS_SENT'] . '<br /><br />' . sprintf($user->lang['RETURN_TOPIC'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", "p=$post_id#p$post_id") . '">', '</a>');

					meta_refresh(3, $meta_info);
					trigger_error($message);
				}
			}
		}

		$use_page = $phpbb_root_path . str_replace('&', '&amp;', $user->page['page']);
		$u_action = reapply_sid($use_page);

		if (!$ajax)
		{
			// We want to make the message available here as a reminder
			// Parse the message and subject
			$message = (strlen($user_row['post_text']) > 1000) ? substr($user_row['post_text'], 0, 1000) . '...' : $user_row['post_text'];
			$message = censor_text($message);

			// Second parse bbcode here
			if ($user_row['bbcode_bitfield'])
			{
				include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);

				$bbcode = new bbcode($user_row['bbcode_bitfield']);
				$bbcode->bbcode_second_pass($message, $user_row['bbcode_uid'], $user_row['bbcode_bitfield']);
			}

			$message = bbcode_nl2br($message);
			$message = smiley_text($message);

			if (!function_exists('get_user_avatar'))
			{
				include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
			}

			$rank_title = $rank_img = $rank_img_src = $rs_rank_title = $rs_rank_img = $rs_rank_img_src = $rs_rank_color = '';
			if ($config['rs_post_detail'])
			{
				get_user_rank($user_row['user_rank'], $user_row['user_posts'], $rank_title, $rank_img, 	$rank_img_src);
				if ($config['rs_ranks']) $reputation->get_rs_rank($user_row['user_reputation'], $rs_rank_title, $rs_rank_img, $rs_rank_img_src, $rs_rank_color);

				$avatar_img = get_user_avatar($user_row['user_avatar'], $user_row['user_avatar_type'], $user_row['user_avatar_width'], $user_row['user_avatar_height']);
			}

			$template->assign_vars(array(
				'POST_DETAIL' 		=> $config['rs_post_detail'] ? true : false,
				'POST'				=> $message,
				'USERNAME'			=> $user_row['username'],
				'USER_COLOR'		=> (!empty($user_row['user_colour'])) ? $user_row['user_colour'] : '',
				'JOINED'			=> $user->format_date($user_row['user_regdate']),
				'POSTS'				=> ($user_row['user_posts']) ? $user_row['user_posts'] : 0,
				'WARNINGS'			=> ($user_row['user_warnings']) ? $user_row['user_warnings'] : 0,
				'REPUTATIONS'		=> ($user_row['user_reputation']) ? $user_row['user_reputation'] : 0,
				'RS_RANK_TITLE'		=> $config['rs_ranks'] ? $rs_rank_title : false,
				'AVATAR_IMG'		=> $avatar_img,
				'RANK_TITLE'		=> $rank_title,
				'RANK_IMG'			=> $rank_img,
				'RS_RANK_TITLE'		=> $rs_rank_title,
				'RS_RANK_IMG'		=> $rs_rank_img,
				'REPUTATION_BOX'	=> $config['rs_ranks'] ? $rs_rank_color : (($user_row['user_reputation'] == 0) ? 'zero' : (($user_row['user_reputation'] > 0) ? 'positive' : 'negative')),
				'HEADER_POINT'		=> ($point == 'negative') ? $user->lang['RS_SUBTRACT_POINTS_CONFIRM'] : $user->lang['RS_ADD_POINTS_CONFIRM'],
			));
		}

		$s_hidden_fields = build_hidden_fields(array(
			'user_id'	=> $user->data['user_id'],
			'sess'		=> $user->session_id)
		);

		page_header($user->lang['RS_POST_RATING']);

		$template->assign_vars(array(
			'ERROR'					=> ($error) ? $error : '',

			'RS_POWER_POINTS_LEFT'		=> $config['rs_power_renewal'] ? sprintf($user->lang['RS_VOTE_POWER_LEFT_OF_MAX'], $voting_power_left, $max_voting_power, $max_voting_allowed) : '',
			'RS_POWER_PROGRESS_EMPTY'	=> $config['rs_power_renewal'] ? round((($max_voting_power - $voting_power_left) / $max_voting_power) * 100,0) : '',

			'USER_COMMENT'				=> (!empty($comment)) ? $comment : (($point == 'negative') ? $user->data['user_rs_comment_neg'] : $user->data['user_rs_comment_pos']),
			'RS_COMMENT_TOO_LONG'		=> sprintf($user->lang['RS_COMMENT_TOO_LONG'], $config['rs_comment_max_chars']), 

			'S_RS_COMMENT_ENABLE'		=> $config['rs_enable_comment'] ? true : false,
			'S_RS_COMMENT_REQ'			=> ($config['rs_force_comment'] == RS_COMMENT_BOTH || $config['rs_force_comment'] == RS_COMMENT_POST) ? true : false,
			'S_RS_COMMENT_TOO_LONG'		=> $config['rs_comment_max_chars'] ? $config['rs_comment_max_chars'] : false,
			'S_RS_PM_NOTIFY' 			=> $config['rs_pm_notify'] ? true : false,
			'S_RS_POWER_ENABLE' 		=> $config['rs_enable_power'] ? true : false,

			'S_CONFIRM_ACTION'			=> $u_action,
			'S_HIDDEN_FIELDS'			=> $s_hidden_fields,
			'AJAX'						=> $ajax ? true : false,
		));

		$template->set_filenames(array(
			'body' => 'reputation/ratepost.html')
		);

		page_footer();

	break;

	case 'rateuser':

		if (!$config['rs_user_rating'] || !$auth->acl_get('u_rs_give'))
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_DISABLED']));
				return;
			}
			else
			{
				$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
				$message = $user->lang['RS_DISABLED'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
				meta_refresh(3, $meta_info);
				trigger_error($message);
			}

		}

		$username = request_var('username', '');
		$notify = request_var('notify_user', '');
		$user_to = request_var('u', 0);
		$comment = utf8_normalize_nfc(request_var('comment', '', true));
		$rep_power = request_var('rep_power', '');

		$mode = 'user';
		$error = '';
		$redirect = append_sid("{$phpbb_root_path}memberlist.$phpEx?mode=viewprofile", "u=$user_to");

		//If cancel was pressed, exit voting
		if (isset($_POST['cancel']))
		{
			//We won't get there if the template is correct, but just to be safe
			if (!$ajax) redirect($redirect);
			return;
		}

		$sql = 'SELECT *
			FROM ' . USERS_TABLE . "
			WHERE user_id = $user_to";
		$result = $db->sql_query($sql);
		$user_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!$user_row)
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_NO_USER_ID']));
				return;
			}
			else
			{
				$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
				$message = $user->lang['RS_NO_USER_ID'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
				meta_refresh(3, $meta_info);
				trigger_error($message);
			}
		}

		if ($user_row['user_type'] == USER_IGNORE)
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_USER_ANONYMOUS']));
				return;
			}
			else
			{
				$message = $user->lang['RS_USER_ANONYMOUS'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		if ($user_row['user_id'] == $user->data['user_id'])
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_SELF']));
				return;
			}
			else
			{
				$message = $user->lang['RS_SELF'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		// Disallow rating banned users
		if ($user->check_ban($user_row['user_id'], false, false, true))
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_USER_BANNED']));
				return;
			}
			else
			{
				$message = $user->lang['RS_USER_BANNED'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		$sql = 'SELECT rep_id, time
			FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_to = $user_to
				AND rep_from = {$user->data['user_id']}
				AND action = 2
			ORDER by rep_id DESC";
		$result = $db->sql_query($sql);
		$check_user = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($check_user && !$config['rs_user_rating_gap'])
		{
			if ($ajax)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_SAME_USER']));
				return;
			}
			else
			{
				$message = $user->lang['RS_SAME_USER'] . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}
		if ($config['rs_user_rating_gap'] && (time() < $check_user['time'] + $config['rs_user_rating_gap'] * 86400))
		{
			//Informe user how long he has to wait to rate user
			$next_vote_time = ($check_user['time'] + $config['rs_user_rating_gap'] * 86400) - time();
			$next_vote_in = '';
			$next_vote_in .= intval($next_vote_time / 86400) ? intval($next_vote_time / 86400) . ' ' . $user->lang['DAYS'] . ' ' : '';
			$next_vote_in .= (intval(intval($next_vote_time) / 3600) && !intval($next_vote_time / 86400))  ? intval(($next_vote_time / 3600) % 24) . ' ' . $user->lang['HOURS'] . ' ' : '';
			$next_vote_in .= intval(($next_vote_time / 60) % 60) ? intval(($next_vote_time / 60) % 60) . ' ' . $user->lang['MINUTES'] : '';
			$next_vote_in .= (intval($next_vote_time) < 60) ? intval($next_vote_time) . ' ' . $user->lang['SECONDS'] : '';

			if ($ajax)
			{
				echo json_encode(array('error_msg' => sprintf($user->lang['RS_USER_GAP'], $next_vote_in)));
				return;
			}
			else
			{
				$message = sprintf($user->lang['RS_USER_GAP'], $next_vote_in) . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . $redirect . '">', '</a>');
				meta_refresh(3, $redirect);
				trigger_error($message);
			}
		}

		// Submit vote
		$submit = false;
		if (isset($_POST['submit']) && !$ajax)
		{
			$submit = true;
		}
		if (isset($_POST['ajax']) && $_POST['ajax'] == 1)
		{
			$submit = true;
		}

		// Force comment
		if ($submit && ($config['rs_force_comment'] == RS_COMMENT_BOTH || $config['rs_force_comment'] == RS_COMMENT_POST) && ((utf8_clean_string($comment) === '')))
		{
			$error = $user->lang['RS_NO_COMMENT'];
		}

		// Comment length
		if ($submit && $config['rs_comment_max_chars'] && (strlen($comment) > $config['rs_comment_max_chars']))
		{
			$error = sprintf($user->lang['RS_TOO_LONG_COMMENT'], strlen($comment), $config['rs_comment_max_chars']);
		}

		if (!$config['rs_enable_comment'] && !$config['rs_enable_power'])
		{
			$error = '';
			$submit = true;
			// Always give positive points
			$rep_power = 1;
		}

		$voting_power_left = $max_voting_allowed = '';
		// Get reputation power
		if ($config['rs_enable_power'])
		{
			$voting_power_pulldown = '';

			//Get details on user voting: how much power he spent, how many bandays he had
			$user_reputation_stats = $reputation->get_reputation_stats($user->data['user_id']);

			//Calculate how much maximum power a user has
			$max_voting_power = $reputation->get_rep_power($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings'], $user_reputation_stats['bancounts']);

			$voting_power_left = $max_voting_power - $user_reputation_stats['renewal_time'];

			//Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			//If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $config['rs_power_renewal'])
			{
				$error_text = sprintf($user->lang['RS_NO_POWER_LEFT'], $max_voting_power);

				if ($ajax)
				{
					echo json_encode(array('error_msg' => $error_text));
					return;
				}
				else
				{
					$message = $error_text . '<br /><br />' . sprintf($user->lang['RETURN_PAGE'], '<a href="' . append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}

			//Preparing HTML for voting by manual spending of user power
			//for($i = 1; $i <= $max_voting_allowed; ++$i)
			$startpower = $config['rs_negative_point'] ? -$max_voting_allowed : 1;
			for($i = $max_voting_allowed; $i >= $startpower; $i--) //from + to -
			//for($i = $startpower; $i <= $reputationpower; ++$i) //from - to +
			{
				if ($i == 0)
				{
					$voting_power_pulldown = '';
				}
				if ($i > 0)
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $user->lang['RS_POSITIVE'] . ' (+' . $i . ') </option>';
				}
				if ($i < 0 && $auth->acl_get('u_rs_give_negative') && (!$config['rs_min_rep_negative'] || ($config['rs_min_rep_negative'] && ($user->data['user_reputation'] >= $config['rs_min_rep_negative']))))
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $user->lang['RS_NEGATIVE'] . ' (' . $i . ') </option>';
				}

				$template->assign_block_vars('reputation', array(
					'REPUTATION_POWER'	=> $voting_power_pulldown)
				);
			}
		}
		else
		{
			$rs_power = '<option value="1">' . $user->lang['RS_POSITIVE'] . '</option>';
			if ($auth->acl_get('u_rs_give_negative'))
			{
				$rs_power .= '<option value="-1">' . $user->lang['RS_NEGATIVE'] . '</option>';
			}
			$template->assign_block_vars('reputation', array(
				'REPUTATION_POWER'	=> $rs_power)
			);
		}

		if ($submit && !$error)
		{
			$user_id = request_var('user_id', 0);
			$session_id = request_var('sess', '');

			if (($user_id != $user->data['user_id'] || $session_id != $user->session_id) && $config['rs_enable_comment'])
			{
				if ($ajax)
				{
					echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
					return;
				}
				else
				{
					$message = $user->lang['RS_USER_DISABLED'] . '<br /><br />' . sprintf($user->lang['RS_RETURN_USER'], '<a href="' . $redirect . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}

			//Prevent cheater to break the forum permissions to give negative points or give more points than they can 
			if (!$auth->acl_get('u_rs_give_negative') && $rep_power < 0 || $rep_power < 0 && $config['rs_min_rep_negative'] && ($user->data['user_reputation'] < $config['rs_min_rep_negative']) || $config['rs_enable_power'] && (($rep_power > $max_voting_allowed) || ($rep_power < -$max_voting_allowed)))
			{
				if ($ajax)
				{
					echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
					return;
				}
				else
				{
					$message = $user->lang['RS_USER_DISABLED'] . '<br /><br />' . sprintf($user->lang['RS_RETURN_USER'], '<a href="' . $redirect . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}

			if ($reputation->give_point($user_row['user_id'], $post_id, $comment, $notify, $rep_power, $mode))
			{
				if ($ajax)
				{
					// If it's an AJAX request, generate JSON reply
					$json_data = array(
						'user_id'				=> $user_row['user_id'],
						'user_reputation'		=> '<strong>' . $reputation->get_user_reputation($user_row['user_id']) . '</strong>',
					);
					echo json_encode($json_data);
					return '';
					//Returned JSON data and stop the script.
				}
				else
				{
					$message = $user->lang['RS_SENT'] . '<br /><br />' . sprintf($user->lang['RS_RETURN_USER'], '<a href="' . $redirect . '">', '</a>');
					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}
		}

		$use_page = $phpbb_root_path . str_replace('&', '&amp;', $user->page['page']);
		$u_action = reapply_sid($use_page);

		$s_hidden_fields = build_hidden_fields(array(
			'user_id'	=> $user->data['user_id'],
			'sess'		=> $user->session_id)
		);

		page_header($user->lang['RS_USER_RATING']);

		$template->assign_vars(array(
			'ERROR'						=> ($error) ? $error : '',

			'RS_POWER_POINTS_LEFT'		=> $config['rs_power_renewal'] ? sprintf($user->lang['RS_VOTE_POWER_LEFT_OF_MAX'], $voting_power_left, $max_voting_power, $max_voting_allowed) : '',
			'RS_POWER_PROGRESS_EMPTY'	=> $config['rs_power_renewal'] ? round((($max_voting_power - $voting_power_left) / $max_voting_power) * 100,0) : '',

			'USER_RATING_CONFIRM'		=> sprintf($user->lang['RS_USER_RATING_CONFIRM'], $user_row['username']),
			'COMMENT'					=> $comment,
			'RS_COMMENT_TOO_LONG'		=> sprintf($user->lang['RS_COMMENT_TOO_LONG'], $config['rs_comment_max_chars']), 

			'S_RS_COMMENT_ENABLE'		=> $config['rs_enable_comment'] ? true : false,
			'S_RS_COMMENT_REQ'			=> ($config['rs_force_comment'] == RS_COMMENT_BOTH || $config['rs_force_comment'] == RS_COMMENT_USER) ? true : false,
			'S_RS_COMMENT_TOO_LONG'		=> $config['rs_comment_max_chars'] ? $config['rs_comment_max_chars'] : false,
			'S_RS_PM_NOTIFY' 			=> $config['rs_pm_notify'] ? true : false,

			'S_CONFIRM_ACTION'		=> $u_action,
			'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
			'AJAX'					=> $ajax ? true : false,)
		);

		$template->set_filenames(array(
			'body' => 'reputation/rateuser.html')
		);

		page_footer();

	break;

	case 'delete':

		$popup = request_var('popup', 0);

		if (empty($id))
		{
			$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
			$message = $user->lang['RS_NO_ID'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		$sql = 'SELECT rep_from, rep_to
			FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_id = $id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$redirect = ($popup) ? append_sid("{$phpbb_root_path}reputation.$phpEx?mode=details&amp;popup=1", "u={$row['rep_to']}") : append_sid("{$phpbb_root_path}reputation.$phpEx?mode=details", "u={$row['rep_to']}");

		if (isset($_POST['cancel']))
		{
			redirect($redirect);
		}

		if ($auth->acl_gets('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_gets('u_rs_delete')))
		{
			$s_hidden_fields = build_hidden_fields(array(
				'id'	=> $id,
				'u'		=> $row['rep_to'],
				'mode'	=> 'delete')
			);

			if (confirm_box(true))
			{
				if ($reputation->delete($id))
				{
					$message = $user->lang['RS_POINT_DELETED'] . '<br /><br />' . sprintf($user->lang['RS_RETURN_DETAILS'], '<a href="' . $redirect . '">', '</a>');

					meta_refresh(3, $redirect);
					trigger_error($message);
				}
			}
			else
			{
				confirm_box(false, $user->lang['RS_DELETE_POINT_CONFIRM'], $s_hidden_fields);
			}
		}

		$message = $user->lang['RS_USER_CANNOT_DELETE'] . '<br /><br />' . sprintf($user->lang['RS_RETURN_POSTDETAILS'], '<a href="' . $redirect . '">', '</a>');
		meta_refresh(3, $redirect);
		trigger_error($message);

	break;

	default:

		$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
		$message = $user->lang['NO_MODE'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
		meta_refresh(3, $meta_info);
		trigger_error($message);

	break;
}

?>