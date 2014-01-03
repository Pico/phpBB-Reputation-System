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
	'UCP_REPUTATION'				=> 'Réputation',
	'UCP_REPUTATION_FRONT'			=> 'Page principale',
	'UCP_REPUTATION_LIST'			=> 'Points reçus',
	'UCP_REPUTATION_GIVEN'			=> 'Points donnés',
	'UCP_REPUTATION_SETTING'		=> 'Préférences',

	'RS_CATCHUP'						=> 'Se mettre au courant des tags',
	'RS_REPUTATION_LISTS_UCP'			=> 'Ceci est la liste de point de réputation. Ici, vous trouverez tout les points de réputation que vous avez reçu des autres membres.',
	'RS_NEW'							=> 'Nouveau!',
	'RS_REPUTATION_GIVEN_LISTS_UCP'		=> 'Ceci est la liste de point de réputation. Ici, vous trouverez tout les points de réputation que vous avez donné aux autres membres',
	'RS_REPUTATION_SETTINGS_UCP'		=> 'Paramètres de Réputation',
	'RS_DEFAULT_POWER'					=> 'pouvoir par défaut',
	'RS_DEFAULT_POWER_EXPLAIN'			=> 'Vous pouvez paramétrer le nombre de point(s) que vous donnerez par défaut.',
	'RS_EMPTY'							=> 'Pas de défaut',
	'RS_DEF_POINT'						=> 'point',
	'RS_DEF_POINTS'						=> 'points',
	'RS_NOTIFICATION'					=> 'Notification',
	'RS_NOTIFICATION_EXPLAIN'			=> 'Autoriser les notifications des nouveaux points de réputations (ceci est indépendant de vos préférences de notifications pas MP).',
	'RS_DISPLAY_REPUTATIONS'			=> 'Afficher les points de réputation à partir du précédent',
));

?>
