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
	'RS_ANTISPAM_INFO'			=> 'Nie możesz wystawić punktów reputacji tak szybko. Spróbuj ponownie później.',
	'RS_COMMENT_TOO_LONG'		=> 'Twój komentarz zawiera %1$s znaków i jest zbyt długi.<br />Dopuszczalna ilość znaków: %2$s.',
	'RS_NEGATIVE'				=> 'Negatywny',
	'RS_NO_COMMENT'				=> 'Nie możesz zostawić pustego pola na komentarz.',
	'RS_NO_POST'				=> 'Nie ma takiego postu.',
	'RS_NO_POWER'				=> 'Twoja moc reputacji jest zbyt niska.',
	'RS_NO_POWER_LEFT'			=> 'Niewystarczająca ilość punktów mocy reputacji.<br/>Zaczekaj aż się odnowią.<br/>Twoja moc reputacji wynosi %s',
	'RS_NO_USER_ID'				=> 'Użytkownik nie istnieje.',
	'RS_POSITIVE'				=> 'Pozytywny',
	'RS_POST_RATING'			=> 'Ocena posta',
	'RS_RATE_BUTTON'			=> 'Oceń',
	'RS_SAME_POST'				=> 'Już oceniłeś ten post.<br />Otrzymał od Ciebie %s punktów reputacji.',
	'RS_SAME_USER'				=> 'Ocena użytkownika została już wystawiona.',
	'RS_SELF'					=> 'Nie możesz przyznać sobie punktów reputacji',
	'RS_USER_ANONYMOUS'			=> 'Nie możesz przyznawać punktów reputacji anonimowym użytkownikom.',
	'RS_USER_BANNED'			=> 'Nie możesz przyznawać punktów reputacji zbanowanym użytkownikom.',
	'RS_USER_CANNOT_DELETE'		=> 'Nie masz uprawnień do usunięcia reputacji.',
	'RS_USER_DISABLED'			=> 'Nie możesz przyznawać punktów reputacji.',
	'RS_USER_GAP'				=> 'Nie mozesz oceniać tego samego użytkownika tak wcześnie. Możesz spróbować ponownie za %s.',
	'RS_USER_NEGATIVE'			=> 'Nie możesz przyznawać negatywnych punktów reputacji.<br />Twoja reputacja musi być wyższa niż %s.',
	'RS_USER_RATING'			=> 'Ocena użytkownika',
	'RS_VIEW_DISALLOWED'		=> 'Nie możesz oglądać punktów reputacji.',
	'RS_VOTE_POWER_LEFT_OF_MAX'	=> '%1$d punktów mocy reputacji zostało z %2$d.<br />Maksimum na ocenę: %3$d',
	'RS_VOTE_SAVED'				=> 'Ocena zapisana',
	'RS_WARNING_RATING'			=> 'Ostrzeżenie',
));
