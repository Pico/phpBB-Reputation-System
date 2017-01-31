<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com) & flyingrub (https://github.com/flyingrub)
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
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Sur cette page, vous pouvez configurer les paramètres du système de réputation. Ils sont divisés en groupes.',
	'ACP_REPUTATION_RATE_EXPLAIN'		=> 'Ici vous pouvez attribuer des points de réputation supplémentaires à tous les utilisateurs.',

	'RS_ENABLE'						=> 'Activer le système de réputation',

	'RS_SYNC'						=> 'Synchroniser le système de réputation',
	'RS_SYNC_EXPLAIN'				=> 'Vous pouvez synchroniser les points de réputation après une suppression de masse de messages / sujets / utilisateurs, d’une modification des paramètres de réputation, de changements d’auteurs de messages ou de conversions depuis d’autres systèmes. Cela peut prendre un certain temps. Vous serez avertis lorsque le processus sera terminé.<br /><strong>Attention !</strong> Tous les points de réputation qui ne correspondent pas aux paramètres de réputation seront supprimés lors de la synchronisation. Il est recommandé d’effectuer une sauvegarde de la table de la réputation (de la base de données) avant la synchronisation.',
	'RS_SYNC_REPUTATION_CONFIRM'	=> 'Êtes-vous sûr de vouloir synchroniser les réputations ?',

	'RS_TRUNCATE'				=> 'Effacer le système de réputation',
	'RS_TRUNCATE_EXPLAIN'		=> 'Cette procédure élimine toutes les données.<br /><strong>L’action est irréversible !</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Êtes-vous sûr de vouloir effacer le système de réputation ?',
	'RS_TRUNCATE_DONE'			=> 'Les réputations ont été effacées.',

	'REPUTATION_SETTINGS_CHANGED'	=> '<strong>Paramètres du système de réputation modifiés</strong>',

	// Setting legend
	'ACP_RS_MAIN'			=> 'Général',
	'ACP_RS_DISPLAY'		=> 'Paramètres d’affichage',
	'ACP_RS_POSTS_RATING'	=> 'Notes des messages',
	'ACP_RS_USERS_RATING'	=> 'Notes des utilisateurs',
	'ACP_RS_COMMENT'		=> 'Commentaires',
	'ACP_RS_POWER'			=> 'Points d’influence',
	'ACP_RS_TOPLIST'		=> 'Top des réputations',

	// General
	'RS_NEGATIVE_POINT'				=> 'Permettre les points négatifs',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'Lorsque désactivé les utilisateurs ne peuvent pas donner de points négatifs.',
	'RS_MIN_REP_NEGATIVE'			=> 'Réputation minimum pour les notes négatives',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'Nombre de points de réputation nécessaires à avoir pour donner des points négatifs. Définir sur 0 pour désactiver cette fonctionnalité.',
	'RS_WARNING'					=> 'Activer les points négatifs pour les avertissements',
	'RS_WARNING_EXPLAIN'			=> 'Les utilisateurs ayant les autorisations appropriées pour avertir les utilisateurs et donner des points négatifs peuvent réaliser ces deux actions en même temps.',
	'RS_WARNING_MAX_POWER'			=> 'Maximum de points d’influence pour les avertissements',
	'RS_WARNING_MAX_POWER_EXPLAIN'	=> 'Nombre maximum de points d’influence qui peuvent être retirés à l’utilisateur recevant un avertissement.',
	'RS_MIN_POINT'					=> 'Points minimums',
	'RS_MIN_POINT_EXPLAIN'			=> 'Nombre minimum de points de réputation qu’un utilisateur peut recevoir. Définir sur 0 pour désactiver cette fonctionnalité.',
	'RS_MAX_POINT'					=> 'Points maximums',
	'RS_MAX_POINT_EXPLAIN'			=> 'Nombre maximum de points de réputation qu’un utilisateur peut recevoir. Définir sur 0 pour désactiver cette fonctionnalité.',
	'RS_PREVENT_OVERRATING'			=> 'Empêcher de surévaluer',
	'RS_PREVENT_OVERRATING_EXPLAIN'	=> 'Bloque les utilisateurs souhaitant noter le même utilisateur.<br /><em>Exemple :</em> Si un utilisateur A a plus de 10 points de réputation dont 85% proviennent d’un utilisateur B, l’utilisateur B ne peut plus noter cet utilisateur tant que son ratio de notes est supérieur à 85%.<br />Pour désactiver cette fonctionnalité définir sur 0 une ou deux de ces valeurs.',
	'RS_PREVENT_NUM'				=> 'Total des points de réputation de l’utilisateur A supérieur ou égal à',
	'RS_PREVENT_PERC'				=> '<br />et le ratio des notes de l’utilisateur B est supérieur ou égal à',
	'RS_PER_PAGE'					=> 'Réputations par page',
	'RS_PER_PAGE_EXPLAIN'			=> 'Combien de lignes devons-nous afficher dans le tableau des points de réputation ?',
	'RS_DISPLAY_AVATAR'				=> 'Afficher les avatars',
	'RS_POINT_TYPE'					=> 'Méthode d’affichage des points',
	'RS_POINT_TYPE_EXPLAIN'			=> 'L’affichage des points de réputation peut être, soit représenté par la valeur exacte des points de réputation qu’un utilisateur a donné, soit sous forme d’une image montrant un plus ou un moins pour les points positifs ou négatifs. La méthode par l’image est utile si vous définissez des points de réputation ainsi la note sera toujours égal à 1 point.',
	'RS_POINT_VALUE'				=> 'Valeur',
	'RS_POINT_IMG'					=> 'Image',

	// Post rating
	'RS_POST_RATING'				=> 'Activer les notes pour les messages',
	'RS_POST_RATING_EXPLAIN'		=> 'Autoriser tout utilisateur à noter les messages postés par les autres utilisateurs.<br />Sur chaque page de gestion des forums, vous pouvez activer ou désactiver cette fonctionnalité.',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Activer le système de réputation dans tous les forums',
	'RS_ANTISPAM'					=> 'Anti-spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Bloque les utilisateurs souhaitant noter plus de messages que la limite de messages définie et suivant le laps de temps limite défini. Pour désactiver cette fonctionnalité définir sur 0 une ou deux de ces valeurs.',
	'RS_POSTS'						=> 'Nombre de messages notés',
	'RS_HOURS'						=> 'suivant le nombre d’heures',
	'RS_ANTISPAM_METHOD'			=> 'Méthode de vérification anti-spam',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Méthode de vérification anti-spam. « Même utilisateur » vérifie les réputations données pour le même utilisateur. « Tous les utilisateurs » vérifie la réputation indépendamment de qui a reçu des points.',
	'RS_SAME_USER'					=> 'Même utilisateur',
	'RS_ALL_USERS'					=> 'Tous les utilisateurs',

	// User rating
	'RS_USER_RATING'				=> 'Permet de noter les utilisateurs depuis leur page de profil',
	'RS_USER_RATING_GAP'			=> 'Intervalle des notes',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Période de temps durant laquelle un utilisateur ne peut pas donner une autre note à un utilisateur qu’il a déjà noté. 0 désactive cette fonctionnalité et permet aux utilisateurs de noter d’autres utilisateurs chaque fois qu’ils le souhaitent.',

	// Comments
	'RS_ENABLE_COMMENT'				=> 'Activer les commentaires',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'Si activé, les utilisateurs seront en mesure d’ajouter un commentaire personnel à leur note.',
	'RS_FORCE_COMMENT'				=> 'Obligation de saisir un commentaire',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Les utilisateurs seront tenus d’ajouter un commentaire à leur note.',
	'RS_COMMENT_NO'					=> 'Jamais',
	'RS_COMMENT_BOTH'				=> 'Les deux',
	'RS_COMMENT_POST'				=> 'Notes pour les messages',
	'RS_COMMENT_USER'				=> 'Notes à l’utilisateur',
	'RS_COMMEN_LENGTH'				=> 'Longueur du commentaire',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'Nombre de caractères autorisés dans un commentaire. Définir sur 0 pour un nombre illimité de caractères.',

	// Reputation power
	'RS_ENABLE_POWER'				=> 'Activer les points d’influence',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Les points d’influence sont obtenus par les utilisateurs et s’utilisent en délivrant une note. Les nouveaux utilisateurs ont peu de points d’influence, les utilisateurs actifs et les utilisateurs anciens ont plus de points d’influence. Plus vous avez de points d’influence plus vous pourrez noter longtemps suivant une période de temps déterminée et plus vous aurez d’influence sur la note d’un autre utilisateur ou d’un message.<br/>Les utilisateurs peuvent choisir lors de leur note combien de points d’influence ils vont utiliser pour cette note, leur permettant ainsi de donner plus de points à des messages intéressants.',
	'RS_POWER_RENEWAL'				=> 'Temps de renouvellement des points d’influence',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'Contrôle la façon dont les utilisateurs utilise leurs points d’influence.<br/>Si vous activez cette option, les utilisateurs doivent patienter durant l’intervalle de temps déterminé avant de pouvoir noter à nouveau. Plus l’utilisateur a des points d’influence, plus il peut utiliser de points durant cet intervalle de temps.<br /> 0 désactive cette fonctionnalité et les utilisateurs peuvent noter sans attendre.',
	'RS_MIN_POWER'					=> 'Minimum de points d’influence',
	'RS_MIN_POWER_EXPLAIN'			=> 'Nombre de points d’influence qu’obtiennent les nouveaux utilisateurs, les utilisateurs bannis, les utilisateurs à faible réputation et les utilisateurs selon d’autres critères. Les utilisateurs ne peuvent pas descendre en-dessous de ce nombre de points d’influence.<br/>0 à 10 sont autorisés. 1 est recommandé.',
	'RS_MAX_POWER'					=> 'Maximum de points d’influence',
	'RS_MAX_POWER_EXPLAIN'			=> 'Nombre maximum de points d’influence qu’un utilisateur peut utiliser par note. Même si un utilisateur a des millions de points, il sera limité par ce nombre maximum au moment de noter.<br/>Les utilisateurs pourront sélectionner ceci dans un menu déroulant : 1 à X<br/>1 à 20 sont autorisés. 3 est recommandé.',
	'RS_POWER_EXPLAIN'				=> 'Explication sur les points d’influence',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Expliquer aux utilisateurs comment les points d’influence sont calculés.',
	'RS_TOTAL_POSTS'				=> 'Points d’influence obtenus suivant le nombre de messages',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'L’utilisateur obtiendra des points d’influence tous les X messages définis ici.',
	'RS_MEMBERSHIP_DAYS'			=> 'Points d’influence obtenus suivant l’ancienneté de l’utilisateur',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'L’utilisateur obtiendra des points d’influence tous les X jours d’ancienneté définis ici.',
	'RS_POWER_REP_POINT'			=> 'Points d’influence obtenus suivant la réputation de l’utilisateur',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'L’utilisateur obtiendra des points d’influence tous les X points de réputation qu’il obtient définis ici.',
	'RS_LOSE_POWER_WARN'			=> 'Points d’influence  retirés suivant les avertissements',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Chaque avertissement diminue la quantité de points d’influence de l’utilisateur. L’expiration des avertissements est définie dans les paramètres Général -> Configuration Générale -> Configuration du forum',

	// Toplist
	'RS_ENABLE_TOPLIST'				=> 'Activer le Top des réputations',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Affiche une liste des utilisateurs ayant le plus de points de réputation sur la page d’index.',
	'RS_TOPLIST_DIRECTION'			=> 'Orientation de la liste',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Affiche les utilisateurs dans la liste suivant une orientation horizontale ou verticale.',
	'RS_TL_HORIZONTAL'				=> 'Horizontale',
	'RS_TL_VERTICAL'				=> 'Verticale',
	'RS_TOPLIST_NUM'				=> 'Nombre d’utilisateurs à afficher',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Nombre d’utilisateurs affichés dans le Top des réputations.',

	// Rate module
	'POINTS_INVALID'	=> 'Le champ Points doit contenir uniquement des chiffres.',
	'RS_VOTE_SAVED'		=> 'Note enregistrée avec succès',
));
