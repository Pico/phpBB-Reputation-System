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
	'RS_ANTISPAM_INFO'			=> 'Vous ne pouvez pas donner des points de réputation si tôt. Vous pouvez réessayer plus tard.',
	'RS_COMMENT_TOO_LONG'		=> 'Votre commentaire contient %1$s caractères et est trop long.<br />Nombre maximum de caractères autorisé : %2$s.',
	'RS_NEGATIVE'				=> 'Négatif',
	'RS_NO_COMMENT'				=> 'Vous ne pouvez pas laisser le champ du commentaire vide.',
	'RS_NO_POST'				=> 'Il n’y a pas de message.',
	'RS_NO_POWER'				=> 'Vos points d’influence ne sont pas suffisants.',
	'RS_NO_POWER_LEFT'			=> 'Pas assez de points d’influence.<br/>Attendez qu’ils se renouvellent.<br/>Vos points d’influence sont de %s',
	'RS_NO_USER_ID'				=> 'L’utilisateur demandé n’existe pas.',
	'RS_POSITIVE'				=> 'Positif',
	'RS_POST_RATING'			=> 'Note donnée au message',
	'RS_RATE_BUTTON'			=> 'Noter',
	'RS_SAME_POST'				=> 'Vous avez déjà noté ce message.<br />Vous avez donné %s points de réputation.',
	'RS_SAME_USER'				=> 'Vous avez déjà noté cet utilisateur.',
	'RS_SELF'					=> 'Vous ne pouvez pas vous donner des points de réputation',
	'RS_USER_ANONYMOUS'			=> 'Vous n’êtes pas autorisé à donner des points de réputation aux utilisateurs anonymes.',
	'RS_USER_BANNED'			=> 'Vous n’êtes pas autorisé à donner des points de réputation pour les utilisateurs bannis.',
	'RS_USER_CANNOT_DELETE'		=> 'Vous n’avez pas la permission de supprimer cette réputation.',
	'RS_USER_DISABLED'			=> 'Vous n’êtes pas autorisé à donner un point de réputation.',
	'RS_USER_GAP'				=> 'Vous ne pouvez pas évaluer le même utilisateur si tôt. Vous pourrez essayer de nouveau dans %s.',
	'RS_USER_NEGATIVE'			=> 'Vous n’êtes pas autorisé à donner des points de réputation négatifs.<br />Votre réputation doit être supérieure à %s.',
	'RS_USER_RATING'			=> 'Note donnée à l’utilisateur',
	'RS_VIEW_DISALLOWED'		=> 'Vous n’êtes pas autorisé à afficher les points de réputation.',
	'RS_VOTE_POWER_LEFT_OF_MAX'	=> '%1$d points d’influence utilisés sur %2$d.<br />Maximum par note : %3$d',
	'RS_VOTE_SAVED'				=> 'Note sauvegardée',
	'RS_WARNING_RATING'			=> 'Avertissement',
));
