<?php
/**
*
* @package		Reputation System
* @author		Pico88 (Pico) (http://www.modsteam.tk)
* @co-author	Versusnja
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
	'REPUTATION_SYSTEM'				=> 'Reputation System',

	'ACP_REPUTATION_SYSTEM'			=> 'Reputation System',
	'ACP_REPUTATION_SYSTEM_EXPLAIN'	=> 'On this page you can check if your version of Reputation System is current or otherwise and take action to update it.<br />You can also configure Reputation System’s settings. They are divided into groups.',
	'ACP_REPUTATION_SETTINGS'		=> 'Settings',
	'ACP_REPUTATION_SYNC'			=> 'Resynchronise',
	'ACP_REPUTATION_GIVE'			=> 'Give point',
	'ACP_REPUTATION_RANKS'			=> 'Ranks',
	'ACP_REPUTATION_BANS'			=> 'Bans',
	'MCP_REPUTATION'				=> 'Reputation',
	'MCP_REPUTATION_FRONT'			=> 'Front page',
	'MCP_REPUTATION_LIST'			=> 'List reputations',
	'MCP_REPUTATION_GIVE'			=> 'Give point',
	'UCP_REPUTATION'				=> 'Reputation',
	'UCP_REPUTATION_FRONT'			=> 'Front page',
	'UCP_REPUTATION_LIST'			=> 'List',
	'UCP_REPUTATION_GIVEN'			=> 'Given points',
	'UCP_REPUTATION_SETTING'		=> 'Settings',

	'ACP_RS_MAIN'					=> 'General settings',
	'ACP_RS_POST_RATING'			=> 'Post rating options',
	'ACP_RS_COMMENT'				=> 'Comments',
	'ACP_RS_POWER'					=> 'Reputation Power',
	'ACP_RS_TOPLIST'		 		=> 'Toplist',
	'ACP_RS_BAN'		 			=> 'Reputation Banning',

	'RS_LATEST_VERSION'				=> 'Latest Version',
	'RS_CURRENT_VERSION'			=> 'Current Version',
	'RS_CLICK_CHECK_NEW_VERSION'	=> 'Click %shere%s to check for a new version.',
	'RS_CLICK_GET_NEW_VERSION'		=> 'Click %shere%s to get the new version.',

	'RS_ENABLE'						=> '<span style="color: red;">Enable Reputation System</span>',
	'RS_AJAX_ENABLE'				=> '<span style="color: green;">Enable AJAX</span>',
	'RS_AJAX_ENABLE_EXPLAIN'		=> 'This option enables AJAX for Reputation System. If there is a conflict with other scripts, you can turn it off and use Reputation System without AJAX.',
	'RS_NEGATIVE_POINT'				=> 'Allow negative points',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'When disabled you are not allowed to give negative points. This is similar to Facebook’s "Like" feature.',
	'RS_MIN_REP_NEGATIVE'			=> 'Minimum reputation for negative voting',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'How much reputation is required to give negative points. Setting the value to 0 disables this behaviour.',
	'RS_WARNING'					=> 'Enable warnings',
	'RS_WARNING_EXPLAIN'			=> 'Users with proper permissions can give negative points when warning users.',
	'RS_USER_RATING'				=> 'Allow user rating',
	'RS_POST_RATING'				=> 'Enable post rating',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Submit and enable Reputation System in all forums',
	'RS_NOTIFICATION'				=> 'Enable notifications',
	'RS_NOTIFICATION_EXPLAIN'		=> 'This option enables notification of new reputation points in the header.',
	'RS_PM_NOTIFY'					=> 'Enable PM notification',
	'RS_PM_NOTIFY_EXPLAIN'			=> 'This option allows users to send a PM notification of new reputation points.',
	'RS_RANK_ENABLE'				=> 'Enable ranks',
	'RS_POINT_TYPE'					=> 'Method for displaying points',
	'RS_POINT_TYPE_EXPLAIN'			=> 'Viewing reputation points can be displayed as either the exact value of reputation points a user gave or as an image showing a plus or minus for positive or negative points. The Image method is useful if you set up reputation points so that one rating always equals to one point.',
	'RS_POINT_VALUE'				=> 'Value',
	'RS_POINT_IMG'					=> 'Image',
	'RS_MIN_POINT'					=> 'Minimum points',
	'RS_MIN_POINT_EXPLAIN'			=> 'Limits the minimum points available to receive. Setting the value to 0 disables this behaviour.',
	'RS_MAX_POINT'					=> 'Maximum points',
	'RS_MAX_POINT_EXPLAIN'			=> 'Limits the maximum points available to receive. Setting the value to 0 disables this behaviour.',
	'RS_PER_PAGE'					=> 'Reputations per page',
	'RS_PER_PAGE_EXPLAIN'			=> 'How many rows should we display in the table of your board’s reputation points.',
	'RS_PER_POPUP'					=> 'Reputations in AJAX popup',
	'RS_PER_POPUP_EXPLAIN'			=> 'How many reputations should be shown in the popup box (works only if AJAX is enabled).<br /><em>You are limited from 1 to 10.</em>',
	'RS_SORT_MEMBERLIST_BY_REPO'	=> 'Sort memberlist by reputation by default',
	'RS_SORT_MEMBERLIST_BY_REPO_EXPLAIN' => 'When the memberlist is being sorted by reputation it makes more sense to check it regularly to keep an eye on development. You may switch it off to keep the default behaviour. <br/>By default it’s sorted by username (which is silly :) ).',

	'RS_POST_DISPLAY'				=> 'Method for displaying post rating',
	'RS_POST_DISPLAY_EXPLAIN'		=> 'How to display rating of a post? You can either show how much power users spent. Otherwise you can simply show how many times users gave points, meaning that one rating = 1 point (while one rep point can have more power).',
	'RS_POINT_METHOD'				=> 'How much total power was given',
	'RS_USER_METHOD'				=> 'How many times users gave points',
	'RS_POST_DETAIL'				=> 'Display post details during post rating',
	'RS_POST_DETAIL_EXPLAIN'		=> 'Only when AJAX voting is disabled.<br/>This option allows users to see the post message and basic information about user.',
	'RS_HIDE_POST'					=> 'Hide posts with low ratings',
	'RS_HIDE_POST_EXPLAIN'			=> 'Posts with a rating less than the number set here will be hidden by default (users have the option to unhide them if they choose). After a post has earned a rating greater than this value, it will no longer be hidden by default. Setting the value to 0 disables this behaviour.',
	'RS_ANTISPAM'					=> 'Anti-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Block users from rating any more posts after they have rated the defined number of posts within the defined number of hours. To disable this feature set one or both values to 0.',
	'RS_POSTS'						=> 'Number of rated posts',
	'RS_HOURS'						=> 'in the last hours',
	'RS_ANTISPAM_METHOD'			=> 'Anti-Spam check method',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Method for checking Anti-Spam. “Same user” method checks reputation given to the same user. “All users” method checks reputation regardless of who received points. ',
	'RS_SAME_USER'					=> 'Same user',
	'RS_ALL_USERS'					=> 'All users',

	'RS_ENABLE_COMMENT'				=> 'Enable comments',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'When enabled, users will be able to add a personal comment with their rating.',
	'RS_FORCE_COMMENT'				=> 'Force user to enter comment',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'If set to "yes", users will be required to add a comment with their rating.',
	'RS_COMMENT_NO'					=> 'No',
	'RS_COMMENT_BOTH'				=> 'Both user and post ratings',
	'RS_COMMENT_POST'				=> 'Only post ratings',
	'RS_COMMENT_USER'				=> 'Only user ratings',

	'RS_ENABLE_POWER'				=> 'Enable reputation power',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Reputation power is something that users earn and spend on voting. New users have low power, active and veteran users gain more power. The more power you have the more you can vote during a specified period of time and the more influence you can have on the rating of another user or post.<br/>Users will choose during voting how much power they will spend on a vote, giving more points to interesting posts.',
	'RS_POWER_LIMIT'				=> 'Power spending limits',
	'RS_POWER_LIMIT_EXPLAIN'		=> 'This controls how much you can spend earned power.<br/>Please note that even if a user is very reputable, helpful, contributing, they won’t be able to spend more power than this setting allows.<br/>Recommended 30 points in 24 hours.<br />To disable this feature set one or both values to 0.',
	'RS_POWER_LIMIT_VALUE'			=> 'A user can spend maximum ',
	'RS_POWER_LIMIT_TIME'			=> ' power points in ',
	'RS_POWER_LIMIT_HOURS'			=> ' hours',
	'RS_MIN_POWER'					=> 'Starting/Minimum reputation power',
	'RS_MIN_POWER_EXPLAIN'			=> 'This is how much reputation power newly registered users, banned users and users with low reputation or other criteria have. Users can’t go lower then minimum voting power.<br/>Allowed 0-10. Recommended 1.',
	'RS_MAX_POWER'					=> 'Maximum power spending per vote',
	'RS_MAX_POWER_EXPLAIN'			=> 'Maximum amount of power that you can spend per vote. Even if you have millions of points, you’ll still be limited by this number when voting.<br/>Users will select this from dropdown menu: 1 to X<br/>Allowed 1-20. Recommended: 3.',
	'RS_MAX_POWER_WARNING'			=> 'Maximum reputation power for warnings',
	'RS_MAX_POWER_WARNING_EXPLAIN'	=> 'Maximum reputation power allowed for warnings.',
	'RS_MAX_POWER_BAN'				=> 'Maximum reputation power for bans',
	'RS_MAX_POWER_BAN_EXPLAIN'		=> 'Maximum reputation points, which user gets if he or she is banned for 1 month or permanently.<br />If a user is banned for a shorter period of time, he or she will receive a relative number of points.',
	'RS_TOTAL_POSTS'				=> 'Gain power with number of posts',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'User will gain 1 reputation power for every x number of posts.',
	'RS_MEMBERSHIP_DAYS'			=> 'Gain power with length of the user’s membership',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'User will gain 1 reputation power for every x number of days.',
	'RS_POWER_REP_POINT'			=> 'Gain power with the user’s reputation',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'User will gain 1 reputation power for every x number of reputation points.',
	'RS_LOOSE_POWER_BAN'			=> 'Lose power with bans',
	'RS_LOOSE_POWER_BAN_EXPLAIN'	=> 'Each ban within the last year decreases reputation power by this amount of points',
	'RS_LOOSE_POWER_WARN'			=> 'Lose power with warnings',
	'RS_LOOSE_POWER_WARN_EXPLAIN'	=> 'Each warning decreases reputation power by this amount of points. Warnings expire in accordance with the settings in General -> Board Configuration -> Board settings',
	'RS_GROUP_POWER'				=> 'Group reputation power',

	'RS_ENABLE_TOPLIST'				=> 'Enable Toplist',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'On the index page a user list will be displayed showing users with the most reputation points.',
	'RS_TOPLIST_DIRECTION'			=> 'Direction of list',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Display the users in the list in a horizontal or vertical direction.',
	'RS_TL_HORIZONTAL'				=> 'Horizontal',
	'RS_TL_VERTICAL'				=> 'Vertical',
	'RS_TOPLIST_NUM'				=> 'Number of Users to Display',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Number of users displayed on the toplist.',

	'RS_ENABLE_BAN'				=> 'Enable bans',
	'RS_ENABLE_EXPLAIN'			=> 'This will allow a user to be banned automatically based on reputation.',
	'RS_BAN_SHIELD'				=> 'Shield for the banned',
	'RS_BAN_SHIELD_EXPLAIN'		=> 'This option protects a previously banned user from further bans based on reputation. Such a user cannot be re-banned in the given time frame after their previous ban has expired.<br />Setting the value to 0 disables this behaviour.',
	'RS_BAN_GROUPS'				=> 'Exclude these groups',
	'RS_BAN_GROUPS_EXPLAIN'		=> 'If there are no selected groups then all users can be banned (except founders). In order to select (or deselect) multiple groups, you must CTRL+CLICK (or CMD-CLICK on Mac) items to add them. If you forget to hold down CTRL/CMD when clicking an item, then all the previously selected items will be deselected.',

	'RS_SYNC'					=> 'Reputation System synchronisation',
	'RS_SYNC_EXPLAIN'			=> 'Here you can resynchronise Reputation System after a mass removal of posts/topics/users, splitting/merging of topics, setting/removing Global Announcements, changing post authors, conversions from others systems, etc. This may take some time. You will be notified when the process is complete.',
	'RS_SYNC_START'				=> 'Resynchronise reputation',
	'RS_SYNC_STEP_DEL'			=> 'Step 1/4 - remove reputation points of non-existent users',
	'RS_SYNC_STEP_USER'			=> 'Step 2/4 - synchronisation of user reputation points',
	'RS_SYNC_STEP_POST_1'		=> 'Step 3/4 - synchronisation of post reputation (part 1 of 2)',
	'RS_SYNC_STEP_POST_2'		=> 'Step 4/4 - synchronisation of post reputation (part 2 of 2)',
	'RS_SYNC_DONE'				=> 'Reputation System synchronisation has finished successfully',
	'RS_RESYNC'					=> 'Resynchronise',

	'RS_GIVE_POINT'					=> 'Give reputation points',
	'RS_GIVE_POINT_EXPLAIN'			=> 'Here you can give additional reputation points to users.',

	'RS_RANKS'						=> 'Manage ranks',
	'RS_RANKS_EXPLAIN'				=> 'Here you can add, edit, view and delete ranks based on reputation points. ',
	'RS_ADD_RANK'					=> 'Add Rank',
	'RS_MUST_SELECT_RANK'			=> 'You must select a rank',
	'RS_NO_RANK_TITLE'				=> 'You must specify a title for the rank',
	'RS_RANK_ADDED'					=> 'The rank was successfully added.',
	'RS_RANK_MIN'					=> 'Minimum points',
	'RS_RANK_TITLE'					=> 'Rank title',
	'RS_RANK_COLOR'					=> 'Rank color',
	'RS_RANK_UPDATED'				=> 'The rank was successfully updated.',

	'RS_BANS'						=> 'Manage reputation bans',
	'RS_BANS_EXPLAIN'				=> 'Using this form you can add, edit, view and delete bans based on reputation points.',
	'RS_BAN_POINT'					=> 'Points to be banned',
	'RS_AUTO_BAN_REASON'			=> 'Auto ban for a low reputation',
	'RS_ADD_BAN'					=> 'Add ban',
	'RS_BAN_ADDED'					=> 'The ban was successfully added.',
	'RS_BAN_UPDATED'				=> 'The ban was successfully updated.',
	'RS_OTHER'						=> 'Other',
	'RS_MINUTES'					=> 'minutes',
	'RS_HOURS'						=> 'hours',
	'RS_DAYS'						=> 'days',

	'RS_FORUM_REPUTATION'			=> 'Enable reputation',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Allow users to rate posts. You can choose if rating posts influence user reputation.',
	'RS_POST_WITH_USER'				=> 'Yes, with influencing on user reputation',
	'RS_POST_WITHOUT_USER'			=> 'Yes, without influencing on user reputation',

	'LOG_REPUTATION_SETTING'		=> '<strong>Altered Reputation System settings</strong>',
	'LOG_REPUTATION_SYNC'			=> '<strong>Reputation System resynchronised</strong>',
	'LOG_RS_BAN_ADDED'				=> '<strong>Added new reputation ban</strong>',
	'LOG_RS_BAN_REMOVED'			=> '<strong>Removed reputation ban</strong>',
	'LOG_RS_BAN_UPDATED'			=> '<strong>Updated reputation ban</strong>',
	'LOG_RS_RANK_ADDED'				=> '<strong>Added new reputation rank</strong><br />» %s',
	'LOG_RS_RANK_REMOVED'			=> '<strong>Removed reputation rank</strong><br />» %s',
	'LOG_RS_RANK_UPDATED'			=> '<strong>Updated reputation rank</strong><br />» %s',
	'LOG_USER_REP_DELETE'			=> '<strong>Reputation point has been deleted</strong><br />User: %s',

	'IMG_ICON_RATE_GOOD'			=> 'Rate good',
	'IMG_ICON_RATE_BAD'				=> 'Rate bad',

	//Installation
	'FILES_NOT_EXIST'				=> 'The rating icons:<br />%s<br /> were not found.<br /><br /><strong>Before continuing, you have to copy the rating icons from the <em>contrib/images</em> folder to the imageset folders of the styles you are using. Then refresh this page.</strong>',
	'CONVERT_THANKS'				=> 'Convert Thanks for posts to Reputation System?',
	'CONVERT_KARMA'					=> 'Convert Karma MOD to Reputation System?',
	'CONVERT_HELPMOD'				=> 'Convert HelpMOD to Reputation System?',
	'CONVERT_DATA'					=> 'Converted MOD: %1$s.<br />Now, you can uninstall %2$s. Go to the ACP and Resynchronise Reputation System.',
	'UPDATE_RS_TABLE'				=> 'Reputation table was updated successfully.',
));

?>
