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

use Symfony\Component\DependencyInjection\Container;

/**
* Reputation manager
*
* This class consists all common methods for reputations
*/
interface reputation_manager_interface
{
	/**
	* Get reputation type id from string
	*
	* @param string $type_string
	* @return $type_id
	* @access public
	*/
	public function get_reputation_type_id($type_string);

	/**
	* Get the reputation types
	*
	* @return array Reputation types
	* @access public
	*/
	public function get_reputation_types();

	/**
	* The main function for recording reputation vote.
	*
	* @param array $data Reputation data
	* @access public
	* @return null
	*/
	public function store_reputation($data);

	/**
	* Response method for displaying reputation messages
	*
	* @param string $message_lang Message user lang
	* @param array $json_data Json data for ajax request
	* @param string $redirect_link Redirect link
	* @param string $redirect_text Redirect text
	* @param bool $is_ajax Ajax request
	* @access public
	* @return string
	*/
	public function response($message_lang, $json_data, $redirect_link, $redirect_text, $is_ajax);

	/**
	* Return post reputation
	*
	* @param int $post_id Post ID
	* @access public
	* @return int post reputation
	*/
	public function get_post_reputation($post_id);

	/**
	* Return user reputation
	*
	* @param int $user_id User ID
	* @access public
	* @return int user reputation
	*/
	public function get_user_reputation($user_id);

	/**
	* Prevent overrating one user by another user
	*
	* @param int $user_id User ID
	* @access public
	* @return bool
	*/
	public function prevent_rating($user_id);
}
