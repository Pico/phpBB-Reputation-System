<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\notification\type;

/**
* Reputation notifications class
* This class handles notifications for Reputation System
*
* @package notifications
*/
class rate_post_negative extends \pico\reputation\notification\type\rate_post_positive
{
	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'pico.reputation.notification.type.rate_post_negative';
	}

	/**
	* Language key used to output the text
	*
	* @var string
	*/
	protected $language_key = 'NOTIFICATION_RATE_POST_NEGATIVE';
}
