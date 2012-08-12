<?php
/**
*
* @package		Reputation System
* @author		Pico88 (Pico) (http://www.modsteam.tk)
* @co-author	Versusnja
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
	var $user_vote_power_current;
	var $user_vote_power_remaining;
	var $user_reputation;

	/**Function returns maximum voting power of one user
	* @param int $user_posts
	* @param $user_regdate
	* @param int $user_reputation
	* @param int $user_group_id
	* @param bool $explain Set to true if you want function to return string explaining structure of the user power
	* @return $user_max_power Value of maximum voting power
	*/
	function get_rep_power($user_posts, $user_regdate, $user_reputation, $user_group_id, $number_of_active_warnings, $number_of_ban_days_in_1year = 0, $explain_structure = false)
	{
		global $config, $db;
		$now = time();
		$user_power = array();

        //Increasing power for number of posts
        if ($config['rs_total_posts'])
        {
            $user_power['for_number_of_posts'] = intval($user_posts / $config['rs_total_posts']);
        }

        //Increasing power for the age of the user
        if ($config['rs_membership_days'])
        {
            $user_power['for_user_age'] = intval(intval(($now - $user_regdate) / 86400) / $config['rs_membership_days']);
        }

        //Increasing power for total reputation
        if ($config['rs_power_rep_point'])
        {
            $user_power['for_reputation'] = intval($user_reputation / $config['rs_power_rep_point']);
        }

		//Decreasing for warnings
		if ($config['rs_power_loose_warn'] > 0)
		{
			$user_power['for_warnings'] = -$number_of_active_warnings * $config['rs_power_loose_warn'];
		};

		//Decreasing for bans
		if ($config['rs_power_loose_ban'] > 0)
		{
			$user_power['for_bans'] = -$number_of_ban_days_in_1year * $config['rs_power_loose_ban'];
		};

		$user_max_power = array_sum($user_power);

		//Checking that user min power is not lower than minimum power set in ACP
		if ($user_max_power < $config['rs_min_power'])
		{
			$user_power['minimum_voting_power'] = $config['rs_min_power'];
			$user_max_power = max($config['rs_min_power'], $user_max_power);
		}

		//Checking that user max power is not higher than maximum power set in ACP
		if ($user_max_power > $config['rs_max_power'])
		{
			$user_power['maximum_voting_power'] = $config['rs_max_power'];
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
				$user_max_power = $group_power;
			}
		}

		//If you want to get explained structure of user power as a string
		if ($explain_structure)
		{
			$output = '';
			foreach($user_power as $reason => $value)
			{
				if ($value <> 0)
				{
					$output .= "$reason: $value";
				}
			}
			return $output;
		}

		return $user_max_power;
	}

	/**Function analyzes voting of a user and returns an array with statistics
	 *
	 */
	function get_reputation_stats($user_id)
	{
		global $db, $config;

		//That's what we will calculate
		$statistics = array(
			'bancounts'			=> 0,
			'vote_power_spent'	=> 0,
		);

		if ($config['rs_power_limit_time'] && $config['rs_power_limit_value'])
		{
			//Until what timestamp should we count user votes
			$voting_timeout = time() - $config['rs_power_limit_time'] * 3600;

			//Let's get all voting data on this user.
			$sql = 'SELECT *
				FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_from = $user_id
					AND post_id != 0
					AND time > $voting_timeout";
			$result = $db->sql_query($sql);

			//Let's run through the rows and make statistics
			while($user_voting = $db->sql_fetchrow($result))
			{
				//How much power a user spent in a specified period of time
				$statistics['vote_power_spent'] += abs($user_voting['point']);
			}
			$db->sql_freeresult($result);
		}

		if ($config['rs_power_loose_ban'] > 0)
		{
			//Until what timestamp should we count user bans
			$bans_timeout = time() - 365 * 24 * 3600;

			$sql = 'SELECT COUNT(rep_id) AS total_bans
				FROM ' . REPUTATIONS_TABLE . "
				WHERE rep_to = $user_id
					AND warning = 2
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
		global $template, $db, $user, $auth;

		//If config allows and we are told so, we should send a private message to a user, who received the vote
		if ($notify && $config['rs_pm_notify'])
		{
			include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
			include_once($phpbb_root_path . 'includes/message_parser.' . $phpEx);

			$message_parser = new parse_message();

			if ($post_id)
			{
				$post_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'p=' . $post_id) . '#p' . $post_id;
				$post_link = '<a href="' . $post_url . '">';

				if (!empty($comment))
				{
					$message_parser->message = sprintf($user->lang['RS_PM_BODY_COMMENT'], $point, $comment, $post_link, '</a>');
				}
				else
				{
					$message_parser->message = sprintf($user->lang['RS_PM_BODY'], $point, $post_link, '</a>');
				}
			}
			elseif ($mode == 'user')
			{
				if (!empty($comment))
				{
					$message_parser->message = sprintf($user->lang['RS_PM_BODY_USER_COMMENT'], $point, $comment);
				}
				else
				{
					$message_parser->message = sprintf($user->lang['RS_PM_BODY_USER'], $point);
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

			submit_pm('post', $user->lang['RS_PM_SUBJECT'], $pm_data, false);
		}

		//Prepare comment text for storage
		$text = utf8_normalize_nfc($comment);
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

		//Now we are ready to save the vote itself
		$sql_data = array(
			'rep_from'			=> $user->data['user_id'],
			'rep_to'			=> $to,
			'time'				=> time(),
			'post_id'			=> $post_id,
			'user'				=> ($mode == 'user') ? 1 : 0,
			'warning'			=> ($mode == 'ban') ? 2 : (($mode == 'warning') ? 1 : 0),
			'point'				=> $point,
			'comment'			=> $text,
			'bbcode_bitfield'	=> $bitfield,
			'bbcode_uid'		=> $uid,
			'enable_bbcode'		=> $allow_bbcode,
			'enable_urls'		=> $allow_urls,
			'enable_smilies'	=> $allow_smilies,
		);

		//Saving the vote. Used for post rating calculation. Not for user rating calculation
		$db->sql_query('INSERT INTO ' . REPUTATIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_data));

		//Nofity user about the point
		$new_points = '';
		if ($config['rs_notification'])
		{
			$sql = 'SELECT user_rep_new
				FROM ' . USERS_TABLE . " 
				WHERE user_id = $to";
			$result = $db->sql_query($sql);
			$rep_new = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$rep_last_time = !$rep_new['user_rep_new'] ? ', user_rep_last = ' . time() . '' : '';
			$new_points = ', user_rep_new = user_rep_new + 1' . $rep_last_time;
		}

		//Caching user reputation
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = user_reputation + $point
				$new_points
			WHERE user_id = $to";
		$db->sql_query($sql);

		//Max user reputation
		if ($config['rs_max_point'])
		{
			$this->check_point($to, 'max');
		}

		//Min user reputation
		if ($config['rs_min_point'])
		{
			$this->check_point($to, 'min');
		}

		//Post reputation
		if ($post_id)
		{
			$post_rs_count = ($point > 0) ? 1 : -1;

			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation + $point,
					post_rs_count = post_rs_count + $post_rs_count
				WHERE post_id = $post_id";
			$db->sql_query($sql);
		}

		if ($config['rs_enable_ban'] && $mode != 'ban')
		{
			$this->ban_user($to);
		}

		return true;
	}

	/**
	* @param int $user_id user ID
	* @param string $mode max or min
	*/
	function check_point($user_id, $mode)
	{
		global $config, $db;

		if ($mode == 'max')
		{
			$point = $config['rs_max_point'];
			$sql_where = 'user_reputation > ' . $config['rs_max_point'];
		}
		elseif ($mode == 'min')
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

	function delete($id = '', $post_id = false)
	{
		global $db, $user, $uid;

		$sql = 'SELECT rep_from, rep_to, point
			FROM ' . REPUTATIONS_TABLE . " r
			WHERE rep_id = $id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($post_id)
		{
			$post_rs_count = ($row['point'] > 0) ? 1 : -1;

			$sql = 'UPDATE ' . POSTS_TABLE . "
				SET post_reputation = post_reputation - {$row['point']},
					post_rs_count = post_rs_count - $post_rs_count
				WHERE post_id = $post_id";
			$db->sql_query($sql);
		}

		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_reputation = user_reputation - {$row['point']}
			WHERE user_id = {$row['rep_to']}";
		$db->sql_query($sql);

		$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . "
			WHERE rep_id = $id";
		$db->sql_query($sql);

		return true;
	}

	function get_rs_rank($points)
	{
		global $cache, $db;

		if ($cache->get('_rs_ranks') === false)
		{
			$ranks = array();
			$sql = 'SELECT rank_title, rank_points, rank_color
				FROM ' . REPUTATIONS_RANKS_TABLE . '
				ORDER BY rank_points DESC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result)) {
				$ranks[] = $row;
			}
			$db->sql_freeresult($result);

			$cache->put('_rs_ranks', $ranks);
		}
		else
		{
			$ranks = $cache->get('_rs_ranks');
		}

		$rs_rank_title = '';
		foreach ($ranks as $rank)
		{
			if ($points >= $rank['rank_points'])
			{
				$rs_rank_title = $rank['rank_title'];
				break;
			}
		}

		return $rs_rank_title;
	}

	function get_rs_rank_color($rs_rank)
	{
		global $cache;

		$ranks = $cache->get('_rs_ranks');

		$rs_rank_color = '';
		foreach ($ranks as $rank)
		{
			if ($rs_rank == $rank['rank_title'])
			{
				$rs_rank_color = $rank['rank_color'];
				break;
			}
		}

		return $rs_rank_color;
	}

	/** Returns the rating of a post.
	* @param $post_id ID of a post
	* @param bool $true_rating If true returns sum of rating points. Otherwise returns count of votes
	*/
	function get_rating($post_id, $true_rating = true)
	{
		global $db;
		$sql = 'SELECT ' . ($true_rating ? 'post_reputation' : 'post_rs_count') . ' as rating
			FROM ' . POSTS_TABLE . "
			WHERE post_id = $post_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row['rating'];
	}

	/**
	* @param $vote Rating value
	* @return string String value of CSS class for voting placeholder
	*/
	static function get_vote_class($vote)
	{
		if ($vote == 0) return 'zero';
		if ($vote < 0) return 'negative';
		if ($vote > 0) return 'positive';
	}

	/** Returns user reputation.
	* @param $user_id user ID
	*/
	function get_user_reputation($user_id)
	{
		global $db;

		$sql = 'SELECT user_reputation as reputation
			FROM ' . USERS_TABLE . "
			WHERE user_id = $user_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row['reputation'];
	}

	/** Ban user by minimum reputation;
	* @param $user_id user ID
	*/
	function ban_user($user_id)
	{
		global $config, $db, $cache;

		//Get data for ban
		$sql = 'SELECT user_type, group_id, user_reputation, user_last_rep_ban
			FROM ' . USERS_TABLE . "
			WHERE user_id = $user_id";
		$result = $db->sql_query($sql);
		$user_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		//Prevent ban founder
		if ($user_row['user_type'] == USER_FOUNDER)
		{
			return;
		}

		//Check user group if it is excluded
		$groups_id = explode(',', $config['rs_ban_groups']);
		$group_id = (is_array($groups_id)) ? (in_array($user_row['group_id'], $groups_id) ? true : false) : (($user_row['group_id'] == $groups_id) ? true : false);
		if ($group_id)
		{
			return;
		}
		
		//Shield for banned user - cannot be banned in that period
		if (time() < $user_row['user_last_rep_ban'])
		{
			return;
		}

		$sql = 'SELECT *
			FROM ' . REPUTATIONS_BANS_TABLE . '
			ORDER BY point ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if ($user_row['user_reputation'] <= $row['point'])
			{
				//Now, let's ban the user
				//We cannot use user_ban function due to an error of redecaler function so lest's write own sql query
				$current_time = time();
				$ban_end = max($current_time, $current_time + ($row['ban_time']) * 60);
				$sql_ary = array(
					'ban_userid'		=> $user_id,
					'ban_start'			=> time(),
					'ban_end'			=> $ban_end,
					'ban_reason'		=> $row['ban_reason'],
					'ban_give_reason'	=> $row['ban_give_reason'],
				);

				$sql = 'INSERT INTO ' . BANLIST_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
				$db->sql_query($sql);

				$cache->destroy('sql', BANLIST_TABLE);

				//Shield for banned
				$next_ban_time = time() + ($row['ban_time'] * 60) + ($config['rs_ban_shield'] * 86400);
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_last_rep_ban = $next_ban_time
					WHERE user_id = $user_id";
				$db->sql_query($sql);

				break;
			}
		}
		$db->sql_freeresult($result);
	}
}

?>