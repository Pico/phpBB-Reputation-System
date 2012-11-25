<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
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
	'UCP_REPUTATION'				=> 'Репутация',
	'UCP_REPUTATION_FRONT'			=> 'Главная страница',
	'UCP_REPUTATION_LIST'			=> 'Вас оценивали',
	'UCP_REPUTATION_GIVEN'			=> 'Вы оценивали',
	'UCP_REPUTATION_SETTING'		=> 'Настройки',

	'RS_CATCHUP'						=> 'Отслеживать новые теги',
	'RS_REPUTATION_LISTS_UCP'			=> 'Список оценок полученных вами от других пользователей.',
	'RS_NEW'							=> 'Новое!',
	'RS_REPUTATION_GIVEN_LISTS_UCP'		=> 'Список выших оценок другим пользователям.',
	'RS_REPUTATION_SETTINGS_UCP'		=> 'Настройки репутации',
	'RS_DEFAULT_POWER'					=> 'Сила по умолчанию',
	'RS_DEFAULT_POWER_EXPLAIN'			=> 'Вы можете указать количество баллов по умолчанию для оценки.',
	'RS_EMPTY'							=> 'Нет значения',
	'RS_DEF_POINT'						=> 'балл',
	'RS_DEF_POINTS'						=> 'баллы',
	'RS_NOTIFICATION'					=> 'Уведомления',
	'RS_NOTIFICATION_EXPLAIN'			=> 'Включить уведомления о новых баллах репутации (это также зависит от ваших настроек личных сообщений).',
	'RS_DISPLAY_REPUTATIONS'			=> 'Показать предыдущие баллы репутации',
));

?>
