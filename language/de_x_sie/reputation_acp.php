<?php
/**
*
* Reputation System
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
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Here you can configure Reputation System’s settings. They are divided into groups.',
	'ACP_REPUTATION_RATE_EXPLAIN'		=> 'Here you can award additional reputation points to any users.',

	'RS_ENABLE'						=> 'Enable Reputation System',

	'RS_SYNC'						=> 'Synchronise Reputation System',
	'RS_SYNC_EXPLAIN'				=> 'You can synchronise reputation points after a mass removal of posts/topics/users, changing reputation settings, changing post authors, conversions from others systems. This may take a while. You will be notified when the process is completed.<br /><strong>Warning!</strong> All reputation points that do not match the reputation settings will be deleted during synchronization . It is recommended to make backup of the reputation table (DB) before synchronisation.',
	'RS_SYNC_REPUTATION_CONFIRM'	=> 'Are you sure you wish to synchronise reputations?',

	'RS_TRUNCATE'				=> 'Clear Reputation System',
	'RS_TRUNCATE_EXPLAIN'		=> 'This procedure completely removes all data.<br /><strong>Action is not reversible!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Are you sure you wish to clear Reputation System?',
	'RS_TRUNCATE_DONE'			=> 'Reputations were cleared.',

	'REPUTATION_SETTINGS_CHANGED'	=> '<strong>Altered Reputation System settings</strong>',

	// Setting legend
	'ACP_RS_MAIN'			=> 'General',
	'ACP_RS_DISPLAY'		=> 'Display settings',
	'ACP_RS_POSTS_RATING'	=> 'Post rating',
	'ACP_RS_USERS_RATING'	=> 'User rating',
	'ACP_RS_COMMENT'		=> 'Comments',
	'ACP_RS_POWER'			=> 'Reputation power',
	'ACP_RS_TOPLIST'		=> 'Toplist',

	// General
	'RS_NEGATIVE_POINT'				=> 'Allow negative points',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'When disabled users can not give negative points.',
	'RS_MIN_REP_NEGATIVE'			=> 'Minimum reputation for negative voting',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'How much reputation is required to give negative points. Setting the value to 0 disables this behaviour.',
	'RS_WARNING'					=> 'Enable warnings',
	'RS_WARNING_EXPLAIN'			=> 'Users with proper permissions can give negative points when warning users.',
	'RS_WARNING_MAX_POWER'			=> 'Maximum reputation power for warnings',
	'RS_WARNING_MAX_POWER_EXPLAIN'	=> 'Maximum reputation power allowed for warnings.',
	'RS_MIN_POINT'					=> 'Minimum points',
	'RS_MIN_POINT_EXPLAIN'			=> 'Limits the minimum reputation points a user can receive. Setting the value to 0 disables this behaviour.',
	'RS_MAX_POINT'					=> 'Maximum points',
	'RS_MAX_POINT_EXPLAIN'			=> 'Limits the maximum reputation points a user can receive. Setting the value to 0 disables this behaviour.',
	'RS_PREVENT_OVERRATING'			=> 'Prevent overrating',
	'RS_PREVENT_OVERRATING_EXPLAIN'	=> 'Block users from rating the same user.<br /><em>Example:</em> if user A has more than 10 reputation entries and 85% of them come from user B, user B can not rate that user until his votes ratio is higher than 85%.<br />To disable this feature set one or both values to 0.',
	'RS_PREVENT_NUM'				=> 'Total reputation entries of user A is equal to or higher than',
	'RS_PREVENT_PERC'				=> '<br />and ratio of user B votes is equal to or higher than',
	'RS_PER_PAGE'					=> 'Reputations per page',
	'RS_PER_PAGE_EXPLAIN'			=> 'How many rows should we display in tables of reputation points?',
	'RS_DISPLAY_AVATAR'				=> 'Display avatars',
	'RS_POINT_TYPE'					=> 'Method for displaying points',
	'RS_POINT_TYPE_EXPLAIN'			=> 'Viewing reputation points can be displayed as either the exact value of reputation points a user gave or as an image showing a plus or minus for positive or negative points. The Image method is useful if you set up reputation points so that one rating always equals to one point.',
	'RS_POINT_VALUE'				=> 'Value',
	'RS_POINT_IMG'					=> 'Image',

	// Post rating
	'RS_POST_RATING'				=> 'Enable post rating',
	'RS_POST_RATING_EXPLAIN'		=> 'Allow users to rate other user posts.<br />On each forums management page you can enable or disable reputations.',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Submit and enable Reputation System in all forums',
	'RS_ANTISPAM'					=> 'Anti-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Block users from rating any more posts after they have rated the defined number of posts within the defined number of hours. To disable this feature set one or both values to 0.',
	'RS_POSTS'						=> 'Number of rated posts',
	'RS_HOURS'						=> 'in the last hours',
	'RS_ANTISPAM_METHOD'			=> 'Anti-Spam check method',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Method for checking Anti-Spam. “Same user” method checks reputation given to the same user. “All users” method checks reputation regardless of who received points.',
	'RS_SAME_USER'					=> 'Same user',
	'RS_ALL_USERS'					=> 'All users',

	// User rating
	'RS_USER_RATING'				=> 'Allow rating of users from their profile page',
	'RS_USER_RATING_GAP'			=> 'Voting gap',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Time period a user must wait before they can give another rating to a user they have already rated. Setting the value to 0 disables this behaviour and users can rate other users once each.',

	// Comments
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

	// Reputation power
	'RS_ENABLE_POWER'				=> 'Enable reputation power',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Reputation power is something that users earn and spend on voting. New users have low power, active and veteran users gain more power. The more power you have the more you can vote during a specified period of time and the more influence you can have on the rating of another user or post.<br/>Users can choose during voting how much power they will spend on a vote, giving more points to interesting posts.',
	'RS_POWER_RENEWAL'				=> 'Power renewal time',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'This controls how users can spend earned power.<br/>If you set this option, users must wait for the given time interval before they can vote again. The more reputation power a user has, the more points they can spend in the set time.<br />Setting the value to 0 disables this behaviour and users can vote without waiting.',
	'RS_MIN_POWER'					=> 'Starting/Minimum reputation power',
	'RS_MIN_POWER_EXPLAIN'			=> 'This is how much reputation power newly registered users, banned users and users with low reputation or other criteria have. Users can’t go lower than this minimum voting power.<br/>Allowed 0-10. Recommended 1.',
	'RS_MAX_POWER'					=> 'Maximum power spending per vote',
	'RS_MAX_POWER_EXPLAIN'			=> 'Maximum amount of power that a user can spend per vote. Even if a user has millions of points, they’ll still be limited by this maximum number when voting.<br/>Users will select this from dropdown menu: 1 to X<br/>Allowed 1-20. Recommended: 3.',
	'RS_POWER_EXPLAIN'				=> 'Reputation power explanation',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Explain how reputation power is calculated to users.',
	'RS_TOTAL_POSTS'				=> 'Gain power with number of posts',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'User will gain 1 reputation power every X number of posts set here.',
	'RS_MEMBERSHIP_DAYS'			=> 'Gain power with length of the user’s membership',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'User will gain 1 reputation power every X number of days set here',
	'RS_POWER_REP_POINT'			=> 'Gain power with the user’s reputation',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'User will gain 1 reputation power every X number of reputation points they earn set here.',
	'RS_LOSE_POWER_WARN'			=> 'Lose power with warnings',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Each warning decreases reputation power by this amount of points. Warnings expire in accordance with the settings in General -> Board Configuration -> Board settings',

	// Toplist
	'RS_ENABLE_TOPLIST'				=> 'Enable Toplist',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Display a list of users with the most reputation points on the index page.',
	'RS_TOPLIST_DIRECTION'			=> 'Direction of list',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Display the users in the list in a horizontal or vertical direction.',
	'RS_TL_HORIZONTAL'				=> 'Horizontal',
	'RS_TL_VERTICAL'				=> 'Vertical',
	'RS_TOPLIST_NUM'				=> 'Number of Users to Display',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Number of users displayed on the toplist.',

	// Rate module
	'POINTS_INVALID'	=> 'Points field has to contain only numbers.',
	'RS_VOTE_SAVED'		=> 'Your vote has been saved successfully',
));
