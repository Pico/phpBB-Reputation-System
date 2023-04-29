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
	'RS_TITLE'			=> 'Reputationssystem',

	'RS_ACTION'					=> 'Aktion',
	'RS_DATE'					=> 'Datum',
	'RS_DETAILS'				=> 'Benutzerbewertungsdetails',
	'RS_FROM'					=> 'Von',
	'RS_LIST'					=> 'Benutzerbewertungsliste',
	'RS_POST_COUNT'				=> 'Punkte für Beitrag',
	'RS_POST_REPUTATION'		=> 'Beitragsbewertung',
	'RS_USER_COUNT'				=> 'Punkte von Benutzer',
	'RS_POSITIVE_COUNT'			=> 'Positiv',
	'RS_NEGATIVE_COUNT'			=> 'Negativ',
	'RS_STATS'					=> 'Statistiken',
	'RS_WEEK'					=> 'Letzte Woche',
	'RS_MONTH'					=> 'Letzer Monat',
	'RS_6MONTHS'				=> 'Letzte 6 Monate',
	'RS_POINT'					=> 'Punkt',
	'RS_POINTS_TITLE'			=> array(
		1	=> 'Punkt: %d',
		2	=> 'Punkte: %d',
	),
	'RS_POST_DELETE'			=> 'Beitrag gelöscht',
	'RS_POWER'					=> 'Reputation',
	'RS_TIME'					=> 'Zeit',
	'RS_TO'						=> 'an',
	'RS_TO_USER'				=> 'An',
	'RS_VOTING_POWER'			=> 'Restliche Reputationspunkte',

	'RS_EMPTY_DATA'				=> 'Es gibt keine Bewertungspunkte.',
	'RS_NA'						=> '--',
	'RS_NO_ID'					=> 'Keine ID',
	'RS_NO_REPUTATION'			=> 'Diese Bewertung gibt es nicht.',

	'NO_REPUTATION_SELECTED'	=> 'Sie haben keinen Bewertungspunkt ausgewählt.',

	'RS_REPUTATION_DELETE_CONFIRM'	=> 'Wollen Sie diese Bewertung wirklich löschen?',
	'RS_REPUTATIONS_DELETE_CONFIRM'	=> 'Wollen Sie diese Bewertungen wirklich löschen?',
	'RS_POINTS_DELETED'			=> array(
		1	=> 'Die Bewertung wurde gelöscht.',
		2	=> 'Die Bewertungen wurden gelöscht.',
	),

	'RS_CLEAR_POST'				=> 'Beitragsbewertung löschen',
	'RS_CLEAR_POST_CONFIRM'		=> 'Wollen Sie wirklich alle Bewertungspunkte für diesen Beitrag löschen?',
	'RS_CLEARED_POST'			=> 'Die Beitragsbewertung wurde gelöscht.',
	'RS_CLEAR_USER'				=> 'Benutzerbewertung löschen',
	'RS_CLEAR_USER_CONFIRM'		=> 'Wollen Sie wirklich alle Bewertungspunkte für diesen Benutzer löschen?',
	'RS_CLEARED_USER'			=> 'Die Benutzerbewertung wurde gelöscht.',

	'RS_LATEST_REPUTATIONS'			=> 'Die letzten Bewertungen',
	'LIST_REPUTATIONS'				=> array(
		1	=> '%d Bewertung',
		2	=> '%d Bewertungen',
	),
	'ALL_REPUTATIONS'				=> 'Alle Bewertungen',

	'RS_NEW_REPUTATIONS'			=> 'Neue Bewertungspunkte',
	'RS_NEW_REP'					=> 'Sie haben <strong>einen neuen</strong> Bewertungskommentar',
	'RS_NEW_REPS'					=> 'Sie haben <strong>%s neue</strong> Bewertungskommentare',
	'RS_CLICK_TO_VIEW'				=> 'Zu erhaltenen Punkten gehen',

	'RS_MORE_DETAILS'				=> '» mehr Details',

	'RS_USER_REPUTATION'			=> '%ss Bewertung',

	'RS_VOTE_POWER_LEFT'			=> '%1$d von %2$d',

	'RS_POWER_DETAILS'				=> 'Wie die Reputation berechnet wird',
	'RS_POWER_DETAIL_AGE'			=> 'Durch Registrierungsdatum',
	'RS_POWER_DETAIL_POSTS'			=> 'Durch die Anzahl der Beiträge',
	'RS_POWER_DETAIL_REPUTATION'		=> 'Durch die Reputation',
	'RS_POWER_DETAIL_WARNINGS'		=> 'Durch Warnungen',
	'RS_POWER_DETAIL_MIN'			=> 'Minimale Reputation für alle Benutzer',
	'RS_POWER_DETAIL_MAX'			=> 'Reputation kann nach oben beschränkt werden',
	'RS_POWER_DETAIL_GROUP_POWER'	=> 'Reputation basierend auf Benutzergruppe',
	'RS_GROUP_POWER'				=> 'Reputation basierend auf Benutzergruppe',
));
