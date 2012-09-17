<?php
/**
*
* @package	Reputation System
* @author	Pico88 (http://www.modsteam.tk)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
	'MCP_REPUTATION'				=> 'Reputacja',
	'MCP_REPUTATION_FRONT'			=> 'Przegląd',
	'MCP_REPUTATION_LIST'			=> 'Lista reputacji',
	'MCP_REPUTATION_GIVE'			=> 'Przyznaj punkt',

	'MCP_RS_REP_POINT'				=> 'Punkty reputacji za ostrzeżenie',
	'MCP_RS_GIVE_REP_POINT'			=> 'Przyznaj punkty',

	'LOG_USER_REP_DELETE'			=> '<strong>Usunięto punkt reputacji</strong><br />Użytkownik: %s',

	'RS_BEST_REPUTATION'			=> 'Użytkownicy z najlepszą reputacją',
	'RS_WORST_REPUTATION'			=> 'Użytkownicy z najgorszą reputacją',
	'RS_REPUTATION_LISTS'			=> 'To jest lista z punktami reputacji. Tutaj znajdziesz wszystkie punkty reputacji jakie zostały udzielone na forum. Możesz użyć różnych filtrów, aby zawęzić obszar wyszukiwań. Możesz także szukać punktów przyznanych przez konkretnego lub konkretnemu użytkownikowi, jak i pomiędzy nimi.',
	'RS_SEARCH_FROM'				=> 'Szukaj punktów przyznanych przez',
	'RS_SEARCH_TO'				  	=> 'Szukaj punktów otrzymanych przez',
	'RS_DISPLAY_REPUTATIONS'		=> 'Wyświetl punkty nie starsze niż',
));

?>
