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

class reputation
{
	private $power;
	private $group;

	/**Function returns maximum voting power of one user
	* @param int $user_posts
	* @param $user_regdate
	* @param int $user_reputation
	* @param int $user_group_id
	* @param int $number_of_active_warnings
	* @param int $number_of_ban_days_in_1year
	*/
	function get_rep_power($user_posts, $user_regdate, $user_reputation, $user_group_id, $number_of_active_warnings, $number_of_ban_days_in_1year = 0)
	{
		global $config, $db;
		$now = time();
		$user_power = array();

		//Increasing power for number of posts
		if ($config['rs_total_posts'])
		{
			$user_power['FOR_NUMBER_OF_POSTS'] = intval($user_posts / $config['rs_total_posts']);
		}

		//Increasing power for the age of the user
		if ($config['rs_membership_days'])
		{
			$user_power['FOR_USER_AGE'] = intval(intval(($now - $user_regdate) / 86400) / $config['rs_membership_days']);
		}

		//Increasing power for total reputation
		if ($config['rs_power_rep_point'])
		{
			$user_power['FOR_REPUTATION'] = intval($user_reputation / $config['rs_power_rep_point']);
		}

		//Decreasing for warnings
		if ($config['rs_power_lose_warn'] > 0)
		{
			$user_power['FOR_WARNINGS'] = -$number_of_active_warnings * $config['rs_power_lose_warn'];
		}

		//Decreasing for bans
		if ($config['rs_power_lose_ban'] > 0)
		{
			$user_power['FOR_BANS'] = -$number_of_ban_days_in_1year * $config['rs_power_lose_ban'];
		}

		$user_max_power = array_sum($user_power);

		//Starting power
		$user_max_power = $user_max_power + $config['rs_min_power'];

		//Check min power - if it is set, inform about it
		if ($config['rs_min_power'])
		{
			$user_power['MINIMUM_VOTING_POWER'] = $config['rs_min_power'];
		}

		//Checking that user min power is not lower than minimum power set in ACP
		if ($user_max_power < $config['rs_min_power'])
		{
			$user_max_power = max($config['rs_min_power'], $user_max_power);
		}

		//Checking that user max power is not higher than maximum power set in ACP
		if ($user_max_power > $config['rs_max_power'])
		{
			$user_power['MAXIMUM_VOTING_POWER'] = $config['rs_max_power'];
			$user_max_power = min($config['rs_max_power'], $user_max_power);
		}

		//Calculating group power, if necessary
		$group_power = 0;
		if ($user_group_id)
		{
			$sql = 'SELECT group_reputation_power
				FROM ' . GROUPS_TABLE . "
				WHERE group_id = $user_group_id";
			$result = $db->sql_query($sql);
			$group_power = (int)$db->sql_fetchfield('group_reputation_power');
			$db->sql_freeresult($result);

			if ($group_power)
			{
				$user_max_power = $user_power['GROUP_VOTING_POWER'] = $group_power;
				$user_power['MAXIMUM_VOTING_POWER'] = false;
			}
			//Put group power into $this->group
			$this->group = $group_power;
		}

		//Put the structure of the user power into $this->power
		$this->power = $user_power;

		return $user_max_power;
	}

	/* Function return an array explaining structure of the user power
	* @return array|int
	*/
	function explain_power()
	{
		return $this->power;
	}

	/* Function return a group power
	* @return int
	*/
	function get_group_power()
	{
		return $this->group;
	}

	/**Function analyzes voting of a user and returns an array with statistics
	* @param $user_id
	* @return array
	*/
	function get_reputation_stats($user_id)
	{
		global $db, $config;

		//That's what we will calculate
		$statistics = array(
			'bancounts'		=> 0,
			'renewal_time'	=> 0,
		);

		if ($config['rs_power_renewal'])
		{
			//Until what timestamp should we count user votes
			$renewal_timeout = time() - $config['rs_power_renewal'] * 3600;

			//Let's get all voting data on this user.
			$sql = 'SELECT point
				FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_from = $user_id
					AND (action = 1 OR action = 2)
					AND time > $renewal_timeout";
			$result = $db->sql_query($sql);

			//Let's run through the rows and make statistics
			while($renewal = $db->sql_fetchrow($result))
			{
				//How much power a user spent in a specified period of time
				$statistics['renewal_time'] += abs($renewal['point']);
			}
			$db->sql_freeresult($result);
		}

		if ($config['rs_power_lose_ban'] > 0)
		{
			//Until what timestamp should we count user bans
			$bans_timeout = time() - 365 * 24 * 3600;

			$sql = 'SELECT COUNT(rep_id) AS total_bans
				FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_to = $user_id
					AND action = 4
					AND time > $bans_timeout";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			$statistics['bancounts'] = $row['total_bans'];
		}

		return $statistics;
	}

	/**Main function for actual recording of voting points.
	* @param int $to user_id who gets the rating
	* @param int $post_id Option post id
	* @param string $comment
	* @param bool $notify Should we send a private message to the user, who got a new vote
	* @param int $point Actual value set by the voting user
	* @param string $mode
	* @return bool
	*/
	function give_point($to, $post_id = 0, $comment, $notify = false, $point, $mode = 'post')
	{
		global $phpEx, $phpbb_root_path, $config;
		global $db, $user;

		//Firstly, select mode
		if ($mode == 'post')
		{
			$action = 1;
		}
		else if ($mode == 'user')
		{
			$action = 2;
		}
		else if ($mode == 'warning')
		{
			$action = 3;
		}
		else if ($mode == 'ban')
		{
			$action = 4;
		}
		else if ($mode == 'onlypost')
		{
			$action = 5;
		}

		if (!class_exists('parse_message'))
		{
			include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
		}

		$message_parser = new parse_message();

		//Prepare comment for storage
		$allow_bbcode = $allow_urls = $allow_smilies = true;

		$message_parser->message = $comment;
		$message_parser->parse($allow_bbcode, $allow_urls, $allow_smilies, false, false, false, false, true, 'comment');

		//Now we are ready to save the vote itself
		$sql_data = array(
			'rep_from'			=> $user->data['user_id'],
			'rep_to'			=> $to,
			'time'				=> time(),
			'action'			=> $action,
			'post_id'			=> $post_id,
			'point'				=> $point,
			'comment'			=> (string) $message_parser->message,
			'bbcode_uid'		=> (string) $message_parser->bbcode_uid,
			'bbcode_bitfield'	=> $message_parser->bbcode_bitfield,
		);

		//Saving the vote. Used for post rating calculation. Not for user rating calculation
		$db->sql_query('INSERT INTO ' . REPUTATIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_data));

		//Post reputation
		if ($post_id)
		{
			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation + $point
				WHERE post_id = $post_id";
			$db->sql_query($sql);
		}

		//Get some user data
		$sql = 'SELECT user_lang, user_reputation, user_rep_new
			FROM ' . USERS_TABLE . " 
			WHERE user_id = $to";
		$result = $db->sql_query($sql);
		$user_data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		//Nofity user about the point
		$new_points = '';
		if ($config['rs_notification'])
		{
			$rep_last_time = !$user_data['user_rep_new'] ? ', user_rep_last = ' . time() . '' : '';
			$new_points = ', user_rep_new = user_rep_new + 1' . $rep_last_time;
		}

		if ($mode == 'onlypost')
		{
			$point = 0;
		}

		//Caching user reputation
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = user_reputation + $point
				$new_points
			WHERE user_id = $to";
		$db->sql_query($sql);

		//Max user reputation
		if ($config['rs_max_point'] && ($config['rs_max_point'] < ($user_data['user_reputation'] + $point)))
		{
			$this->check_point($to, 'max');
		}

		//Min user reputation
		if ($config['rs_min_point'] && ($config['rs_min_point'] > ($user_data['user_reputation'] + $point)))
		{
			$this->check_point($to, 'min');
		}

		//If config allows and we are told so, we should send a private message to a user, who received the vote
		if ($notify && $config['rs_pm_notify'])
		{
			include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);

			//Select receiver language
			$user_data['user_lang'] = (file_exists($phpbb_root_path . 'language/' . $user_data['user_lang'] . '/mods/reputation_system.' .$phpEx)) ? $user_data['user_lang'] : $config['default_lang'];

			//Load receiver language
			include($phpbb_root_path . 'language/' . basename($user_data['user_lang']) . '/mods/reputation_system.' . $phpEx);

			if ($post_id)
			{
				$post_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id;
				$post_link = '<a href="' . $post_url . '">';

				if (!empty($comment))
				{
					$message_parser->message = sprintf($lang['RS_PM_BODY_COMMENT'], $point, $comment, $post_link, '</a>');
				}
				else
				{
					$message_parser->message = sprintf($lang['RS_PM_BODY'], $point, $post_link, '</a>');
				}
			}
			else if ($mode == 'user')
			{
				if (!empty($comment))
				{
					$message_parser->message = sprintf($lang['RS_PM_BODY_USER_COMMENT'], $point, $comment);
				}
				else
				{
					$message_parser->message = sprintf($lang['RS_PM_BODY_USER'], $point);
				}
			}

			$message_parser->parse(true, true, true, false, false, true, true);

			$pm_data = array(
				'from_user_id'		=> $user->data['user_id'],
				'from_user_ip'		=> $user->ip,
				'from_username'		=> $user->data['username'],
				'enable_sig'		=> false,
				'enable_bbcode'		=> true,
				'enable_smilies'	=> true,
				'enable_urls'		=> true,
				'icon_id'			=> 0,
				'bbcode_bitfield'	=> $message_parser->bbcode_bitfield,
				'bbcode_uid'		=> $message_parser->bbcode_uid,
				'message'			=> $message_parser->message,
				'address_list'		=> array('u' => array($to => 'to')),
			);

			submit_pm('post', $lang['RS_PM_SUBJECT'], $pm_data, false);
		}

		return true;
	}

	/**
	* @param int $user_id user ID
	* @param string $mode max or min
	*/
	private function check_point($user_id, $mode)
	{
		global $config, $db;

		if ($mode == 'max')
		{
			$point = $config['rs_max_point'];
			$sql_where = 'user_reputation > ' . $config['rs_max_point'];
		}
		else if ($mode == 'min')
		{
			$point = $config['rs_min_point'];
			$sql_where = 'user_reputation < ' . $config['rs_min_point'];
		}

		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = $point
			WHERE $sql_where
				AND user_id = $user_id";
		$db->sql_query($sql);
	}

	/** Function responsible for deleting reputation
	* @param int $id reputation ID
	* @return bool
	*/
	function delete($id)
	{
		global $db;

		if (empty($id))
		{
			return false;
		}

		$sql_array = array(
			'SELECT'	=> 'r.rep_to, r.action, r.post_id, r.point, u.username',
			'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_to = u.user_id',
				),
			),
			'WHERE'		=> 'r.rep_id = ' . $id
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row['post_id'])
		{
			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation - {$row['point']}
				WHERE post_id = {$row['post_id']}";
			$db->sql_query($sql);
		}

		if ($row['action'] != 5)
		{
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_reputation = user_reputation - {$row['point']}
				WHERE user_id = {$row['rep_to']}";
			$db->sql_query($sql);
		}

		$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_id = $id";
		$db->sql_query($sql);

		add_log('mod', '', '', 'LOG_USER_REP_DELETE', $row['username']);

		return true;
	}

	/** Function responsible for clearing user or post reputation
	* @param string $mode user or post
	* @param int $id post or user ID
	* @param array $post_ids post IDs for user with post mode
	* @return bool
	*/
	function clear_reputation($mode, $id, $post_ids = array())
	{
		global $db;

		if (empty($mode) || empty($id))
		{
			return;
		}

		if ($mode == 'post')
		{
			$sql = 'SELECT SUM(point) AS user_points, rep_to, action
				FROM ' . REPUTATIONS_TABLE . "
				WHERE post_id = $id";
			$result = $db->sql_query($sql);
			$point = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if ($point['action'] != 5)
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_reputation = user_reputation - {$point['user_points']}
					WHERE user_id = {$point['rep_to']}";
				$db->sql_query($sql);
			}

			$sql = 'SELECT  topic_id, forum_id, post_subject
				FROM ' . POSTS_TABLE . "
				WHERE post_id = $id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = 0
				WHERE post_id = $id";
			$db->sql_query($sql);

			$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . "
				WHERE post_id = $id";
			$db->sql_query($sql);

			$log_forum = $row['forum_id'];
			$log_topic = $row['topic_id'];
			$log_clear_data = $row['post_subject'];
			$log_clear_action = 'LOG_CLEAR_POST_REP';
		}
		else if ($mode == 'user')
		{
			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_reputation = 0
				WHERE user_id = $id";
			$db->sql_query($sql);

			$sql = 'UPDATE ' . POSTS_TABLE . '
				SET post_reputation = 0
				WHERE ' . $db->sql_in_set('post_id', $post_ids, false, true);
			$db->sql_query($sql);

			$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_to = $id";
			$db->sql_query($sql);

			$sql = 'SELECT  username
				FROM ' . USERS_TABLE . "
				WHERE user_id = $id";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$log_topic = '';
			$log_forum = '';
			$log_clear_action = 'LOG_CLEAR_USER_REP';
			$log_clear_data = $row['username'];
		}
		else
		{
			return;
		}

		add_log('mod', $log_forum,  $log_topic, $log_clear_action, $log_clear_data);
	}

	/** Obtain reputation ranks
	*/
	function obtain_rs_ranks()
	{
		global $cache, $config;

		if (!$config['rs_enable'] || !$config['rs_ranks'])
		{
			return;
		}

		if (($rs_ranks = $cache->get('_rs_ranks')) === false)
		{
			global $db;

			$sql = 'SELECT *
				FROM ' . REPUTATIONS_RANKS_TABLE . '
				ORDER BY rank_points DESC';
			$result = $db->sql_query($sql);

			$rs_ranks = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$rs_ranks[] = $row;
			}
			$db->sql_freeresult($result);

			$cache->put('_rs_ranks', $rs_ranks);
		}

		return $rs_ranks;
	}


	/** Get user rank title, image and color
	* @param int $points the users reputations
	*/
	function get_rs_rank($points, &$rs_rank_title, &$rs_rank_img, &$rs_rank_img_src, &$rs_rank_color)
	{
		global $rs_ranks, $config, $phpbb_root_path, $user;

		//Don't display reputation ranks for guests, bots
		if ($user->data['is_bot'])
		{
			return;
		}

		if (empty($rs_ranks))
		{
			$rs_ranks = self::obtain_rs_ranks();
		}

		$rs_rank_title = $rs_rank_img = $rs_rank_img_src = $rs_rank_color = '';

		foreach ($rs_ranks as $rank)
		{
			if ($points >= $rank['rank_points'])
			{
				$rs_rank_title = $rank['rank_title'];
				$rs_rank_img = (!empty($rank['rank_image'])) ? '<img src="' . $phpbb_root_path . $config['rs_ranks_path'] . '/' . $rank['rank_image'] . '" alt="' . $rank['rank_title'] . '" title="' . $rank['rank_title'] . '" />' : '';
				$rs_rank_img_src = (!empty($rank['rank_image'])) ? $phpbb_root_path . $config['rs_ranks_path'] . '/' . $rank['rank_image'] : '';
				$rs_rank_color = $rank['rank_color'];
				break;
			}
		}
	}

	/** Return post reputation
	* @param $post_id ID of a post
	*/
	function get_post_reputation($post_id)
	{
		global $db;
		$sql = 'SELECT post_reputation
			FROM ' . POSTS_TABLE . "
			WHERE post_id = $post_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row['post_reputation'];
	}

	/**
	* @param $points Rating points
	* @return string String value of CSS class for voting placeholder
	*/
	static function get_vote_class($points)
	{
		if ($points > 0) 
		{
			return 'positive';
		}
		else if ($points < 0) 
		{
			return 'negative';
		}
		else
		{
			return 'zero';
		}
	}

	/** Return user reputation
	* @param $user_id user ID
	*/
	function get_user_reputation($user_id)
	{
		global $db;

		$sql = 'SELECT user_reputation
			FROM ' . USERS_TABLE . "
			WHERE user_id = $user_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row['user_reputation'];
	}

	/** Return user reputation rank
	* @param $points reputation points
	* @param bool $title return rank title
	* @param bool $color return rank color
	*/
	function get_rs_new_rank($points, $title = false, $color = false)
	{
		global $rs_ranks, $config, $phpbb_root_path;

		if (empty($rs_ranks))
		{
			$rs_ranks = self::obtain_rs_ranks();
		}

		$rs_rank_img = $rs_rank_title = $rs_rank_color = '';
		foreach ($rs_ranks as $rank)
		{
			if ($points >= $rank['rank_points'])
			{
				$rs_rank_title = $rank['rank_title'];
				$rs_rank_img = (!empty($rank['rank_image'])) ? '<img src="' . $phpbb_root_path . $config['rs_ranks_path'] . '/' . $rank['rank_image'] . '" alt="' . $rank['rank_title'] . '" title="' . $rank['rank_title'] . '" />' : '';
				$rs_rank_color = $rank['rank_color'];
				break;
			}
		}

		if ($title)
		{
			$return_rank = $rs_rank_title;
		}
		else if ($color)
		{
			$return_rank = $rs_rank_color;
		}
		else
		{
			$return_rank = $rs_rank_img;
		}

		return $return_rank;
	}

	/** Return user rating row
	* @param $user_id user ID
	*/
	function get_row($user_id)
	{
		global $auth, $config, $db, $user;
		global $phpbb_root_path, $phpEx;

		if (!function_exists('get_user_avatar'))
		{
			include_once($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		}

		$sql_array = array(
			'SELECT'	=> 'u.username, u.user_colour, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, r.*',
			'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'r.rep_from = u.user_id',
				),
			),
			'WHERE'		=> 'r.rep_to = ' . $user_id,
			'ORDER_BY'	=> 'r.rep_id DESC'
		);
		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		$avatar_img = $row['user_avatar'] ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? 60 : (60 / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? 60 : (60 / $row['user_avatar_width']) * $row['user_avatar_height']) : '<img src="./' . $phpbb_root_path . 'styles/' . rawurlencode($user->theme['theme_path']) . '/theme/images/no_avatar.gif" width="60px;" height="60px;" alt="" />';

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
		$row['bbcode_options'] = OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS;

		$detail_row = '';
		$detail_row .= '<div class="reputation-list bg2" id="r' . $row['rep_id'] . '">';
		$detail_row .= $config['rs_display_avatar'] ? '<div class="reputation-avatar">' . $avatar_img . '</div>' : '';
		$detail_row .= '<div class="reputation-detail"' . ($config['rs_display_avatar'] ? ' style="margin-left: 72px;"' : '') . '>';
		$detail_row .= ($auth->acl_get('m_rs_moderate') || ($row['rep_from'] == $user->data['user_id'] && $auth->acl_get('u_rs_delete'))) ? '<a href="#" class="reputation-delete" title="{L_DELETE}" class="reputation-delete post" onclick="jRS.del(' . $row['rep_id'] . ', \'user\'); return false;">' . $user->lang['DELETE'] . '</a>' : '';
		$detail_row .= '<span style="float: left;"><strong>' . get_username_string('full', $row['rep_from'], $row['username'], $row['user_colour']) . '</strong> &raquo; ' . $user->format_date($row['time']) . '</span>';
		$detail_row .= '<span class="reputation-rating ' . $point_class . '">' . ($config['rs_point_type'] ? $point_img : $row['point']) . '</span><br />';
		$detail_row .= '<span>' . $user->lang['RS_USER_RATING'] . '</span><br />';
		$detail_row .= ($config['rs_enable_comment'] && !empty($row['comment'])) ? '<span>' . $user->lang['RS_COMMENT'] . '</span>' : '';
		$detail_row .= ($config['rs_enable_comment'] && !empty($row['comment'])) ? '<div class="comment_message">' . generate_text_for_display($row['comment'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']) . '</div>' : '';
		$detail_row .= '</div>';

		return $detail_row;
	}
}

?>