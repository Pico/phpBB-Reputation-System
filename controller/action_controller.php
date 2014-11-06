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

class action_controller
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var \pico\reputation\core\reputation_helper */
	protected $reputation_helper;

	/** @var \pico\reputation\core\reputation_manager */
	protected $reputation_manager;

	/** @var string The table we use to store our reputations */
	protected $reputations_table;

	/** @var string The database table the reputation types are stored */
	protected $reputation_types_table;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth						Auth object
	* @param \phpbb\controller\helper					Controller helper object
	* @param \phpbb\db\driver\driver $db				Database object
	* @param \phpbb\request\request $request			Request object
	* @param \phpbb\user $user							User object
	* @param \pico\reputation\core\reputation_helper	Reputation helper object
	* @param \pico\reputation\core\reputation_manager	Reputation manager object
	* @param string $reputations_table					Name of the table used to store reputations data
	* @param string $root_path							phpBB root path
	* @param string $php_ext							phpEx
	* @return \pico\reputation\controller\rating_controller
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\controller\helper $helper, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\user $user, \pico\reputation\core\reputation_helper $reputation_helper, \pico\reputation\core\reputation_manager $reputation_manager, $reputations_table, $reputation_types_table, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->db = $db;
		$this->helper = $helper;
		$this->request = $request;
		$this->user = $user;
		$this->reputation_helper = $reputation_helper;
		$this->reputation_manager = $reputation_manager;
		$this->reputations_table = $reputations_table;
		$this->reputation_types_table = $reputation_types_table;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Delete reputation page/action
	*
	* @param int $rid	Reputation ID taken from the URL
	* @return null
	* @access public
	*/
	public function delete($rid)
	{
		$this->user->add_lang_ext('pico/reputation', 'reputation_system');
		$is_ajax = $this->request->is_ajax();
		$submit = false;

		$post_type_id = (int) $this->reputation_manager->get_reputation_type_id('post');

		$sql_array = array(
			'SELECT'	=> 'r.*, rt.reputation_type_name, p.post_id, uf.username AS username_from, ut.username AS username_to',
			'FROM'		=> array(
				$this->reputations_table => 'r',
				$this->reputation_types_table => 'rt',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(POSTS_TABLE => 'p'),
					'ON'	=> 'p.post_id = r.reputation_item_id
						AND r.reputation_type_id = ' . $post_type_id,
				),
				array(
					'FROM'	=> array(USERS_TABLE => 'uf'),
					'ON'	=> 'r.user_id_from = uf.user_id ',
				),
				array(
					'FROM'	=> array(USERS_TABLE => 'ut'),
					'ON'	=> 'r.user_id_to = ut.user_id ',
				),
			),
			'WHERE'	=> 'r.reputation_id = ' . $rid,
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		//We couldn't find this reputation. May be it was deleted meanwhile?
		if (empty($row))
		{
			$message = $this->user->lang('RS_NO_REPUTATION');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if ($this->request->is_set_post('cancel'))
		{
			redirect($this->helper->route('reputation_details_controller', array('uid' => $row['user_id_to'])));
		}

		if ($this->auth->acl_gets('m_rs_moderate') || (($row['user_id_from'] == $this->user->data['user_id']) && $this->auth->acl_get('u_rs_delete')))
		{
			if ($is_ajax)
			{
				$submit = true;
			}
			else
			{
				$s_hidden_fields = build_hidden_fields(array(
					'r'		=> $rid,
				));

				if (confirm_box(true))
				{
					$submit = true;
				}
				else
				{
					confirm_box(false, $this->user->lang('RS_REPUTATION_DELETE_CONFIRM'), $s_hidden_fields);
				}
			}
		}
		else
		{
			$message = $this->user->lang('RS_USER_CANNOT_DELETE');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = $this->helper->route('reputation_details_controller', array('uid' => $row['user_id_to']));
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if ($submit)
		{
			try
			{
				$this->reputation_manager->delete_reputation($row);
			}
			catch (\pico\reputation\exception\base $e)
			{
				// Catch exception
				trigger_error($e->get_message($this->user));
			}

			$user_reputation = $this->reputation_manager->get_user_reputation($row['user_id_to']);

			$message = $this->user->lang('RS_POINTS_DELETED');
			$json_data = array(
				'rid'					=> $rid,
				'user_reputation'		=> $user_reputation,
			);

			if (isset($row['post_id']))
			{
				$post_reputation = $this->reputation_manager->get_post_reputation($row['post_id']);

				$json_data = array_merge($json_data, array(
					'poster_id'				=> $row['user_id_to'],
					'post_id'				=> $row['post_id'],
					'post_reputation'		=> $post_reputation,
					'reputation_class'		=> $this->reputation_helper->reputation_class($post_reputation),
					'own_vote'				=> ($row['user_id_from'] == $this->user->data['user_id']) ? true : false,
				));
			}

			$redirect = $this->helper->route('reputation_details_controller', array('uid' => $row['user_id_to']));
			$redirect_text = 'RETURN_PAGE';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}
	}

	/**
	* Clear post reputation
	*
	* @param int $post_id	Post ID
	* @return null
	* @access public
	*/
	public function clear_post($post_id)
	{
		$this->user->add_lang_ext('pico/reputation', 'reputation_system');
		$is_ajax = $this->request->is_ajax();
		$submit = false;
		$post_type_id = (int) $this->reputation_manager->get_reputation_type_id('post');

		$sql_array = array(
			'SELECT'	=> 'r.*, p.post_subject, p.post_reputation, ut.username AS username_to',
			'FROM'		=> array(
				$this->reputations_table => 'r',
				POSTS_TABLE => 'p',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'ut'),
					'ON'	=> 'r.user_id_to = ut.user_id ',
				),
			),
			'WHERE'	=> 'r.reputation_item_id = ' . $post_id . '
				AND r.reputation_type_id = ' . $post_type_id . '
				AND p.post_id = r.reputation_item_id',
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		//We couldn't find this reputation. May be it was deleted meanwhile?
		if (empty($row))
		{
			$message = $this->user->lang('RS_NO_REPUTATION');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$redirect = $this->helper->route('reputation_post_details_controller', array('post_id' => $post_id));

		if ($this->request->is_set_post('cancel'))
		{
			redirect($redirect);
		}

		$redirect_text = 'RETURN_PAGE';

		if ($this->auth->acl_gets('m_rs_moderate'))
		{
			if ($is_ajax)
			{
				$submit = true;
			}
			else
			{
				$s_hidden_fields = build_hidden_fields(array(
					'p'		=> $post_id,
				));

				if (confirm_box(true))
				{
					$submit = true;
				}
				else
				{
					confirm_box(false, $this->user->lang('RS_CLEAR_POST_CONFIRM'), $s_hidden_fields);
				}
			}
		}
		else
		{
			$message = $this->user->lang('RS_USER_CANNOT_DELETE');
			$json_data = array(
				'error_msg' => $message,
			);

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if ($submit)
		{
			try
			{
				$this->reputation_manager->clear_post_reputation($post_id, $row);
			}
			catch (\pico\reputation\exception\base $e)
			{
				// Catch exception
				trigger_error($e->get_message($this->user));
			}

			$message = $this->user->lang('RS_CLEARED_POST');
			$json_data = array(
				'clear_post'			=> true,
				'post_id'				=> $post_id,
				'poster_id'				=> $row['user_id_to'],
				'user_reputation'		=> $this->reputation_manager->get_user_reputation($row['user_id_to']),
				'post_reputation'		=> 0,
				'reputation_class'		=> 'neutral',
			);

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}
	}

	/**
	* Clear user reputation
	*
	* @param int $uid	User ID
	* @return null
	* @access public
	*/
	public function clear_user($uid)
	{
		$this->user->add_lang_ext('pico/reputation', 'reputation_system');
		$is_ajax = $this->request->is_ajax();
		$submit = false;

		$sql_array = array(
			'SELECT'	=> 'r.*, ut.username AS username_to',
			'FROM'		=> array(
				$this->reputations_table => 'r',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'ut'),
					'ON'	=> 'r.user_id_to = ut.user_id ',
				),
			),
			'WHERE'	=> 'r.user_id_to = ' . $uid
		);
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		//We couldn't find this reputation. May be it was deleted meanwhile?
		if (empty($row))
		{
			$message = $this->user->lang('RS_NO_REPUTATION');
			$json_data = array(
				'error_msg' => $message,
			);
			$redirect = append_sid("{$this->root_path}index.$this->php_ext");
			$redirect_text = 'RETURN_INDEX';

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		$redirect = $this->helper->route('reputation_details_controller', array('uid' => $uid));

		if ($this->request->is_set_post('cancel'))
		{
			redirect($redirect);
		}

		$post_ids = array();
		$post_type_id = (int) $this->reputation_manager->get_reputation_type_id('post');

		$sql = 'SELECT reputation_item_id
			FROM ' . $this->reputations_table . "
			WHERE user_id_to = {$uid}
				AND reputation_type_id = {$post_type_id}
			GROUP BY reputation_item_id";
		$result = $this->db->sql_query($sql);

		while ($post_row = $this->db->sql_fetchrow($result))
		{
			$post_ids[] = $post_row['reputation_item_id'];
		}
		$this->db->sql_freeresult($result);

		$redirect_text = 'RETURN_PAGE';

		if ($this->auth->acl_gets('m_rs_moderate'))
		{
			if ($is_ajax)
			{
				$submit = true;
			}
			else
			{
				$s_hidden_fields = build_hidden_fields(array(
					'u'		=> $uid,
				));

				if (confirm_box(true))
				{
					$submit = true;
				}
				else
				{
					confirm_box(false, $this->user->lang('RS_CLEAR_POST_CONFIRM'), $s_hidden_fields);
				}
			}
		}
		else
		{
			$message = $this->user->lang('RS_USER_CANNOT_DELETE');
			$json_data = array(
				'error_msg' => $message,
			);

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}

		if ($submit)
		{
			try
			{
				$this->reputation_manager->clear_user_reputation($uid, $row, $post_ids);
			}
			catch (\pico\reputation\exception\base $e)
			{
				// Catch exception
				trigger_error($e->get_message($this->user));
			}

			$message = $this->user->lang('RS_CLEARED_USER');
			$json_data = array(
				'clear_user'			=> true,
				'post_ids'				=> $post_ids,
				'poster_id'				=> $uid,
				'user_reputation'		=> 0,
				'post_reputation'		=> 0,
				'reputation_class'		=> 'neutral',
			);

			$this->reputation_manager->response($message, $json_data, $redirect, $redirect_text, $is_ajax);
		}
	}
}
