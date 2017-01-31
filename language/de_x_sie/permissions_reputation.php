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
	'ACL_CAT_REPUTATION'	=> 'Reputation',

	'ACL_A_REPUTATION'	=> 'Kann Reputationseinstellungen verwalten',

	'ACL_M_RS_MODERATE'	=> 'Kann Reputationspunkte moderieren',
	'ACL_M_RS_RATE'		=> 'Kann zusätzliche Reputationspunkte verteilen',

	'ACL_U_RS_DELETE'			=> 'Kann Bewertungen löschen',
	'ACL_U_RS_RATE'				=> 'Kann andere Benutzer bewerten',
	'ACL_U_RS_RATE_NEGATIVE'	=> 'Kann andere Benutzer negativ bewerten<br /><em>Benutzer muss die Möglichkeit zur Bewertung anderer Benutzer haben, um andere Benutzer negativ bewerten zu können.</em>',
	'ACL_U_RS_RATE_POST'		=> 'Kann die Beiträge anderer Benutzer bewerten',
	'ACL_U_RS_VIEW'				=> 'Kann die Reputation anzeigen',

	'ACL_F_RS_RATE'				=> 'Kann die Beiträge anderer Benutzer bewerten',
	'ACL_F_RS_RATE_NEGATIVE'	=> 'Kann die Beiträge anderer Benutzer negativ bewerten<br /><em>Benutzer muss die Möglichkeit zur Bewertung von Beiträgen haben, um Beiträge negativ bewerten zu können.</em>',
));
