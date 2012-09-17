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
	'UCP_REPUTATION'				=> 'Reputacja',
	'UCP_REPUTATION_FRONT'			=> 'Przegląd',
	'UCP_REPUTATION_LIST'			=> 'Lista otrzymanych punktów',
	'UCP_REPUTATION_GIVEN'			=> 'Lista przyznanych punktów',
	'UCP_REPUTATION_SETTING'		=> 'Preferencje',

	'RS_CATCHUP'						=> 'Aktualizuj',
	'RS_REPUTATION_LISTS_UCP'			=> 'To jest lista reputacji. Tutaj możesz przeglądać wszystkie punkty jakie otrzymałeś/aś.',
	'RS_NEW'							=> 'Nowy!',
	'RS_REPUTATION_GIVEN_LISTS_UCP'		=> 'To jest lista reputacji. Tutaj możesz przeglądać wszystkie punkty jakie przyznałeś/aś innym użytkownikom.',
	'RS_REPUTATION_SETTINGS_UCP'		=> 'Ustawienia reputacji',
	'RS_DEFAULT_COMMENT_POS'			=> 'Domyślny pozytywny komentarz',
	'RS_DEFAULT_COMMENT_POS_EXPLAIN'	=> 'Możesz zdefiniować domyślny komentarz dla pozystywnej oceny postu.',
	'RS_DEFAULT_COMMENT_NEG'			=> 'Domyślny negatywny komentarz',
	'RS_DEFAULT_COMMENT_NEG_EXPLAIN'	=> 'Możesz zdefiniować domyślny komentarz dla negatywnej oceny postu.',
	'RS_DEFAULT_POWER'					=> 'Domyślna siła reputacji',
	'RS_DEFAULT_POWER_EXPLAIN'			=> 'Możesz ustawić domyślną siłę reputacji',
	'RS_EMPTY'							=> 'Brak domyślnej',
	'RS_DEF_POINT'						=> 'punkt',
	'RS_DEF_POINTS'						=> 'punkty',
	'RS_NOTIFICATION'					=> 'Powiadomienia',
	'RS_NOTIFICATION_EXPLAIN'			=> 'Włącz powiadomienia o nowych punktach reputacji (opcja ta nie ma wpływu na powiadomienia w prywatnych wiadomościach).',
	'RS_DISPLAY_REPUTATIONS'			=> 'Wyświetl punkty nie starsze niż',
));

?>
