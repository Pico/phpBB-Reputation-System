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
	'REPUTATION'		=> 'Reputação',

	'RS_DISABLED'		=> 'Desculpe, mas o administrador desabilitou esta funcionalidade.',
	'RS_TITLE'			=> 'Sistema de Reputação',

	'RS_ACTION'					=> 'Ação',
	'RS_BAN'					=> 'Banir usuário',
	'RS_COMMENT'				=> 'Comentário',
	'RS_DATE'					=> 'Data',
	'RS_DETAILS'				=> 'Detalhes da reputação do usuário',
	'RS_FROM'					=> 'De',
	'RS_LIST'					=> 'Lista de pontos de reputação do usuário',
	'RS_POSITIVE_COUNT'			=> 'Pontos positivos',
	'RS_NEGATIVE_COUNT'			=> 'Pontos negativos',
	'RS_STATS'					=> 'Estatísticas',
	'RS_WEEK'					=> 'Última semana',
	'RS_MONTH'					=> 'Último mês',
	'RS_6MONTHS'				=> 'ÚLtimos seis meses',
	'RS_NEGATIVE'				=> 'Negativo',
	'RS_POSITIVE'				=> 'Positivo',
	'RS_POINT'					=> 'Pontos',
	'RS_POINTS'					=> 'Pontos',
	'RS_POST'					=> 'Postar',
	'RS_POST_DELETE'			=> 'Post deletado',
	'RS_POWER'					=> 'Poder de reputação',
	'RS_POST_RATING'			=> 'Avaliar post',
	'RS_ONLYPOST_RATING'		=> 'Avaliando post',
	'RS_RATE_BUTTON'			=> 'Avaliar',
	'RS_RATE_POST'				=> 'Avaliar post',
	'RS_RATE_USER'				=> 'Avaliar usuário',
	'RS_RANK'					=> 'Rank de reputação',
	'RS_SENT'					=> 'Seu ponto de reputação foi enviado com sucess',
	'RS_TIME'					=> 'Hora',
	'RS_TO'						=> 'para',
	'RS_TO_USER'				=> 'Para',
	'RS_TYPE'					=> 'Tipo',
	'RS_USER_RATING'			=> 'Avaliar usuário',
	'RS_USER_RATING_CONFIRM'	=> 'Você realmente quer avaliar %s?',
	'RS_VIEW_DETAILS'			=> 'Ver detalhes',
	'RS_VOTING_POWER'			=> 'Pontos restantes de energia',
	'RS_WARNING'				=> 'Avisar usuário',

	'RS_EMPTY_DATA'				=> 'Não há pontos de reputação.',
	'RS_NA'						=> 'n/a',
	'RS_NO_COMMENT'				=> 'Você não pode deixar o campo de comentário vazio.',
	'RS_NO_ID'					=> 'Sem ID',
	'RS_NO_POST_ID'				=> 'Não existe este post.',
	'RS_NO_POWER'				=> 'Seu poder de reputação está muito baixo.',
	'RS_NO_POWER_LEFT'			=> 'Not enough reputation power points.<br/>Wait until they renew.<br/>Your reputation power is %s',
	'RS_NO_USER_ID'				=> 'The requested user does not exist.',
	'RS_TOO_LONG_COMMENT'		=> 'Your comment contains %1$d characters. The maximum number of allowed characters is %2$d.',
	'RS_COMMENT_TOO_LONG'		=> 'Too long comment.<br />Max characters: %s. Your comment:',

	'RS_NO_POST'				=> 'There is no such post.',
	'RS_SAME_POST'				=> 'You have already rated this post.<br />You gave %s reputation points.',
	'RS_SAME_USER'				=> 'You have already given reputation to this user.',
	'RS_SELF'					=> 'You cannot give reputation to yourself.',
	'RS_USER_ANONYMOUS'			=> 'You are not allowed to give reputation points to anonymous users.',
	'RS_USER_BANNED'			=> 'You are not allowed to give reputation points to banned users.',
	'RS_USER_CANNOT_DELETE'		=> 'You do not have permission to delete points.',
	'RS_USER_DISABLED'			=> 'You are not allowed to give reputation point.',
	'RS_USER_NEGATIVE'			=> 'You are not allowed to give negative reputation point.<br />Your reputation has to be higher than %s.',
	'RS_VIEW_DISALLOWED'		=> 'You are not allowed to view the reputation points.',

	'RS_DELETE_POINT'			=> 'Delete point',
	'RS_DELETE_POINT_CONFIRM'	=> 'Do you really want to delete this reputation point?',
	'RS_POINT_DELETED'			=> 'The reputation point has been deleted.',
	'RS_DELETE_POINTS'			=> 'Delete points',
	'RS_DELETE_POINTS_CONFIRM'	=> 'Do you really want to delete these reputation points?',
	'RS_POINTS_DELETED'			=> 'The reputation points have been deleted.',
	'NO_REPUTATION_SELECTED'	=> 'You did not select reputation point.',
	'RS_CLEAR_POST_CONFIRM'		=> 'Do you really want to delete all reputation points of that post?',
	'RS_CLEAR_USER_CONFIRM'		=> 'Do you really want to delete all reputation points of that user?',
	'RS_CLEAR_POST'				=> 'Clear post reputation',
	'RS_CLEAR_USER'				=> 'Clear user reputation',

	'RS_PM_BODY'				=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Click %shere%s to view the post.',
	'RS_PM_BODY_COMMENT'		=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Comment: [i]%s&nbsp;[/i] <br />Click %shere%s to view the post.',
	'RS_PM_BODY_USER'			=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b]',
	'RS_PM_BODY_USER_COMMENT'	=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Comment: [i]%s&nbsp;[/i]',
	'RS_PM_SUBJECT'				=> 'You received a reputation point',

	'RS_TOPLIST'			=> 'Reputation Toplist',
	'RS_TOPLIST_EXPLAIN'	=> 'Most popular members',

	'NOTIFY_USER_REP'		=> 'Notify user about the point?',

	'RS_LATEST_REPUTATIONS'			=> 'Latest reputations',
	'LIST_REPUTATION'				=> '1 reputation',
	'LIST_REPUTATIONS'				=> '%s reputations',
	'ALL_REPUTATIONS'				=> 'All reputations',

	'RS_NEW_REPUTATIONS'			=> 'New reputation points',
	'RS_NEW_REP'					=> 'You received <strong>1 new</strong> reputation comment',
	'RS_NEW_REPS'					=> 'You received <strong>%s new</strong> reputation comments',
	'RS_CLICK_TO_VIEW'				=> 'Go to received points',

	'RS_MORE_DETAILS'				=> '» more details',

	'RS_HIDE_POST'					=> 'This post was made by <strong>%1$s</strong> and was hidden because it had too low rating. %2$s',
	'RS_SHOW_HIDDEN_POST'			=> 'Show this post',
	'RS_SHOW_HIDE_HIDDEN_POST'		=> 'Show / Hide',
	'RS_ANTISPAM_INFO'				=> 'You cannot give reputation so soon. You may try again later.',
	'RS_POST_REPUTATION'			=> 'Post reputation',
	'RS_USER_REPUTATION'			=> '%s\'s reputation',
	'RS_YOU_RATED'					=> 'You rated that post. Points:',

	'RS_VOTE_POWER_LEFT_OF_MAX'		=> '%1$d reputation power points left of %2$d.<br />Maximum per vote: %3$d',
	'RS_VOTE_POWER_LEFT'			=> '%1$d of %2$d',

	'RS_POWER_DETAILS'				=> 'How reputation power should be calculated',
	'RS_POWER_DETAIL_AGE'			=> 'By registration date',
	'RS_POWER_DETAIL_POSTS'			=> 'By number of posts',
	'RS_POWER_DETAIL_REPUTAION'		=> 'By reputation',
	'RS_POWER_DETAIL_WARNINGS'		=> 'By warnings',
	'RS_POWER_DETAIL_BANS'			=> 'By number of bans within the last year',
	'RS_POWER_DETAIL_MIN'			=> 'Minimum reputation power for all users',
	'RS_POWER_DETAIL_MAX'			=> 'Reputation power capped at maximum allowed',
	'RS_GROUP_POWER'				=> 'Reputation power based on usergroup',

	'RS_USER_GAP'					=> 'Você não pode avaliar o mesmo usuário tão rápido. Tente novamente em %s.',
));

?>
