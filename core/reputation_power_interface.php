<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\core;

/**
* Reputation power
*/
interface reputation_power_interface
{
	/**
	* Function returns maximum reputation power of one user
	*
	* @param int $posts User posts
	* @param timestamp $regdate User registration date
	* @param int $reputation User reputation
	* @param int $warnings User warnings
	* @param int $user_group_id User group ID
	* @return int User power reputation
	* @access public
	*/
	public function get($posts, $regdate, $reputation, $warnings, $user_group_id);

	/**
	* Function returns an array explaining structure of the user reputation power
	*
	* @return array
	* @access public
	*/
	public function explain();

	/**
	* Function returns used reputation power by an user
	*
	* @param $user_id User ID
	* @return int Used reputation power
	* @access public
	*/
	public function used($user_id);
}
