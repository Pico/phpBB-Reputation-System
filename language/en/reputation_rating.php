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
	'RS_ANTISPAM_INFO'			=> 'You cannot give reputation points so soon. You may try again later.',
	'RS_COMMENT_TOO_LONG'		=> 'Your comment contains %1$s characters and is too long.<br />The maximum allowed characters: %2$s.',
	'RS_NEGATIVE'				=> 'Negative',
	'RS_NO_COMMENT'				=> 'You cannot leave the comment field blank.',
	'RS_NO_POST'				=> 'There is no such post.',
	'RS_NO_POWER'				=> 'Your reputation power is too low.',
	'RS_NO_POWER_LEFT'			=> 'Not enough reputation power points.<br/>Wait until they renew.<br/>Your reputation power is %s',
	'RS_NO_USER_ID'				=> 'The requested user does not exist.',
	'RS_POSITIVE'				=> 'Positive',
	'RS_POST_RATING'			=> 'Rating post',
	'RS_RATE_BUTTON'			=> 'Rate',
	'RS_SAME_POST'				=> 'You have already rated this post.<br />You gave %s reputation points.',
	'RS_SAME_USER'				=> 'You have already rated this user.',
	'RS_SELF'					=> 'You cannot give reputation points to yourself',
	'RS_USER_ANONYMOUS'			=> 'You are not allowed to give reputation points to anonymous users.',
	'RS_USER_BANNED'			=> 'You are not allowed to give reputation points to banned users.',
	'RS_USER_CANNOT_DELETE'		=> 'You do not have permission to delete that reputation.',
	'RS_USER_DISABLED'			=> 'You are not allowed to give reputation point.',
	'RS_USER_GAP'				=> 'You cannot rate the same user so soon. You can try again in %s.',
	'RS_USER_NEGATIVE'			=> 'You are not allowed to give negative reputation points.<br />Your reputation has to be higher than %s.',
	'RS_USER_RATING'			=> 'Rating user',
	'RS_VIEW_DISALLOWED'		=> 'You are not allowed to view reputation points.',
	'RS_VOTE_POWER_LEFT_OF_MAX'	=> '%1$d reputation power points left of %2$d.<br />Maximum per vote: %3$d',
	'RS_VOTE_SAVED'				=> 'Vote saved',
	'RS_WARNING_RATING'			=> 'Warning',
));
