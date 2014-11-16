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

/**
*
*/
class rating_controller
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

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

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** Constants for comments */
	const RS_COMMENT_BOTH = 1;
	const RS_COMMENT_POST = 2;
	const RS_COMMENT_USER = 3;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth						Auth object
	* @param \phpbb\config\config $config				Config object
	* @param \phpbb\controller\helper					Controller helper object
	* @param \phpbb\db\driver\driver $db				Database object
	* @param \phpbb\request\request $request			Request object
	* @param \phpbb\symfony_request $symfony_request	Symfony Request object
	* @param \phpbb\template\template $template			Template object
	* @param \phpbb\user $user							User object
	* @param \pico\reputation\core\reputation_helper	Reputation helper object
	* @param \pico\reputation\core\reputation_manager	Reputation manager object
	* @param \pico\reputation\core\reputation_power		Reputation power object
	* @param string $reputations_table					Name of the table used to store reputations data
	* @param string $root_path							phpBB root path
	* @param string $php_ext							phpEx
	* @return \pico\reputation\controller\rating_controller
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\symfony_request $symfony_request, \phpbb\template\template $template, \phpbb\user $user, \pico\reputation\core\reputation_helper $reputation_helper, \pico\reputation\core\reputation_manager $reputation_manager, \pico\reputation\core\reputation_power $reputation_power, $reputations_table, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->request = $request;
		$this->symfony_request = $symfony_request;
		$this->template = $template;
		$this->user = $user;
		$this->reputation_helper = $reputation_helper;
		$this->reputation_manager = $reputation_manager;
		$this->reputation_power = $reputation_power;
		$this->reputations_table = $reputations_table;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Display the post rating page
	*
	* @param string $mode		Mode taken from the URL 
	* @param int $post_id		Post ID taken from the URL
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	* @access public
	*/
	public function post($mode, $post_id)
	{
		$this->user->add_lang_ext('pico/reputation', 'reputation_rating');

		// Define basic variables
		$error = '';
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

		$reputation_type_id = (int) $this->reputation_manager->get_reputation_type_id('post');

		$sql_array = array(
			'SELECT'	=> 'p.forum_id, p.poster_id, p.post_subject, u.user_type, u.user_reputation, f.reputation_enabled, r.reputation_id, r.reputation_points',
			'FROM'		=> array(
				POSTS_TABLE		=> 'p',
				USERS_TABLE		=> 'u',
				FORUMS_TABLE	=> 'f',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array($this->reputations_table => 'r'),
					'ON'	=> 'p.post_id = r.reputation_item_id
						AND r.reputation_type_id = ' . $reputation_type_id . '
						AND r.user_id_from = ' . $this->user->data['user_id'],
				),
			),
			'WHERE'		=> 'p.post_id = ' . $post_id . '
				AND p.poster_id = u.user_id
				AND p.forum_id = f.forum_id',
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// We couldn't find this post. May be it was deleted while user voted?
		if (!$row)
		{
			$message = $this->user->lang('RS_NO_POST');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Cancel action
		if ($this->request->is_set_post('cancel'))
		{
			redirect(append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id);
		}

		// Fire error if post rating is disabled
		if (!$this->config['rs_post_rating'] || !$this->config['rs_negative_point'] && $mode == 'negative' || !$row['reputation_enabled'])
		{
			$message = $this->user->lang('RS_DISABLED');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// No anonymous voting is allowed
		if ($row['user_type'] == USER_IGNORE)
		{
			$message = $this->user->lang('RS_USER_ANONYMOUS');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// You cannot rate your own post
		if ($row['poster_id'] == $this->user->data['user_id'])
		{
			$message = $this->user->lang('RS_SELF');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Don't allow to rate same post
		if ($row['reputation_id'])
		{
			$message = $this->user->lang('RS_SAME_POST', $row['reputation_points']);
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Check if user is allowed to vote
		if (!$this->auth->acl_get('f_rs_rate', $row['forum_id']) || !$this->auth->acl_get('f_rs_rate_negative', $row['forum_id']) && $mode == 'negative' || !$this->auth->acl_get('u_rs_rate_post'))
		{
			$message = $this->user->lang('RS_USER_DISABLED');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		//Check if user reputation is enough to give negative points
		if ($this->config['rs_min_rep_negative'] && ($this->user->data['user_reputation'] < $this->config['rs_min_rep_negative']) && $mode == 'negative')
		{
			$message = $this->user->lang('RS_USER_NEGATIVE', $this->config['rs_min_rep_negative']);
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Anti-abuse behaviour
		if (!empty($this->config['rs_anti_time']) && !empty($this->config['rs_anti_post']))
		{
			$anti_time = time() - $this->config['rs_anti_time'] * 3600;
			$sql_and = (!$this->config['rs_anti_method']) ? 'AND user_id_to = ' . $row['poster_id'] : '';
			$sql = 'SELECT COUNT(reputation_id) AS reputation_per_day
				FROM ' . $this->reputations_table . '
				WHERE user_id_from = ' . $this->user->data['user_id'] . '
					' . $sql_and . '
					AND reputation_type_id = ' . $reputation_type_id . '
					AND reputation_time > ' . $anti_time;
			$result = $this->db->sql_query($sql);
			$anti_row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($anti_row['reputation_per_day'] >= $this->config['rs_anti_post'])
			{
				$message = $this->user->lang('RS_ANTISPAM_INFO');
				$json_data = array(
					'error_msg' => $message,
				);
				$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
				$redirect_text = 'RETURN_TOPIC';

				$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
			}
		}

		// Disallow rating banned users
		if ($this->user->check_ban($row['poster_id'], false, false, true))
		{
			$message = $this->user->lang('RS_USER_BANNED');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Prevent overrating one user by another
		if ($this->reputation_manager->prevent_rating($row['poster_id']))
		{
			$message = $this->user->lang('RS_ANTISPAM_INFO');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Request variables
		$points = $this->request->variable('points', '');
		$comment = $this->request->variable('comment', '', true);

		// Submit vote
		$submit = false;

		if ($this->request->is_set_post('submit_vote'))
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

				if ($is_ajax)
				{
					$json_response = new \phpbb\json_response();
					$json_data = array(
						'comment_error' => $error,
					);
					$json_response->send($json_data);
				}
			}

			// Force the comment
			if (($this->config['rs_force_comment'] == self::RS_COMMENT_BOTH || $this->config['rs_force_comment'] == self::RS_COMMENT_POST) && empty($comment))
			{
				$submit = false;
				$error = $this->user->lang('RS_NO_COMMENT');

				if ($is_ajax)
				{
					$json_response = new \phpbb\json_response();
					$json_data = array(
						'comment_error' => $error,
					);
					$json_response->send($json_data);
				}
			}
		}

		// Sumbit vote when the comment and the reputation power are disabled
		if (!$this->config['rs_enable_comment'] && !$this->config['rs_enable_power'])
		{
			$submit = true;
			$points = ($mode == 'negative') ? '-1' : '1';
		}

		// Get reputation power
		if ($this->config['rs_enable_power'])
		{
			$voting_power_pulldown = '';

			// Get details on user voting - how much power was used
			$used_power = $this->reputation_power->used($this->user->data['user_id']);

			// Calculate how much maximum power the user has
			$max_voting_power = $this->reputation_power->get($this->user->data['user_posts'], $this->user->data['user_regdate'], $this->user->data['user_reputation'], $this->user->data['user_warnings'], $this->user->data['group_id']);

			if ($max_voting_power < 1)
			{
				$message = $this->user->lang('RS_NO_POWER');
				$json_data = array(
					'error_msg' => $message,
				);
				$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
				$redirect_text = 'RETURN_TOPIC';

				$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
			}

			$voting_power_left = $max_voting_power - $used_power;

			// Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $this->config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			// If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $this->config['rs_power_renewal'])
			{
				$message = $this->user->lang('RS_NO_POWER_LEFT', $max_voting_power);
				$json_data = array(
					'error_msg' => $message,
				);
				$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
				$redirect_text = 'RETURN_TOPIC';

				$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
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
					$voting_power_pulldown = '<option value="-' . $i . '">' . $this->user->lang('RS_NEGATIVE') . ' (-' . $i . ') </option>';
				}
				else
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $this->user->lang('RS_POSITIVE') . ' (+' . $i . ')</option>';
				}

				$this->template->assign_block_vars('reputation', array(
					'REPUTATION_POWER'	=> $voting_power_pulldown)
				);
			}
		}
		else
		{
			$points = ($mode == 'negative') ? '-1' : '1';
		}

		// Save vote
		if ($submit)
		{
			// Prevent cheater to break the forum permissions to give negative points or give more points than they can 
			if (!$this->auth->acl_get('f_rs_rate_negative', $row['forum_id']) && $points < 0 || $points < 0 && $this->config['rs_min_rep_negative'] && ($this->user->data['user_reputation'] < $this->config['rs_min_rep_negative']) || $this->config['rs_enable_power'] && (($points > $max_voting_allowed) || ($points < -$max_voting_allowed)))
			{
				$submit = false;
				$error = $this->user->lang('RS_USER_CANNOT_RATE');

				if ($is_ajax)
				{
					$json_response = new \phpbb\json_response();
					$json_data = array(
						'comment_error' => $error,
					);
					$json_response->send($json_data);
				}
			}
		}

		if (!empty($error))
		{
			$submit = false;
		}

		if ($submit)
		{
			$data = array(
				'user_id_from'			=> $this->user->data['user_id'],
				'user_id_to'			=> $row['poster_id'],
				'reputation_type'		=> 'post',
				'reputation_item_id'	=> $post_id,
				'reputation_points'		=> $points,
				'reputation_comment'	=> $comment,
			);

			try
			{
				$this->reputation_manager->store_reputation($data);
			}
			catch (\pico\reputation\exception\base $e)
			{
				// Catch exception
				$error = $e->get_message($this->user);
			}

			// Notification data
			$notification_data = array(
				'user_id_to'		=> $row['poster_id'],
				'user_id_from'		=> $this->user->data['user_id'],
				'post_id'			=> $post_id,
				'post_subject'		=> $row['post_subject'],
			);

			$notification_type = ($points > 0) ? 'pico.reputation.notification.type.rate_post_positive' : 'pico.reputation.notification.type.rate_post_negative';
			$this->reputation_manager->add_notification($notification_type, $notification_data);

			// Get post reputation
			$post_reputation = $this->reputation_manager->get_post_reputation($post_id);

			$message = $this->user->lang('RS_VOTE_SAVED');
			$json_data = array(
				'post_id'				=> $post_id,
				'poster_id'				=> $row['poster_id'],
				'post_reputation'		=> $post_reputation,
				'user_reputation'		=> $this->reputation_manager->get_user_reputation($row['poster_id']),
				'reputation_class'		=> $this->reputation_helper->reputation_class($post_reputation),
				'reputation_vote'		=> ($points > 0) ? 'rated_good' : 'rated_bad',
				'success_msg'			=> $message,
			);
			$redirect = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;p=' . $post_id) . '#p' . $post_id;
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$this->template->assign_vars(array(
			'ERROR_MSG'					=> $error,

			'S_CONFIRM_ACTION'			=> $this->helper->route('reputation_post_rating_controller', array('mode' => $mode, 'post_id' => $post_id)),
			'S_ERROR'					=> (!empty($error)) ? true : false,
			'S_RS_COMMENT_ENABLE'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_RS_POWER_ENABLE' 		=> $this->config['rs_enable_power'] ? true : false,
			'S_IS_AJAX'					=> $is_ajax,

			'U_RS_REFERER'	=> $referer,
		));

		return $this->helper->render('ratepost.html', $this->user->lang('RS_POST_RATING'));
	}

	/**
	* Display the user rating page
	*
	* @param int $uid	User ID taken from the URL
	* @return Symfony\Component\HttpFoundation\Response A Symfony Response object
	* @access public
	*/
	public function user($uid)
	{
		$this->user->add_lang_ext('pico/reputation', 'reputation_rating');

		// Define some variables
		$error = '';
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

		if (!$this->config['rs_user_rating'] || !$this->auth->acl_get('u_rs_rate'))
		{
			$message = $this->user->lang('RS_DISABLED');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$sql = 'SELECT user_id, user_type
			FROM ' . USERS_TABLE . "
			WHERE user_id = $uid";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			$message = $this->user->lang('RS_NO_USER_ID');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Cancel action
		if ($this->request->is_set_post('cancel'))
		{
			redirect(append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid));
		}

		if ($row['user_type'] == USER_IGNORE)
		{
			$message = $this->user->lang('RS_USER_ANONYMOUS');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if ($row['user_id'] == $this->user->data['user_id'])
		{
			$message = $this->user->lang('RS_SELF');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Disallow rating banned users
		if ($this->user->check_ban($uid, false, false, true))
		{
			$message = $this->user->lang('RS_USER_BANNED');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$reputation_type_id = (int) $this->reputation_manager->get_reputation_type_id('user');

		$sql = 'SELECT reputation_id, reputation_time
			FROM ' . $this->reputations_table . "
			WHERE user_id_to = {$uid}
				AND user_id_from = {$this->user->data['user_id']}
				AND reputation_type_id = {$reputation_type_id}
			ORDER by reputation_id DESC";
		$result = $this->db->sql_query($sql);
		$check_user = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($check_user && !$this->config['rs_user_rating_gap'])
		{
			$message = $this->user->lang('RS_SAME_USER');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if ($this->config['rs_user_rating_gap'] && (time() < $check_user['reputation_time'] + $this->config['rs_user_rating_gap'] * 86400))
		{
			//Inform user how long he has to wait to rate the user
			$next_vote_time = ($check_user['reputation_time'] + $this->config['rs_user_rating_gap'] * 86400) - time();
			$next_vote_in = '';
			$next_vote_in .= intval($next_vote_time / 86400) ? intval($next_vote_time / 86400) . ' ' . $this->user->lang('DAYS') . ' ' : '';
			$next_vote_in .= intval(($next_vote_time / 3600) % 24)  ? intval(($next_vote_time / 3600) % 24) . ' ' . $this->user->lang('HOURS') . ' ' : '';
			$next_vote_in .= intval(($next_vote_time / 60) % 60) ? intval(($next_vote_time / 60) % 60) . ' ' . $this->user->lang('MINUTES') : '';
			$next_vote_in .= (intval($next_vote_time) < 60) ? intval($next_vote_time) . ' ' . $this->user->lang('SECONDS') : '';

			$message = $this->user->lang('RS_USER_GAP', $next_vote_in);
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if ($this->reputation_manager->prevent_rating($uid))
		{
			$message = $this->user->lang('RS_SAME_USER');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
			$redirect_text = 'RETURN_TOPIC';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		// Request variables
		$points = $this->request->variable('points', '');
		$comment = $this->request->variable('comment', '', true);

		$error = '';

		// Submit vote
		$submit = false;

		if ($this->request->is_set_post('submit_vote'))
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

				if ($is_ajax)
				{
					$json_response = new \phpbb\json_response();
					$json_data = array(
						'comment_error' => $error,
					);
					$json_response->send($json_data);
				}
			}

			// Force the comment
			if (($this->config['rs_force_comment'] == self::RS_COMMENT_BOTH || $this->config['rs_force_comment'] == self::RS_COMMENT_USER) && empty($comment))
			{
				$submit = false;
				$error = $this->user->lang('RS_NO_COMMENT');

				if ($is_ajax)
				{
					$json_response = new \phpbb\json_response();
					$json_data = array(
						'comment_error' => $error,
					);
					$json_response->send($json_data);
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
			$max_voting_power = $this->reputation_power->get($this->user->data['user_posts'], $this->user->data['user_regdate'], $this->user->data['user_reputation'], $this->user->data['user_warnings'], $this->user->data['group_id']);

			if ($max_voting_power < 1)
			{
				$message = $this->user->lang('RS_NO_POWER');
				$json_data = array(
					'error_msg' => $message,
				);
				$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
				$redirect_text = 'RETURN_PAGE';

				$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
			}

			$voting_power_left = $max_voting_power - $used_power;

			//Don't allow to vote more than set in ACP per 1 vote
			$max_voting_allowed = $this->config['rs_power_renewal'] ? min($max_voting_power, $voting_power_left) : $max_voting_power;

			//If now voting power left - fire error and exit
			if ($voting_power_left <= 0 && $this->config['rs_power_renewal'])
			{
				$message = $this->user->lang('RS_NO_POWER_LEFT', $max_voting_power);
				$json_data = array(
					'error_msg' => $message,
				);
				$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
				$redirect_text = 'RETURN_PAGE';

				$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
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
					$voting_power_pulldown = '<option value="' . $i . '">' . $this->user->lang('RS_POSITIVE') . ' (+' . $i . ') </option>';
				}
				if ($i < 0 && $this->auth->acl_get('u_rs_rate_negative') && $this->config['rs_negative_point'] && (($this->config['rs_min_rep_negative'] != 0) ? ($this->user->data['user_reputation'] >= $this->config['rs_min_rep_negative']) : true))
				{
					$voting_power_pulldown = '<option value="' . $i . '">' . $this->user->lang('RS_NEGATIVE') . ' (' . $i . ') </option>';
				}

				$this->template->assign_block_vars('reputation', array(
					'REPUTATION_POWER'	=> $voting_power_pulldown)
				);
			}
		}
		else
		{
			$rs_power = '<option value="1">' . $this->user->lang('RS_POSITIVE') . '</option>';
			if ($this->auth->acl_get('u_rs_rate_negative') && $this->config['rs_negative_point'] && (($this->config['rs_min_rep_negative'] != 0) ? ($this->user->data['user_reputation'] >= $this->config['rs_min_rep_negative']) : true))
			{
				$rs_power .= '<option value="-1">' . $this->user->lang('RS_NEGATIVE') . '</option>';
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
			if (!$this->auth->acl_get('u_rs_rate_negative') && $points < 0 || $points < 0 && $this->config['rs_min_rep_negative'] && ($this->user->data['user_reputation'] < $this->config['rs_min_rep_negative']) || $this->config['rs_enable_power'] && (($points > $max_voting_allowed) || ($points < -$max_voting_allowed)))
			{
				$submit = false;
				$error = $this->user->lang('RS_USER_CANNOT_RATE');

				if ($is_ajax)
				{
					$json_response = new \phpbb\json_response();
					$json_data = array(
						'comment_error' => $error,
					);
					$json_response->send($json_data);
				}
			}
		}

		if (!empty($error))
		{
			$submit = false;
		}

		if ($submit)
		{
			$data = array(
				'user_id_from'			=> $this->user->data['user_id'],
				'user_id_to'			=> $uid,
				'reputation_type'		=> 'user',
				'reputation_item_id'	=> $uid,
				'reputation_points'		=> $points,
				'reputation_comment'	=> $comment,
			);

			try
			{
				$this->reputation_manager->store_reputation($data);
			}
			catch (\pico\reputation\exception\base $e)
			{
				// Catch exception
				$error = $e->get_message($this->user);
			}

			// Prepare notification data and notify user
			$notification_data = array(
				'user_id_to'	=> $uid,
				'user_id_from'	=> $this->user->data['user_id'],
			);
			$this->reputation_manager->add_notification('pico.reputation.notification.type.rate_user', $notification_data);

			$message = $this->user->lang('RS_VOTE_SAVED');
			$json_data = array(
				'user_reputation'		=> '<strong>' . $this->reputation_manager->get_user_reputation($uid) . '</strong>',
				'success_msg'			=> $message,
			);
			$redirect = append_sid("memberlist.$this->php_ext", 'mode=viewprofile&amp;u=' . $uid);
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$this->template->assign_vars(array(
			'ERROR_MSG'					=> $error,

			'S_CONFIRM_ACTION'			=> $this->helper->route('reputation_user_rating_controller', array('uid' => $uid)),
			'S_RS_COMMENT_ENABLE'		=> $this->config['rs_enable_comment'] ? true : false,
			'S_IS_AJAX'					=> $is_ajax,

			'U_RS_REFERER'	=> $referer,
		));

		return $this->helper->render('rateuser.html', $this->user->lang('RS_USER_RATING'));
	}
}