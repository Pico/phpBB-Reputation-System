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
	'MCP_RS_ADD_WARNING'			=> 'Punkty reputacji za ostrzeżenie',
	'MCP_RS_ADD_WARNING_EXPLAIN'	=> 'Możesz dać negatywne punkty reputacji temu użytkownikowi za złe zachowanie itp. To będzie dizałać tylko jeżeli zaznaczyłeś opcję poniżej.',
	'MCP_RS_ADD_REPUTATION'			=> 'Dodaj reputację',

	'MCP_RS_POINTS'	=> array(
		1	=> '-%d punkt',
		2	=> '-%d punkty',
		5	=> '-%d punktów',
	),
));
