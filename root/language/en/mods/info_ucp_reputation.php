<?php
/**
*
* @package	Reputation System
* @author	Pico88 (http://www.modsteam.tk)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'UCP_REPUTATION'				=> 'Reputation',
	'UCP_REPUTATION_FRONT'			=> 'Front page',
	'UCP_REPUTATION_LIST'			=> 'Received points',
	'UCP_REPUTATION_GIVEN'			=> 'Given points',
	'UCP_REPUTATION_SETTING'		=> 'Preferences',

	'RS_CATCHUP'						=> 'Catchup new tags',
	'RS_REPUTATION_LISTS_UCP'			=> 'This is a reputation points list. Here you find all the reputation points you have received from other members.',
	'RS_NEW'							=> 'New!',
	'RS_REPUTATION_GIVEN_LISTS_UCP'		=> 'This is a reputation points list. Here you find all the reputation points you have given to other members.',
	'RS_REPUTATION_SETTINGS_UCP'		=> 'Reputation settings',
	'RS_DEFAULT_COMMENT_POS'			=> 'Default positive comment',
	'RS_DEFAULT_COMMENT_POS_EXPLAIN'	=> 'You can define a default positive comment for post ratings.',
	'RS_DEFAULT_COMMENT_NEG'			=> 'Default negative comment',
	'RS_DEFAULT_COMMENT_NEG_EXPLAIN'	=> 'You can define a default negative comment for post ratings.',
	'RS_DEFAULT_POWER'					=> 'Default power',
	'RS_DEFAULT_POWER_EXPLAIN'			=> 'You can set the default point(s) you prefer to give.',
	'RS_EMPTY'							=> 'No default',
	'RS_DEF_POINT'						=> 'point',
	'RS_DEF_POINTS'						=> 'points',
	'RS_NOTIFICATION'					=> 'Notification',
	'RS_NOTIFICATION_EXPLAIN'			=> 'Enable notifications of new reputation points (this is independent of your other PM notification preferences).',
	'RS_DISPLAY_REPUTATIONS'			=> 'Display reputation points from previous',
));

?>
