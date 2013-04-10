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

	'RS_POST_RATING'				=> 'Enable post rating',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Submit and enable Reputation System in all forums',
	'RS_HIGHLIGHT_POST'				=> 'Highlighting a post',
	'RS_HIGHLIGHT_POST_EXPLAIN'		=> 'Post with rating higer than the number set here will be highlighted. Setting the value to 0 disables this behaviour.<br /><em>Note:</em> You can modify default highlighting by editing <strong>highlight</strong> class in reputation.css.',
	'RS_HIDE_POST'					=> 'Hide posts with low ratings',
	'RS_HIDE_POST_EXPLAIN'			=> 'Posts with a rating less than the number set here will be hidden by default (users have the option to unhide them if they choose). After a post has earned a rating greater than this value, it will no longer be hidden by default. Setting the value to 0 disables this behaviour.',
	'RS_ANTISPAM'					=> 'Anti-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Block users from rating any more posts after they have rated the defined number of posts within the defined number of hours. To disable this feature set one or both values to 0.',
	'RS_POSTS'						=> 'Number of rated posts',
	'RS_HOURS'						=> 'in the last hours',
	'RS_ANTISPAM_METHOD'			=> 'Anti-Spam check method',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Method for checking Anti-Spam. “Same user” method checks reputation given to the same user. “All users” method checks reputation regardless of who received points. ',
	'RS_SAME_USER'					=> 'Same user',
	'RS_ALL_USERS'					=> 'All users',

	'RS_USER_RATING'				=> 'Allow rating of users from their profile page',
	'RS_USER_RATING_GAP'			=> 'Voting gap',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Time period a user must wait before they can give another rating to a user they have already rated. Setting the value to 0 disables this behaviour and users can rate other users once each.',

	'RS_ENABLE_COMMENT'				=> 'Enable comments',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'When enabled, users will be able to add a personal comment with their rating.',
	'RS_FORCE_COMMENT'				=> 'Force user to enter comment',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Users will be required to add a comment with their rating.',
	'RS_COMMENT_NO'					=> 'No',
	'RS_COMMENT_BOTH'				=> 'Both user and post ratings',
	'RS_COMMENT_POST'				=> 'Only post ratings',
	'RS_COMMENT_USER'				=> 'Only user ratings',
	'RS_COMMEN_LENGTH'				=> 'Comment length',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'The number of characters allowed within a comment. Set to 0 for unlimited characters.',

	'RS_ENABLE_POWER'				=> 'Enable reputation power',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Reputation power is something that users earn and spend on voting. New users have low power, active and veteran users gain more power. The more power you have the more you can vote during a specified period of time and the more influence you can have on the rating of another user or post.<br/>Users can choose during voting how much power they will spend on a vote, giving more points to interesting posts.',
	'RS_POWER_RENEWAL'				=> 'Power renewal time',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'This controls how users can spend earned power.<br/>If you set this option, users must wait for the given time interval before they can vote again. The more reputation power a user has, the more points they can spend in the set time.<br/>Recommended 5 hours.<br />Setting the value to 0 disables this behaviour and users can vote without waiting.',
	'RS_MIN_POWER'					=> 'Starting/Minimum reputation power',
	'RS_MIN_POWER_EXPLAIN'			=> 'This is how much reputation power newly registered users, banned users and users with low reputation or other criteria have. Users can’t go lower than this minimum voting power.<br/>Allowed 0-10. Recommended 1.',
	'RS_MAX_POWER'					=> 'Maximum power spending per vote',
	'RS_MAX_POWER_EXPLAIN'			=> 'Maximum amount of power that a user can spend per vote. Even if a user has millions of points, they’ll still be limited by this maximum number when voting.<br/>Users will select this from dropdown menu: 1 to X<br/>Allowed 1-20. Recommended: 3.',
	'RS_MAX_POWER_WARNING'			=> 'Maximum reputation power for warnings',
	'RS_MAX_POWER_WARNING_EXPLAIN'	=> 'Maximum reputation power allowed for warnings.',
	'RS_MAX_POWER_BAN'				=> 'Maximum reputation power for bans',
	'RS_MAX_POWER_BAN_EXPLAIN'		=> 'Maximum reputation power a user gets if they are banned for 1 month or permanently. If a user is banned for a shorter period of time, they will receive a relative number of points.',
	'RS_POWER_EXPLAIN'				=> 'Reputation power explanation',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Explain how reputation power is calculated to users.',
	'RS_TOTAL_POSTS'				=> 'Gain power with number of posts',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'User will gain 1 reputation power every X number of posts set here.',
	'RS_MEMBERSHIP_DAYS'			=> 'Gain power with length of the user’s membership',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'User will gain 1 reputation power every X number of days set here',
	'RS_POWER_REP_POINT'			=> 'Gain power with the user’s reputation',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'User will gain 1 reputation power every X number of reputation points they earn set here.',
	'RS_LOSE_POWER_BAN'				=> 'Lose power with bans',
	'RS_LOSE_POWER_BAN_EXPLAIN'		=> 'Each ban within the last year decreases reputation power by this amount of points',
	'RS_LOSE_POWER_WARN'			=> 'Lose power with warnings',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Each warning decreases reputation power by this amount of points. Warnings expire in accordance with the settings in General -> Board Configuration -> Board settings',
	'RS_GROUP_POWER'				=> 'Group reputation power',

	'RS_RANKS_ENABLE'				=> 'Enable ranks',
	'RS_RANKS_PATH'					=> 'Reputation rank image storage path',
	'RS_RANKS_PATH_EXPLAIN'			=> 'Path in your phpBB root directory, e.g. <samp>images/reputation</samp>.',

	'RS_ENABLE_TOPLIST'				=> 'Enable Toplist',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Display a list of users with the most reputation points on the index page.',
	'RS_TOPLIST_DIRECTION'			=> 'Direction of list',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Display the users in the list in a horizontal or vertical direction.',
	'RS_TL_HORIZONTAL'				=> 'Horizontal',
	'RS_TL_VERTICAL'				=> 'Vertical',
	'RS_TOPLIST_NUM'				=> 'Number of Users to Display',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Number of users displayed on the toplist.',

	'RS_ENABLE_BAN'				=> 'Enable bans',
	'RS_ENABLE_BAN_EXPLAIN'		=> 'This will allow a user to be banned automatically based on reputation.',
	'RS_BAN_SHIELD'				=> 'Shield for the banned',
	'RS_BAN_SHIELD_EXPLAIN'		=> 'This option protects a previously banned user from further bans based on reputation. Such a user cannot be re-banned in the given time frame after their previous ban has expired.<br />Setting the value to 0 disables this behaviour.',
	'RS_BAN_GROUPS'				=> 'Exclude these groups from banning',
	'RS_BAN_GROUPS_EXPLAIN'		=> 'If there are no selected groups then all users can be banned (except founders). In order to select (or deselect) multiple groups, you must CTRL+CLICK (or CMD-CLICK on Mac) items to add them. If you forget to hold down CTRL/CMD when clicking an item, then all the previously selected items will be deselected.',

	'RS_SYNC'						=> 'Resynchronise reputations',
	'RS_SYNC_EXPLAIN'				=> 'You can resynchronise Reputation System after a mass removal of posts/topics/users, changing reputation settings, changing post authors, conversions from others systems. This may take a while. You will be notified when the process is completed.<br /><strong>Warning!</strong> During synchronization will be deleted reputation points that do not match the reputation settings. It is recommended to make backup of the reputation table (DB) before synchronisation.',
	'RS_SYNC_STEP_DEL'				=> 'Step 1/7 - removing reputation points of non-existent users',
	'RS_SYNC_STEP_POSTS_DEL'		=> 'Step 2/7 - removing reputation points of deleted posts',
	'RS_SYNC_STEP_REPS_DEL'			=> 'Step 3/7 - removing reputations, which do not match reputation settings',
	'RS_SYNC_STEP_POST_AUTHOR'		=> 'Step 4/7 - checking author of a post and synchronising reputation entry if it was changed',
	'RS_SYNC_STEP_FORUM'			=> 'Step 5/7 - checking forum settings and synchronising post reputation influence on user reputation',
	'RS_SYNC_STEP_USER'				=> 'Step 6/7 - synchronisation of users reputations',
	'RS_SYNC_STEP_POSTS'			=> 'Step 7/7 - synchronisation of posts reputations',
	'RS_SYNC_DONE'					=> 'Reputation System synchronisation has finished successfully',
	'RS_RESYNC_REPUTATION_CONFIRM'	=> 'Are you sure you wish to resynchronise reputations?',

	'RS_TRUNCATE'				=> 'Clear Reputation System',
	'RS_TRUNCATE_EXPLAIN'		=> 'This procedure completely removes all data.<br /><strong>Action is not reversible!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Are you sure you wish to clear Reputation System?',
	'RS_TRUNCATE_DONE'			=> 'Reputations were cleared.',

	'RS_GIVE_POINT'				=> 'Give reputation points',
	'RS_GIVE_POINT_EXPLAIN'		=> 'Here you can give additional reputation points to users.',

	'RS_RANKS'					=> 'Manage ranks',
	'RS_RANKS_EXPLAIN'			=> 'Here you can add, edit, view and delete ranks based on reputation points. ',
	'RS_ADD_RANK'				=> 'Add Rank',
	'RS_MUST_SELECT_RANK'		=> 'You must select a rank',
	'RS_NO_RANK_TITLE'			=> 'You must specify a title for the rank',
	'RS_RANK_ADDED'				=> 'The rank was successfully added.',
	'RS_RANK_MIN'				=> 'Minimum points',
	'RS_RANK_TITLE'				=> 'Rank title',
	'RS_RANK_IMAGE'				=> 'Rank image',
	'RS_RANK_COLOR'				=> 'Rank color',
	'RS_RANK_UPDATED'			=> 'The rank was successfully updated.',
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

	'RS_FORUM_REPUTATION'			=> 'Enable reputation',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Allow users to rate posts. You can choose if rating posts influence user reputation.',
	'RS_POST_WITH_USER'				=> 'Yes, with influence on user reputation',
	'RS_POST_WITHOUT_USER'			=> 'Yes, without influence on user reputation',

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