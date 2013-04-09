<?php
/**
*
* @package	Reputación System
* @author	mvader (https://github.com/mvader)
* @copyright (c) 2013
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
	'REPUTATION'		=> 'Reputación',

	'RS_DISABLED'		=> 'Lo sentimos, el administrador del sitio ha desactivado esta característica.',
	'RS_TITLE'			=> 'Sistema de reputación',

	'RS_ACTION'					=> 'Acción',
	'RS_BAN'					=> 'Excluir usuario',
	'RS_COMMENT'				=> 'Comentario',
	'RS_DATE'					=> 'Fecha',
	'RS_DETAILS'				=> 'Detalles de la reputación del usuario',
	'RS_FROM'					=> 'De',
	'RS_LIST'					=> 'Lista de puntos de reputación del usuario',
	'RS_POSITIVE_COUNT'			=> 'Puntos positivos',
	'RS_NEGATIVE_COUNT'			=> 'Puntos negativos',
	'RS_STATS'					=> 'Estadísticas',
	'RS_WEEK'					=> 'Última semana',
	'RS_MONTH'					=> 'Último mes',
	'RS_6MONTHS'				=> 'Últimos 6 meses',
	'RS_NEGATIVE'				=> 'Negativo',
	'RS_POSITIVE'				=> 'Positivo',
	'RS_POINT'					=> 'Punto',
	'RS_POINTS'					=> 'Puntos',
	'RS_POST'					=> 'Mensaje',
	'RS_POST_DELETE'			=> 'Mensaje borrado',
	'RS_POWER'					=> 'Poder de reputación',
	'RS_POST_RATING'			=> 'Puntuación del mensaje',
	'RS_ONLYPOST_RATING'		=> 'Evaluando mensaje',
	'RS_RATE_BUTTON'			=> 'Puntuar',
	'RS_RATE_POST'				=> 'Puntuar mensaje',
	'RS_RATE_USER'				=> 'Puntuar usuario',
	'RS_RANK'					=> 'Rango de reputación',
	'RS_SENT'					=> 'Tu punto de reputación ha sido enviado correctamente',
	'RS_TIME'					=> 'Hora',
	'RS_TO'						=> 'a',
	'RS_TO_USER'				=> 'de',
	'RS_TYPE'					=> 'Tipo',
	'RS_USER_RATING'			=> 'Puntuar usuario',
	'RS_USER_RATING_CONFIRM'	=> '¿Realmente quieres puntuar a %s?',
	'RS_VIEW_DETAILS'			=> 'Ver detalles',
	'RS_VOTING_POWER'			=> 'Puntos de poder restantes',
	'RS_WARNING'				=> 'Advirtiendo usuario',

	'RS_EMPTY_DATA'				=> 'No hay puntos de reputación.',
	'RS_NA'						=> 'n/a',
	'RS_NO_COMMENT'				=> 'No puedes dejar el campo de comentario en blanco.',
	'RS_NO_ID'					=> 'No hay ID',
	'RS_NO_POST_ID'				=> 'No existe el tema especificado.',
	'RS_NO_POWER'				=> 'Tu poder de reputación es demasiado bajo.',
	'RS_NO_POWER_LEFT'			=> 'No tienes suficiente poder de reputación.<br/>Espera hasta que se renueven.<br/>Tu poder de reputación es %s',
	'RS_NO_USER_ID'				=> 'El usuario seleccionado no existe.',
	'RS_TOO_LONG_COMMENT'		=> 'Tu comentario contiene %1$d caracteres. El máximo número de caracteres permitidos es %2$d.',
	'RS_COMMENT_TOO_LONG'		=> 'Comentario demasiado largo.<br />Caracteres máximos: %s. Tu comentario:',

	'RS_NO_POST'				=> 'No existe el mensaje.',
	'RS_SAME_POST'				=> 'You have already rated this post.<br />You gave %s reputación points.',
	'RS_SAME_USER'				=> 'You have already given reputación to this user.',
	'RS_SELF'					=> 'You cannot give reputación to yourself.',
	'RS_USER_ANONYMOUS'			=> 'You are not allowed to give reputación points to anonymous users.',
	'RS_USER_BANNED'			=> 'You are not allowed to give reputación points to banned users.',
	'RS_USER_CANNOT_DELETE'		=> 'You do not have permission to delete points.',
	'RS_USER_DISABLED'			=> 'You are not allowed to give reputación point.',
	'RS_USER_NEGATIVE'			=> 'You are not allowed to give negative reputación point.<br />Your reputación has to be higher than %s.',
	'RS_VIEW_DISALLOWED'		=> 'You are not allowed to view the reputación points.',

	'RS_DELETE_POINT'			=> 'Delete point',
	'RS_DELETE_POINT_CONFIRM'	=> 'Do you really want to delete this reputación point?',
	'RS_POINT_DELETED'			=> 'The reputación point has been deleted.',
	'RS_DELETE_POINTS'			=> 'Delete points',
	'RS_DELETE_POINTS_CONFIRM'	=> 'Do you really want to delete these reputación points?',
	'RS_POINTS_DELETED'			=> 'The reputación points have been deleted.',
	'NO_REPUTATION_SELECTED'	=> 'You did not select reputación point.',
	'RS_CLEAR_POST_CONFIRM'		=> 'Do you really want to delete all reputación points of that post?',
	'RS_CLEAR_USER_CONFIRM'		=> 'Do you really want to delete all reputación points of that user?',
	'RS_CLEAR_POST'				=> 'Clear post reputación',
	'RS_CLEAR_USER'				=> 'Clear user reputación',

	'RS_PM_BODY'				=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Click %shere%s to view the post.',
	'RS_PM_BODY_COMMENT'		=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Comment: [i]%s&nbsp;[/i] <br />Click %shere%s to view the post.',
	'RS_PM_BODY_USER'			=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b]',
	'RS_PM_BODY_USER_COMMENT'	=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Comment: [i]%s&nbsp;[/i]',
	'RS_PM_SUBJECT'				=> 'Has recibido un punto de reputación',

	'RS_TOPLIST'			=> 'Top de reputación',
	'RS_TOPLIST_EXPLAIN'	=> 'Miembros más populares',

	'NOTIFY_USER_REP'		=> '¿Notificar al usuario sobre el punto?',

	'RS_LATEST_REPUTATIONS'			=> 'Últimas reputaciones',
	'LIST_REPUTATION'				=> '1 reputación',
	'LIST_REPUTATIONS'				=> '%s reputaciones',
	'ALL_REPUTATIONS'				=> 'Todas las reputaciones',

	'RS_NEW_REPUTATIONS'			=> 'Nuevos puntos de reputación',
	'RS_NEW_REP'					=> 'Has recibido <strong>1 nuevo</strong> comentario de reputación',
	'RS_NEW_REPS'					=> 'You received <strong>%s nuevos</strong> comentarios de reputación',
	'RS_CLICK_TO_VIEW'				=> 'Ir a puntos recibidos',

	'RS_MORE_DETAILS'				=> '» más detalles',

	'RS_HIDE_POST'					=> 'Este post fue modificado por <strong>%1$s</strong> y fue ocultado porque tiene una puntuación demasiado baja. %2$s',
	'RS_SHOW_HIDDEN_POST'			=> 'Mostrar el mensaje',
	'RS_SHOW_HIDE_HIDDEN_POST'		=> 'Mostrar / Ocultar',
	'RS_ANTISPAM_INFO'				=> 'No puedes dar reputación tan pronto. Prueba más tarde.',
	'RS_POST_REPUTATION'			=> 'Reputación del mensaje',
	'RS_USER_REPUTATION'			=> 'Reputación de %s',
	'RS_YOU_RATED'					=> 'Has puntuado este mensaje. Puntos:',

	'RS_VOTE_POWER_LEFT_OF_MAX'		=> 'Te quedan %1$d puntos de poder de reputación de %2$d.<br />Máximos por voto: %3$d',
	'RS_VOTE_POWER_LEFT'			=> '%1$d de %2$d',

	'RS_POWER_DETAILS'				=> 'Como debe ser calculado el poder de reputación',
	'RS_POWER_DETAIL_AGE'			=> 'Por fecha de registro',
	'RS_POWER_DETAIL_POSTS'			=> 'Por número de mensajes',
	'RS_POWER_DETAIL_REPUTAION'		=> 'Por reputación',
	'RS_POWER_DETAIL_WARNINGS'		=> 'Por advertencias',
	'RS_POWER_DETAIL_BANS'			=> 'Por número de exclusiones en el último año',
	'RS_POWER_DETAIL_MIN'			=> 'Mínimo poder de reputación para todos los usuarios',
	'RS_POWER_DETAIL_MAX'			=> 'Poder de reputación al máximo permitido',
	'RS_GROUP_POWER'				=> 'Poder de reputación basado en el grupo del usuario',

	'RS_USER_GAP'					=> 'No puedes puntuar al mismo usuario tan rápido. Prueba de nuevo en %s.',
));