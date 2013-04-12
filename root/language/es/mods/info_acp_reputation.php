<?php
/**
*
* @package	Reputation System
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
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'REPUTATION_SYSTEM'				=> 'Sistema de reputación',

	'ACP_REPUTATION_SYSTEM'				=> 'Sistema de reputación',
	'ACP_REPUTATION_SYSTEM_EXPLAIN'		=> 'Desde aquí puedes alcanzar todas las funciones de configuración necesarias para administrar el sistema de reputación.',
	'ACP_REPUTATION_OVERVIEW'			=> 'Vista',
	'ACP_REPUTATION_SETTINGS'			=> 'Configuración',
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'En esta página puedes configurar las opciones del sistema de reputación. Están divididas en grupos.',
	'ACP_REPUTATION_GIVE'				=> 'Dar punto',
	'ACP_REPUTATION_RANKS'				=> 'Rangos',
	'ACP_REPUTATION_BANS'				=> 'Exclusiones',
	'MCP_REPUTATION'					=> 'Reputación',
	'MCP_REPUTATION_FRONT'				=> 'Página frontal',
	'MCP_REPUTATION_LIST'				=> 'Lista de reputación',
	'MCP_REPUTATION_GIVE'				=> 'Dar punto',
	'UCP_REPUTATION'					=> 'Reputación',
	'UCP_REPUTATION_FRONT'				=> 'Página frontal',
	'UCP_REPUTATION_LIST'				=> 'Lista',
	'UCP_REPUTATION_GIVEN'				=> 'Puntos dados',
	'UCP_REPUTATION_SETTING'			=> 'Opciones',

	'ACP_RS_MAIN'			=> 'General',
	'ACP_RS_DISPLAY'		=> 'Mostrar',
	'ACP_RS_POSTS_RATING'	=> 'Puntuación de temas',
	'ACP_RS_USERS_RATING'	=> 'Puntuación de usuarios',
	'ACP_RS_COMMENT'		=> 'Comentarios',
	'ACP_RS_POWER'			=> 'Poder de reputación',
	'ACP_RS_RANKS'			=> 'Rangos',
	'ACP_RS_TOPLIST'		=> 'Toplist',
	'ACP_RS_BAN'		 	=> 'Exclusiones',

	'RS_ENABLE'		=> 'Activar sistema de reputación',

	'RS_NEGATIVE_POINT'				=> 'Permitir puntuaciones negativas',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'Cuando está deshabilitado no se pueden dar puntuaciones negativas, similar a la función Like de Facebook.',
	'RS_MIN_REP_NEGATIVE'			=> 'Mínima reputación para votaciones negativas',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'Cuánta reputación se necesita para dar puntuaciones negativas. El valor 0 desactiva esta opción.',
	'RS_WARNING'					=> 'Activar advertencias',
	'RS_WARNING_EXPLAIN'			=> 'Los usuarios que tengan permisos necesarios podrán dar puntuaciones negativas al poner advertencias.',
	'RS_NOTIFICATION'				=> 'Activar notificaciones',
	'RS_NOTIFICATION_EXPLAIN'		=> 'Esta opción activa las notificaciones de nuevas puntuaciones en la cabecera.',
	'RS_PM_NOTIFY'					=> 'Activar notificaciones por MP',
	'RS_PM_NOTIFY_EXPLAIN'			=> 'Esta opción permite enviar MPs al dar reputación.',
	'RS_MIN_POINT'					=> 'Puntos mínimos',
	'RS_MIN_POINT_EXPLAIN'			=> 'Limita el número mínimo de puntos re reputación que un usuario puede recibir. Poner el valor a 0 deshabilita este comportamiento.',
	'RS_MAX_POINT'					=> 'Puntos máximos',
	'RS_MAX_POINT_EXPLAIN'			=> 'Limita el número máximo de puntos re reputación que un usuario puede recibir. Poner el valor a 0 deshabilita este comportamiento.',

	'RS_PER_PAGE'							=> 'Reputaciones por página',
	'RS_PER_PAGE_EXPLAIN'					=> '¿Cuántas filas se deben mostrar en la tabla de reputaciones?',
	'RS_DISPLAY_AVATAR'						=> 'Mostrar avatares',
	'RS_SORT_MEMBERLIST_BY_REPO'			=> 'Ordenar lista de miembros por reputación',
	'RS_SORT_MEMBERLIST_BY_REPO_EXPLAIN'	=> 'Cuando la lista de miembros está ordenada por reputación tiene más sentido ir a mirarla de vez en cuando para ver el desarrollo. Puedes desactivar la opción para volver a la ordenación por nombre.',
	'RS_POINT_TYPE'							=> 'Método para mostrar mensajes',
	'RS_POINT_TYPE_EXPLAIN'					=> 'La vista de puntos de reputación puede ser mostrada como la cantidad exacta o como una imagen mostrando si la puntuación es positiva o negativa. El método de la imagen es útil si tienes configurado que solo puedan darse reputacione de un punto.',
	'RS_POINT_VALUE'						=> 'Valor',
	'RS_POINT_IMG'							=> 'Imagen',

	'RS_POST_RATING'				=> 'Activar puntuación de mensajes',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Enviar y activar la reputación en todos los foros',
	'RS_HIGHLIGHT_POST'				=> 'Mensajes destacados',
	'RS_HIGHLIGHT_POST_EXPLAIN'		=> 'Mensajes con una puntuación mayor a la seleccionada serán destacados. Un valor de 0 desactiva este comportamiento.<br /><em>Nota:</em> Puedes alterar el destacado por defecto modificando la clase <strong>highlight</strong> en reputation.css.',
	'RS_HIDE_POST'					=> 'Ocultar mensajes con baja puntuación',
	'RS_HIDE_POST_EXPLAIN'			=> 'Posts with a rating less than the number set here will be hidden by default (users have the option to unhide them if they choose). After a post has earned a rating greater than this value, it will no longer be hidden by default. Setting the value to 0 disables this behaviour.',
	'RS_ANTISPAM'					=> 'Anti-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Block users from rating any more posts after they have rated the defined number of posts within the defined number of hours. To disable this feature set one or both values to 0.',
	'RS_POSTS'						=> 'Número de mensajes puntuados',
	'RS_HOURS'						=> 'en las últimas horas',
	'RS_ANTISPAM_METHOD'			=> 'Comprobación Anti-Spam',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Método para comprobación Anti-Spam. El método “Mismo usuario” comprueba la reputación dada al mismo usuario. El método “Todos los usuarios” comprueba la reputación independientemente de quien recibió los puntos. ',
	'RS_SAME_USER'					=> 'Mismo usuario',
	'RS_ALL_USERS'					=> 'Todos los usuarios',

	'RS_USER_RATING'				=> 'Permitir puntuación de usuarios desde su perfil',
	'RS_USER_RATING_GAP'			=> 'Tiempo de espera entre votos',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Tiempo de espera para votar a un usuario que ya se ha botado. Un valor de 0 permite que los usuarios solo puedan ser puntuados una vez.',

	'RS_ENABLE_COMMENT'				=> 'Activar comentarios',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'Cuando está habilitado, los usuarios serán capaces de añadir un comentario a sus votos.',
	'RS_FORCE_COMMENT'				=> 'Forzar usuarios a introducir un comentario',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Los usuarios serán obligados a introducir un comentario junto a su puntuación.',
	'RS_COMMENT_NO'					=> 'No',
	'RS_COMMENT_BOTH'				=> 'Puntuación de usuarios y mensajes',
	'RS_COMMENT_POST'				=> 'Solo puntuación de mensajes',
	'RS_COMMENT_USER'				=> 'Solo puntuación de usuarios',
	'RS_COMMEN_LENGTH'				=> 'Longitud del comentario',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'Número de caracteres máximos permitidos en el comentario. Un valor de 0 permitirá caracteres ilimitados.',

	'RS_ENABLE_POWER'				=> 'Activar poder de reputación',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'El poder de reputación es algo que los usuarios gastan y ganan al votar. Los nuevos usuarios tienen poco poder, los activos y veteranos ganan más poder. Cuanto más poder tengas más podrás votar en un periodo de tiempo y más influencia tendrás en la puntuación de otro usuario o mensaje.<br/>Los usuarios pueden decidir en la votación cuantos puntos darán, dando más puntos a temas más interesantes.',
	'RS_POWER_RENEWAL'				=> 'Tiempo de renovación de poder',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'Esto controla como los usuarios pueden gastar su poder ganado.<br/>Si seleccionas esta opción, los usuarios deberán esperar el intervalo de tiempo especificado para votar de nuevo. Cuanto más poder de reputación tenga un usuario, más puntos podrá gastar en ese intervalo.<br/>El valor recomendado es 5 horas.<br />Un valor de 0 desactiva esta característica y los usuarios pueden votar sin esperar.',
	'RS_MIN_POWER'					=> 'Mínimo/inicial poder de reputación',
	'RS_MIN_POWER_EXPLAIN'			=> 'Cuánto poder de reputación tienen los nuevos usuarios, usuarios excluidos o con baja reputación. Los usuarios no pueden tener menos poder que este.<br/>Permitido de 0 a 10. Recomendado un valor de 1.',
	'RS_MAX_POWER'					=> 'Máximo gasto de poder de reputación por voto',
	'RS_MAX_POWER_EXPLAIN'			=> 'Máxima cantidad de poder que el usuario puede gastar en un voto. Aunque el usuario tenga millones de puntos será limitado por este máximo.<br/>Los usuarios seleccionarán esto desde el menú desplegable: 1 a X<br/>Permitido 1-20. Recomendado: 3.',
	'RS_MAX_POWER_WARNING'			=> 'Máximo poder de reputación por advertencias',
	'RS_MAX_POWER_WARNING_EXPLAIN'	=> 'Máximo poder de reputación permitido por advertencias.',
	'RS_MAX_POWER_BAN'				=> 'Máximo poder de reputación por exclusiones',
	'RS_MAX_POWER_BAN_EXPLAIN'		=> 'Máximo poder de reputación que un usuario puede ganar si son baneados por un mes o permanentemente. Si un usuario es baneado por menos tiempo obtendrá una cantidad relativa de puntos.',
	'RS_POWER_EXPLAIN'				=> 'Explicación del poder de reputación',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Explica como es explicado el cálculo del poder de reputación a otros usuarios.',
	'RS_TOTAL_POSTS'				=> 'Obtener poder con número de mensajes',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'El usuario ganará un punto de poder de reputación por cada X mensajes.',
	'RS_MEMBERSHIP_DAYS'			=> 'Ganar poder con la longitud de la membresía del usuario',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'El usuario ganará un punto de poder de reputación por cada X días.',
	'RS_POWER_REP_POINT'			=> 'Ganar poder con la reputación del usuario',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'El usuario ganará un punto de de poder reputación por cada X puntos de reputación que ganen.',
	'RS_LOSE_POWER_BAN'				=> 'Perder poder con exclusiones',
	'RS_LOSE_POWER_BAN_EXPLAIN'		=> 'Cada exclusión en el último año disminuye el poder de reputación esta cantidad de puntos',
	'RS_LOSE_POWER_WARN'			=> 'Perder poder con advertencias',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Cada advertencia disminuye el poder de reputación con este número de puntos. Las advertencias expiran de acuerdo a las preferencias configuradas en General -> Configuración del sitio -> Preferencias del sitio',
	'RS_GROUP_POWER'				=> 'Poder de reputación de grupo',

	'RS_RANKS_ENABLE'				=> 'Activar rangos',
	'RS_RANKS_PATH'					=> 'Ruta de imágenes de rangos',
	'RS_RANKS_PATH_EXPLAIN'			=> 'Ruta en tu directorio raíz de phpBB, ejemplo: <samp>images/reputation</samp>.',

	'RS_ENABLE_TOPLIST'				=> 'Activar top',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Muestra una lista con los usuarios con más puntuación en el índice de foros.',
	'RS_TOPLIST_DIRECTION'			=> 'Dirección de la lista',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Mostrar los usuarios de forma horizontal o vertical.',
	'RS_TL_HORIZONTAL'				=> 'Horizontal',
	'RS_TL_VERTICAL'				=> 'Vertical',
	'RS_TOPLIST_NUM'				=> 'Número de usuarios a mostrar',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Número de usuarios mostrados en el top.',

	'RS_ENABLE_BAN'				=> 'Activar exclusiones',
	'RS_ENABLE_BAN_EXPLAIN'		=> 'Esto permitirá que el usuario sea excluido automáticamente basado en la reputación.',
	'RS_BAN_SHIELD'				=> 'Escudo para los excluidos',
	'RS_BAN_SHIELD_EXPLAIN'		=> 'Esta opción protege a usuarios anteriormente excluidos por reputación de volver a ser excluidos. Ese usuario no puede ser excluido de nuevo en el intervalo de tiempo definido tras su anterior exclusión.<br />Un valor de 0 desactiva este comportamiento.',
	'RS_BAN_GROUPS'				=> 'Excluir estos grupos de exclusiones',
	'RS_BAN_GROUPS_EXPLAIN'		=> 'Si no hay grupos seleccionados todos los usuarios pueden ser excluidos (excepto fundadores). Para seleccionar (o deseleccionar) varios grupos, debes CTRL+CLICK (o CMD-CLICK en Mac) los elementos para seleccionarlos. Si olvidas presionar CTRL/CMD cuando clickas sobre un elemento, todos los elementos previamente seleccionados serán deseleccionados.',

	'RS_SYNC'						=> 'Resincronizar reputaciones',
	'RS_SYNC_EXPLAIN'				=> 'Puedes resincronizar el sistema de reputación tras un borrado de temas/mensajes/usuarios, cambiar preferencias de reputación, cambiar autores de mensajes, conversiones desde otros sistemas. Esto puede tardar un poco. Serás notificado cuando el proceso finalice.<br /><strong>¡Advertencia!</strong> Durante la sincronización serán borrados los puntos de reputación que no concuerden con las preferencias de reputación. Es recomendable hacer un respaldo de la tabla de reputación antes de resincronizar.',
	'RS_SYNC_STEP_DEL'				=> 'Paso 1/7 - borrando puntos de reputación de usuarios inexistentes',
	'RS_SYNC_STEP_POSTS_DEL'		=> 'Paso 2/7 - borrando puntos de reputación de mensajes borrados',
	'RS_SYNC_STEP_REPS_DEL'			=> 'Paso 3/7 - borrando puntos de reputación que no se ajustan a las configuraciones de reputación',
	'RS_SYNC_STEP_POST_AUTHOR'		=> 'Paso 4/7 - comprobando autor de los mensajes y ajustándolo si este fue cambiado',
	'RS_SYNC_STEP_FORUM'			=> 'Paso 5/7 - comprobando preferencias del foro y comprobando su influencia en la reputación del usuario',
	'RS_SYNC_STEP_USER'				=> 'Paso 6/7 - sincronización de la reputación de los usuarios',
	'RS_SYNC_STEP_POSTS'			=> 'Paso 7/7 - sincronización de la reputación de los mensajes',
	'RS_SYNC_DONE'					=> 'La resincronización del sistema de reputación ha finalizado correctamente.',
	'RS_RESYNC_REPUTATION_CONFIRM'	=> '¿Estás seguro de que quieres resincronizar las reputaciones?',

	'RS_TRUNCATE'				=> 'Limpiar el sistema de reputación',
	'RS_TRUNCATE_EXPLAIN'		=> 'Este proceso borra todos los datos del sistema de reputación.<br /><strong>¡La acción no es reversible!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> '¿Estás seguro de que quieres limpiar el sistema de reputación?',
	'RS_TRUNCATE_DONE'			=> 'Las reputaciones fueron limpiadas.',

	'RS_GIVE_POINT'				=> 'Dar puntos de reputación',
	'RS_GIVE_POINT_EXPLAIN'		=> 'Aquí puedes dar puntos de reputación adicionales a los usuarios.',

	'RS_RANKS'					=> 'Gestionar rangos',
	'RS_RANKS_EXPLAIN'			=> 'Aquí puedes ver, editar, borrar y modificar rangos basados en la reputación. ',
	'RS_ADD_RANK'				=> 'Añadir rango',
	'RS_MUST_SELECT_RANK'		=> 'Debes seleccionar un rango',
	'RS_NO_RANK_TITLE'			=> 'Debes especificar un título para el rango',
	'RS_RANK_ADDED'				=> 'El rango fue añadido correctamente.',
	'RS_RANK_MIN'				=> 'Puntos mínimos',
	'RS_RANK_TITLE'				=> 'Título del rango',
	'RS_RANK_IMAGE'				=> 'Imagen del rango',
	'RS_RANK_COLOR'				=> 'Color del rango',
	'RS_RANK_UPDATED'			=> 'El rango ha sido correctamente actualizado.',
	'RS_IMAGE_IN_USE'			=> '(En uso)',
	'RS_RANKS_ON'				=> '<span style="color:green;">Los rangos por reputación están activados.</span>',
	'RS_RANKS_OFF'				=> '<span style="color:red;">Los rangos por reputación están desactivados.</span>',

	'RS_BANS'					=> 'Gestionar exclusiones por reputación',
	'RS_BANS_EXPLAIN'			=> 'Puedes usar este formulario para ver, añadir y borrar exclusiones por reputación.',
	'RS_BAN_POINT'				=> 'Puntos para ser excluido',
	'RS_AUTO_BAN_REASON'		=> 'Auto excluir por baja reputción',
	'RS_ADD_BAN'				=> 'Añadir exclusión',
	'RS_BAN_ADDED'				=> 'La exclusión fue añadida correctamente.',
	'RS_BAN_UPDATED'			=> 'La exclusión fue actualizada correctamente.',
	'RS_OTHER'					=> 'Otros',
	'RS_MINUTES'				=> 'minutos',
	'RS_HOURS'					=> 'horas',
	'RS_DAYS'					=> 'días',

	'RS_FORUM_REPUTATION'			=> 'Activar reputación',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Permitir a los usuarios puntuar mensajes. Puedes elegir si los votos influyen en la reputación del usuario.',
	'RS_POST_WITH_USER'				=> 'Sí, con influencia en la reputación del usuario',
	'RS_POST_WITHOUT_USER'			=> 'Sí, sin influencia en la reputación del usuario',

	'LOG_REPUTATION_SETTING'		=> '<strong>Alteradas las preferencias del sistema de reputación</strong>',
	'LOG_REPUTATION_SYNC'			=> '<strong>Sistema de reputación resincronizado</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Limpiadas las reputaciones</strong>',
	'LOG_RS_BAN_ADDED'				=> '<strong>Añadida nueva exclusión de reputación</strong>',
	'LOG_RS_BAN_REMOVED'			=> '<strong>Eliminada exclusión de reputación</strong>',
	'LOG_RS_BAN_UPDATED'			=> '<strong>Actualizada exclusión de reputación</strong>',
	'LOG_RS_RANK_ADDED'				=> '<strong>Añadido rango de reputación</strong><br />» %s',
	'LOG_RS_RANK_REMOVED'			=> '<strong>Borrado el rango de reputación</strong><br />» %s',
	'LOG_RS_RANK_UPDATED'			=> '<strong>Actualizado el rango de reputación</strong><br />» %s',
	'LOG_USER_REP_DELETE'			=> '<strong>El punto de reputación ha sido borrado/strong><br />Usuario: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>Limpiada la reputación del mensaje</strong><br />Mensaje: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Limpiada la reputación de usuario</strong><br />Usuario: %s',

	'IMG_ICON_RATE_GOOD'		=> 'Puntuar positivo',
	'IMG_ICON_RATE_BAD'			=> 'Puntuar negativo',

	//Installation
	'FILES_NOT_EXIST'		=> 'Los iconos de puntuación:<br />%s<br /> no fueron encontrados.<br /><br /><strong>Antes de continuar debes copiar los iconos de puntuación del directorio <em>contrib/images</em> al directorio imageset del estilo que estás usando. Luego refresca esta página.</strong>',
	'CONVERTER'				=> 'Conversor',
	'CONVERT_THANKS'		=> 'Convertir Thanks for posts a Reputation System',
	'CONVERT_KARMA'			=> 'Convertir Karma MOD a Reputation System',
	'CONVERT_HELPMOD'		=> 'Convertir HelpMOD a Reputation System',
	'CONVERT_LIKE'			=> 'Convertir phpBB Ajax Like a Reputation System',
	'CONVERT_THANK'			=> 'Convertir Thank You Mod a Reputation System',
	'CONVERT_DATA'			=> 'MOD convertido: %1$s.<br />Ahora, puedes desinstalar %2$s. Ve al Panel de Administración y resincroniza el sistema de reputación.',
	'UPDATE_RS_TABLE'		=> 'La tabla de reputación fue actualizada correctamente.',

	//MOD Version Check
	'ANNOUNCEMENT_TOPIC'		=> 'Anuncio de lanzamiento',
	'CURRENT_VERSION'			=> 'Versión actual',
	'DOWNLOAD_LATEST'			=> 'Descargar última versión',
	'LATEST_VERSION'			=> 'Última versión',
	'NO_INFO'					=> 'El servidor de versiones no pudo ser contactado',
	'NOT_UP_TO_DATE'			=> '%s no está actualizado',
	'RELEASE_ANNOUNCEMENT'		=> 'Tema de anuncio',
	'UP_TO_DATE'				=> '%s está actualizado',
	'VERSION_CHECK'				=> 'Comprobar versión del MOD',
));