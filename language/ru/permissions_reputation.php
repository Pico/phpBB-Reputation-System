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
	'ACL_CAT_REPUTATION'	=> 'Репутация',

	'ACL_A_REPUTATION'	=> 'Может управлять настройками репутации',

	'ACL_M_RS_MODERATE'	=> 'Может модерировать очки репутации',
	'ACL_M_RS_RATE'		=> 'Может награждать дополнительными очками репутации',

	'ACL_U_RS_DELETE'			=> 'Может удалять указанные очки',
	'ACL_U_RS_RATE'				=> 'Может оценивать других пользователей',
	'ACL_U_RS_RATE_NEGATIVE'	=> 'Может отрицательно оценивать других пользователей<br /><em>Пользователь должен иметь возможность оценивать других пользователей, прежде чем сможет оставлять отрицательные отзывы.</em>',
	'ACL_U_RS_RATE_POST'		=> 'Может оценивать сообщения других пользователей',
	'ACL_U_RS_VIEW'				=> 'Может просматривать репутацию',

	'ACL_F_RS_RATE'				=> 'Может оценивать сообщения других пользователей',
	'ACL_F_RS_RATE_NEGATIVE'	=> 'Может отрицательно оценивать сообщения других пользователей<br /><em>Пользователь должен иметь возможность оценивать сообщения других пользователей, прежде чем сможет оставлять отрицательные отзывы.</em>',
));
