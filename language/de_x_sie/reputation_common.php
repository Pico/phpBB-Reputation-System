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

	'RS_DISABLED'		=> 'Diese Funktion wurde vom Administator deaktiviert.',

	'RS_COMMENT'		=> 'Kommentar',
	'RS_POINTS'			=> 'Punkte',

	'RS_POST_REPUTATION'	=> 'Beitragsbewertung',
	'RS_POST_RATED'			=> 'Sie haben diesen Beitrag bewertet',
	'RS_RATE_POST_POSITIVE'	=> 'Beitrag positiv bewerten',
	'RS_RATE_POST_NEGATIVE'	=> 'Beitrag negativ bewerten',
	'RS_RATE_USER'			=> 'Benutzer bewerten',
	'RS_VIEW_DETAILS'		=> 'Details zeigen',

	'NOTIFICATION_TYPE_REPUTATION'		=> 'Jemand hat Ihnen eine Bewertung gegeben',
	'NOTIFICATION_RATE_POST_POSITIVE'	=> '<strong>Positiv bewertet</strong> durch %s für einen Beitrag',
	'NOTIFICATION_RATE_POST_NEGATIVE'	=> '<strong>Negativ bewertet</strong> durch %s für einen Beitrag',
	'NOTIFICATION_RATE_USER'			=> '<strong>Bewertet</strong> durch %s',
));
