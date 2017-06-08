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
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, array(
	'RS_TITLE'			=> 'Система репутации',

	'RS_ACTION'					=> 'Действие',
	'RS_DATE'					=> 'Дата',
	'RS_DETAILS'				=> 'Сведения о репутации пользователя',
	'RS_FROM'					=> 'От',
	'RS_LIST'					=> 'Список баллов репутации пользователя',
	'RS_POST_COUNT'				=> 'За сообщения',
	'RS_POST_REPUTATION'		=> 'Репутация сообщения',
	'RS_USER_COUNT'				=> 'От пользователей',
	'RS_POSITIVE_COUNT'			=> 'Положительные',
	'RS_NEGATIVE_COUNT'			=> 'Отрицательные',
	'RS_STATS'					=> 'Статистика',
	'RS_WEEK'					=> 'Последняя неделя',
	'RS_MONTH'					=> 'Последний месяц',
	'RS_6MONTHS'				=> 'Последние 6 месяцев',
	'RS_POINT'					=> 'Балл',
	'RS_POINTS_TITLE'			=> array(
		1	=> 'Балл: %d',
		2	=> 'Баллы: %d',
		5	=> 'Баллов: %d',
	),
	'RS_POST_DELETE'			=> 'Сообщение удалено',
	'RS_POWER'					=> 'Мощность репутации',
	'RS_TIME'					=> 'Время',
	'RS_TO'						=> 'кому',
	'RS_TO_USER'				=> 'Кому',
	'RS_VOTING_POWER'			=> 'Оставшиется очки мощности',

	'RS_EMPTY_DATA'				=> 'Больше нет баллов репутации.',
	'RS_NA'						=> 'н/д',
	'RS_NO_ID'					=> 'Нет ID',
	'RS_NO_REPUTATION'			=> 'Нет такой репутации.',

	'NO_REPUTATION_SELECTED'	=> 'Вы не выбрали балл репутации.',

	'RS_REPUTATION_DELETE_CONFIRM'	=> 'Вы действительно хотите удалить эту репутацию?',
	'RS_REPUTATIONS_DELETE_CONFIRM'	=> 'Вы действительно хотите удалить эту репутацию?',
	'RS_POINTS_DELETED'			=> array(
		1	=> 'Репутация была удалена.',
		2	=> 'Репутации были удалены.',
	),

	'RS_CLEAR_POST'				=> 'Очистить репутацию сообщения',
	'RS_CLEAR_POST_CONFIRM'		=> 'Вы действительно хотите удалить все отметки репутации для этой записи?',
	'RS_CLEARED_POST'			=> 'Репутация сообщения была очищена.',
	'RS_CLEAR_USER'				=> 'Очистить репутацию пользователя',
	'RS_CLEAR_USER_CONFIRM'		=> 'Вы действительно хотите удалить все отметки репутации для этого пользователя?',
	'RS_CLEARED_USER'			=> 'Репутация пользователя была очищена.',

	'RS_LATEST_REPUTATIONS'			=> 'Последняя репутация',
	'LIST_REPUTATIONS'				=> array(
		1	=> '%d репутация',
		2	=> '%d репутации',
	),
	'ALL_REPUTATIONS'				=> 'Вся репутация',

	'RS_NEW_REPUTATIONS'			=> 'Новые баллы репутации',
	'RS_NEW_REP'					=> 'Вы получили <strong>1 новый</strong> комментарий репутации',
	'RS_NEW_REPS'					=> 'Вы получили <strong>%s новых</strong> комментариев репутации',
	'RS_CLICK_TO_VIEW'				=> 'Перейти к полученным баллам',

	'RS_MORE_DETAILS'				=> '» подробности',

	'RS_USER_REPUTATION'			=> 'Репутация пользователя %s',

	'RS_VOTE_POWER_LEFT'			=> '%1$d из %2$d',

	'RS_POWER_DETAILS'				=> 'Как высчитывается мощность репутации',
	'RS_POWER_DETAIL_AGE'			=> 'По дате регистрации',
	'RS_POWER_DETAIL_POSTS'			=> 'По количеству сообщений',
	'RS_POWER_DETAIL_REPUTATION'		=> 'По репутации',
	'RS_POWER_DETAIL_WARNINGS'		=> 'По предупреждениям',
	'RS_POWER_DETAIL_MIN'			=> 'Минимальная мощность репутации для всех пользователей',
	'RS_POWER_DETAIL_MAX'			=> 'Мощность репутации ограничена максимально допустимой',
	'RS_POWER_DETAIL_GROUP_POWER'	=> 'Мощность репутации, основанная на группе пользователей',
	'RS_GROUP_POWER'				=> 'Мощность репутации, основанная на группе пользователей',
));
