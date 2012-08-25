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
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, array(
	'REPUTATION'		=> 'Reputation',

	'RS_DISABLED'		=> 'Sorry, but the board administrator has disabled this feature.',
	'RS_TITLE'			=> 'Reputation System',

	'RS_ADD_POINTS'					=> 'Give positive reputation point',
	'RS_ADD_POINTS_CONFIRM'			=> 'Do you really want to give a positive reputation point?',
	'RS_SUBTRACT_POINTS'			=> 'Give negative reputation point',
	'RS_SUBTRACT_POINTS_CONFIRM'	=> 'Do you really want to give a negative reputation point?',
	'RS_POINT_EXPLAIN'				=> 'By using this form you can post a comment for giving a reputation point for this post. If you want to notify user about this action, check the option below.',
	'RS_USER_POINT_EXPLAIN'			=> 'By using this form you can post a comment for giving a reputation point. If you want to notify user about this action, check the option below.',

	'RS_ACTION'					=> 'Action',
	'RS_BAN'					=> 'Ban user',
	'RS_COMMENT'				=> 'Comment',
	'RS_DATE'					=> 'Date',
	'RS_DETAILS'				=> 'User reputation details',
	'RS_FROM'					=> 'From',
	'RS_LIST'					=> 'User reputation points list',
	'RS_POSITIVE_COUNT'			=> 'Positive points',
	'RS_NEGATIVE_COUNT'			=> 'Negative points',
	'RS_STATS'					=> 'Statistics',
	'RS_WEEK'					=> 'Last week',
	'RS_MONTH'					=> 'Last month',
	'RS_6MONTHS'				=> 'Last 6 months',
	'RS_NEGATIVE'				=> 'Negative',
	'RS_POSITIVE'				=> 'Positive',
	'RS_POINTS'					=> 'Points',
	'RS_POST'					=> 'Post',
	'RS_POST_DELETE'			=> 'Post deleted',
	'RS_POWER'					=> 'Reputation power',
	'RS_POST_RATING'			=> 'Rating post',
	'RS_ONLYPOST_RATING'		=> 'Evaluating post',
	'RS_RATE_BUTTON'			=> 'Rate',
	'RS_RATE_USER'				=> 'Rate user',
	'RS_RANK'					=> 'Reputation rank',
	'RS_SENT'					=> 'Your reputation point has been sent successfully',
	'RS_TIME'					=> 'Time',
	'RS_TO'						=> 'to',
	'RS_TYPE'					=> 'Type',
	'RS_USER_RATING'			=> 'Rating user',
	'RS_USER_RATING_CONFIRM'	=> 'Do you really want to rate %s?',
	'RS_VIEW_DETAILS'			=> 'View details',
	'RS_VOTING_POWER'			=> 'Remaing power points',
	'RS_WARNING'				=> 'Warning user',

	'RS_EMPTY_DATA'				=> 'There are no reputation points.',
	'RS_NA'						=> 'n/a',
	'RS_NO_COMMENT'				=> 'You cannot leave the comment field blank.',
	'RS_NO_ID'					=> 'No ID',
	'RS_NO_POST_ID'				=> 'There is no such post.',
	'RS_NO_POWER_LEFT'			=> 'Not enough voting power points.<br/>Wait until they replenish.<br/>Your voting power is %s',
	'RS_NO_USER_ID'				=> 'The requested user does not exist.',

	'RS_NO_POST'				=> 'There is no such post.',
	'RS_SAME_POST'				=> 'You have already rated this post.<br />You gave %s reputation points.',
	'RS_SAME_USER'				=> 'You have already given reputation to this user.',
	'RS_SELF'					=> 'You cannot give reputation to yourself.',
	'RS_USER_ANONYMOUS'			=> 'You are not allowed to give reputation points to anonymous users.',
	'RS_USER_BANNED'			=> 'You are not allowed to give reputation points to banned users.',
	'RS_USER_CANNOT_DELETE'		=> 'You do not have permission to delete points.',
	'RS_USER_DISABLED'			=> 'You are not allowed to give reputation point.',
	'RS_USER_NEGATIVE'			=> 'You are not allowed to give negative reputation point.<br />Your reputation has to be higher than %s.',
	'RS_VIEW_DISALLOWED'		=> 'You are not allowed to view the reputation points.',

	'RS_DELETE_POINT'			=> 'Delete point',
	'RS_DELETE_POINT_CONFIRM'	=> 'Do you really want to delete this reputation point?',
	'RS_POINT_DELETED'			=> 'The reputation point has been deleted.',

	'RS_PM_BODY'				=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Click %shere%s to view the post.',
	'RS_PM_BODY_COMMENT'		=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Comment: [i]%s&nbsp;[/i] <br />Click %shere%s to view the post.',
	'RS_PM_BODY_USER'			=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b]',
	'RS_PM_BODY_USER_COMMENT'	=> 'You received a point from the sender of this message. <br />Points: [b]%s&nbsp;[/b] <br />Comment: [i]%s&nbsp;[/i]',
	'RS_PM_SUBJECT'				=> 'You received a reputation point',

	'RS_RETURN_DETAILS'			=> '%sReturn to the reputation list%s',
	'RS_RETURN_POSTDETAILS'		=> '%sReturn to the reputation post list%s',
	'RS_RETURN_USER'			=> '%sReturn to the user details%s',

	'RS_TOPLIST'			=> 'Reputation Toplist',
	'RS_TOPLIST_EXPLAIN'	=> 'We are proud of',

	'NOTIFY_USER_REP'		=> 'Notify user about the point?',

	'RS_LATEST_REPUTATIONS'			=> 'Latest reputations',
	'LIST_REPUTATION'				=> '1 reputation',
	'LIST_REPUTATIONS'				=> '%s reputations',
	'ALL_REPUTATIONS'				=> 'All reputations',
	'RS_TO_USER'					=> 'To',
	'RS_POINT'						=> 'Point',
	'RS_NEW_REP'					=> 'You have <strong>1 new</strong> reputation comment',
	'RS_NEW_REPS'					=> 'You have <strong>%s new</strong> reputation comments',

	'RS_CLOSE_POPUP'				=> 'close',
	'RS_POPUP_MORE_DETAILS'			=> 'more details',

	'RS_HIDE_POST'					=> 'This post by <strong>%1$s</strong> is hidden because is has too low rating. %2$s %3$s %4$s',
	'RS_SHOW_HIDDEN_POST'			=> 'Show this post',
	'RS_SHOW_HIDE_HIDDEN_POST'		=> 'Show / Hide',
	'RS_ANTISPAM_INFO'				=> 'You cannot give reputation so soon. You may try again later.',
	'RS_POST_REPUTATION'			=> 'Post reputation',
	'RS_YOU_HAVE_VOTED'				=> 'You have given reputation points. Points:',

	'RS_VOTE_POWER_LEFT_OF_MAX'		=> '%1$d voting power points left of %2$d. Maximum per vote: %3$d',
	'RS_VOTE_POWER_LEFT'			=> '%1$d of %2$d',

	'RS_POWER_DETAILS'				=> 'How voting power should be calculated',
	'RS_POWER_DETAIL_AGE'			=> 'By registration date',
	'RS_POWER_DETAIL_POSTS'			=> 'By number of posts',
	'RS_POWER_DETAIL_REPUTAION'		=> 'By reputation',
	'RS_POWER_DETAIL_WARNINGS'		=> 'By warnings',
	'RS_POWER_DETAIL_BANS'			=> 'By number of bans within the last year',
	'RS_POWER_DETAIL_MIN'			=> 'Minimum voting power for all users',
	'RS_POWER_DETAIL_MAX'			=> 'Voting power capped at maximum allowed',
	'RS_GROUP_POWER'				=> 'Voting power based on usergroup',
));

?>