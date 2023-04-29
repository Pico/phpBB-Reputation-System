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
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, array(
	'RS_TITLE'			=> 'System reputacji',

	'RS_ACTION'					=> 'Akcja',
	'RS_DATE'					=> 'Data',
	'RS_DETAILS'				=> 'Detale reputacji użytkownika',
	'RS_FROM'					=> 'Od',
	'RS_LIST'					=> 'Lista punktów reputacji użytkownika',
	'RS_POST_COUNT'				=> 'Punkty za post',
	'RS_POST_REPUTATION'		=> 'Reputacja posta',
	'RS_USER_COUNT'				=> 'Punkty od użytkownika',
	'RS_POSITIVE_COUNT'			=> 'Pozytywne',
	'RS_NEGATIVE_COUNT'			=> 'Negatywne',
	'RS_STATS'					=> 'Statystyki',
	'RS_WEEK'					=> 'Ostatni tydzień',
	'RS_MONTH'					=> 'Ostatni miesiąc',
	'RS_6MONTHS'				=> 'Ostatnie pół roku',
	'RS_POINT'					=> 'Punkt',
	'RS_POINTS_TITLE'			=> array(
		1	=> 'Punkt: %d',
		2	=> 'Punkty: %d',
		5	=> 'Punktów: %d',
	),
	'RS_POST_DELETE'			=> 'Post usunięty',
	'RS_POWER'					=> 'Moc reputacji',
	'RS_TIME'					=> 'Czas',
	'RS_TO'						=> 'do',
	'RS_TO_USER'				=> 'Do',
	'RS_VOTING_POWER'			=> 'Pozostałe punkty mocy',

	'RS_EMPTY_DATA'				=> 'Nie ma punktów reputacji.',
	'RS_NA'						=> 'n/a',
	'RS_NO_ID'					=> 'Brak ID',
	'RS_NO_REPUTATION'			=> 'Nie ma takiej reputacji.',

	'NO_REPUTATION_SELECTED'	=> 'Nie wybrałeś punktu reputacji.',

	'RS_REPUTATION_DELETE_CONFIRM'	=> 'Czy na pewno chcesz usunąć tę reputację?',
	'RS_REPUTATIONS_DELETE_CONFIRM'	=> 'Czy na pewno chcesz usunąć te reputacje?',
	'RS_POINTS_DELETED'			=> array(
		1	=> 'Reputacja została usunięta.',
		2	=> 'Reputacje zostały usunięte.',
		5	=> 'Reputacji zostało usuniętych.',
	),

	'RS_CLEAR_POST'				=> 'Wyczyść reputację postu',
	'RS_CLEAR_POST_CONFIRM'		=> 'Czy na pewno chcesz usunąć wszystkie punkty reputacji dla tego postu?',
	'RS_CLEARED_POST'			=> 'Reputacja postu została wyczyszczona.',
	'RS_CLEAR_USER'				=> 'Wyczyść reputację użytkownika',
	'RS_CLEAR_USER_CONFIRM'		=> 'Czy na pewno chcesz usunąć wszystkie punkty reputacji dla tego użytkownika?',
	'RS_CLEARED_USER'			=> 'Reputacja użytkownika została wyczyszczona.',

	'RS_LATEST_REPUTATIONS'			=> 'Ostatnie reputacje',
	'LIST_REPUTATIONS'				=> array(
		1	=> '%d reputacja',
		2	=> '%d reputacje',
		5	=> '%d reputacji',
	),
	'ALL_REPUTATIONS'				=> 'Wszystkie reputacje',

	'RS_NEW_REPUTATIONS'			=> 'Nowe punkty reputacji',
	'RS_NEW_REP'					=> 'Otrzymano <strong>1 nowy</strong> komentarz z reputacją',
	'RS_NEW_REPS'					=> 'Otrzymano <strong>nowe</strong> komentarze z reputacją w <strong>%s</strong> sztukach',
	'RS_CLICK_TO_VIEW'				=> 'Idź do otrzymanych punktów',

	'RS_MORE_DETAILS'				=> '» więcej szczegółów',

	'RS_USER_REPUTATION'			=> 'Reputacja użytkownika %s',

	'RS_VOTE_POWER_LEFT'			=> '%1$d z %2$d',

	'RS_POWER_DETAILS'				=> 'Sposób obliczania mocy reputacji',
	'RS_POWER_DETAIL_AGE'			=> 'Według daty rejestracji',
	'RS_POWER_DETAIL_POSTS'			=> 'Według liczby postów',
	'RS_POWER_DETAIL_REPUTATION'		=> 'Według reputacji',
	'RS_POWER_DETAIL_WARNINGS'		=> 'Według ostrzeżeń',
	'RS_POWER_DETAIL_MIN'			=> 'Minimum mocy reputacji dla wszystkich użytkowników',
	'RS_POWER_DETAIL_MAX'			=> 'Moc reputacji ograniczona do określonego maksimum jest włączona',
	'RS_POWER_DETAIL_GROUP_POWER'	=> 'Moc reputacji zależna od grupy użytkownika',
	'RS_GROUP_POWER'				=> 'Moc reputacji zależna od grupy użytkownika',
));
