<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2013
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

//Don't load hook if not installed.
if (!isset($config['rs_version']) || !isset($config['rs_enable']) || !isset($config['rs_notification']) || !isset($config['rs_enable_toplist']))
{
	return;
}

/**
* Reputation System
*/
function hook_reputation_system()
{
	global $config, $template, $user;
	global $phpbb_root_path, $phpEx;

	$notification = false;
	if ($config['rs_notification'] && $user->data['user_rep_new'] && $user->data['user_rs_notification'])
	{
		$notification = true;
	}

	$template->assign_vars(array(
		'S_REPUTATION'		=> $config['rs_enable'] ? true : false,
		'S_RS_NOTIFICATION'	=> $notification,

		'U_RS_NOTIFICATION'	=> append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=reputation&amp;mode=list'),
		'U_REPUTATION'		=> append_sid("{$phpbb_root_path}reputation.$phpEx"),
	));
}

function hook_rs_copyright()
{
	global $template;

	$copy_string = 'Powered by <a href="http://pico88.github.com/phpBB-Reputation-System/" title="Reputation System">Reputation System</a> &copy; Pico88';
	if (!isset($template->_tpldata['.'][0]['CREDIT_LINE']))
	{
		$template->_tpldata['.'][0]['CREDIT_LINE'] = '';
	}

	if (isset($template->_tpldata['.'][0]['CREDIT_LINE']) && strpos($template->_tpldata['.'][0]['CREDIT_LINE'], $copy_string) === false)
	{
		$credit_line =& $template->_tpldata['.'][0]['CREDIT_LINE'];
		$credit_line = ((!empty($credit_line)) ? $credit_line . '<br />' : '') . $copy_string;
	}
}

function hook_rs_toplist()
{
	global $config, $db;
	global $template, $phpEx, $phpbb_root_path, $user;
		
	if (!$config['rs_enable_toplist'] || !$config['rs_toplist_num'])
	{
		return;
	}

	if (!defined('LOAD_TOPLIST') && ($user->page['page'] == 'index.'.$phpEx))
	{
		define('LOAD_TOPLIST', true);
	}

	if (!defined('LOAD_TOPLIST') || LOAD_TOPLIST == false)
	{
		return;
	}

	$user->add_lang('mods/reputation_system');
	
	$reputation_toplist = '';
	$sql = 'SELECT user_id, username, user_colour, user_reputation
		FROM ' . USERS_TABLE . '
		WHERE user_id <> ' . ANONYMOUS . '
			AND user_reputation > 0
		ORDER BY user_reputation DESC';
	$result = $db->sql_query_limit($sql, $config['rs_toplist_num']);

	while ($row = $db->sql_fetchrow($result))
	{	
		$direction = $config['rs_toplist_direction'] ? '<br />' : ', ';
		$reputation_toplist .= (($reputation_toplist != '') ? $direction : '') . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']) . ' (' . $row['user_reputation'] . ')';
	}
	$db->sql_freeresult($result);

	// Assign index specific vars
	$template->assign_vars(array(
		'S_RS_TOPLIST'	=> true,
		'RS_TOPLIST'	=> $config['rs_toplist_direction'] ? '<br />' . $reputation_toplist : $reputation_toplist
	));
}

$phpbb_hook->register('phpbb_user_session_handler', 'hook_reputation_system');
if ($config['rs_enable'])
{
	$phpbb_hook->register(array('template', 'display'), 'hook_rs_copyright');

	if ($config['rs_enable_toplist'] && $config['rs_toplist_num'])
	{
		$phpbb_hook->register(array('template', 'display'), 'hook_rs_toplist');
	}
}
?>