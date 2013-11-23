<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
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
	'RS_DISABLED'		=> 'Sorry, but the board administrator has disabled this feature.',
	'RS_TITLE'			=> 'Reputation System',

	'RS_ACTION'					=> 'Action',
	'RS_COMMENT'				=> 'Comment',
	'RS_DATE'					=> 'Date',
	'RS_DETAILS'				=> 'User reputation details',
	'RS_FROM'					=> 'From',
	'RS_LIST'					=> 'User reputation points list',
	'RS_POST_COUNT'				=> 'Points for post',
	'RS_USER_COUNT'				=> 'Points from user',
	'RS_POSITIVE_COUNT'			=> 'Positive',
	'RS_NEGATIVE_COUNT'			=> 'Negative',
	'RS_STATS'					=> 'Statistics',
	'RS_WEEK'					=> 'Last week',
	'RS_MONTH'					=> 'Last month',
	'RS_6MONTHS'				=> 'Last 6 months',
	'RS_NEGATIVE'				=> 'Negative',
	'RS_POSITIVE'				=> 'Positive',
	'RS_POINT'					=> 'Point',
	'RS_POINTS'					=> 'Points',
	'RS_POINTS_TITLE'			=> array(
		1	=> 'Point: %d',
		2	=> 'Points: %d',
	),
	'RS_POST'					=> 'Post',
	'RS_POST_DELETE'			=> 'Post deleted',
	'RS_POWER'					=> 'Reputation power',
	'RS_POST_RATING'			=> 'Rating post',
	'RS_ONLYPOST_RATING'		=> 'Evaluating post',
	'RS_RATE_BUTTON'			=> 'Rate',
	'RS_RANK'					=> 'Reputation rank',
	'RS_SENT'					=> 'Your reputation point has been sent successfully',
	'RS_TIME'					=> 'Time',
	'RS_TO'						=> 'to',
	'RS_TO_USER'				=> 'To',
	'RS_TYPE'					=> 'Type',
	'RS_USER_RATING'			=> 'Rating user',
	'RS_USER_RATING_CONFIRM'	=> 'Do you really want to rate %s?',
	'RS_VOTING_POWER'			=> 'Remaing power points',

	'RS_EMPTY_DATA'				=> 'There are no reputation points.',
	'RS_NA'						=> 'n/a',
	'RS_NO_COMMENT'				=> 'You cannot leave the comment field blank.',
	'RS_NO_ID'					=> 'No ID',
	'RS_NO_POST_ID'				=> 'There is no such post.',
	'RS_NO_POWER'				=> 'Your reputation power is too low.',
	'RS_NO_POWER_LEFT'			=> 'Not enough reputation power points.<br/>Wait until they renew.<br/>Your reputation power is %s',
	'RS_NO_USER_ID'				=> 'The requested user does not exist.',
	'RS_COMMENT_TOO_LONG'		=> 'Your comment contains %1$s characters and it is too long.<br />The maximum allowed characters: %2$s.',

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

	'RS_DELETE_POINTS'			=> array(
		1	=> 'Delete point',
		2	=> 'Delete points'
	),
	'RS_DELETE_POINTS_CONFIRM'	=> array(
		1	=> 'Do you really want to delete this reputation point?',
		2	=> 'Do you really want to delete these reputation points?',
	),
	'RS_POINTS_DELETED'			=> array(
		1	=> 'The reputation point has been deleted.',
		2	=> 'The reputation points have been deleted.',
	),
	'NO_REPUTATION_SELECTED'	=> 'You did not select reputation point.',
	'RS_CLEAR_POST_CONFIRM'		=> 'Do you really want to delete all reputation points of that post?',
	'RS_CLEAR_USER_CONFIRM'		=> 'Do you really want to delete all reputation points of that user?',
	'RS_CLEAR_POST'				=> 'Clear post reputation',
	'RS_CLEAR_USER'				=> 'Clear user reputation',


	'RS_LATEST_REPUTATIONS'			=> 'Latest reputations',
	'LIST_REPUTATIONS'				=> array(
		1	=> '%d reputation',
		2	=> '%d reputations',
	),
	'ALL_REPUTATIONS'				=> 'All reputations',

	'RS_NEW_REPUTATIONS'			=> 'New reputation points',
	'RS_NEW_REP'					=> 'You received <strong>1 new</strong> reputation comment',
	'RS_NEW_REPS'					=> 'You received <strong>%s new</strong> reputation comments',
	'RS_CLICK_TO_VIEW'				=> 'Go to received points',

	'RS_MORE_DETAILS'				=> '» more details',

	'RS_ANTISPAM_INFO'				=> 'You cannot give reputation so soon. You may try again later.',
	'RS_USER_REPUTATION'			=> '%s\'s reputation',
	'RS_YOU_RATED'					=> 'You rated that post. Points:',

	'RS_VOTE_POWER_LEFT_OF_MAX'		=> '%1$d reputation power points left of %2$d.<br />Maximum per vote: %3$d',
	'RS_VOTE_POWER_LEFT'			=> '%1$d of %2$d',

	'RS_POWER_DETAILS'				=> 'How reputation power should be calculated',
	'RS_POWER_DETAIL_AGE'			=> 'By registration date',
	'RS_POWER_DETAIL_POSTS'			=> 'By number of posts',
	'RS_POWER_DETAIL_REPUTAION'		=> 'By reputation',
	'RS_POWER_DETAIL_WARNINGS'		=> 'By warnings',
	'RS_POWER_DETAIL_MIN'			=> 'Minimum reputation power for all users',
	'RS_POWER_DETAIL_MAX'			=> 'Reputation power capped at maximum allowed',

	'RS_USER_GAP'					=> 'You cannot rate the same user so soon. You can try again in %s.',
	'RS_VOTE_SAVED'					=> 'Vote saved',
));

?>