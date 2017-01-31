<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com), tomberaid (https://github.com/tomberaid), flyingrub (https://github.com/flyingrub) & monpseudo (https://github.com/monpseudo)
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
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
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_REPUTATION_SYSTEM'				=> 'Système de réputation',
	'ACP_REPUTATION_OVERVIEW'			=> 'Vue d’ensemble',
	'ACP_REPUTATION_SETTINGS'			=> 'Paramètres',
	'ACP_REPUTATION_RATE'				=> 'Noter',
	'ACP_REPUTATION_SYNC'				=> 'Synchroniser',

	'RS_FORUM_REPUTATION'			=> 'Activer les notes pour les messages (réputation)',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Autoriser tout utilisateur à noter les messages postés par les autres utilisateurs dans ce forum.',

	'RS_GROUP_POWER'				=> 'Points d’influence du groupe',
	'RS_GROUP_POWER_EXPLAIN'		=> 'Si ce champ est rempli, les points d’influence des membres seront écrasés et ne seront pas basés sur les messages etc.',

	'LOG_REPUTATION_DELETED'		=> '<strong>Réputation supprimée</strong><br />De l’utilisateur : %1$s<br />Pour l’utilisateur : %2$s<br />Points : %3$s<br/>Type : %4$s<br/>ID du message : %5$s',
	'LOG_POST_REPUTATION_CLEARED'	=> '<strong>Réputation du message effacée</strong><br />Auteur du message : %1$s<br />Sujet du message : %2$s',
	'LOG_USER_REPUTATION_CLEARED'	=> '<strong>Réputation de l’utilisateur effacée</strong><br />Utilisateur : %1$s',
	'LOG_REPUTATION_SYNC'			=> '<strong>Système de réputation resynchronisé</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Réputations effacées</strong>',
	'REPUTATION_SETTINGS_CHANGED'	=> '<strong>Paramètres du système de réputation modifiés</strong>',
));
