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
	'UCP_REPUTATION'				=> 'Reputación',
	'UCP_REPUTATION_FRONT'			=> 'Página frontal',
	'UCP_REPUTATION_LIST'			=> 'Puntos recibidos',
	'UCP_REPUTATION_GIVEN'			=> 'Puntos dados',
	'UCP_REPUTATION_SETTING'		=> 'Preferencias',

	'RS_CATCHUP'						=> 'Puesta al día',
	'RS_REPUTATION_LISTS_UCP'			=> 'Esta es una lista de puntuaciones de reputación. Aquí encontrarás todas las puntuaciones que has recibido de otros usuarios.',
	'RS_NEW'							=> '¡Nuevo!',
	'RS_REPUTATION_GIVEN_LISTS_UCP'		=> 'Esta es una lista de puntuaciones de reputación. Aquí encontrarás todas las puntuaciones que has dado a otros usuarios.',
	'RS_REPUTATION_SETTINGS_UCP'		=> 'Opciones de reputación',
	'RS_DEFAULT_POWER'					=> 'Poder por defecto',
	'RS_DEFAULT_POWER_EXPLAIN'			=> 'Puedes configurar el valor de puntos a dar por defecto.',
	'RS_EMPTY'							=> 'Sin valor por defecto',
	'RS_DEF_POINT'						=> 'punto',
	'RS_DEF_POINTS'						=> 'puntos',
	'RS_NOTIFICATION'					=> 'Notificación',
	'RS_NOTIFICATION_EXPLAIN'			=> 'Activar notificaciones de nuevos puntos de reputación.',
	'RS_DISPLAY_REPUTATIONS'			=> 'Mostrar previas puntuaciones de reputación',
));