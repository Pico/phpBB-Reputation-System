<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class viewtopic_listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \pico\reputation\core\reputation_helper */
	protected $reputation_helper;

	/** @var string The table we use to store reputations */
	protected $reputations_table;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth                           $auth               Auth object
	* @param \phpbb\config\config                       $config             Config object
	* @param \phpbb\controller\helper                   $helper             Controller helper object
	* @param \phpbb\template\template                   $template           Template object
	* @param \phpbb\user                                $user               User object
	* @param \pico\reputation\core\reputation_helper    $reputation_helper  Reputation helper object
	* @param string                                     $reputations_table  Name of the table used to store reputations data
	* @return \pico\reputation\event\viewtopic_listener
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \pico\reputation\core\reputation_helper $reputation_helper,  $reputations_table)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->reputation_helper = $reputation_helper;
		$this->reputations_table = $reputations_table;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_assign_template_vars_before'	=> 'assign_reputation',
			'core.viewtopic_get_post_data'					=> 'modify_sql_array',
			'core.viewtopic_post_rowset_data'				=> 'post_rowset_reputation_data',
			'core.viewtopic_cache_guest_data'				=> 'cache_reputation_data',
			'core.viewtopic_cache_user_data'				=> 'cache_reputation_data',
			'core.viewtopic_modify_post_row'				=> 'post_row_reputation',
		);
	}

	/**
	* Add global template var for reputation in forums
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function assign_reputation($event)
	{
		if ($this->config['rs_enable'])
		{
			$topic_data = $event['topic_data'];

			// Author note
			//	Post rating is not allowed in the global announcements
			//	because there is no option to set proper permissions for such topics
			$this->template->assign_vars(array(
				'S_FORUM_REPUTATION'	=> ($topic_data['reputation_enabled'] && $this->config['rs_post_rating'] && ($topic_data['topic_type'] != POST_GLOBAL)) ? true : false,

				'U_REPUTATION_REFERER'	=> $this->helper->get_current_url(),
			));
		}
	}

	/**
	* Modify sql array by adding reputations table
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function modify_sql_array($event)
	{
		// Modify sql array when the extension and its post rating are enabled
		// Otherwise do nothing
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$sql_ary = $event['sql_ary'];

			$sql_ary['LEFT_JOIN'][] = array(
				'FROM'	=> array($this->reputations_table => 'r'),
				'ON'	=> 'r.reputation_item_id = p.post_id
					AND r.reputation_type_id = 1
					AND r.user_id_from =' . $this->user->data['user_id'],
			);
			$sql_ary['SELECT'] .= ', r.reputation_id, r.reputation_points';

			$event['sql_ary'] = $sql_ary;
		}
	}

	/**
	* Add reputation data to rowset
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function post_rowset_reputation_data($event)
	{
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$rowset_data = $event['rowset_data'];
			$row = $event['row'];

			$rowset_data = array_merge($rowset_data, array(
				'post_reputation'	=> $row['post_reputation'],
				'user_voted'		=> $row['reputation_id'],
				'reputation_points'	=> $row['reputation_points'],
			));

			$event['rowset_data'] = $rowset_data;
		}
	}

	/**
	* Add guest user's and user's data to display their reputation
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function cache_reputation_data($event)
	{
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$user_cache_data = $event['user_cache_data'];
			$user_cache_data['reputation'] = $event['row']['user_reputation'];
			$event['user_cache_data'] = $user_cache_data;
		}
	}

	/**
	* Add post row data for displaying reputation
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function post_row_reputation($event)
	{
		if ($this->config['rs_enable'] && $this->config['rs_post_rating'])
		{
			$row = $event['row'];
			$user_poster_data = $event['user_poster_data'];
			$post_row = $event['post_row'];
			$post_id = $row['post_id'];
			$poster_id = $event['poster_id'];

			// Check post status and return its status as class
			//	Is it own post, rated good or rated bad?
			if ($this->user->data['user_id'] == $poster_id)
			{
				$post_vote_class = 'own';
			}
			else
			{
				$post_vote_class = $row['user_voted'] ? (($row['reputation_points'] > 0) ? 'rated_good' : 'rated_bad') : '';
			}

			$post_row = array_merge($post_row, array(
				'S_VIEW_REPUTATION'		=> ($this->auth->acl_get('u_rs_view')) ? true : false,
				'S_RATE_POST'			=> ($this->auth->acl_get('f_rs_rate', $row['forum_id']) && $this->auth->acl_get('u_rs_rate_post') && $poster_id != ANONYMOUS) ? true : false,
				'S_RATE_POST_NEGATIVE'	=> ($this->auth->acl_get('f_rs_rate_negative', $row['forum_id']) && $this->config['rs_negative_point']) ? true : false,

				'RS_RATE_POST_NEGATIVE'	=> $row['user_voted'] ? $this->user->lang('RS_POST_RATED') : $this->user->lang('RS_RATE_POST_NEGATIVE'),
				'RS_RATE_POST_POSITIVE'	=> $row['user_voted'] ? $this->user->lang('RS_POST_RATED') : $this->user->lang('RS_RATE_POST_POSITIVE'),

				'U_RATE_POST_POSITIVE'		=> $this->helper->route('reputation_post_rating_controller', array('mode' => 'positive', 'post_id' => $post_id)),
				'U_RATE_POST_NEGATIVE'		=> $this->helper->route('reputation_post_rating_controller', array('mode' => 'negative', 'post_id' => $post_id)),
				'U_VIEW_POST_REPUTATION'	=> $this->helper->route('reputation_post_details_controller', array('post_id' => $post_id)),
				'U_VIEW_USER_REPUTATION'	=> $this->helper->route('reputation_user_details_controller', array('uid' => $poster_id)),

				'POST_REPUTATION'		=> $row['post_reputation'],
				'POST_REPUTATION_CLASS'	=> $this->reputation_helper->reputation_class($row['post_reputation']),
				'POST_VOTE_CLASS'		=> $post_vote_class,
				'USER_REPUTATION'		=> $user_poster_data['reputation'],
			));

			$event['post_row'] = $post_row;
		}
	}
}
