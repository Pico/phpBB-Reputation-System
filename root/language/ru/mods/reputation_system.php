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
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, array(
	'REPUTATION'		=> 'Репутация',

	'RS_DISABLED'		=> 'Извините, эту функцию отключил администратор.',
	'RS_TITLE'			=> 'Система репутации',

	'RS_ADD_POINTS'					=> 'Оценить положительно',
	'RS_ADD_POINTS_CONFIRM'			=> 'Вы действительно хотите оценить положительно?',
	'RS_SUBTRACT_POINTS'			=> 'Оценить отрицательно',
	'RS_SUBTRACT_POINTS_CONFIRM'	=> 'Вы действительно хотите оценить отрицательно?',
	'RS_POINT_EXPLAIN'				=> 'При помощи этой формы Вы можете прокомментировать свою оценку сообщения. Если Вы хотите сообщить об этом автору сообщения, поставьте галочку ниже.',
	'RS_USER_POINT_EXPLAIN'			=> 'При помощи этой формы Вы можете прокомментировать свою оценку пользователя. Если Вы хотите сообщить об этом самому пользователю, поставьте галочку ниже.',

	'RS_ACTION'					=> 'Действие',
	'RS_BAN'					=> 'Забанить пользователя',
	'RS_COMMENT'				=> 'Комментарий',
	'RS_DATE'					=> 'Дата',
	'RS_DETAILS'				=> 'Детали репутации',
	'RS_FROM'					=> 'От кого',
	'RS_LIST'					=> 'Список очков репутации',
	'RS_POSITIVE_COUNT'			=> 'Положительные оценки',
	'RS_NEGATIVE_COUNT'			=> 'Негативные оценки',
	'RS_STATS'					=> 'Статистика',
	'RS_WEEK'					=> 'На прошлой неделе',
	'RS_MONTH'					=> 'В прошлом месяце',
	'RS_6MONTHS'				=> 'За последние 6 мес.',
	'RS_NEGATIVE'				=> 'Отрицательно',
	'RS_POSITIVE'				=> 'Положительно',
	'RS_POINTS'					=> 'Оценка',
	'RS_POST'					=> 'Сообщение',
	'RS_POST_DELETE'			=> 'Сообщение удалено',
	'RS_POWER'					=> 'Сила репутации',
	'RS_POST_RATING'			=> 'Rating post',
	'RS_RATE_BUTTON'			=> 'Оценить',
	'RS_RATE_USER'				=> 'Оценить пользователя',
	'RS_RANK'					=> 'Ранг',
	'RS_SENT'					=> 'Ваша оценка записана',
	'RS_TIME'					=> 'Время',
	'RS_TO'						=> '',
	'RS_TYPE'					=> 'Тип',
	'RS_USER_RATING'			=> 'Rating user',
	'RS_USER_RATING_CONFIRM'	=> 'Вы действительно хотите оценить %s?',
	'RS_VIEW_DETAILS'			=> 'Посмотреть подробнее',
	'RS_VOTING_POWER'			=> 'Сила голоса',
	'RS_WARNING'				=> 'Предупреждение',

	'RS_EMPTY_DATA'				=> 'Нет оценок',
	'RS_NA'						=> 'нет',
	'RS_NO_COMMENT'				=> 'Комментарий нельзя оставлять пустым.',
	'RS_NO_ID'					=> 'Нет ID',
	'RS_NO_POST_ID'				=> 'Нет такого сообщения.',
	'RS_NO_POWER_LEFT'			=> 'У Вас недостаточно силы для голосования.<br/>Подождите пока сила накопится.<br/>Ваша сила: %s',
	'RS_NO_USER_ID'				=> 'Такого пользователя не существует.',

	'RS_NO_POST'				=> 'Такого сообщения не сущесвтет.',
	'RS_NO_POWER_POST'			=> 'У Вас недостаточно силы, чтобы проголосовать за это сообщение.',
	'RS_SAME_POST'				=> 'Вы уже голосовали за это сообщение.<br />Вы дали %s очков.',
	'RS_SAME_USER'				=> 'Вы уже давали оцнеку этому пользователю',
	'RS_SELF'					=> 'Вы не можете голосовать за себя.',
	'RS_USER_ANONYMOUS'			=> 'Вы не можете голосовать за анонимных пользователей.',
	'RS_USER_BANNED'			=> 'Вы не можете голосовать за забаненных пользователей.',
	'RS_USER_CANNOT_DELETE'		=> 'Вы не можете удалять оценки.',
	'RS_USER_DISABLED'			=> 'Вы не можете голосовать.',
	'RS_VIEW_DISALLOWED'		=> 'Вы не можете смотреть подробности голосования.',

	'RS_DELETE_POINT'			=> 'Удалить оценку',
	'RS_DELETE_POINT_CONFIRM'	=> 'Вы действительно хотите удалить оценку?',
	'RS_POINT_DELETED'			=> 'Оценка удалена.',

	'RS_PM_BODY'				=> 'Отправитель этого сообщения дал оценку Вашему сообщению. <br />Оценка: [b]%s&nbsp;[/b] <br />Щелкните %sздесь%s, чтобы посмотреть сообщение.',
	'RS_PM_BODY_COMMENT'		=> 'Отправитель этого сообщения дал оценку Вашему сообщению. <br />Оценка: [b]%s&nbsp;[/b] <br />Комментарий: [i]%s&nbsp;[/i] <br />Щелкните %sздесь%s, чтобы посмотреть сообщение.',
	'RS_PM_BODY_USER'			=> 'Отправитель этого сообщения дал Вам оценку. <br />Оценка: [b]%s&nbsp;[/b]',
	'RS_PM_BODY_USER_COMMENT'	=> 'Отправитель этого сообщения дал Вам оценку. <br />Оценка: [b]%s&nbsp;[/b] <br />Комменарий: [i]%s&nbsp;[/i]',
	'RS_PM_SUBJECT'				=> 'Вы получили новую оценку',

	'RS_RETURN_DETAILS'			=> '%sВернутся к списку оценок%s',
	'RS_RETURN_POSTDETAILS'		=> '%sВернуться к списку оценок сообщения%s',
	'RS_RETURN_USER'			=> '%sВернуться к подробностям о пользователе%s',

	'RS_TOPLIST'			=> 'Лучшая репутация',
	'RS_TOPLIST_EXPLAIN'	=> 'Мы гордимся',

	'NOTIFY_USER_REP'		=> 'Оповестить пользователя?',

	'RS_LATEST_REPUTATIONS'			=> 'Последние оценки',
	'LIST_REPUTATION'				=> '1 оценка',
	'LIST_REPUTATIONS'				=> '%s оценок',
	'ALL_REPUTATIONS'				=> 'Все оценки',
	'RS_TO_USER'					=> 'Кому',
	'RS_POINT'						=> 'Оценка',
	'RS_NEW_REP'					=> 'У Вас <strong>1 новый</strong> комментарий к оценке',
	'RS_NEW_REPS'					=> 'У Вас <strong>%s новых</strong> комментариев к оценке',

	'RS_CLOSE_POPUP'				=> 'закрыть',
	'RS_POPUP_MORE_DETAILS'			=> 'подробнее',

	'RS_HIDE_POST'					=> 'Это сообщение, написанное <strong>%1$s</strong>, скрыто, так как у сообщения низкая репутация.',
	'RS_SHOW_HIDDEN_POST'			=> 'Показать сообщение',
	'RS_SHOW_HIDE_HIDDEN_POST'		=> 'Показать / Скрыть',
	'RS_ANTISPAM_INFO'				=> 'Вы не можете так часто давать оценку. Попробуйте позже.',
	'RS_POST_REPUTATION'			=> 'Репутация сообщения',
	'RS_YOU_HAVE_VOTED'				=> 'Вы проголосовали. Оценка:',

	'RS_VOTE_POWER'					=> 'Сила голоса: %s',
	'RS_VOTE_POWER_MAX'				=> 'Максимальная оценка: %s',
	'RS_VOTE_POWER_LEFT_OF_MAX'		=> 'Осталось силы: %1$d из максимальных %2$d.',
	'RS_VOTE_POWER_LEFT'			=> '%1$d из %2$d',
));

?>