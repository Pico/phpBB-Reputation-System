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
	'REPUTATION_SYSTEM'				=> 'Système de réputation',

	'ACP_REPUTATION_SYSTEM'				=> 'Système de réputation',
	'ACP_REPUTATION_SYSTEM_EXPLAIN'		=> 'Sur cette page, vous pouvez gérer le système de réputation.<br />Le menu de gauche vous aidera à naviguer entre les sections.',
	'ACP_REPUTATION_OVERVIEW'			=> 'Informations',
	'ACP_REPUTATION_SETTINGS'			=> 'Paramètres',
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Sur cette page, vous pouvez configurer les paramètres du système de réputation. Ils sont divisés en groupe.',
	'ACP_REPUTATION_GIVE'				=> 'Donner un point',
	'ACP_REPUTATION_RANKS'				=> 'Rang',
	'MCP_REPUTATION'					=> 'Réputation',
	'MCP_REPUTATION_FRONT'				=> 'Première page',
	'MCP_REPUTATION_LIST'				=> 'Liste',
	'MCP_REPUTATION_GIVE'				=> 'Donner un point',
	'UCP_REPUTATION'					=> 'Réputation',
	'UCP_REPUTATION_FRONT'				=> 'Première page',
	'UCP_REPUTATION_LIST'				=> 'Liste des points reçus',
	'UCP_REPUTATION_GIVEN'				=> 'Liste des points donnés',
	'UCP_REPUTATION_SETTING'			=> 'Paramètres',

	'ACP_RS_MAIN'			=> 'Général',
	'ACP_RS_DISPLAY'		=> 'Affichage',
	'ACP_RS_POSTS_RATING'	=> 'Classement des messages',
	'ACP_RS_USERS_RATING'	=> 'Classement des utilisateurs',
	'ACP_RS_COMMENT'		=> 'Commentaires',
	'ACP_RS_POWER'			=> 'Pouvoir de réputation',
	'ACP_RS_RANKS'			=> 'Rangs',
	'ACP_RS_TOPLIST'		=> 'Liste principale',

	'RS_ENABLE'		=> 'Acitver le Système de réputation',

	'RS_NEGATIVE_POINT'				=> 'Autoriser les points négatifs',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'Quand désactivé les utilisateurs ne peuvent pas donner de points négatif.',
	'RS_MIN_REP_NEGATIVE'			=> 'Réputation minimum pour les votes négatif',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'Combien de point de réputation est il nécessaire d avoir pour donner des points négatif. Mettre à 0 pour désactiver.',
	'RS_WARNING'					=> 'Active les avertissements',
	'RS_WARNING_EXPLAIN'			=> 'Utilisateurs avec les bonnes permissions peuvent donner des points négatifs en donnant des avertissements.',
	'RS_NOTIFICATION'				=> 'Active les notification',
	'RS_NOTIFICATION_EXPLAIN'		=> 'Cette option active la notification des nouveaux points de réputation dans l en tête.',
	'RS_PM_NOTIFY'					=> 'Active les notifications par MP',
	'RS_PM_NOTIFY_EXPLAIN'			=> 'Cette option autorise les utilisateurs a envoyer des MP a chaque nouveau points de réputations8.',
	'RS_MIN_POINT'					=> 'Points minimum',
	'RS_MIN_POINT_EXPLAIN'			=> 'Limite les points de réputation minimum qu un utilisateur peux recevoir. Mettre à 0 pour désactiver.',
	'RS_MAX_POINT'					=> 'point maximum',
	'RS_MAX_POINT_EXPLAIN'			=> 'Limite les points de réputation maximum qu un utilisateur peux recevoir. Mettre à 0 pour désactiver.',
	'RS_PREVENT_OVERRATING'			=> 'Éviter la sur-notation',
	'RS_PREVENT_OVERRATING_EXPLAIN'	=> 'Bloque les utilisateurs de noter le même utilisateur.<br /><em>Exemple:</em> Si un utilisateur a plus de 10 point de réputation et 85% d enter eux viennent de l utilisateur B, celui ci ne peux plus attribuer de point cet utilisateur jusqu à ce que sont ratio soit inférieur.<br />Mettre à 0 une ou deux de ces valeurs pour désactiver.',
	'RS_PREVENT_NUM'				=> 'Le nombre total de point d un utilisateurs A est plus grand ou egal à',
	'RS_PREVENT_PERC'				=> '<br />et le ratio de l utilisateur B est plus grand ou égal à',

	'RS_PER_PAGE'							=> 'Réputation par page',
	'RS_PER_PAGE_EXPLAIN'					=> 'Combien de How many rangée doit-on afficher dans la table des points de réputation?',
	'RS_DISPLAY_AVATAR'						=> 'Affiche les avatars',
	'RS_SORT_MEMBERLIST_BY_REPO'			=> 'Trier les membres par leur réputation',
	'RS_SORT_MEMBERLIST_BY_REPO_EXPLAIN'	=> 'When the memberlist is being sorted by reputation it makes more sense to check it regularly to keep an eye on development. You may switch this feature off to return to the default behaviour of sorting by username.',
	'RS_POINT_TYPE'							=> 'Method for displaying points',
	'RS_POINT_TYPE_EXPLAIN'					=> 'Viewing reputation points can be displayed as either the exact value of reputation points a user gave or as an image showing a plus or minus for positive or negative points. The Image method is useful if you set up reputation points so that one rating always equals to one point.',
	'RS_POINT_VALUE'						=> 'Value',
	'RS_POINT_IMG'							=> 'Image',

	'RS_POST_RATING'				=> 'Enable post rating',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Submit and enable Reputation System in all forums',
	'RS_HIGHLIGHT_POST'				=> 'Highlighting a post',
	'RS_HIGHLIGHT_POST_EXPLAIN'		=> 'Post with rating higer than the number set here will be highlighted. Setting the value to 0 disables this behaviour.<br /><em>Note:</em> You can modify default highlighting by editing <strong>highlight</strong> class in reputation.css.',
	'RS_HIDE_POST'					=> 'Cacher les posts avec un vote négatif',
	'RS_HIDE_POST_EXPLAIN'			=> 'Posts with a rating less than the number set here will be hidden by default (users have the option to unhide them if they choose). After a post has earned a rating greater than this value, it will no longer be hidden by default. Setting the value to 0 disables this behaviour.',
	'RS_ANTISPAM'					=> 'Anti-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Block users from rating any more posts after they have rated the defined number of posts within the defined number of hours. To disable this feature set one or both values to 0.',
	'RS_POSTS'						=> 'Nombre de post noté',
	'RS_HOURS'						=> 'Dans la dernière heure',
	'RS_ANTISPAM_METHOD'			=> 'Anti-Spam check method',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Method for checking Anti-Spam. “Same user” method checks reputation given to the same user. “All users” method checks reputation regardless of who received points.',
	'RS_SAME_USER'					=> 'Même utilisateurs',
	'RS_ALL_USERS'					=> 'Tou les utilisateurs',

	'RS_USER_RATING'				=> 'Allow rating of users from their profile page',
	'RS_USER_RATING_GAP'			=> 'Voting gap',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Time period a user must wait before they can give another rating to a user they have already rated. Setting the value to 0 disables this behaviour and users can rate other users once each.',

	'RS_ENABLE_COMMENT'				=> 'Enable comments',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'When enabled, users will be able to add a personal comment with their rating.',
	'RS_FORCE_COMMENT'				=> 'Force user to enter comment',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Users will be required to add a comment with their rating.',
	'RS_COMMENT_NO'					=> 'No',
	'RS_COMMENT_BOTH'				=> 'Both user and post ratings',
	'RS_COMMENT_POST'				=> 'Only post ratings',
	'RS_COMMENT_USER'				=> 'Only user ratings',
	'RS_COMMEN_LENGTH'				=> 'Comment length',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'The number of characters allowed within a comment. Set to 0 for unlimited characters.',

	'RS_ENABLE_POWER'				=> 'Enable reputation power',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Reputation power is something that users earn and spend on voting. New users have low power, active and veteran users gain more power. The more power you have the more you can vote during a specified period of time and the more influence you can have on the rating of another user or post.<br/>Users can choose during voting how much power they will spend on a vote, giving more points to interesting posts.',
	'RS_POWER_RENEWAL'				=> 'Power renewal time',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'This controls how users can spend earned power.<br/>If you set this option, users must wait for the given time interval before they can vote again. The more reputation power a user has, the more points they can spend in the set time.<br/>Recommended 5 hours.<br />Setting the value to 0 disables this behaviour and users can vote without waiting.',
	'RS_MIN_POWER'					=> 'Starting/Minimum reputation power',
	'RS_MIN_POWER_EXPLAIN'			=> 'This is how much reputation power newly registered users, banned users and users with low reputation or other criteria have. Users can’t go lower than this minimum voting power.<br/>Allowed 0-10. Recommended 1.',
	'RS_MAX_POWER'					=> 'Maximum power spending per vote',
	'RS_MAX_POWER_EXPLAIN'			=> 'Maximum amount of power that a user can spend per vote. Even if a user has millions of points, they’ll still be limited by this maximum number when voting.<br/>Users will select this from dropdown menu: 1 to X<br/>Allowed 1-20. Recommended: 3.',
	'RS_MAX_POWER_WARNING'			=> 'Maximum reputation power for warnings',
	'RS_MAX_POWER_WARNING_EXPLAIN'	=> 'Maximum reputation power allowed for warnings.',
	'RS_MAX_POWER_BAN'				=> 'Maximum reputation power for bans',
	'RS_MAX_POWER_BAN_EXPLAIN'		=> 'Maximum reputation power a user gets if they are banned for 1 month or permanently. If a user is banned for a shorter period of time, they will receive a relative number of points.',
	'RS_POWER_EXPLAIN'				=> 'Reputation power explanation',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Explain how reputation power is calculated to users.',
	'RS_TOTAL_POSTS'				=> 'Gain power with number of posts',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'User will gain 1 reputation power every X number of posts set here.',
	'RS_MEMBERSHIP_DAYS'			=> 'Gain power with length of the user’s membership',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'User will gain 1 reputation power every X number of days set here',
	'RS_POWER_REP_POINT'			=> 'Gain power with the user’s reputation',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'User will gain 1 reputation power every X number of reputation points they earn set here.',
	'RS_LOSE_POWER_BAN'				=> 'Lose power with bans',
	'RS_LOSE_POWER_BAN_EXPLAIN'		=> 'Each ban within the last year decreases reputation power by this amount of points',
	'RS_LOSE_POWER_WARN'			=> 'Lose power with warnings',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Each warning decreases reputation power by this amount of points. Warnings expire in accordance with the settings in General -> Board Configuration -> Board settings',
	'RS_GROUP_POWER'				=> 'Group reputation power',

	'RS_RANKS_ENABLE'				=> 'Enable ranks',
	'RS_RANKS_PATH'					=> 'Reputation rank image storage path',
	'RS_RANKS_PATH_EXPLAIN'			=> 'Path in your phpBB root directory, e.g. <samp>images/reputation</samp>.',

	'RS_ENABLE_TOPLIST'				=> 'Enable Toplist',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Display a list of users with the most reputation points on the index page.',
	'RS_TOPLIST_DIRECTION'			=> 'Direction of list',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Display the users in the list in a horizontal or vertical direction.',
	'RS_TL_HORIZONTAL'				=> 'Horizontal',
	'RS_TL_VERTICAL'				=> 'Vertical',
	'RS_TOPLIST_NUM'				=> 'Number of Users to Display',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Number of users displayed on the toplist.',

	'RS_SYNC'						=> 'Synchronise Reputation System',
	'RS_SYNC_EXPLAIN'				=> 'You can synchronise reputation points after a mass removal of posts/topics/users, changing reputation settings, changing post authors, conversions from others systems. This may take a while. You will be notified when the process is completed.<br /><strong>Warning!</strong> All reputation points, that do not match the reputation settings, will be deleted during synchronization . It is recommended to make backup of the reputation table (DB) before synchronisation.',
	'RS_SYNC_REPUTATION_CONFIRM'	=> 'Are you sure you wish to synchronise reputations?',
	'RS_SYNC_STEP_DEL'				=> 'Step 1/7 - removing reputation points of non-existent users',
	'RS_SYNC_STEP_POSTS_DEL'		=> 'Step 2/7 - removing reputation points of deleted posts',
	'RS_SYNC_STEP_REPS_DEL'			=> 'Step 3/7 - removing reputations, which do not match reputation settings',
	'RS_SYNC_STEP_POST_AUTHOR'		=> 'Step 4/7 - checking author of a post and synchronising reputation entry if it was changed',
	'RS_SYNC_STEP_FORUM'			=> 'Step 5/7 - checking forum settings and synchronising post reputation influence on user reputation',
	'RS_SYNC_STEP_USERS'			=> 'Step 6/7 - synchronisation of users reputations',
	'RS_SYNC_STEP_POSTS'			=> 'Step 7/7 - synchronisation of posts reputations',
	'RS_SYNC_DONE'					=> 'Reputation System synchronisation has finished successfully',

	'RS_TRUNCATE'				=> 'Clear Reputation System',
	'RS_TRUNCATE_EXPLAIN'		=> 'This procedure completely removes all data.<br /><strong>Action is not reversible!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Are you sure you wish to clear Reputation System?',
	'RS_TRUNCATE_DONE'			=> 'Reputations were cleared.',

	'RS_GIVE_POINT'				=> 'Give reputation points',
	'RS_GIVE_POINT_EXPLAIN'		=> 'Here you can give additional reputation points to users.',

	'RS_RANKS'					=> 'Manage ranks',
	'RS_RANKS_EXPLAIN'			=> 'Here you can add, edit, view and delete ranks based on reputation points. ',
	'RS_ADD_RANK'				=> 'Add Rank',
	'RS_MUST_SELECT_RANK'		=> 'You must select a rank',
	'RS_NO_RANK_TITLE'			=> 'You must specify a title for the rank',
	'RS_RANK_ADDED'				=> 'The rank was successfully added.',
	'RS_RANK_MIN'				=> 'Minimum points',
	'RS_RANK_TITLE'				=> 'Rank title',
	'RS_RANK_IMAGE'				=> 'Rank image',
	'RS_RANK_COLOR'				=> 'Rank color',
	'RS_RANK_UPDATED'			=> 'The rank was successfully updated.',
	'RS_IMAGE_IN_USE'			=> '(In use)',
	'RS_RANKS_ON'				=> '<span style="color:green;">Reputation ranks are turned on.</span>',
	'RS_RANKS_OFF'				=> '<span style="color:red;">Reputation ranks are turned off.</span>',
	'RS_NO_RANKS'				=> 'No reputation ranks',

	'RS_FORUM_REPUTATION'			=> 'Enable reputation',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Allow users to rate posts. You can choose if rating posts influence user reputation.',
	'RS_POST_WITH_USER'				=> 'Yes, with influence on user reputation',
	'RS_POST_WITHOUT_USER'			=> 'Yes, without influence on user reputation',

	'LOG_REPUTATION_SETTING'		=> '<strong>Altered Reputation System settings</strong>',
	'LOG_REPUTATION_SYNC'			=> '<strong>Reputation System resynchronised</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Cleared reputations</strong>',
	'LOG_RS_RANK_ADDED'				=> '<strong>Added new reputation rank</strong><br />» %s',
	'LOG_RS_RANK_REMOVED'			=> '<strong>Removed reputation rank</strong><br />» %s',
	'LOG_RS_RANK_UPDATED'			=> '<strong>Updated reputation rank</strong><br />» %s',
	'LOG_USER_REP_DELETE'			=> '<strong>Reputation point has been deleted</strong><br />User: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>Cleared post reputation</strong><br />Post: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Cleared user reputation</strong><br />User: %s',

	'IMG_ICON_RATE_GOOD'		=> 'Rate good',
	'IMG_ICON_RATE_BAD'			=> 'Rate bad',

	//Installation
	'FILES_NOT_EXIST'		=> 'The rating icons:<br />%s<br /> were not found.<br /><br /><strong>Before continuing, you have to copy the rating icons from the <em>contrib/images</em> folder to the imageset folders of the styles you are using. Then refresh this page.</strong>',
	'CONVERTER'				=> 'Converter',
	'CONVERT_THANKS'		=> 'Convert Thanks for posts to Reputation System',
	'CONVERT_KARMA'			=> 'Convert Karma MOD to Reputation System',
	'CONVERT_HELPMOD'		=> 'Convert HelpMOD to Reputation System',
	'CONVERT_LIKE'			=> 'Convert phpBB Ajax Like to Reputation System',
	'CONVERT_THANK'			=> 'Convert Thank You Mod to Reputation System',
	'CONVERT_DATA'			=> 'Converted MOD: %1$s.<br />Now, you can uninstall %2$s. Go to the ACP and resynchronise Reputation System.',
	'UPDATE_RS_TABLE'		=> 'Reputation table was successfully updated.',

	//MOD Version Check
	'ANNOUNCEMENT_TOPIC'		=> 'Release Announcement',
	'CURRENT_VERSION'			=> 'Current Version',
	'DOWNLOAD_LATEST'			=> 'Download Latest Version',
	'LATEST_VERSION'			=> 'Latest Version',
	'NO_INFO'					=> 'Version server could not be contacted',
	'NOT_UP_TO_DATE'			=> '%s is not up to date',
	'RELEASE_ANNOUNCEMENT'		=> 'Annoucement Topic',
	'UP_TO_DATE'				=> '%s is up to date',
	'VERSION_CHECK'				=> 'MOD Version Check',
));

?>
