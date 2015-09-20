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
	'RS_TITLE'			=> 'Système de réputation',

	'RS_ACTION'					=> 'Action',
	'RS_DATE'					=> 'Date',
	'RS_DETAILS'				=> 'Détails de la réputation de l’utilisateur',
	'RS_FROM'					=> 'De',
	'RS_LIST'					=> 'Liste des points de réputation de l’utilisateur',
	'RS_POST_COUNT'				=> 'Points des messages',
	'RS_POST_REPUTATION'		=> 'Réputation du message',
	'RS_USER_COUNT'				=> 'Points d’utilisateur',
	'RS_POSITIVE_COUNT'			=> 'Notes positives',
	'RS_NEGATIVE_COUNT'			=> 'Notes négatives',
	'RS_STATS'					=> 'Statistiques',
	'RS_WEEK'					=> 'La semaine dernière',
	'RS_MONTH'					=> 'Le mois dernier',
	'RS_6MONTHS'				=> 'Les 6 derniers mois',
	'RS_POINT'					=> 'Point',
	'RS_POINTS_TITLE'			=> array(
		1	=> 'Point : %d',
		2	=> 'Point(s) : %d',
	),
	'RS_POST_DELETE'			=> 'Message supprimé',
	'RS_POWER'					=> 'Points d’influence',
	'RS_TIME'					=> 'Heure',
	'RS_TO'						=> 'à',
	'RS_TO_USER'				=> 'À',
	'RS_VOTING_POWER'			=> 'Points d’influence restants',

	'RS_EMPTY_DATA'				=> 'Il n’y a pas de point de réputation.',
	'RS_NA'						=> 'N/A',
	'RS_NO_ID'					=> 'Aucun ID',
	'RS_NO_REPUTATION'			=> 'Il n’y a pas de réputation.',

	'NO_REPUTATION_SELECTED'	=> 'Vous n’avez pas sélectionné de point de réputation.',

	'RS_REPUTATION_DELETE_CONFIRM'	=> 'Voulez-vous vraiment supprimer cette réputation ?',
	'RS_REPUTATIONS_DELETE_CONFIRM'	=> 'Voulez-vous vraiment supprimer ces réputations ?',
	'RS_POINTS_DELETED'			=> array(
		1	=> 'La réputation a été supprimée.',
		2	=> 'Les réputations ont été supprimées.',
	),

	'RS_CLEAR_POST'				=> 'Effacer la réputation de ce message',
	'RS_CLEAR_POST_CONFIRM'		=> 'Voulez-vous vraiment supprimer tous les points de réputation pour ce message ?',
	'RS_CLEARED_POST'			=> 'Le message réputation a été effacé.',
	'RS_CLEAR_USER'				=> 'Effacer la réputation',
	'RS_CLEAR_USER_CONFIRM'		=> 'Voulez-vous vraiment supprimer tous les points de réputation pour cet utilisateur ?',
	'RS_CLEARED_USER'			=> 'La réputation de l’utilisateur a été effacée.',

	'RS_LATEST_REPUTATIONS'			=> 'Dernières réputations',
	'LIST_REPUTATIONS'				=> array(
		1	=> '%d réputation',
		2	=> '%d réputations',
	),
	'ALL_REPUTATIONS'				=> 'Toutes les réputations',

	'RS_NEW_REPUTATIONS'			=> 'Nouveaux points de réputation',
	'RS_NEW_REP'					=> 'Vous avez reçu <strong>1 nouveau</strong> commentaire de réputation',
	'RS_NEW_REPS'					=> 'Vous avez reçu <strong>%s nouveaux</strong> commentaires de réputation',
	'RS_CLICK_TO_VIEW'				=> 'Voir les points reçus',

	'RS_MORE_DETAILS'				=> '» Plus de détails',

	'RS_USER_REPUTATION'			=> 'Réputation de %s',

	'RS_VOTE_POWER_LEFT'			=> '%1$d sur %2$d',

	'RS_POWER_DETAILS'				=> 'Points d’influence calculés selon',
	'RS_POWER_DETAIL_AGE'			=> 'Date d’inscription',
	'RS_POWER_DETAIL_POSTS'			=> 'Nombre de messages',
	'RS_POWER_DETAIL_REPUTATION'		=> 'Réputation',
	'RS_POWER_DETAIL_WARNINGS'		=> 'Avertissements',
	'RS_POWER_DETAIL_MIN'			=> 'Minimum de points d’influence autorisé',
	'RS_POWER_DETAIL_MAX'			=> 'Maximum de points d’influence autorisé',
	'RS_POWER_DETAIL_GROUP_POWER'	=> 'Points d’influence basés sur les groupes d’utilisateurs',
	'RS_GROUP_POWER'				=> 'Groupes d’utilisateurs',
));
