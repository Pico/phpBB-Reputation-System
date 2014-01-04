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
	'MCP_REPUTATION'				=> 'Réputation',
	'MCP_REPUTATION_FRONT'			=> 'Page principale',
	'MCP_REPUTATION_LIST'			=> 'Liste des réputations',
	'MCP_REPUTATION_GIVE'			=> 'Donner un point',

	'MCP_RS_ADD_WARNING'			=> 'Points de réputation pour un avertissement',
	'MCP_RS_ADD_WARNING_EXPLAIN'	=> 'Vous pouvez donner des point de réputation négatif à cet utilisateurs pour un mauvais comportement etc. Cela ne marchera que si vous avez activé la checkbox en dessous.',
	'MCP_RS_POINTS'					=> 'Points',
	'MCP_RS_COMMENT'				=> 'Commentaires',
	'MCP_RS_GIVE_REP_POINT'			=> 'Donner un point de réputation',

	'LOG_USER_REP_DELETE'			=> '<strong>Le point de réputation a été supprimé</strong><br />Utilisateur: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>Réputation du message supprimée</strong><br />Messages: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Réputation de l utilisateur supprimée</strong><br />Utilisateur: %s',

	'RS_BEST_REPUTATION'			=> 'Utilisateur qui à la meilleure réputation',
	'RS_WORST_REPUTATION'			=> 'Utilisateur avec la moins bonne réputation',
	'RS_REPUTATION_LISTS'			=> 'Ceci est la liste de point de réputation. Ici, vous trouverez tout les points de réputation. Vous pouvez utiliser les filtres pour limiter la recherche. Vous pouvez remplir les deux champs pour trouver tout les points de réputation donné à utilisateurs par un autre.',
	'RS_SEARCH_FROM'				=> 'Chercher les points de réputation donner par',
	'RS_SEARCH_TO'				  	=> 'Chercher les points de réputation reçu par',
	'RS_DISPLAY_REPUTATIONS'		=> 'Afficher les points de réputation à partir du précédent',
));

?>
