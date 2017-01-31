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
	'REPUTATION'		=> 'Reputacja',

	'RS_DISABLED'		=> 'Administrator forum wyłączył tę funkcjonalność.',

	'RS_COMMENT'		=> 'Komentarz',
	'RS_POINTS'			=> 'Punkty',

	'RS_POST_REPUTATION'	=> 'Reputacja posta',
	'RS_POST_RATED'			=> 'Już oceniłeś ten post',
	'RS_RATE_POST_POSITIVE'	=> 'Oceń pozytywnie',
	'RS_RATE_POST_NEGATIVE'	=> 'Oceń negatywnie',
	'RS_RATE_USER'			=> 'Oceń użytkownika',
	'RS_VIEW_DETAILS'		=> 'Wyświetl detale',

	'NOTIFICATION_TYPE_REPUTATION'		=> 'Ktoś podarował Ci punkt reputacji',
	'NOTIFICATION_RATE_POST_POSITIVE'	=> '%s <strong>ocenił(a) pozytywnie</strong> post',
	'NOTIFICATION_RATE_POST_NEGATIVE'	=> '%s <strong>ocenił(a) negatywnie</strong> post',
	'NOTIFICATION_RATE_USER'			=> ' %s<strong>ocenił(a)</strong>',
));
