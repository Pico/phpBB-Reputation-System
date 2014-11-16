<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation;

/**
* Extension class for custom enable/disable/purge actions
*/
class ext extends \phpbb\extension\base
{
	protected $reputation_notification_types = array(
		'pico.reputation.notification.type.rate_post_positive',
		'pico.reputation.notification.type.rate_post_negative',
		'pico.reputation.notification.type.rate_user',
	);

	/**
	* Overwrite enable_step to enable reputation notifications
	* before any included migrations are installed.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				// Enable reputation notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				foreach ($this->reputation_notification_types as $reputation_notification_type)
				{
					$phpbb_notifications->enable_notifications($reputation_notification_type);
				}
				return 'notifications';
			break;
			default:
				// Run parent enable step method
				return parent::enable_step($old_state);
			break;
		}
	}
	/**
	* Overwrite disable_step to disable reputation notifications
	* before the extension is disabled.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function disable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				// Disable reputation notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				foreach ($this->reputation_notification_types as $reputation_notification_type)
				{
					$phpbb_notifications->disable_notifications($reputation_notification_type);
				}
				return 'notifications';
			break;
			default:
				// Run parent disable step method
				return parent::disable_step($old_state);
			break;
		}
	}
	/**
	* Overwrite purge_step to purge reputation notifications before
	* any included and installed migrations are reverted.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function purge_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				// Purge reputation notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				foreach ($this->reputation_notification_types as $reputation_notification_type)
				{
					$phpbb_notifications->purge_notifications($reputation_notification_type);
				}
				return 'notifications';
			break;
			default:
				// Run parent purge step method
				return parent::purge_step($old_state);
			break;
		}
	}
}
