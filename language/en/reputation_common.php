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
	'REPUTATION'		=> 'Reputation',

	'RS_DISABLED'		=> 'Sorry, but the board administrator has disabled this feature.',

	'RS_COMMENT'		=> 'Comment',
	'RS_POINTS'			=> 'Points',

	'RS_POST_REPUTATION'	=> 'Post reputation',
	'RS_POST_RATED'			=> 'You have rated this post',
	'RS_RATE_POST_POSITIVE'	=> 'Rate post positive',
	'RS_RATE_POST_NEGATIVE'	=> 'Rate post negative',
	'RS_RATE_USER'			=> 'Rate user',
	'RS_VIEW_DETAILS'		=> 'View details',

	'NOTIFICATION_TYPE_REPUTATION'		=> 'Someone gives you reputation point',
	'NOTIFICATION_RATE_POST_POSITIVE'	=> '<strong>Rated positively</strong> by %s for post',
	'NOTIFICATION_RATE_POST_NEGATIVE'	=> '<strong>Rated negatively</strong> by %s for post',
	'NOTIFICATION_RATE_USER'			=> '<strong>Rated</strong> by %s',
));
