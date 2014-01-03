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
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, array(
	'REPUTATION'		=> 'Reputation',

	'RS_DISABLED'		=> 'Désolé, mais l'administrateur a désactivé cette fonctionnalité.',
	'RS_TITLE'			=> 'Système de réputation',

	'RS_ACTION'					=> 'Action',
	'RS_BAN'					=> 'Utilisateur banni',
	'RS_COMMENT'				=> 'Commentaire',
	'RS_DATE'					=> 'Date',
	'RS_DETAILS'				=> 'Détails de la réputation utilisateur',
	'RS_FROM'					=> 'venant',
	'RS_LIST'					=> 'Liste des points de réputation utilisateur',
	'RS_POST_COUNT'				=> 'Points des posts',
	'RS_USER_COUNT'				=> 'Points de l`utilisateur',
	'RS_POSITIVE_COUNT'			=> 'Positif',
	'RS_NEGATIVE_COUNT'			=> 'Negatif',
	'RS_STATS'					=> 'Statistique',
	'RS_WEEK'					=> 'Semaine passée',
	'RS_MONTH'					=> 'Mois passé',
	'RS_6MONTHS'				=> 'Derniers 6 mois',
	'RS_NEGATIVE'				=> 'Negatif',
	'RS_POSITIVE'				=> 'Positif',
	'RS_POINT'					=> 'Point',
	'RS_POINTS'					=> 'Points',
	'RS_POST'					=> 'Post',
	'RS_POST_DELETE'			=> 'Post supprimé',
	'RS_POWER'					=> 'Pouvoir de réputation',
	'RS_POST_RATING'			=> 'Notation du post',
	'RS_ONLYPOST_RATING'		=> 'Evaluation du post',
	'RS_RATE_BUTTON'			=> 'Note',
	'RS_RATE_POST'				=> 'Note du post',
	'RS_RATE_USER'				=> 'Note de l`utilisateur',
	'RS_RANK'					=> 'Rang de réputation',
	'RS_SENT'					=> 'Votre note de réputation a été envoyée avec succès',
	'RS_TIME'					=> 'Temps',
	'RS_TO'						=> 'à',
	'RS_TO_USER'				=> 'A',
	'RS_TYPE'					=> 'Ecrire',
	'RS_USER_RATING'			=> 'Notation de l`utilisateur',
	'RS_USER_RATING_CONFIRM'	=> 'Voulez-vous vraiment évaluer %s?',
	'RS_VIEW_DETAILS'			=> 'Voir les details',
	'RS_VOTING_POWER'			=> 'Points de pouvoir restants',
	'RS_WARNING'				=> 'Avertissement utilisateur',

	'RS_EMPTY_DATA'				=> 'Il n`y a pas de points de réputation.',
	'RS_NA'						=> 'n/a',
	'RS_NO_COMMENT'				=> 'Vous ne pouvez pas laissez le champ de commentaire vide.',
	'RS_NO_ID'					=> 'No ID',
	'RS_NO_POST_ID'				=> 'There is no such post.',
	'RS_NO_POWER'				=> 'Votre réputation est trop faible.',
	'RS_NO_POWER_LEFT'			=> 'Pas assez points de pouvoir de réputation.<br/>Attendez qu`ils raffraichissent.<br/>Votre pouvoir de réputation est %s',
	'RS_NO_USER_ID'				=> 'L`utilisateur demandé nèxiste pas.',
	'RS_TOO_LONG_COMMENT'		=> 'Votre commentaire contient %1$d caractères. Le nombre maximal de caractères autorisés est %2$d.',
	'RS_COMMENT_TOO_LONG'		=> 'Commentaire trop long.<br />Max caractères: %s. Votre commentaire:',

	'RS_NO_POST'				=> 'Il n`y a pas de tel post.',
	'RS_SAME_POST'				=> 'Vous avez déjà évalué ce post.<br />Vous avez donné %s points de réputation.',
	'RS_SAME_USER'				=> 'Vous avez déjà donné la réputation de cet utilisateur.',
	'RS_SELF'					=> 'Vous ne pouvez pas vous évaluer vous même.',
	'RS_USER_ANONYMOUS'			=> 'Vous n`êtes pas autorisé à donner des points de réputation aux utilisateurs anonymes.',
	'RS_USER_BANNED'			=> 'Vous n`êtes pas autorisé à donner des points de réputation aux utilisateurs bannis.',
	'RS_USER_CANNOT_DELETE'		=> 'Vous n`êtes pas autorisé à supprimer des points.',
	'RS_USER_DISABLED'			=> 'Vous n`êtes pas autorisé à donner des points de réputation.',
	'RS_USER_NEGATIVE'			=> 'Vous n`êtes pas autorisé à donner des points de réputation négatifs.<br />Votre réputation doit être supérieure à %s.',
	'RS_VIEW_DISALLOWED'		=> 'Vous n`êtes pas autorisé à voir les points de réputation.',

	'RS_DELETE_POINT'			=> 'Supprimer un point',
	'RS_DELETE_POINT_CONFIRM'	=> 'Voulez-vous vraiment supprimer ce point de réputation ?',
	'RS_POINT_DELETED'			=> 'Le point de réputation a été supprimé.',
	'RS_DELETE_POINTS'			=> 'Supprimer des points',
	'RS_DELETE_POINTS_CONFIRM'	=> 'Voulez-vous vraiment supprimer ces points de réputation ?',
	'RS_POINTS_DELETED'			=> 'Les points de réputation ont été supprimés.',
	'NO_REPUTATION_SELECTED'	=> 'Vous n`avez pas sélectionné de point de réputation.',
	'RS_CLEAR_POST_CONFIRM'		=> 'Voulez-vous vraiment supprimer tous les points de réputation de ce post ?',
	'RS_CLEAR_USER_CONFIRM'		=> 'Voulez-vous vraiment supprimer tous les points de réputation de cet utilisateur ?',
	'RS_CLEAR_POST'				=> 'Vider la réputation du post',
	'RS_CLEAR_USER'				=> 'Vider la réputation de l`utilisateur',

	'RS_PM_BODY'				=> 'Vous avez reçu un point de l`expéditeur de ce message. <br />Points: [b]%s&nbsp;[/b] <br />Cliquez %sici%s pour afficher le message.',
	'RS_PM_BODY_COMMENT'		=> 'Vous avez reçu un point de l`expéditeur de ce message. <br />Points: [b]%s&nbsp;[/b] <br />Commentaire: [i]%s&nbsp;[/i] <br />Cliquez %sici%s pour afficher le message.',
	'RS_PM_BODY_USER'			=> 'Vous avez reçu un point de l`expéditeur de ce message. <br />Points: [b]%s&nbsp;[/b]',
	'RS_PM_BODY_USER_COMMENT'	=> 'Vous avez reçu un point de l`expéditeur de ce message. <br />Points: [b]%s&nbsp;[/b] <br />Commentaire: [i]%s&nbsp;[/i]',
	'RS_PM_SUBJECT'				=> 'Vous avez reçu un point de réputation',

	'RS_TOPLIST'			=> 'Toplist de réputation',
	'RS_TOPLIST_EXPLAIN'	=> 'Membres les plus populaires',

	'NOTIFY_USER_REP'		=> 'Notifier l utilisateur du point?',

	'RS_LATEST_REPUTATIONS'			=> 'Dernières réputations',
	'LIST_REPUTATION'				=> '1 réputation',
	'LIST_REPUTATIONS'				=> '%s réputations',
	'ALL_REPUTATIONS'				=> 'Toutes les réputations',

	'RS_NEW_REPUTATIONS'			=> 'Nouveaux points de réputation',
	'RS_NEW_REP'					=> 'Vous avez reçu un <bon>1 nouveau</bon> commentaire de réputation',
	'RS_NEW_REPS'					=> 'Vous avez reçu de <bons>%s nouveaux</bons> commentaires de réputation',
	'RS_CLICK_TO_VIEW'				=> 'Aller aux points reçus',

	'RS_MORE_DETAILS'				=> '» Plus de détails',

	'RS_HIDE_POST'					=> 'Ce message a été fait par <strong>%1$s</strong> et a été caché car il avait une trop faible cote. %2$s',
	'RS_SHOW_HIDDEN_POST'			=> 'Afficher ce message',
	'RS_SHOW_HIDE_HIDDEN_POST'		=> 'Afficher / Cacher',
	'RS_ANTISPAM_INFO'				=> 'Vous ne pouvez pas donner réputation si tôt. You may try again later.',
	'RS_POST_REPUTATION'			=> 'Réputation de post',
	'RS_USER_REPUTATION'			=> '%s\'s réputation',
	'RS_YOU_RATED'					=> 'Vous avez noté ce post. Points:',

	'RS_VOTE_POWER_LEFT_OF_MAX'		=> '%1$d points de pouvoir de réputation à gauche de %2$d.<br />Maximum par vote: %3$d',
	'RS_VOTE_POWER_LEFT'			=> '%1$d of %2$d',

	'RS_POWER_DETAILS'				=> 'Comment le pouvoir de réputation doit être calculé',
	'RS_POWER_DETAIL_AGE'			=> 'Par date dìnscription',
	'RS_POWER_DETAIL_POSTS'			=> 'Par le nombre de posts',
	'RS_POWER_DETAIL_REPUTAION'		=> 'Par réputation',
	'RS_POWER_DETAIL_WARNINGS'		=> 'Par avertissements',
	'RS_POWER_DETAIL_BANS'			=> 'Par nombre de bannissements dans la dernière année',
	'RS_POWER_DETAIL_MIN'			=> 'Pouvoir de réputation minimum pour tous les utilisateurs',
	'RS_POWER_DETAIL_MAX'			=> 'Pouvoir de réputation plafonnée à la valeur maximale autorisée',
	'RS_GROUP_POWER'				=> 'Pouvoir de réputation basé sur un groupe d`utilisateurs',

	'RS_USER_GAP'					=> 'Vous ne pouvez pas évaluer le même utilisateur si tôt. Vous pouvez réessayer dans %s.',
));

?>
