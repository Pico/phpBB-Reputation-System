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
	'RS_NO_POWER'				=> 'Seu força de reputação está muito baixo.',
	'RS_NO_POWER_LEFT'			=> 'Não há mais pontos de força de reputação.<br/>Aguarde até que eles sejam atualizados<br/>Sua força de reputação é %s',
	'RS_NO_USER_ID'				=> 'Este usuário não existe.',
	'RS_TOO_LONG_COMMENT'		=> 'Seu comentário contém %1$d caracteres. O número máximo de caracteres é  %2$d.',
	'RS_COMMENT_TOO_LONG'		=> 'Comentário muito longo.<br />Máximo de caracteres: %s. Seu comentário:',

	'RS_NO_POST'				=> 'Este post não existe.',
	'RS_SAME_POST'				=> 'Você já avaliou este post.<br />Você enviou %s ponto de reputação.',
	'RS_SAME_USER'				=> 'Você já enviou reputação para este usuário.',
	'RS_SELF'					=> 'Você não pode enviar reputação para você mesmo.',
	'RS_USER_ANONYMOUS'			=> 'Você não está autorizado a enviar pontos de reputação para usuários anônimos.',
	'RS_USER_BANNED'			=> 'Você não está autorizado a enviar pontos de reputação para usuários banidos.',
	'RS_USER_CANNOT_DELETE'		=> 'Você não tem permissão para deletar pontos.',
	'RS_USER_DISABLED'			=> 'Você não tem permissão para enviar pontos de reputação.',
	'RS_USER_NEGATIVE'			=> 'Você não tem permissão para envair pontos de reputação negativos.<br />Sua reputação tem que ser maior que  %s.',
	'RS_VIEW_DISALLOWED'		=> 'Você não tem permissão para visualizar pontos de reputação.',

	'RS_DELETE_POINT'			=> 'Deletar ponto',
	'RS_DELETE_POINT_CONFIRM'	=> 'Você realmente quer deletar este ponto de reputação?',
	'RS_POINT_DELETED'			=> 'O ponto de reputação foi deletado.',
	'RS_DELETE_POINTS'			=> 'Deletar pontos',
	'RS_DELETE_POINTS_CONFIRM'	=> 'VOcê realmente quer deletar estes pontos de reputação?',
	'RS_POINTS_DELETED'			=> 'Os pontos de reputação foram deletados.',
	'NO_REPUTATION_SELECTED'	=> 'Você não selecionou nenhum ponto de reputação.',
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
	'RS_POWER_DETAIL_AGE'			=> 'Por data de registro',
	'RS_POWER_DETAIL_POSTS'			=> 'Por número de posts',
	'RS_POWER_DETAIL_REPUTAION'		=> 'Por reputação',
	'RS_POWER_DETAIL_WARNINGS'		=> 'Por avisos',
	'RS_POWER_DETAIL_BANS'			=> 'Por número de banimentos no último ano',
	'RS_POWER_DETAIL_MIN'			=> 'Mínima força de reputação para todos os usuários',
	'RS_POWER_DETAIL_MAX'			=> 'Reputation power capped at maximum allowed',
	'RS_GROUP_POWER'				=> 'Poder de reputação baseado no grupo de usuários,

	'RS_USER_GAP'					=> 'Você não pode avaliar o mesmo usuário tão rápido. Tente novamente em %s.',
));

?>
