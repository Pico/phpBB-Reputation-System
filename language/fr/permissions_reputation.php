<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com) & flyingrub (https://github.com/flyingrub)
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
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'ACL_CAT_REPUTATION'	=> 'Réputation',

	'ACL_A_REPUTATION'	=> 'Peut gérer les paramètres de réputation.',

	'ACL_M_RS_MODERATE'	=> 'Peut modérer les points de réputation.',
	'ACL_M_RS_RATE'		=> 'Peut attribuer des points de réputation supplémentaires.',

	'ACL_U_RS_DELETE'			=> 'Peut supprimer les points donnés.',
	'ACL_U_RS_RATE'				=> 'Peut noter les autres utilisateurs.',
	'ACL_U_RS_RATE_NEGATIVE'	=> 'Peut noter négativement les autres utilisateurs.<br /><em>L’utilisateur doit être en mesure de noter les autres utilisateurs avant qu’il / elle puisse noter négativement les autres utilisateurs.</em>',
	'ACL_U_RS_RATE_POST'		=> 'Peut noter les messages postés par les autres utilisateurs.',
	'ACL_U_RS_VIEW'				=> 'Peut voir la réputation.',

	'ACL_F_RS_RATE'				=> 'Peut noter les messages postés par les autres utilisateurs.',
	'ACL_F_RS_RATE_NEGATIVE'	=> 'Peut noter négativement les messages postés par les autres utilisateurs.<br /><em>L’utilisateur doit être en mesure de noter les messages postés par les autres utilisateurs avant qu’il / elle puisse noter négativement les messages postés par les autres utilisateurs.</em>',
));
