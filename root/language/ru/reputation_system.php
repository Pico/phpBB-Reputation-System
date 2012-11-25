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
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, array(
	'REPUTATION'		=> 'Репутация',

	'RS_DISABLED'		=> 'Извините, эту функцию отключил администратор.',
	'RS_TITLE'			=> 'Система репутации',

	'RS_ACTION'					=> 'Действие',
	'RS_BAN'					=> 'Забанить пользователя',
	'RS_COMMENT'				=> 'Комментарий',
	'RS_DATE'					=> 'Дата',
	'RS_DETAILS'				=> 'Подробности репутации',
	'RS_FROM'					=> 'От кого',
	'RS_LIST'					=> 'Список баллов репутации',
	'RS_POSITIVE_COUNT'			=> 'Положительные оценки',
	'RS_NEGATIVE_COUNT'			=> 'Отрицательные оценки',
	'RS_STATS'					=> 'Статистика',
	'RS_WEEK'					=> 'На прошлой неделе',
	'RS_MONTH'					=> 'В прошлом месяце',
	'RS_6MONTHS'				=> 'За последние 6 мес.',
	'RS_NEGATIVE'				=> 'Отрицательно',
	'RS_POSITIVE'				=> 'Положительно',
	'RS_POINT'					=> 'Балл',
	'RS_POINTS'					=> 'Баллы',
	'RS_POST'					=> 'Сообщение',
	'RS_POST_DELETE'			=> 'Сообщение удалена',
	'RS_POWER'					=> 'Сила репутации',
	'RS_POST_RATING'			=> 'Оценка сообщения',
	'RS_ONLYPOST_RATING'		=> 'Оценка только сообщения',
	'RS_RATE_BUTTON'			=> 'Оценить',
	'RS_RATE_POST'				=> 'Оценить сообщение',
	'RS_RATE_USER'				=> 'Оценить пользователя',
	'RS_RANK'					=> 'Ранг',
	'RS_SENT'					=> 'Ваша оценка записана',
	'RS_TIME'					=> 'Время',
	'RS_TO'						=> '',
	'RS_TO_USER'				=> '',
	'RS_TYPE'					=> 'Тип',
	'RS_USER_RATING'			=> 'Оценка пользователя',
	'RS_USER_RATING_CONFIRM'	=> 'Действительно хотите оценить %s?',
	'RS_VIEW_DETAILS'			=> 'Просмотреть подробности',
	'RS_VOTING_POWER'			=> 'Осталось силы',
	'RS_WARNING'				=> 'Предупреждение',

	'RS_EMPTY_DATA'				=> 'Нет баллов репутации.',
	'RS_NA'						=> 'н/д',
	'RS_NO_COMMENT'				=> 'Нельзя оставить пустой комментарий.',
	'RS_NO_ID'					=> 'Нет ID',
	'RS_NO_POST_ID'				=> 'Нет такого сообщения.',
	'RS_NO_POWER'				=> 'У вас недостаточно силы.',
	'RS_NO_POWER_LEFT'			=> 'У вас недостаточно силы для голосования.<br/>Подождите пока сила накопится.<br/>Ваша сила: %s',
	'RS_NO_USER_ID'				=> 'Такого пользователя не существует.',
	'RS_TOO_LONG_COMMENT'		=> 'Комментарий содержит %1$d символов. Максимальная разрешенная длина комментария %2$d символов.',
	'RS_COMMENT_TOO_LONG'		=> 'Слишком длинный комментарий.<br />Разрешено до: %s символов. Длина вашего комментария:',

	'RS_NO_POST'				=> 'Нет такого сообщения.',
	'RS_SAME_POST'				=> 'Вы уже голосовали за это сообщение.<br />Вы оценили ее в %s баллов.',
	'RS_SAME_USER'				=> 'Вы уже давали оценку этому пользователю.',
	'RS_SELF'					=> 'Вы не можете оценивать сами себя.',
	'RS_USER_ANONYMOUS'			=> 'Вы не можете оценивать анонимных пользователей.',
	'RS_USER_BANNED'			=> 'Вы не можете оценивать забаненных пользователей.',
	'RS_USER_CANNOT_DELETE'		=> 'Вы не можете удалять оценки.',
	'RS_USER_DISABLED'			=> 'Вы не можете голосовать.',
	'RS_USER_NEGATIVE'			=> 'Вы не можете давать отрицательные оценки.<br />Ваша репутация должна быть выше %s.',
	'RS_VIEW_DISALLOWED'		=> 'Вы не можете смотреть подробности голосования.',

	'RS_DELETE_POINT'			=> 'Удалить оценку',
	'RS_DELETE_POINT_CONFIRM'	=> 'Действительно удалить эту оценку?',
	'RS_POINT_DELETED'			=> 'Оценка была удалена.',
	'RS_DELETE_POINTS'			=> 'Удалить оценки',
	'RS_DELETE_POINTS_CONFIRM'	=> 'Действительно удалить эти оценки?',
	'RS_POINTS_DELETED'			=> 'Оценки были удалены.',
	'NO_REPUTATION_SELECTED'	=> 'Вы не выбрали оценку.',
	'RS_TRUNCATE_POST_CONFIRM'	=> 'Действительно удалить все оценки для этого сообщения?',
	'RS_CLEAR_POST'				=> 'Сбросить оценки сообщения',

	'RS_PM_BODY'				=> 'Отправитель этого сообщения дал оценку Вашему сообщению. <br />Оценка: [b]%s&nbsp;[/b] <br />Щелкните %sздесь%s, чтобы посмотреть сообщение.',
	'RS_PM_BODY_COMMENT'		=> 'Отправитель этого сообщения дал оценку Вашему сообщению. <br />Оценка: [b]%s&nbsp;[/b] <br />Комментарий: [i]%s&nbsp;[/i] <br />Щелкните %sздесь%s, чтобы посмотреть сообщение.',
	'RS_PM_BODY_USER'			=> 'Отправитель этого сообщения дал Вам оценку. <br />Оценка: [b]%s&nbsp;[/b]',
	'RS_PM_BODY_USER_COMMENT'	=> 'Отправитель этого сообщения дал Вам оценку. <br />Оценка: [b]%s&nbsp;[/b] <br />Комментарий: [i]%s&nbsp;[/i]',
	'RS_PM_SUBJECT'				=> 'Вы получили новую оценку',

	'RS_TOPLIST'			=> 'Лучшая репутация',
	'RS_TOPLIST_EXPLAIN'	=> 'Мы гордимся',

	'NOTIFY_USER_REP'		=> 'Оповестить пользователя?',

	'RS_LATEST_REPUTATIONS'			=> 'Последние оценки',
	'LIST_REPUTATION'				=> '1 оценка',
	'LIST_REPUTATIONS'				=> '%s оценок',
	'ALL_REPUTATIONS'				=> 'Все оценки',

	'RS_NEW_REPUTATIONS'			=> 'Новые оценки',
	'RS_NEW_REP'					=> 'У Вас <strong>1 новый</strong> комментарий к оценке',
	'RS_NEW_REPS'					=> 'У Вас <strong>%s новых</strong> комментариев к оценке',
	'RS_CLICK_TO_VIEW'				=> 'Просмотреть полученные оценки',

	'RS_MORE_DETAILS'				=> '» подробнее',
	'RS_HIDE_POST'					=> 'Это сообщение, написанное <strong>%1$s</strong>, скрыто, так как у сообщения низкая репутация.',
	'RS_SHOW_HIDDEN_POST'			=> 'Показать сообщение',
	'RS_SHOW_HIDE_HIDDEN_POST'		=> 'Показать / Скрыть',
	'RS_ANTISPAM_INFO'				=> 'Вы не можете так часто давать оценку. Попробуйте позже.',
	'RS_POST_REPUTATION'			=> 'Репутация записи',
	'RS_USER_REPUTATION'			=> '%s\'s reputation',
	'RS_YOU_RATED'					=> 'Вы оценили это сообщение. Оценки:',

	'RS_VOTE_POWER_LEFT_OF_MAX'		=> '%1$d баллов репутации осталось из %2$d.<br />Максимум для оценки: %3$d',
	'RS_VOTE_POWER_LEFT'			=> '%1$d из %2$d',

	'RS_POWER_DETAILS'				=> 'Как рассчитывать силу репутации',
	'RS_POWER_DETAIL_AGE'			=> 'По дате регистрации',
	'RS_POWER_DETAIL_POSTS'			=> 'По количеству сообщений',
	'RS_POWER_DETAIL_REPUTAION'		=> 'По репутации',
	'RS_POWER_DETAIL_WARNINGS'		=> 'По предупреждениям',
	'RS_POWER_DETAIL_BANS'			=> 'По количеству банов за год',
	'RS_POWER_DETAIL_MIN'			=> 'Минимальная репутация для всех пользователей',
	'RS_POWER_DETAIL_MAX'			=> 'Предел репутации пользователей',
	'RS_GROUP_POWER'				=> 'Сила репутации на основе группы',

	'RS_USER_GAP'					=> 'Вы не можете часто повторно оценивать одного и того же пользователя. Попробуйте снова через %s.',
));

?>