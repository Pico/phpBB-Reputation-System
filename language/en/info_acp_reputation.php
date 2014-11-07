<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
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
	'ACP_REPUTATION_SYSTEM'				=> 'Reputation System',
	'ACP_REPUTATION_OVERVIEW'			=> 'Overview',
	'ACP_REPUTATION_SETTINGS'			=> 'Settings',
	'ACP_REPUTATION_RATE'				=> 'Rate',
	'ACP_REPUTATION_SYNC'				=> 'Synchronise',

	'RS_FORUM_REPUTATION'			=> 'Enable post rating (reputation)',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Allow members to rate posts made by other users in that forum.',

	'RS_GROUP_POWER'				=> 'Group reputation power',
	'RS_GROUP_POWER_EXPLAIN'		=> 'If this field is filled, the reputation power of members will be overwritten and will not be based on posts etc.',

	'LOG_REPUTATION_DELETED'		=> '<strong>Deleted reputation</strong><br />From user: %1$s<br />To user: %2$s<br />Points: %3$s<br/>Type: %4$s<br/>Item ID: %5$s',
	'LOG_POST_REPUTATION_CLEARED'	=> '<strong>Cleared post reputation</strong><br />Post author: %1$s<br />Post subject: %2$s',
	'LOG_USER_REPUTATION_CLEARED'	=> '<strong>Cleared user reputation</strong><br />User: %1$s',
	'LOG_REPUTATION_SYNC'			=> '<strong>Reputation System resynchronised</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Cleared reputations</strong>',
));
