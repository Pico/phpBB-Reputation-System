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
	'MCP_REPUTATION'				=> 'Reputação',
	'MCP_REPUTATION_FRONT'			=> 'Página Inicial',
	'MCP_REPUTATION_LIST'			=> 'Lista de Reputações',
	'MCP_REPUTATION_GIVE'			=> 'Conceder ponto',

	'MCP_RS_ADD_WARNING'			=> 'Ponto de reputação para o aviso',
	'MCP_RS_ADD_WARNING_EXPLAIN'	=> 'Você pode dar pontos negativos para um usuário por um mal comportamento etc. Isto somente funciona se você marcar o checkbox abaixo.',
	'MCP_RS_POINTS'					=> 'Pontos',
	'MCP_RS_COMMENT'				=> 'Comentário',
	'MCP_RS_GIVE_REP_POINT'			=> 'Conceder pontos de reputação',

	'LOG_USER_REP_DELETE'			=> '<strong>Ponto de reputação foi deletado</strong><br />User: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>LImpar reputação do post</strong><br />Post: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Limpar reputação de usuário</strong><br />User: %s',

	'RS_BEST_REPUTATION'			=> 'Usuários com a melhor reputação',
	'RS_WORST_REPUTATION'			=> 'Usuários com a pior reputação',
	'RS_REPUTATION_LISTS'			=> 'Esta é uma lista de pontos de reputação. Aqui você pode encontrar todos os pontos de reputação. Você pode utilizar os filtros abaixo para refinar sua pesquisa. Você pode preencher ambos os campos para achar todos os pontos de reputação concedidos por um usuário a outro.',

	'RS_SEARCH_FROM'				=> 'Procurar por pontos de reputação concedidos por',
	'RS_SEARCH_TO'				  	=> 'Procurar por pontos de reputação recebidos por',
	'RS_DISPLAY_REPUTATIONS'		=> 'Mostrar pontos de reputação a partir de anteriores',
));

?>
