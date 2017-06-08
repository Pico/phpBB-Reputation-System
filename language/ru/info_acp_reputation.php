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
	'ACP_REPUTATION_SYSTEM'				=> 'Система репутации',
	'ACP_REPUTATION_OVERVIEW'			=> 'Общие',
	'ACP_REPUTATION_SETTINGS'			=> 'Настройки',
	'ACP_REPUTATION_RATE'				=> 'Оценить',
	'ACP_REPUTATION_SYNC'				=> 'Синхронизировать',

	'RS_FORUM_REPUTATION'			=> 'Включить рейтинг сообщений (репутацию)',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Разрешить пользователям оценивать сообщения, сделанные другими пользователями на этом форуме.',

	'RS_GROUP_POWER'				=> 'Групповая мощность репутации',
	'RS_GROUP_POWER_EXPLAIN'		=> 'Если это поле заполнено, мощность репутации пользователей будет перезаписана и не будет базироваться на сообщения и т.д.',

	'LOG_REPUTATION_DELETED'		=> '<strong>Удалена репутация</strong><br />От пользователя: %1$s<br />Пользователю: %2$s<br />Очки: %3$s<br/>Тип: %4$s<br/>Идентификатор: %5$s',
	'LOG_POST_REPUTATION_CLEARED'	=> '<strong>Очищена репутация сообщения</strong><br />Автор сообщения: %1$s<br />Тема сообщения: %2$s',
	'LOG_USER_REPUTATION_CLEARED'	=> '<strong>Очищена репутация пользователя</strong><br />Пользователь: %1$s',
	'LOG_REPUTATION_SYNC'			=> '<strong>Система репутации была синхронизирована</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Очищена репутация</strong>',
	'REPUTATION_SETTINGS_CHANGED'	=> '<strong>Изменены настройки Системы репутации</strong>',
));
