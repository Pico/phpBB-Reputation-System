<?php
/**
*
* @package	Reputation System
* @author	Pico88 (http://www.modsteam.tk)
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
	'MCP_REPUTATION'				=> 'Reputation',
	'MCP_REPUTATION_FRONT'			=> 'Front page',
	'MCP_REPUTATION_LIST'			=> 'List reputations',
	'MCP_REPUTATION_GIVE'			=> 'Give point',

	'MCP_RS_REP_POINT'				=> 'Reputation points for warning',
	'MCP_RS_GIVE_REP_POINT'			=> 'Give reputation points',

	'LOG_USER_REP_DELETE'			=> '<strong>Reputation point has been deleted</strong><br />User: %s',

	'RS_BEST_REPUTATION'			=> 'Users with the best reputation',
	'RS_WORST_REPUTATION'			=> 'Users with the worst reputation',
	'RS_REPUTATION_LISTS'			=> 'This is a reputation points list. Here you can find all reputation points. You can use the filters below to narrow the search. You can fill both search fields to find all reputation points given from one user to another.',
	'RS_SEARCH_FROM'				=> 'Search reputation points given by',
	'RS_SEARCH_TO'				  	=> 'Search reputation points received by',
	'RS_DISPLAY_REPUTATIONS'		=> 'Display reputation points from previous',
));

?>
