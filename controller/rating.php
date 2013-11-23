<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\controller;

class rating
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @ reputation display */
	protected $reputation_display;

	/**  @ reputation helper */
	protected $reputation_helper;

	/** @ reputation manager */
	protected $reputation_manager;

	/** @ reputation power */
	protected $reputation_power;

	protected $reputations_table;

	/** @bool ajax*/
	private $is_ajax;

	/** Constants for comments */
	const RS_COMMENT_OFF = 0;
	const RS_COMMENT_BOTH = 1;
	const RS_COMMENT_POST = 2;
	const RS_COMMENT_USER = 3;

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
	* @param string $reputation_manager Reputation manager service
	* @param string $reputation_power Reputation power service
	* @param string $reputation_table Name of the table uses to store reputations
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $controller_helper,  \phpbb\db\driver\driver $db,\phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $reputation_display, $reputation_helper, $reputation_manager, $reputation_power, $reputations_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->controller_helper = $controller_helper;
		$this->db = $db;
		$this->template = $template;
		$this->request = $request;
		$this->user = $user;
		$this->reputation_display = $reputation_display;
		$this->reputation_helper = $reputation_helper;
		$this->reputation_manager = $reputation_manager;
		$this->reputation_power = $reputation_power;
		$this->reputations_table = $reputations_table;

		$this->is_ajax = $request->is_ajax();

		$this->user->add_lang_ext('pico88/reputation', 'reputation_system');

		if (!$this->config['rs_enable'])
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_DISABLED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}
	}

	/**
	* Post rating controller to be accessed with the URL /reputation/ratepost/{post_id}/{mode}
	* (where {post_id} is the placeholder for a value)
	* (where {mode} is the placeholder for a string)
	*
	* @param int	$post_id	Post ID taken from the URL
	* @param strng	$mode		Mode taken from the URL 
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function post($mode, $post_id)
	{
		//Let get some data
		$sql_array = array(
			'SELECT'	=> 'u.user_type, u.username, u.user_colour, p.forum_id, p.poster_id, p.post_username , f.enable_reputation, r.rep_id, r.point',
			'FROM'		=> array(
				POSTS_TABLE => 'p',
				USERS_TABLE => 'u'
			),
			'LEFT_JOIN' => array(
				array(
					'FROM'	=> array(FORUMS_TABLE => 'f'),
					'ON'	=> 'p.forum_id = f.forum_id',
				),
				array(
					'FROM'	=> array($this->reputations_table => 'r'),
					'ON'	=> 'p.post_id = r.post_id AND rep_from = ' . $this->user->data['user_id'],
				),
			),
			'WHERE'		=> 'p.post_id = ' . $post_id . '
				AND p.poster_id = u.user_id',
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		//We couldn't find this post. May be it was deleted while user voted?
		if (!$row)
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_NO_POST']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		//Fire error if it's disabled and exit
		if (!$this->config['rs_post_rating'] || !$this->config['rs_negative_point'] && $mode == 'negative' || !$row['enable_reputation'])
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_DISABLED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		//No anonymous voting is allowed
		if ($row['user_type'] == USER_IGNORE)
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_USER_ANONYMOUS']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		//You can not vote for your posts
		if ($row['poster_id'] == $this->user->data['user_id'])
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_SELF']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		// Don't allow to rate same post
		if ($row['rep_id'])
		{
			$json_data = array(
				'error_msg' => $this->user->lang('RS_SAME_POST', $row['point'])
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		//Check if user is allowed to vote
		if (!$this->auth->acl_get('f_rs_give', $row['forum_id']) || !$this->auth->acl_get('f_rs_give_negative', $row['forum_id']) && $mode == 'negative' || !$this->auth->acl_get('u_rs_ratepost'))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_USER_DISABLED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		//Check if user reputation is enought to give negative points
		if ($this->config['rs_min_rep_negative'] && ($this->user->data['user_reputation'] < $this->config['rs_min_rep_negative']) && $mode == 'negative')
		{
			$json_data = array(
				'error_msg' => $this->user->lang('RS_USER_NEGATIVE', $this->config['rs_min_rep_negative'])
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		// Anti-abuse behaviour
		if (!empty($this->config['rs_anti_time']) && !empty($this->config['rs_anti_post']))
		{
			$anti_time = time() - $this->config['rs_anti_time'] * 3600;
			$sql_and = (!$this->config['rs_anti_method']) ? 'AND rep_to = ' . $row['poster_id'] : '';
			$sql = 'SELECT COUNT(rep_id) AS rep_per_day
				FROM ' . $this->reputations_table . '
				WHERE rep_from = ' . $user->data['user_id'] . '
					' . $sql_and . '
					AND post_id != 0
					AND time > ' . $anti_time;
			$result = $this->db->sql_query($sql);
			$anti_row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($anti_row['rep_per_day'] >= $this->config['rs_anti_post'])
			{
				$json_data = array(
					'error_msg' => $this->user->lang['RS_ANTISPAM_INFO']
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}
		}

		// Disallow rating banned users
		if ($this->user->check_ban($row['poster_id'], false, false, true))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_USER_BANNED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		// Request variables
		$points = $this->request->variable('points', '');
		$comment = $this->request->variable('comment', '', true);

		$error = '';

		// Submit vote
		$submit = false;
		if ($this->request->is_set_post('rate'))
		{
			$submit = true;
		}

		// The comment
		if ($submit && $this->config['rs_enable_comment'])
		{
			// The comment is too long
			if (strlen($comment) > $this->config['rs_comment_max_chars'])
			{
				$submit = false;
				$error = $this->user->lang('RS_COMMENT_TOO_LONG', strlen($comment), $this->config['rs_comment_max_chars']);
				if ($this->is_ajax)
				{
					echo json_encode(array('comment_error' => $error));
					exit;
				}
			}

			// Force the comment
			if (($this->config['rs_force_comment'] == 1 || $this->config['rs_force_comment'] == 2) && empty($comment))
			{
				$submit = false;
				$error = $this->user->lang['RS_NO_COMMENT'];
				if ($this->is_ajax)
				{
					echo json_encode(array('comment_error' => $error));
					exit;
				}
			}
		}

		// Sumbit vote when the comment and the reputation power are disabled
		if (!$this->config['rs_enable_comment'] && !$this->config['rs_enable_power'])
		{
			$submit = true;
			$points = ($mode == 'negative') ? -1 : 1;
		}

		// Get reputation power
		if ($this->config['rs_enable_power'])
		{
			$voting_power_pulldown = '';

			// Get details on user voting - how much power was used
			$used_power = $this->reputation_power->used($this->user->data['user_id']);

			// Calculate how much maximum power the user has
			$max_voting_power = $this->reputation_power->get($this->user->data['user_posts'], $this->user->data['user_regdate'], $this->user->data['user_reputation'], $this->user->data['user_warnings']);

			if ($max_voting_power < 1)
			{
				$json_data = array(
					'error_msg' => $this->user->lang['RS_NO_POWER']
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}

			$voting_power_left = $max_voting_power - $used_power;

			// Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $this->config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			// If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $this->config['rs_power_renewal'])
			{
				$json_data = array(
					'error_msg' => $this->user->lang('RS_NO_POWER_LEFT', $max_voting_power)
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}

			$this->template->assign_vars(array(
				'RS_POWER_POINTS_LEFT'		=> $this->config['rs_power_renewal'] ? $this->user->lang('RS_VOTE_POWER_LEFT_OF_MAX', $voting_power_left, $max_voting_power, $max_voting_allowed) : '',
				'RS_POWER_PROGRESS_EMPTY'	=> ($this->config['rs_power_renewal'] && $max_voting_power) ? round((($max_voting_power - $voting_power_left) / $max_voting_power) * 100, 0) : '',
			));

			//Preparing HTML for voting by manual spending of user power
			for($i = 1; $i <= $max_voting_allowed; ++$i)
			{
				if ($mode == 'negative')
				{
					$voting_power_pulldown = '<option value="-' . $i . '">' . $this->user->lang['RS_NEGATIVE'] . ' (-' . $i . ') </option>';

					if ($i == $this->user->data['user_rs_default_power'] && isset($this->user->data['user_rs_default_power']))
					{
						$voting_power_pulldown = '<option value="-' . $i . '" selected="selected">' . $this->user->lang['RS_NEGATIVE'] . ' (-' . $i . ') </option>';
					}
				}
				else
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $this->user->lang['RS_POSITIVE'] . ' (+' . $i . ')</option>';

					if ($i == $this->user->data['user_rs_default_power'] && isset($this->user->data['user_rs_default_power']))
					{
						$voting_power_pulldown = '<option value="' . $i . '" selected="selected">' . $this->user->lang['RS_POSITIVE'] . ' (+' . $i . ') </option>';
					}
				}

				$this->template->assign_block_vars('reputation', array(
					'REPUTATION_POWER'	=> $voting_power_pulldown)
				);
			}
		}
		else
		{
			$points = ($mode == 'negative') ? -1 : 1;
		}

		// Save vote
		if ($submit)
		{
			// Prevent cheater to break the forum permissions to give negative points or give more points than they can 
			if (!$this->auth->acl_get('f_rs_give_negative', $row['forum_id']) && $points < 0 || $points < 0 && $this->config['rs_min_rep_negative'] && ($this->user->data['user_reputation'] < $this->config['rs_min_rep_negative']) || $this->config['rs_enable_power'] && (($points > $max_voting_allowed) || ($points < -$max_voting_allowed)))
			{
				$json_data = array(
					'error_msg' => $this->user->lang['RS_USER_DISABLED']
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}

			// Prevent overrating one user by another
			if ($this->reputation_manager->prevent_rating($row['poster_id']))
			{
				$json_data = array(
					'error_msg' => $this->user->lang['RS_ANTISPAM_INFO']
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}

			$post_rating_mode = ($row['enable_reputation'] == 1) ? 'post' : 'onlypost';

			if ($this->reputation_manager->give_point($row['poster_id'], $post_id, $comment, $points, $post_rating_mode))
			{
				// Generate JSON reply
				$post_reputation = $this->reputation_manager->get_post_reputation($post_id);
				$user_reputation = $this->reputation_manager->get_user_reputation($row['poster_id']);
				$json_data = array(
					'post_id'				=> $post_id,
					'poster_id'				=> $row['poster_id'],
					'post_reputation'		=> $post_reputation,
					'user_reputation'		=> '<strong>' . $user_reputation . '</strong>',
					'reputation_class'		=> $this->reputation_display->vote_class($post_reputation),
					'reputation_vote'		=> ($points > 0) ? 'rated_good' : 'rated_bad',
					'success_msg'			=> $this->user->lang['RS_VOTE_SAVED'],
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}
		}

		$this->template->assign_vars(array(
			'ERROR_MSG'					=> $error,

			'S_CONFIRM_ACTION'			=> $this->reputation_helper->generate_url('reputation/rate/post/' . $mode . '/' . $post_id, $this->is_ajax),
			'S_ERROR'					=> (!empty($error)) ? true : false,
			'S_RS_COMMENT_ENABLE'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_RS_POWER_ENABLE' 		=> $this->config['rs_enable_power'] ? true : false,
			'S_IS_AJAX'					=> $this->is_ajax,
		));

		return $this->controller_helper->render('ratepost.html');
	}

	/**
	* User rating controller to be accessed with the URL /reputation/rate/user/{uid}
	* (where {uid} is the placeholder for a value)
	*
	* @param int    $uid   User ID taken from the URL
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function user($uid)
	{
		if (!$this->config['rs_user_rating'] || !$this->auth->acl_get('u_rs_give'))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_DISABLED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		$sql = 'SELECT user_id, user_type
			FROM ' . USERS_TABLE . "
			WHERE user_id = $uid";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_NO_USER_ID']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		if ($row['user_type'] == USER_IGNORE)
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_USER_ANONYMOUS']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		if ($row['user_id'] == $this->user->data['user_id'])
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_SELF']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		// Disallow rating banned users
		if ($this->user->check_ban($uid, false, false, true))
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_USER_BANNED']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		$sql = 'SELECT rep_id, time
			FROM ' . $this->reputations_table . "
			WHERE rep_to = {$uid}
				AND rep_from = {$this->user->data['user_id']}
				AND action = 2
			ORDER by rep_id DESC";
		$result = $this->db->sql_query($sql);
		$check_user = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($check_user && !$this->config['rs_user_rating_gap'])
		{
			$json_data = array(
				'error_msg' => $this->user->lang['RS_SAME_USER']
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		if ($this->config['rs_user_rating_gap'] && (time() < $check_user['time'] + $this->config['rs_user_rating_gap'] * 86400))
		{
			//Informe user how long he has to wait to rate user
			$next_vote_time = ($check_user['time'] + $this->config['rs_user_rating_gap'] * 86400) - time();
			$next_vote_in = '';
			$next_vote_in .= intval($next_vote_time / 86400) ? intval($next_vote_time / 86400) . ' ' . $this->user->lang['DAYS'] . ' ' : '';
			$next_vote_in .= intval(($next_vote_time / 3600) % 24)  ? intval(($next_vote_time / 3600) % 24) . ' ' . $this->user->lang['HOURS'] . ' ' : '';
			$next_vote_in .= intval(($next_vote_time / 60) % 60) ? intval(($next_vote_time / 60) % 60) . ' ' . $this->user->lang['MINUTES'] : '';
			$next_vote_in .= (intval($next_vote_time) < 60) ? intval($next_vote_time) . ' ' . $this->user->lang['SECONDS'] : '';

			$json_data = array(
				'error_msg' => $this->user->lang('RS_USER_GAP', $next_vote_in)
			);
			$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
		}

		// Request variables
		$points = $this->request->variable('points', '');
		$comment = $this->request->variable('comment', '', true);

		$error = '';

		// Submit vote
		$submit = false;
		if ($this->request->is_set_post('rate'))
		{
			$submit = true;
		}

		// The comment
		if ($submit && $this->config['rs_enable_comment'])
		{
			// The comment is too long
			if (strlen($comment) > $this->config['rs_comment_max_chars'])
			{
				$submit = false;
				$error = $this->user->lang('RS_COMMENT_TOO_LONG', strlen($comment), $this->config['rs_comment_max_chars']);
				if ($this->is_ajax)
				{
					echo json_encode(array('comment_error' => $error));
					exit;
				}
			}

			// Force the comment
			if (($this->config['rs_force_comment'] == 1 || $this->config['rs_force_comment'] == 3) && empty($comment))
			{
				$submit = false;
				$error = $this->user->lang['RS_NO_COMMENT'];
				if ($this->is_ajax)
				{
					echo json_encode(array('comment_error' => $error));
					exit;
				}
			}
		}

		// Get reputation power
		if ($this->config['rs_enable_power'])
		{
			$voting_power_pulldown = '';

			// Get details on user voting - how much power was used
			$used_power = $this->reputation_power->used($this->user->data['user_id']);

			//Calculate how much maximum power a user has
			$max_voting_power = $this->reputation_power->get($this->user->data['user_posts'], $this->user->data['user_regdate'], $this->user->data['user_reputation'], $this->user->data['user_warnings']);

			if ($max_voting_power < 1)
			{
				echo json_encode(array('error_msg' => $this->user->lang['RS_NO_POWER']));
				exit;
			}

			$voting_power_left = $max_voting_power - $used_power;

			//Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $this->config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			//If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $this->config['rs_power_renewal'])
			{
				$json_data = array(
					'error_msg' => $this->user->lang('RS_NO_POWER_LEFT', $max_voting_power)
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}

			$this->template->assign_vars(array(
				'RS_POWER_POINTS_LEFT'		=> $this->config['rs_power_renewal'] ? $this->user->lang('RS_VOTE_POWER_LEFT_OF_MAX', $voting_power_left, $max_voting_power, $max_voting_allowed) : '',
				'RS_POWER_PROGRESS_EMPTY'	=> ($this->config['rs_power_renewal'] && $max_voting_power) ? round((($max_voting_power - $voting_power_left) / $max_voting_power) * 100, 0) : '',
			));

			//Preparing HTML for voting by manual spending of user power
			$startpower = $this->config['rs_negative_point'] ? -$max_voting_allowed : 1;
			for($i = $max_voting_allowed; $i >= $startpower; $i--) //from + to -
			//for($i = $startpower; $i <= $reputationpower; ++$i) //from - to +
			{
				if ($i == 0)
				{
					$voting_power_pulldown = '';
				}
				if ($i > 0)
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $this->user->lang['RS_POSITIVE'] . ' (+' . $i . ') </option>';
				}
				if ($i < 0 && $this->auth->acl_get('u_rs_give_negative') && $this->config['rs_negative_point'] && (($this->config['rs_min_rep_negative'] != 0) ? ($this->user->data['user_reputation'] >= $this->config['rs_min_rep_negative']) : true))
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $this->user->lang['RS_NEGATIVE'] . ' (' . $i . ') </option>';
				}

				$this->template->assign_block_vars('reputation', array(
					'REPUTATION_POWER'	=> $voting_power_pulldown)
				);
			}
		}
		else
		{
			$rs_power = '<option value="1">' . $this->user->lang['RS_POSITIVE'] . '</option>';
			if ($this->auth->acl_get('u_rs_give_negative') && $this->config['rs_negative_point'] && (($this->config['rs_min_rep_negative'] != 0) ? ($this->user->data['user_reputation'] >= $this->config['rs_min_rep_negative']) : true))
			{
				$rs_power .= '<option value="-1">' . $this->user->lang['RS_NEGATIVE'] . '</option>';
			}
			else if ($this->config['rs_enable_comment'])
			{
				$points = 1;
			}
			else
			{
				$submit = true;
				$points = 1;
			}

			$this->template->assign_block_vars('reputation', array(
				'REPUTATION_POWER'	=> $rs_power)
			);
		}

		if ($submit)
		{
			//Prevent cheater to break the forum permissions to give negative points or give more points than they can 
			if (!$this->auth->acl_get('u_rs_give_negative') && $points < 0 || $points < 0 && $this->config['rs_min_rep_negative'] && ($this->user->data['user_reputation'] < $this->config['rs_min_rep_negative']) || $this->config['rs_enable_power'] && (($points > $max_voting_allowed) || ($points < -$max_voting_allowed)))
			{
				$json_data = array(
					'error_msg' => $this->user->lang['RS_USER_DISABLED']
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}

			if ($this->reputation_manager->prevent_rating($uid))
			{
				$json_data = array(
					'error_msg' => $this->user->lang['RS_SAME_USER']
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
			}

			if ($this->reputation_manager->give_point($uid, 0, $comment, $points, 'user'))
			{
				// If it's an AJAX request, generate JSON reply
				$user_reputation = $this->reputation_manager->get_user_reputation($uid);
				$json_data = array(
					'user_reputation'		=> '<strong>' . $user_reputation . '</strong>',
					'success_msg'			=> $this->user->lang['RS_VOTE_SAVED'],
				);
				$this->reputation_manager->reputation_response($json_data, $this->is_ajax);
				//Returned JSON data and stop the script.
			}
		}

		$this->template->assign_vars(array(
			'ERROR_MSG'					=> $error,

			'S_CONFIRM_ACTION'			=> $this->reputation_helper->generate_url('reputation/rate/user/' . $uid, $this->is_ajax),
			'S_RS_COMMENT_ENABLE'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_IS_AJAX'					=> $this->is_ajax,
		));

		return $this->controller_helper->render('rateuser.html');
	}
}