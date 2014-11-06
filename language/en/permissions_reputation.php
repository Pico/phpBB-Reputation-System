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

	'ACL_A_REPUTATION'	=> 'Can manage reputation settings',

	'ACL_M_RS_MODERATE'	=> 'Can moderate reputation points',
	'ACL_M_RS_RATE'		=> 'Can award additional reputation points',

	'ACL_U_RS_DELETE'			=> 'Can delete given points',
	'ACL_U_RS_RATE'				=> 'Can rate other users',
	'ACL_U_RS_RATE_NEGATIVE'	=> 'Can negatively rate other users<br /><em>User has to be able to rate other users before he/she can negatively rate other users.</em>',
	'ACL_U_RS_RATE_POST'		=> 'Can rate posts made by other users',
	'ACL_U_RS_VIEW'				=> 'Can view reputation',

	'ACL_F_RS_RATE'				=> 'Can rate posts made by other users',
	'ACL_F_RS_RATE_NEGATIVE'	=> 'Can negatively rate posts made by other users<br /><em>User has to be able to rate posts before he/she can negatively rate posts made by other users.</em>',
));
