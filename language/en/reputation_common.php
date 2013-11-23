<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
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
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, array(
	'REPUTATION'				=> 'Reputation',

	'RS_POST_REPUTATION'		=> 'Post reputation',
	'RS_RATE_POST'				=> 'Rate post',
	'RS_RATE_USER'				=> 'Rate user',
	'RS_VIEW_DETAILS'			=> 'View details',

	'RS_TOPLIST'				=> 'Reputation Toplist',
	'RS_TOPLIST_EXPLAIN'		=> 'Most popular members',

	'NOTIFICATION_REPUTATION'	=> array(
		1	=> 'You received %1$d reputation point from %2$s.',
		2	=> 'You received %1$d reputation points from %2$s.',
	),
	'NOTIFICATION_TYPE_REPUTATION'	=> 'Someone gave you a reputation point',
));

?>