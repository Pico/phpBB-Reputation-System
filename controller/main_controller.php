<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\controller;

use Symfony\Component\DependencyInjection\Container;

class main_controller
{
	/** @var \phpbb\request\request */
	protected $request;

	/** @var Container */
	protected $phpbb_container;

	/**
	* Constructor
	*
	* @param \phpbb\request\request $request		Request object
	* @param Container	$phpbb_container			Service container object
	* @return \pico\reputation\controller\main_controller
	* @access public
	*/
	public function __construct(\phpbb\request\request $request, Container $phpbb_container)
	{
		$this->request = $request;
		$this->phpbb_container = $phpbb_container;
	}

	/**
	* Main controller for reputation
	*	Access path: ./reputation.php
	*	It allows to use AJAX request with routing pages
	*
	* @access public
	*/
	public function display()
	{
		if ($this->request->is_set('action'))
		{
			$action_mode = $this->request->variable('action', '', true);
			$action = $this->phpbb_container->get('pico.reputation.action.controller');

			switch ($action_mode)
			{
				case 'delete':
					$rid = $this->request->variable('r', 0);
					return $action->delete($rid);
				break;

				case 'clear_post':
					$post_id = $this->request->variable('p', 0);
					return $action->clear_post($post_id);
				break;

				case 'clear_user':
					$user_id = $this->request->variable('u', 0);
					return $action->clear_user($user_id);
				break;
			}
		}

		if ($this->request->is_set('rate'))
		{
			$rate_mode = $this->request->variable('rate', '', true);
			$rate = $this->phpbb_container->get('pico.reputation.rating.controller');

			switch ($rate_mode)
			{
				case 'post':
					$mode = $this->request->variable('mode', '');
					$post_id = $this->request->variable('p', 0);
					return $rate->post($mode, $post_id);
				break;

				case 'user':
					$user_id = $this->request->variable('u', 0);
					return $rate->user($user_id);
				break;
			}
		}

		if ($this->request->is_set('details'))
		{
			$viewdetails_mode = $this->request->variable('details', '', true);
			$details = $this->phpbb_container->get('pico.reputation.details.controller');

			$sk = $this->request->variable('sk', 'id', true);
			$sd = $this->request->variable('sd', 'dsc', true);

			switch ($viewdetails_mode)
			{
				case 'post':
					$post_id = $this->request->variable('p', 0);
					return $details->postdetails($post_id, $sk, $sd);
				break;

				case 'user':
					$user_id = $this->request->variable('u', 0);
					return $details->userdetails($user_id, $sk, $sd);
				break;
			}
		}
	}
}
