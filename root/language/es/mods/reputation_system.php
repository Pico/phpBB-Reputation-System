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
	'RS_SAME_POST'				=> 'Ya has puntuado este mensaje.<br />Diste %s puntos de reputación.',
	'RS_SAME_USER'				=> 'Ya has dado reputación a ese usuario.',
	'RS_SELF'					=> 'No puedes darte reputación a ti mismo.',
	'RS_USER_ANONYMOUS'			=> 'No puedes dar reputación a usuarios anónimos.',
	'RS_USER_BANNED'			=> 'No puedes dar reputación a usuarios excluidos.',
	'RS_USER_CANNOT_DELETE'		=> 'No tienes permiso para borrar puntos.',
	'RS_USER_DISABLED'			=> 'No tienes permiso para dar reputación.',
	'RS_USER_NEGATIVE'			=> 'No tienes permiso para dar reputación negativa.<br />Tu reputación debe ser mayor a %s.',
	'RS_VIEW_DISALLOWED'		=> 'No tienes permiso para ver puntos de reputación.',

	'RS_DELETE_POINT'			=> 'Borrar punto',
	'RS_DELETE_POINT_CONFIRM'	=> '¿Realmente quieres borrar este punto de reputación?',
	'RS_POINT_DELETED'			=> 'El punto de reputación ha sido borrado correctamente.',
	'RS_DELETE_POINTS'			=> 'Borrar puntos',
	'RS_DELETE_POINTS_CONFIRM'	=> '¿Realmente quieres borrar estos puntos de reputación?',
	'RS_POINTS_DELETED'			=> 'Los puntos de reputación han sido borrados correctamente.',
	'NO_REPUTATION_SELECTED'	=> 'No has seleccionado un punto de reputación.',
	'RS_CLEAR_POST_CONFIRM'		=> '¿Realmente quieres borrar todos los puntos de reputación de este mensaje?',
	'RS_CLEAR_USER_CONFIRM'		=> '¿Realmente quieres borrar todos los puntos de reputación de este usuario?',
	'RS_CLEAR_POST'				=> 'Limpiar reputación del mensaje',
	'RS_CLEAR_USER'				=> 'Limpiar reputación del usuario',

	'RS_PM_BODY'				=> 'Has recibido un punto del remitente de este mensaje. <br />Puntos: [b]%s&nbsp;[/b] <br />Click %saqu&iacute;%s para ver el mensaje.',
	'RS_PM_BODY_COMMENT'		=> 'Has recibido un punto del remitente de este mensaje. <br />Puntos: [b]%s&nbsp;[/b] <br />Comentario: [i]%s&nbsp;[/i] <br />Click %saqu&iacute;%s para ver el mensaje.',
	'RS_PM_BODY_USER'			=> 'Has recibido un punto del remitente de este mensaje. <br />Puntos: [b]%s&nbsp;[/b]',
	'RS_PM_BODY_USER_COMMENT'	=> 'Has recibido un punto del remitente de este mensaje. <br />Puntos: [b]%s&nbsp;[/b] <br />Comentario: [i]%s&nbsp;[/i]',
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