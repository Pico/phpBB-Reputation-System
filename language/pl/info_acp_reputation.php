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
	'ACP_REPUTATION_SYSTEM'				=> 'System reputacji',
	'ACP_REPUTATION_OVERVIEW'			=> 'Przegląd',
	'ACP_REPUTATION_SETTINGS'			=> 'Ustawienia',
	'ACP_REPUTATION_RATE'				=> 'Ocena',
	'ACP_REPUTATION_SYNC'				=> 'Synchronizuj',

	'RS_FORUM_REPUTATION'			=> 'Pozwól na ocenianie postów (reputacja)',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Zezwól użytkownikom oceniać posty napisane przez innych użytkowników',

	'RS_GROUP_POWER'				=> 'Moc reputacji grupy',
	'RS_GROUP_POWER_EXPLAIN'		=> 'Jeżeli to pole jest wypełnione, moc reputacji członków jest nadpisywana i nie zależy od postów itp.',

	'LOG_REPUTATION_DELETED'		=> '<strong>Usunięto reputację</strong><br />Od użytkownika: %1$s<br />Dla użytkownika: %2$s<br />Punkty: %3$s<br/>Typ: %4$s<br/>ID: %5$s',
	'LOG_POST_REPUTATION_CLEARED'	=> '<strong>Usunięto reputację posta</strong><br />Autor posta: %1$s<br />Temat posta: %2$s',
	'LOG_USER_REPUTATION_CLEARED'	=> '<strong>Usunięto reputację użytkownika</strong><br />Użytkownik: %1$s',
	'LOG_REPUTATION_SYNC'			=> '<strong>System reputacji ponownie zsynchronizowany</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Reputacja wyczyszczona</strong>',
	
	'REPUTATION_SETTINGS_CHANGED' => '<strong>Altered Reputation System settings</strong>',
));
