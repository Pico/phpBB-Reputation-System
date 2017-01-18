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
	'ACL_CAT_REPUTATION'	=> 'Reputacja',

	'ACL_A_REPUTATION'	=> 'Zarządza ustawieniami systemu reputacji',

	'ACL_M_RS_MODERATE'	=> 'Moderuje punkty reputacji',
	'ACL_M_RS_RATE'		=> 'Przyznaje dodatkowe punkty reputacji',

	'ACL_U_RS_DELETE'			=> 'Usuwa przyznane punkty',
	'ACL_U_RS_RATE'				=> 'Ocenia innych użytkowników',
	'ACL_U_RS_RATE_NEGATIVE'	=> 'Negatywnie ocenia innych użytkowników<br /><em>Użytkownik musi móc oceniać innych użytkowników, zanim negatywnie oceni innych użytkowników.</em>',
	'ACL_U_RS_RATE_POST'		=> 'Ocenia posty napisane przez innych użytkowników',
	'ACL_U_RS_VIEW'				=> 'Wyświetla punkty reputacji',

	'ACL_F_RS_RATE'				=> 'Ocenia posty innych użytkowników',
	'ACL_F_RS_RATE_NEGATIVE'	=> 'Negatywnie oceniać posty innych użytkowników<br /><em>Użytkownik musi móc oceniać posty innych użytkowników, zanim negatywnie oceni posty innych użytkowników.</em>',
));
