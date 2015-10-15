<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com)
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
	'REPUTATION'		=> 'Réputation',

	'RS_DISABLED'		=> 'Désolé, mais l’administrateur a désactivé cette fonctionnalité.',

	'RS_COMMENT'		=> 'Commentaire',
	'RS_POINTS'			=> 'Points',

	'RS_POST_REPUTATION'	=> 'Réputation du message',
	'RS_POST_RATED'			=> 'Vous avez noté ce message',
	'RS_RATE_POST_POSITIVE'	=> 'Note positive au message',
	'RS_RATE_POST_NEGATIVE'	=> 'Note négative au message',
	'RS_RATE_USER'			=> 'Noter l’utilisateur',
	'RS_VIEW_DETAILS'		=> 'Voir les détails',

	'NOTIFICATION_TYPE_REPUTATION'		=> 'Quelqu’un vous a donné un point de réputation',
	'NOTIFICATION_RATE_POST_POSITIVE'	=> '<strong>Noté positivement</strong> par %s pour le message',
	'NOTIFICATION_RATE_POST_NEGATIVE'	=> '<strong>Noté négativement</strong> par %s pour le message',
	'NOTIFICATION_RATE_USER'			=> '<strong>Noté</strong> par %s',
));
