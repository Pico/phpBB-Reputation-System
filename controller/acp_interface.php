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
* Interface for reputation acp controller
*
* This describes all of the public methods which are used for the admin front-end of this extension
*/
interface acp_interface
{
	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action);

	/**
	* Display reputation overview
	*
	* @return null
	* @access public
	*/
	public function display_overview();

	/**
	* Manage the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function manage_options();

	/**
	* Rate user
	*
	* @return null
	* @access public
	*/
	public function rate_user();
}
