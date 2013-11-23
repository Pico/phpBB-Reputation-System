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

$lang = array_merge($lang, array(
	'ACL_CAT_REPUTATION'		=> 'Reputation',

	'ACL_A_REPUTATION'			=> 'Can manage reputation points',
	'ACL_M_RS_GIVE'				=> 'Can give additional reputation points',
	'ACL_M_RS_MODERATE'			=> 'Can moderate reputation points',
	'ACL_U_RS_DELETE'			=> 'Can delete given points',
	'ACL_U_RS_GIVE'				=> 'Can rate users (give reputation points)',
	'ACL_U_RS_GIVE_NEGATIVE'	=> 'Can give negative points (user rating)',
	'ACL_U_RS_RATEPOST'			=> 'Can rate posts',
	'ACL_U_RS_VIEW'				=> 'Can view reputation',
	'ACL_F_RS_GIVE'				=> 'Can rate posts (give reputation points)',
	'ACL_F_RS_GIVE_NEGATIVE'	=> 'Can give negative points',
));

?>