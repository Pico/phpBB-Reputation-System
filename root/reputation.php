<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2013
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

// Start session management, do not update session page.
$user->session_begin(false);
$auth->acl($user->data);
$user->setup(array('mods/reputation_system', 'memberlist'));

$mode = request_var('mode', '');
$id = intval(request_var('id', ''));
$uid = intval(request_var('u', ''));
$post_id = intval(request_var('p', ''));
$start = request_var('start', 0);
$rpmode = request_var('rpmode', '');

if (!$config['rs_enable'])
{
	$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
	$message = $user->lang['RS_DISABLED'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
	meta_refresh(3, $meta_info);
	trigger_error($message);
}

switch ($mode)
{
	case 'ratepost':
		//Let get some data
		$sql_array = array(
			'SELECT'	=> 'u.user_type, u.username, u.user_colour, p.forum_id, p.poster_id, p.post_username , f.enable_reputation',
			'FROM'		=> array(
				POSTS_TABLE => 'p',
				USERS_TABLE => 'u'
			),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(FORUMS_TABLE => 'f'),
					'ON'	=> 'p.forum_id = f.forum_id',
				),
			),
			'WHERE'		=> 'p.post_id = ' . $post_id . '
				AND p.poster_id = u.user_id',
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		//We couldn't find this post. May be it was deleted while user voted?
		if (!$row)
		{
			echo json_encode(array('error_msg' => $user->lang['RS_NO_POST']));
			return;
		}

		//Fire error if it's disabled and exit
		if (!$config['rs_post_rating'] || !$config['rs_negative_point'] && $rpmode == 'negative' || !$row['enable_reputation'])
		{
			echo json_encode(array('error_msg' => $user->lang['RS_DISABLED']));
			return;
		}

		//No anonymous voting is allowed
		if ($row['user_type'] == USER_IGNORE)
		{
			echo json_encode(array('error_msg' => $user->lang['RS_USER_ANONYMOUS']));
			return;
		}

		//You can not vote for your posts
		if ($row['poster_id'] == $user->data['user_id'])
		{
			echo json_encode(array('error_msg' => $user->lang['RS_SELF']));
			return;
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
			echo json_encode(array('error_msg' => sprintf($user->lang['RS_SAME_POST'], $check_user['point'])));
			return;
		}

		//Check if user is allowed to vote
		if (!$auth->acl_get('f_rs_give', $row['forum_id']) || !$auth->acl_get('f_rs_give_negative', $row['forum_id']) && $rpmode == 'negative' || !$auth->acl_get('u_rs_ratepost'))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
			return;
		}

		//Check if user reputation is enought to give negative points
		if ($config['rs_min_rep_negative'] && ($user->data['user_reputation'] < $config['rs_min_rep_negative']) && $rpmode == 'negative')
		{
			echo json_encode(array('error_msg' => sprintf($user->lang['RS_USER_NEGATIVE'], $config['rs_min_rep_negative'])));
			return;
		}

		// Anti-abuse behaviour
		if (!empty($config['rs_anti_time']) && !empty($config['rs_anti_post']))
		{
			$anti_time = time() - $config['rs_anti_time'] * 3600;
			$sql_and = (!$config['rs_anti_method']) ? 'AND rep_to = ' . $row['poster_id'] : '';
			$sql = 'SELECT COUNT(rep_id) AS rep_per_day
				FROM ' . REPUTATIONS_TABLE . '
				WHERE rep_from = ' . $user->data['user_id'] . '
					' . $sql_and . '
					AND post_id != 0
					AND time > ' . $anti_time;
			$result = $db->sql_query($sql);
			$anti_row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if ($anti_row['rep_per_day'] >= $config['rs_anti_post'])
			{
				echo json_encode(array('error_msg' => $user->lang['RS_ANTISPAM_INFO']));
				return;
			}
		}

		// Disallow rating banned users
		if ($user->check_ban($row['poster_id'], false, false, true))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_USER_BANNED']));
			return;
		}

		$notify = request_var('notify_user', '');
		$comment = utf8_normalize_nfc(request_var('comment', '', true));
		$rep_power = request_var('rep_power', '');

		// Submit vote
		$submit = false;
		if (isset($_POST['rate']) && $_POST['rate'] == 1)
		{
			$submit = true;
		}

		if (!$config['rs_enable_comment'] && !$config['rs_enable_power'])
		{
			$submit = true;
			$rep_power = ($rpmode == 'negative') ? -1 : 1;
		}

		// Get reputation power
		if ($config['rs_enable_power'])
		{
			$voting_power_pulldown = '';

			//Get details on user voting: how much power he spent, how many bandays he had
			$user_reputation_stats = $reputation->get_reputation_stats($user->data['user_id']);

			//Calculate how much maximum power a user has
			$max_voting_power = $reputation->get_rep_power($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings'], $user_reputation_stats['bancounts']);

			if ($max_voting_power < 1)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_NO_POWER']));
				return;
			}

			$voting_power_left = $max_voting_power - $user_reputation_stats['renewal_time'];

			//Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			//If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $config['rs_power_renewal'])
			{
				$error_text = sprintf($user->lang['RS_NO_POWER_LEFT'], $max_voting_power);

				echo json_encode(array('error_msg' => $error_text));
				return;
			}

			$template->assign_vars(array(
				'RS_POWER_POINTS_LEFT'		=> $config['rs_power_renewal'] ? sprintf($user->lang['RS_VOTE_POWER_LEFT_OF_MAX'], $voting_power_left, $max_voting_power, $max_voting_allowed) : '',
				'RS_POWER_PROGRESS_EMPTY'	=> ($config['rs_power_renewal'] && $max_voting_power) ? round((($max_voting_power - $voting_power_left) / $max_voting_power) * 100, 0) : '',
			));

			//Preparing HTML for voting by manual spending of user power
			for($i = 1; $i <= $max_voting_allowed; ++$i)
			{
				if ($rpmode == 'negative')
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
		else
		{
			$rep_power = ($rpmode == 'negative') ? -1 : 1;
		}

		if ($submit)
		{
			//Prevent cheater to break the forum permissions to give negative points or give more points than they can 
			if (!$auth->acl_get('f_rs_give_negative', $row['forum_id']) && $rep_power < 0 || $rep_power < 0 && $config['rs_min_rep_negative'] && ($user->data['user_reputation'] < $config['rs_min_rep_negative']) || $config['rs_enable_power'] && (($rep_power > $max_voting_allowed) || ($rep_power < -$max_voting_allowed)))
			{
				echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
				return;
			}

			//Prevent overrating one user by another
			if ($reputation->prevent_rating($row['poster_id']))
			{
				echo json_encode(array('error_msg' => $user->lang['RS_ANTISPAM_INFO']));
				return;
			}

			$post_rating_mode = ($row['enable_reputation'] == 1) ? 'post' : 'onlypost';
			if ($reputation->give_point($row['poster_id'], $post_id, $comment, $notify, $rep_power, $post_rating_mode))
			{
				// If it's an AJAX request, generate JSON reply
				$post_reputation = $reputation->get_post_reputation($post_id);
				$user_reputation = $reputation->get_user_reputation($row['poster_id']);
				$reputation_rank = $config['rs_ranks'] ? $reputation->get_rs_new_rank($user_reputation) : '';
				$json_data = array(
					'post_id'				=> $post_id,
					'poster_id'				=> $row['poster_id'],
					'post_reputation'		=> $post_reputation,
					'user_reputation'		=> '<strong>' . $user_reputation . '</strong>',
					'reputation_rank'		=> $reputation_rank,
					'reputation_class'		=> $reputation->get_vote_class($post_reputation),
					'reputation_vote'		=> ($rep_power > 0) ? 'rated_good' : 'rated_bad',
					'highlight'				=> (!empty($config['rs_post_highlight']) && ($post_reputation >= $config['rs_post_highlight'])) ? true : false,
					'hidden'				=> (!empty($config['rs_hide_post']) && ($post_reputation > $config['rs_hide_post'])) ? true : false,
					'hidepost'				=> (!empty($config['rs_hide_post']) && ($post_reputation <= $config['rs_hide_post'])) ? true : false,
					'hidemessage'			=> '<div id="hideshow">' . sprintf($user->lang['RS_HIDE_POST'], get_username_string('full', $row['poster_id'], $row['username'], $row['user_colour'], $row['post_username']), '<a href="#" onclick="jRS.showhide(this); return false;">' . $user->lang['RS_SHOW_HIDE_HIDDEN_POST'] . '</a>') . '</div>',
				);

				echo json_encode($json_data);
				return;
				//Returned JSON data and stop the script.
			}
		}

		$s_hidden_fields = build_hidden_fields(array(
			'rpmode'	=> $rpmode,
		));

		$template->assign_vars(array(
			'POST_ID'					=> $post_id,

			'RS_COMMENT_TOO_LONG'		=> sprintf($user->lang['RS_COMMENT_TOO_LONG'], $config['rs_comment_max_chars']), 

			'S_RS_COMMENT_ENABLE'		=> $config['rs_enable_comment'] ? true : false,
			'S_RS_COMMENT_REQ'			=> ($config['rs_force_comment'] == RS_COMMENT_BOTH || $config['rs_force_comment'] == RS_COMMENT_POST) ? true : false,
			'S_RS_COMMENT_TOO_LONG'		=> $config['rs_comment_max_chars'] ? $config['rs_comment_max_chars'] : false,
			'S_RS_PM_NOTIFY' 			=> $config['rs_pm_notify'] ? true : false,
			'S_RS_POWER_ENABLE' 		=> $config['rs_enable_power'] ? true : false,
			'S_HIDDEN_FIELDS'			=> $s_hidden_fields,
		));

		$template->set_filenames(array(
			'body' => 'reputation/ratepost.html')
		);

		$template->display('body');

	break;

	case 'rateuser':

		if (!$config['rs_user_rating'] || !$auth->acl_get('u_rs_give'))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_DISABLED']));
			return;
		}

		$notify = request_var('notify_user', '');
		$comment = utf8_normalize_nfc(request_var('comment', '', true));
		$rep_power = request_var('rep_power', '');

		$mode = 'user';

		$sql = 'SELECT user_id, user_type
			FROM ' . USERS_TABLE . "
			WHERE user_id = $uid";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!$row)
		{
			echo json_encode(array('error_msg' => $user->lang['RS_NO_USER_ID']));
			return;
		}

		if ($row['user_type'] == USER_IGNORE)
		{
			echo json_encode(array('error_msg' => $user->lang['RS_USER_ANONYMOUS']));
			return;
		}

		if ($row['user_id'] == $user->data['user_id'])
		{
			echo json_encode(array('error_msg' => $user->lang['RS_SELF']));
			return;
		}

		// Disallow rating banned users
		if ($user->check_ban($uid, false, false, true))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_USER_BANNED']));
			return;
		}

		$sql = 'SELECT rep_id, time
			FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_to = {$row['user_id']}
				AND rep_from = {$user->data['user_id']}
				AND action = 2
			ORDER by rep_id DESC";
		$result = $db->sql_query($sql);
		$check_user = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($check_user && !$config['rs_user_rating_gap'])
		{
			echo json_encode(array('error_msg' => $user->lang['RS_SAME_USER']));
			return;
		}

		if ($config['rs_user_rating_gap'] && (time() < $check_user['time'] + $config['rs_user_rating_gap'] * 86400))
		{
			//Informe user how long he has to wait to rate user
			$next_vote_time = ($check_user['time'] + $config['rs_user_rating_gap'] * 86400) - time();
			$next_vote_in = '';
			$next_vote_in .= intval($next_vote_time / 86400) ? intval($next_vote_time / 86400) . ' ' . $user->lang['DAYS'] . ' ' : '';
			$next_vote_in .= intval(($next_vote_time / 3600) % 24)  ? intval(($next_vote_time / 3600) % 24) . ' ' . $user->lang['HOURS'] . ' ' : '';
			$next_vote_in .= intval(($next_vote_time / 60) % 60) ? intval(($next_vote_time / 60) % 60) . ' ' . $user->lang['MINUTES'] : '';
			$next_vote_in .= (intval($next_vote_time) < 60) ? intval($next_vote_time) . ' ' . $user->lang['SECONDS'] : '';

			echo json_encode(array('error_msg' => sprintf($user->lang['RS_USER_GAP'], $next_vote_in)));
			return;
		}

		// Submit vote
		$submit = false;
		if (isset($_POST['rate']) && $_POST['rate'] == 1)
		{
			$submit = true;
		}

		// Get reputation power
		if ($config['rs_enable_power'])
		{
			$voting_power_pulldown = '';

			//Get details on user voting: how much power he spent, how many bandays he had
			$user_reputation_stats = $reputation->get_reputation_stats($user->data['user_id']);

			//Calculate how much maximum power a user has
			$max_voting_power = $reputation->get_rep_power($user->data['user_posts'], $user->data['user_regdate'], $user->data['user_reputation'], $user->data['group_id'], $user->data['user_warnings'], $user_reputation_stats['bancounts']);

			if ($max_voting_power < 1)
			{
				echo json_encode(array('error_msg' => $user->lang['RS_NO_POWER']));
				return;
			}

			$voting_power_left = $max_voting_power - $user_reputation_stats['renewal_time'];

			//Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			//If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $config['rs_power_renewal'])
			{
				$error_text = sprintf($user->lang['RS_NO_POWER_LEFT'], $max_voting_power);

				echo json_encode(array('error_msg' => $error_text));
				return;
			}

			$template->assign_vars(array(
				'RS_POWER_POINTS_LEFT'		=> $config['rs_power_renewal'] ? sprintf($user->lang['RS_VOTE_POWER_LEFT_OF_MAX'], $voting_power_left, $max_voting_power, $max_voting_allowed) : '',
				'RS_POWER_PROGRESS_EMPTY'	=> ($config['rs_power_renewal'] && $max_voting_power) ? round((($max_voting_power - $voting_power_left) / $max_voting_power) * 100, 0) : '',
			));
			//Preparing HTML for voting by manual spending of user power
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
				if ($i < 0 && $auth->acl_get('u_rs_give_negative') && $config['rs_negative_point'] && (($config['rs_min_rep_negative'] != 0) ? ($user->data['user_reputation'] >= $config['rs_min_rep_negative']) : true))
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
			if ($auth->acl_get('u_rs_give_negative') && $config['rs_negative_point'] && (($config['rs_min_rep_negative'] != 0) ? ($user->data['user_reputation'] >= $config['rs_min_rep_negative']) : true))
			{
				$rs_power .= '<option value="-1">' . $user->lang['RS_NEGATIVE'] . '</option>';
			}
			else if ($config['rs_enable_comment'])
			{
				$rep_power = 1;
			}
			else
			{
				$submit = true;
				$rep_power = 1;
			}

			$template->assign_block_vars('reputation', array(
				'REPUTATION_POWER'	=> $rs_power)
			);
		}

		if ($submit)
		{
			//Prevent cheater to break the forum permissions to give negative points or give more points than they can 
			if (!$auth->acl_get('u_rs_give_negative') && $rep_power < 0 || $rep_power < 0 && $config['rs_min_rep_negative'] && ($user->data['user_reputation'] < $config['rs_min_rep_negative']) || $config['rs_enable_power'] && (($rep_power > $max_voting_allowed) || ($rep_power < -$max_voting_allowed)))
			{
				echo json_encode(array('error_msg' => $user->lang['RS_USER_DISABLED']));
				return;
			}

			if ($reputation->prevent_rating($row['user_id']))
			{
				echo json_encode(array('error_msg' => $user->lang['RS_SAME_USER']));
				return;
			}

			if ($reputation->give_point($row['user_id'], $post_id, $comment, $notify, $rep_power, $mode))
			{
				// If it's an AJAX request, generate JSON reply
				$user_reputation = $reputation->get_user_reputation($row['user_id']);
				$json_data = array(
					'user_reputation'		=> '<strong>' . $user_reputation . '</strong>',
				);
				echo json_encode($json_data);
				return;
				//Returned JSON data and stop the script.
			}
		}

		$template->assign_vars(array(
			'USER_ID'					=> $row['user_id'],

			'RS_COMMENT_TOO_LONG'		=> sprintf($user->lang['RS_COMMENT_TOO_LONG'], $config['rs_comment_max_chars']), 

			'S_RS_COMMENT_ENABLE'		=> $config['rs_enable_comment'] ? true : false,
			'S_RS_COMMENT_REQ'			=> ($config['rs_force_comment'] == RS_COMMENT_BOTH || $config['rs_force_comment'] == RS_COMMENT_USER) ? true : false,
			'S_RS_COMMENT_TOO_LONG'		=> $config['rs_comment_max_chars'] ? $config['rs_comment_max_chars'] : false,
			'S_RS_PM_NOTIFY' 			=> $config['rs_pm_notify'] ? true : false,
		));

		$template->set_filenames(array(
			'body' => 'reputation/rateuser.html')
		);

		$template->display('body');

	break;

	case 'postdetails':

		if (!$auth->acl_get('u_rs_view'))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_VIEW_DISALLOWED']));
			return;
		}

		if (empty($post_id))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_NO_POST_ID']));
			return;
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
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$post_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		//We couldn't find this post. May be it was deleted while user voted?
		if (!$post_row)
		{
			echo json_encode(array('error_msg' => $user->lang['RS_NO_POST']));
			return;
		}

		if (!function_exists('get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		}

		$sort_key = request_var('sk', 'd');
		$sort_dir = request_var('sd', 'd');

		$sort_key_sql = array(
			'a'	=> 'u.username_clean',
			'b'	=> 'r.time',
			'c'	=> 'r.point',
			'd'	=> 'r.rep_id'
		);

		if (!isset($sort_key_sql[$sort_key]))
		{
			$sort_key = $default_key;
		}

		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		$sql_array = array(
			'SELECT'	=> 'u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_reputation, r.*',
			'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_from = u.user_id',
				),
			),
			'WHERE'		=> 'r.post_id = ' . $post_id,
			'ORDER_BY'	=> $order_by
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$userid_to = $row['rep_to'];
			$row['bbcode_options'] = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

			$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : false;
			$time = $user->format_date($row['time']);
			$user_from = get_username_string('full', $row['rep_from'], $row['username'], $row['user_colour']);
			$avatar_img = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 40 : (40 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 40 : (40 / $row['user_avatar_width']) * $row['user_avatar_height']) : '<img src="./' . $phpbb_root_path . 'styles/' . rawurlencode($user->theme['theme_path']) . '/theme/images/no_avatar.gif" width="40px;" height="40px;" alt="" />';

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
			if($auth->acl_get('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete')))
			{
				$delete_link = true;
			}

			$template->assign_block_vars('reputation', array(
				'REP_ID'			=> $row['rep_id'],
				'USERNAME'			=> $user_from,
				'AVATAR_IMG'		=> $avatar_img,
				'TIME'				=> $time,
				'COMMENT'			=> $comment,
				'DELETE'			=> $delete_link,
				'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
				'POINT_CLASS'		=> $config['rs_point_type'] ? '' : $point_class,
			));
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'POST_ID'			=> $post_id,
			'POST_SUBJECT'		=> $post_row['post_subject'],
			'POST_AUTHOR'		=> get_username_string('full', $post_row['poster_id'], $post_row['username'], $post_row['user_colour']),

			'V_SORT_DIR'		=> ($sort_dir == 'd') ? 'a' : 'd',

			'S_RS_AVATAR'		=> $config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $config['rs_enable_comment'] ? true : false,
			'S_TRUNCATE'		=> $auth->acl_gets('m_rs_moderate') ? true : false,
		));

		$template->set_filenames(array(
			'body' => 'reputation/postdetails.html')
		);

		$template->display('body');

	break;

	case 'userdetails':

		if (!$auth->acl_get('u_rs_view'))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_VIEW_DISALLOWED']));
			return;
		}

		if (empty($uid))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_NO_USER_ID']));
			return;
		}

		$sql = 'SELECT user_id, username, user_colour
			FROM ' . USERS_TABLE . "
			WHERE user_type <> 2
				AND user_id = $uid";
		$result = $db->sql_query($sql);
		$user_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (empty($uid) || empty($user_row))
		{
			echo json_encode(array('error_msg' => $user->lang['RS_NO_USER_ID']));
			return;
		}

		if (!function_exists('get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		}

		$sort_key = request_var('sk', 'f');
		$sort_dir = request_var('sd', 'd');

		$sort_key_sql = array(
			'a'	=> 'u.username_clean',
			'b'	=> 'r.time',
			'c'	=> 'r.point',
			'd'	=> 'r.action',
			'e'	=> 'r.post_id',
			'f'	=> 'r.rep_id'
		);

		if (!isset($sort_key_sql[$sort_key]))
		{
			$sort_key = $default_key;
		}

		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		$sql_array = array(
			'SELECT'	=> 'u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_reputation, r.*, p.post_id AS real_post_id, p.forum_id, p.post_subject',
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
			'ORDER_BY'	=> $order_by
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$row['bbcode_options'] = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

			$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : false;
			$time = $user->format_date($row['time']);
			$user_from = get_username_string('full', $row['rep_from'], $row['username'], $row['user_colour']);
			$avatar_img = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 40 : (40 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 40 : (40 / $row['user_avatar_width']) * $row['user_avatar_height']) : '<img src="./' . $phpbb_root_path . 'styles/' . rawurlencode($user->theme['theme_path']) . '/theme/images/no_avatar.gif" width="40px;" height="40px;" alt="" />';

			$post_subject = (empty($row['real_post_id'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'] . ' [#p' . $row['post_id'] . ']';
			$post_link = (!empty($row['real_post_id'])) ? ($auth->acl_get('f_read', $row['forum_id']) ? '- <a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : '') : '- ' . $post_subject;

			$go_to_post = '';
			if ($row['action'] == 1)
			{
				$action = $user->lang['RS_POST_RATING'];
				$go_to_post = $post_link;
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
				$action = $user->lang['RS_ONLYPOST_RATING'];
				$go_to_post = $post_link;
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
			if($auth->acl_get('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete')))
			{
				$delete_link = true;
			}

			$template->assign_block_vars('reputation', array(
				'REP_ID'			=> $row['rep_id'],
				'USERNAME'			=> $user_from,
				'ACTION'			=> $action,
				'GO_TO_POST'		=> $go_to_post,
				'AVATAR_IMG'		=> $avatar_img,
				'TIME'				=> $time,
				'COMMENT'			=> $comment,
				'DELETE'			=> $delete_link,
				'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
				'POINT_CLASS'		=> $config['rs_point_type'] ? '' : $point_class,
			));
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'USER_ID'			=> $uid,

			'U_RS_USER_DETAILS'		=> append_sid("{$phpbb_root_path}reputation.$phpEx", "mode=details&amp;u=$uid"),

			'L_RS_USER_REPUTATION'	=> sprintf($user->lang['RS_USER_REPUTATION'], get_username_string('username', $user_row['user_id'], $user_row['username'], $user_row['user_colour'])),

			'V_SORT_DIR'		=> ($sort_dir == 'd') ? 'a' : 'd',

			'S_RS_AVATAR'		=> $config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $config['rs_enable_comment'] ? true : false,
			'S_TRUNCATE'		=> $auth->acl_gets('m_rs_moderate') ? true : false,
		));

		$template->set_filenames(array(
			'body' => 'reputation/userdetails.html')
		);

		$template->display('body');

	break;

	case 'details':

		if (!$auth->acl_get('u_rs_view'))
		{
			$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
			$message = $user->lang['RS_VIEW_DISALLOWED'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		$sql = 'SELECT *
			FROM ' . USERS_TABLE . "
			WHERE user_type <> 2
				AND user_id = $uid";
		$result = $db->sql_query($sql);
		$user_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (empty($uid) || empty($user_row))
		{
			$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
			$message = $user->lang['RS_NO_USER_ID'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
			meta_refresh(3, $meta_info);
			trigger_error($message);
		}

		if (!function_exists('get_user_avatar'))
		{
			include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		}

		$default_key = 'f';

		$check_params = array(
			'u'		=> array('u', $uid),
			'sk'	=> array('sk', $default_key),
			'sd'	=> array('sd', 'd')
		);

		$sort_key = request_var('sk', $default_key);
		$sort_dir = request_var('sd', 'd');

		$params[] = "mode=details";
		$sort_params[] = "mode=details";

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

		$sort_key_sql = array(
			'a'	=> 'u.username_clean',
			'b'	=> 'r.time',
			'c'	=> 'r.point',
			'd'	=> 'r.action',
			'e'	=> 'r.post_id',
			'f'	=> 'r.rep_id'
		);

		$pagination_url = append_sid("{$phpbb_root_path}reputation.$phpEx", implode('&amp;', $params));
		$sort_url = append_sid("{$phpbb_root_path}reputation.$phpEx", implode('&amp;', $sort_params));

		if (!isset($sort_key_sql[$sort_key]))
		{
			$sort_key = $default_key;
		}

		$order_by = $sort_key_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		$sql = 'SELECT COUNT(rep_id) AS total_reps
			FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_to = $uid";
		$result = $db->sql_query($sql);
		$total_reps = (int) $db->sql_fetchfield('total_reps');
		$db->sql_freeresult($result);

		$sql_array = array(
			'SELECT'	=> 'u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.user_reputation, r.*, p.post_id AS real_post_id, p.forum_id, p.post_subject',
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
		$result = $db->sql_query_limit($sql, $config['rs_per_page'], $start);

		while ($row = $db->sql_fetchrow($result))
		{
			$row['bbcode_options'] = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

			$comment = (!empty($row['comment'])) ? generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) : false;
			$time = $user->format_date($row['time']);
			$user_from = get_username_string('full', $row['rep_from'], $row['username'], $row['user_colour']);
			$avatar_img = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 60 : (60 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 60 : (60 / $row['user_avatar_width']) * $row['user_avatar_height']) : '<img src="./' . $phpbb_root_path . 'styles/' . rawurlencode($user->theme['theme_path']) . '/theme/images/no_avatar.gif" width="60px;" height="60px;" alt="" />';

			$post_subject = (empty($row['real_post_id'])) ? '<strong>' . $user->lang['RS_POST_DELETE'] . '</strong>' : $row['post_subject'] . ' [#p' . $row['post_id'] . ']';
			$post_link = (!empty($row['real_post_id'])) ? ($auth->acl_get('f_read', $row['forum_id']) ? '- <a href="viewtopic.' . $phpEx . '?p=' . $row['post_id'] . '#p' . $row['post_id'] . '">' . $post_subject . '</a>' : '') : '- ' . $post_subject;

			$go_to_post = '';
			if ($row['action'] == 1)
			{
				$action = $user->lang['RS_POST_RATING'];
				$go_to_post = $post_link;
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
				$action = $user->lang['RS_ONLYPOST_RATING'];
				$go_to_post = $post_link;
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
			if($auth->acl_get('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete')))
			{
				$delete_link = true;
			}

			$template->assign_block_vars('reputation', array(
				'REP_ID'			=> $row['rep_id'],
				'USERNAME'			=> $user_from,
				'ACTION'			=> $action,
				'GO_TO_POST'		=> $go_to_post,
				'AVATAR_IMG'		=> $avatar_img,
				'TIME'				=> $time,
				'COMMENT' 			=> $comment,
				'DELETE' 			=> $delete_link,
				'POINT_VALUE'		=> $config['rs_point_type'] ? $point_img : $row['point'],
				'POINT_CLASS'		=> $config['rs_point_type'] ? '' : $point_class,
			));
		}
		$db->sql_freeresult($result);

		$rank_title = $rank_img = $rank_img_src = $rs_rank_title = $rs_rank_img = $rs_rank_img_src = $rs_rank_color = '';
		get_user_rank($user_row['user_rank'], $user_row['user_posts'], $rank_title, $rank_img, $rank_img_src);
		if ($config['rs_ranks'])
		{
			$reputation->get_rs_rank($user_row['user_reputation'], $rs_rank_title, $rs_rank_img, $rs_rank_img_src, $rs_rank_color);
		}

		$avatar_img = get_user_avatar($user_row['user_avatar'], $user_row['user_avatar_type'], $user_row['user_avatar_width'], $user_row['user_avatar_height']);

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
			FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_to = $uid";
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

		if ($config['rs_enable_power'])
		{
			$user_reputation_stats = $reputation->get_reputation_stats($user_row['user_id']);
			$user_max_voting_power = $reputation->get_rep_power($user_row['user_posts'], $user_row['user_regdate'], $user_row['user_reputation'], $user_row['group_id'], $user_row['user_warnings'], $user_reputation_stats['bancounts']);
			$user_power_explain = $reputation->explain_power();
			$voting_power_left = '';
			if ($config['rs_power_renewal'])
			{
				$voting_power_left = $user_max_voting_power - $user_reputation_stats['renewal_time'];
				if ($voting_power_left <= 0)
				{
					$voting_power_left = 0;
				}
			}

			$group_power = $reputation->get_group_power();

			$template->assign_vars(array(
				'S_RS_POWER_EXPLAIN'		=> $config['rs_power_explain'] ? true : false,
				'RS_POWER'					=> $user_max_voting_power,
				'RS_POWER_LEFT'				=> $config['rs_power_renewal'] ? sprintf($user->lang['RS_VOTE_POWER_LEFT'], $voting_power_left, $user_max_voting_power) : '',
				'RS_CFG_TOTAL_POSTS'		=> $config['rs_total_posts'] ? true : false,
				'RS_CFG_MEMBERSHIP_DAYS'	=> $config['rs_membership_days'] ? true : false,
				'RS_CFG_REP_POINT'			=> $config['rs_power_rep_point'] ? true : false,
				'RS_CFG_LOOSE_WARN'			=> $config['rs_power_lose_warn'] ? true : false,
				'RS_CFG_LOOSE_BAN'			=> $config['rs_power_lose_ban'] ? true : false,
				'RS_GROUP_POWER'			=> $group_power ? true : false,
			));

			$template->assign_vars($user_power_explain);
		}

		page_header($user->lang['RS_DETAILS']);

		$template->assign_vars(array(
			'USER_ID'			=> $user_row['user_id'],
			'USERNAME'			=> get_username_string('username', $user_row['user_id'], $user_row['username'], $user_row['user_colour']),
			'USERNAME_FULL'		=> get_username_string('full', $user_row['user_id'], $user_row['username'], $user_row['user_colour']),
			'REPUTATIONS'		=> ($user_row['user_reputation']),
			'AVATAR_IMG'		=> $avatar_img,
			'RANK_TITLE'		=> $rank_title,
			'RANK_IMG'			=> $rank_img,
			'RS_RANK_TITLE'		=> $rs_rank_title,
			'RS_RANK_IMG'		=> $rs_rank_img,
			'REPUTATION_BOX'	=> $config['rs_ranks'] ? $rs_rank_color : (($user_row['user_reputation'] == 0) ? 'zero' : (($user_row['user_reputation'] > 0) ? 'positive' : 'negative')),

			'PAGINATION'		=> generate_pagination($pagination_url, $total_reps, $config['rs_per_page'], $start),
			'PAGE_NUMBER'		=> on_page($total_reps, $config['rs_per_page'], $start),
			'TOTAL'				=> $total_reps,
			'TOTAL_REPS'		=> ($total_reps == 1) ? $user->lang['LIST_REPUTATION'] : sprintf($user->lang['LIST_REPUTATIONS'], $total_reps),

			'U_SORT_USERNAME'	=> $sort_url . '&amp;sk=a&amp;sd=' . (($sort_key == 'a' && $sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_TIME'		=> $sort_url . '&amp;sk=b&amp;sd=' . (($sort_key == 'b' && $sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_POINTS'		=> $sort_url . '&amp;sk=c&amp;sd=' . (($sort_key == 'c' && $sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_ACTION'		=> $sort_url . '&amp;sk=d&amp;sd=' . (($sort_key == 'd' && $sort_dir == 'a') ? 'd' : 'a'),
			'U_SORT_POSTS'		=> $sort_url . '&amp;sk=e&amp;sd=' . (($sort_key == 'e' && $sort_dir == 'a') ? 'd' : 'a'),

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
			'S_RS_AVATAR'		=> $config['rs_display_avatar'] ? true : false,
			'S_RS_COMMENT'		=> $config['rs_enable_comment'] ? true : false,
			'S_RS_NEGATIVE'		=> $config['rs_negative_point'] ? true : false,
			'S_RS_POWER_ENABLE'	=> $config['rs_enable_power'] ? true : false,
			'S_TRUNCATE'		=> $auth->acl_gets('m_rs_moderate') ? true : false,
		 ));

		$template->set_filenames(array(
			'body' => 'reputation/details.html')
		);

		page_footer();

	break;

	case 'delete':

		$del_mode = request_var('dm', '');

		$sql_array = array(
			'SELECT'	=> 'r.rep_from, r.rep_to, r.post_id, u.username, u.user_colour, p.post_username',
			'FROM'		=> array(
				REPUTATIONS_TABLE => 'r',
				USERS_TABLE => 'u'
			),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'r.post_id = p.post_id',
				),
			),
			'WHERE'		=> 'r.rep_id = ' . $id . '
				AND r.rep_to = u.user_id',
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($auth->acl_gets('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete')))
		{
			$reputation->delete($id);

			$user_reputation = $reputation->get_user_reputation($row['rep_to']);
			$reputation_rank = $config['rs_ranks'] ? $reputation->get_rs_new_rank($user_reputation) : '';
			$json_data = array();

			if ($del_mode == 'post')
			{
				$post_reputation = $reputation->get_post_reputation($row['post_id']);
				$json_data = array(
					'post_id'				=> $row['post_id'],
					'poster_id'				=> $row['rep_to'],
					'rep_id'				=> $id,
					'user_reputation'		=> '<strong>' . $user_reputation . '</strong>',
					'reputation_rank'		=> $reputation_rank,
					'post_reputation'		=> $post_reputation,
					'reputation_class'		=> $reputation->get_vote_class($post_reputation),
					'highlight'				=> (!empty($config['rs_post_highlight']) && ($post_reputation < $config['rs_post_highlight'])) ? true : false,
					'hidden'				=> (!empty($config['rs_hide_post']) && ($post_reputation > $config['rs_hide_post'])) ? true : false,
					'hidepost'				=> (!empty($config['rs_hide_post']) && ($post_reputation <= $config['rs_hide_post'])) ? true : false,
					'hidemessage'			=> '<div id="hideshow">' . sprintf($user->lang['RS_HIDE_POST'], get_username_string('full', $row['rep_to'], $row['username'], $row['user_colour'], $row['post_username']), '<a href="#" onclick="jRS.showhide(this); return false;">' . $user->lang['RS_SHOW_HIDE_HIDDEN_POST'] . '</a>') . '</div>',
				);
			}
			else if ($del_mode == 'user')
			{
				$reputation_title = $config['rs_ranks'] ? $reputation->get_rs_new_rank($user_reputation, true) : '';
				$reputation_color = $config['rs_ranks'] ? $reputation->get_rs_new_rank($user_reputation, false, true) : $reputation->get_vote_class($user_reputation);
				$json_data = array(
					'rep_id'				=> $id,
					'user_reputation'		=> '<strong>' . $user_reputation . '</strong>',
					'reputation_rank'		=> $reputation_rank,
					'reputation_class'		=> $reputation_color,
					'rank_title'			=> $reputation_title,
					'empty'					=> '<div class="reputation-list empty bg3"><span>' . $user->lang['RS_EMPTY_DATA'] . '</span></div>',
				);
			}

			echo json_encode($json_data);
			return;
		}
		else
		{
			echo json_encode(array('error_msg' => $user->lang['RS_USER_CANNOT_DELETE']));
			return;
		}

	break;

	case 'clear':

		$clear_mode = request_var('cm', '');
		$clear_page = request_var('cp', '');

		if ($auth->acl_gets('m_rs_moderate'))
		{
			if ($clear_mode == 'post')
			{
				$sql = 'SELECT rep_to, post_id
					FROM ' . REPUTATIONS_TABLE . "
					WHERE post_id = $post_id";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$reputation->clear_reputation('post', $post_id);

				$user_reputation = $reputation->get_user_reputation($row['rep_to']);
				$reputation_rank = $config['rs_ranks'] ? $reputation->get_rs_new_rank($user_reputation) : '';

				$json_data = array(
					'post_id'				=> $post_id,
					'poster_id'				=> $row['rep_to'],
					'user_reputation'		=> '<strong>' . $user_reputation . '</strong>',
					'reputation_rank'		=> $reputation_rank,
					'post_reputation'		=> 0,
					'reputation_class'		=> 'zero',
				);
			}
			else if ($clear_mode == 'user')
			{
				$post_ids = array();

				$sql = 'SELECT post_id
					FROM ' . REPUTATIONS_TABLE . "
					WHERE rep_to = $uid
					GROUP BY post_id";
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$post_ids[] = $row['post_id'];
				}
				$db->sql_freeresult($result);

				$reputation->clear_reputation('user', $uid, $post_ids);

				$user_reputation = $reputation->get_user_reputation($uid);
				$reputation_rank = $config['rs_ranks'] ? $reputation->get_rs_new_rank($user_reputation) : '';

				$json_data = array(
					'post_ids'				=> $post_ids,
					'poster_id'				=> $uid,
					'user_reputation'		=> '<strong>0</strong>',
					'reputation_rank'		=> $reputation_rank,
					'post_reputation'		=> 0,
					'reputation_class'		=> 'zero',
				);
			}

			echo json_encode($json_data);
			return;
		}
		else
		{
			echo json_encode(array('error_msg' => $user->lang['RS_USER_CANNOT_DELETE']));
			return;
		}

	break;

	case 'catchup':

		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_rep_last = " . time() . "
			WHERE user_id = {$user->data['user_id']}";
		$db->sql_query($sql);

		//Send empty data
		echo json_encode('');
		return;

	break;

	case 'newpopup':

		$template->assign_vars(array(
			'NEW_REPUTATIONS'		=> ($user->data['user_rep_new'] == 1) ? $user->lang['RS_NEW_REP'] : sprintf($user->lang['RS_NEW_REPS'], $user->data['user_rep_new']),
		));

		if ($user->data['user_rep_new'])
		{
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_rep_new = 0
				WHERE user_id = {$user->data['user_id']}";
			$db->sql_query($sql);
		}

		$template->set_filenames(array(
			'body' => 'reputation/newpopup.html')
		);

		$template->display('body');

	break;

	default:

		$meta_info = append_sid("{$phpbb_root_path}index.$phpEx", "");
		$message = $user->lang['NO_MODE'] . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx", "") . '">', '</a>');
		meta_refresh(3, $meta_info);
		trigger_error($message);

	break;
}

?>