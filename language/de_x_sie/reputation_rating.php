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
	'RS_ANTISPAM_INFO'			=> 'Sie können nicht so schnell Bewertungen vergeben. Versuchen Sie es später erneut.',
	'RS_COMMENT_TOO_LONG'		=> 'Ihr Kommenator enthält %1$s Zeichen und ist zu lang.<br />Das Maximum der erlaubten Zeichen ist: %2$s.',
	'RS_NEGATIVE'				=> 'Negativ',
	'RS_NO_COMMENT'				=> 'Sie können das Kommentarfeld nicht leer belassen.',
	'RS_NO_POST'				=> 'Dieser Beitrag existiert nicht.',
	'RS_NO_POWER'				=> 'Ihre Reputation ist zu niedrig.',
	'RS_NO_POWER_LEFT'			=> 'Nicht genug Reputationspunkte.<br/>Warten Sie bis sie erneuert werden.<br/>Ihre Reputation ist %s',
	'RS_NO_USER_ID'				=> 'Der angefragte Benutzer existiert nicht.',
	'RS_POSITIVE'				=> 'Positiv',
	'RS_POST_RATING'			=> 'Beitragsbewertung',
	'RS_RATE_BUTTON'			=> 'Bewerten',
	'RS_SAME_POST'				=> 'Sie haben diesen Beitrag bereits bewertet.<br />Sie haben %s Punkte vergeben.',
	'RS_SAME_USER'				=> 'Sie haben diesen Benutzer bereits bewertet',
	'RS_SELF'					=> 'Sie können sich nicht selbst bewerten',
	'RS_USER_ANONYMOUS'			=> 'Sie können keine anonymen Benutzer bewerten.',
	'RS_USER_BANNED'			=> 'Sie können keine gesperrten Benutzer bewerten.',
	'RS_USER_CANNOT_DELETE'		=> 'Sie können diese Bewertung nicht löschen.',
	'RS_USER_DISABLED'			=> 'Sie können keine Bewertungen vergeben.',
	'RS_USER_GAP'				=> 'Sie können denselben Benutzer nicht so schnell hintereinander bewerten. Versuchen Sie es in %s.',
	'RS_USER_NEGATIVE'			=> 'Sie können keine negativen Bewertungen vergeben.<br />Ihre Reputation muss höher als %s sein.',
	'RS_USER_RATING'			=> 'Benutzerbewertung',
	'RS_VIEW_DISALLOWED'		=> 'Sie dürfen keine Reputationspunkte sehen.',
	'RS_VOTE_POWER_LEFT_OF_MAX'	=> '%1$d Reputationspunkte von %2$d übrig.<br />Maximum pro Abstimmung: %3$d',
	'RS_VOTE_SAVED'				=> 'Stimme gespeichert',
	'RS_WARNING_RATING'			=> 'Warnung',
));
