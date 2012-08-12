<?php
/**
*
* @package	Reputation System
* @author	Pico88 (Pico) (http://www.modsteam.tk), Versusnja
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
	'MCP_REPUTATION'				=> 'Репутация',
	'MCP_REPUTATION_FRONT'			=> 'Стартовая страница',
	'MCP_REPUTATION_LIST'			=> 'Список оценок',
	'MCP_REPUTATION_GIVE'			=> 'Дать оценку',

	'MCP_RS_REP_POINT'				=> 'Оценка за предупреждение',
	'MCP_RS_GIVE_REP_POINT'			=> 'Дать оценку вместе с предупреждением',

	'LOG_USER_REP_DELETE'			=> '<strong>Оценка удалена</strong><br />Пользователь: %s',

	'RS_BEST_REPUTATION'			=> 'Лучшая репутация',
	'RS_WORST_REPUTATION'			=> 'Хадшая репутация',
	'RS_DELETE_POINTS'				=> 'Удалить оценку',
	'RS_DELETE_POINTS_CONFIRM'		=> 'Вы действительно хотите удалить эту оценку?',
	'RS_POINTS_DELETED'				=> 'Оценку удалена.',
	'RS_REPUTATION_LISTS'			=> 'Это список оценок. Используйте фильтр, чтобы найти нужные Вам оценки.',
	'RS_SEARCH_FROM'				=> 'Оценка ОТ',
	'RS_SEARCH_TO'				  	=> 'КОГО оценивали',
	'RS_DISPLAY_REPUTATIONS'		=> 'Показать оценки за последние',
));

?>
