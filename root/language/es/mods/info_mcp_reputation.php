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
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'MCP_REPUTATION'				=> 'Reputación',
	'MCP_REPUTATION_FRONT'			=> 'Página frontal',
	'MCP_REPUTATION_LIST'			=> 'Lista de reputación',
	'MCP_REPUTATION_GIVE'			=> 'Dar punto',

	'MCP_RS_ADD_WARNING'			=> 'Puntos de reputación por advertencia',
	'MCP_RS_ADD_WARNING_EXPLAIN'	=> 'Puedes dar puntos negativos a un usuario por su mal comportamiento, etc. Esto solo funcionará si has activado la caja de abajo.',
	'MCP_RS_POINTS'					=> 'Puntos',
	'MCP_RS_COMMENT'				=> 'Comentario',
	'MCP_RS_GIVE_REP_POINT'			=> 'Dar puntos de reputación',

	'LOG_USER_REP_DELETE'			=> '<strong>Los puntos de reputación han sido borrados</strong><br />Usuario: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>Borrada la reputación del mensaje</strong><br />Mensaje: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Borrada la reputación del usuario</strong><br />Usuario: %s',

	'RS_BEST_REPUTATION'			=> 'Usuarios con la mejor reputación',
	'RS_WORST_REPUTATION'			=> 'Usuarios con la peor reputación',
	'RS_REPUTATION_LISTS'			=> 'Esta es una lista de puntos de reputación. Aquí puedes encontrar todos los puntos de reputación. Puedes usar los filtros para estrechar la búsqueda. Puedes rellenar ambos campos para encontrar los puntos dados de un usuario a otro.',
	'RS_SEARCH_FROM'				=> 'Buscar puntos de reputación dados por',
	'RS_SEARCH_TO'				  	=> 'Buscar puntos de reputación recibidos por',
	'RS_DISPLAY_REPUTATIONS'		=> 'Mostrar previas puntuaciones de reputación',
));