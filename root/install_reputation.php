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
 * @ignore
 */
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();


if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
$language_file = 'mods/info_acp_reputation';

// The name of the mod to be displayed during installation.
$mod_name = 'REPUTATION_SYSTEM';

/*
* The name of the config variable which will hold the currently installed version
* UMIL will handle checking, setting, and updating the version itself.
*/
$version_config_name = 'rs_version';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/

if (!isset($config['rs_version']))
{
	$error = array();

	$sql = 'SELECT imageset_path FROM ' . STYLES_IMAGESET_TABLE;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$rate_good = $phpbb_root_path . 'styles/' . $row['imageset_path'] . '/imageset/icon_rate_good.gif';

		if (!file_exists($rate_good))
		{
			$error[] = $rate_good;
		}

		$rate_bad = $phpbb_root_path . 'styles/' . $row['imageset_path'] . '/imageset/icon_rate_bad.gif';

		if (!file_exists($rate_bad))
		{
			$error[] = $rate_bad;
		}
	}
	$db->sql_freeresult($result);

	if ($error)
	{
		$user->add_lang('mods/info_acp_reputation');
		
		$error_msg = implode('<br/>', $error);
		$error_msg = sprintf($user->lang['FILES_NOT_EXIST'], $error_msg);
		$template->assign_vars(array(
			'S_ERROR'	=> true,
			'ERROR_MSG'	=> $error_msg,
		));
	}

	$options = array();

	if (isset($config['thanks_mod_version']))
	{
		$options += array(
			'thanks'	=> array('lang' => 'CONVERT_THANKS', 'type' => 'radio:yes_no', 'default' => false),
		);
	}

	if (isset($config['karma_version']))
	{
		$options += array(
			'karma'		=> array('lang' => 'CONVERT_KARMA', 'type' => 'radio:yes_no', 'default' => false),
		);
	}

	if (isset($config['helpmod_version']))
	{
		$options += array(
			'helpmod'	=> array('lang' => 'CONVERT_HELPMOD', 'type' => 'radio:yes_no', 'default' => false),
		);
	}
}

@set_time_limit(1200);
@set_time_limit(0);

$versions = array(
	'0.4.0' => array(
		'config_add' => array(
			array('rs_enable', '0', 0),
			array('rs_ajax_enable', '1', 0),
			array('rs_negative_point', '1', 0),
			array('rs_warning', '1', 0),
			array('rs_user_rating', '1', 0),
			array('rs_post_rating', '1', 0),
			array('rs_notification', '1', 0),
			array('rs_pm_notify', '1', 0),
			array('rs_ranks', '0', 0),
			array('rs_point_type', '0', 0),
			array('rs_min_point', '0', 0),
			array('rs_max_point', '0', 0),
			array('rs_per_page', '15', 0),
			array('rs_per_popup', '5', 0),
			array('rs_post_display', '1', 0),
			array('rs_post_detail', '1', 0),
			array('rs_hide_post', '0', 0),
			array('rs_anti_time', '0', 0),
			array('rs_anti_post', '0', 0),
			array('rs_anti_method', '0', 0),
			array('rs_enable_comment', '1', 0),
			array('rs_force_comment', '0', 0),
			array('rs_enable_power', '1', 0),
			array('rs_total_posts', '0', 0),
			array('rs_membership_days', '80', 0),
			array('rs_power_rep_point', '10', 0),
			array('rs_max_power', '5', 0),
			array('rs_max_power_warning', '10', 0),
			array('rs_enable_toplist', '0', 0),
			array('rs_toplist_direction', '0', 0),
			array('rs_toplist_num', '5', 0),
		),
		
		'table_column_add'	=> array(
			array('phpbb_forums', 'enable_reputation', array('BOOL', 0)),
			array('phpbb_groups', 'group_reputation_power', array('UINT', 0)),
			array('phpbb_posts', 'post_reputation', array('INT:11', 0)),
			array('phpbb_posts', 'post_rs_count', array('INT:11', 0)),
			array('phpbb_users', 'user_reputation', array('INT:11', 0)),
			array('phpbb_users', 'user_rep_new', array('INT:4', 0)),
			array('phpbb_users', 'user_rep_last', array('INT:11', 0)),
			array('phpbb_users', 'user_rs_notification', array('BOOL', 1)),
			array('phpbb_users', 'user_rs_default_power', array('INT:11', 0)),
			array('phpbb_users', 'user_rs_comment_pos', array('VCHAR:255', '')),
			array('phpbb_users', 'user_rs_comment_neg', array('VCHAR:255', '')),
		),

		'module_add' => array(
			array('acp', 'ACP_CAT_DOT_MODS', 'ACP_REPUTATION_SYSTEM'),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'ACP_REPUTATION_SETTINGS',
				'module_mode'		=> 'settings',
				'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'ACP_REPUTATION_RANKS',
				'module_mode'		=> 'ranks',
				'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'ACP_REPUTATION_GIVE',
				'module_mode'		=> 'give_point',
				'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'ACP_REPUTATION_SYNC',
				'module_mode'		=> 'sync',
				'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('mcp', false, 'MCP_REPUTATION'),
			array('mcp', 'MCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'MCP_REPUTATION_FRONT',
				'module_mode'		=> 'front',
				'module_auth'		=> 'acl_m_rs_moderate',
				),
			),
			array('mcp', 'MCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'MCP_REPUTATION_LIST',
				'module_mode'		=> 'list',
				'module_auth'		=> 'acl_m_rs_moderate',
				),
			),
			array('ucp', false, 'UCP_REPUTATION'),
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'UCP_REPUTATION_FRONT',
				'module_mode'		=> 'front',
				'module_auth'		=> 'cfg_rs_enable',
				),
			),
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'UCP_REPUTATION_LIST',
				'module_mode'		=> 'list',
				'module_auth'		=> 'cfg_rs_enable',
				),
			),
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'UCP_REPUTATION_GIVEN',
				'module_mode'		=> 'given',
				'module_auth'		=> 'cfg_rs_enable',
				),
			),
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'	=> 'UCP_REPUTATION_SETTING',
				'module_mode'		=> 'setting',
				'module_auth'		=> 'cfg_rs_enable',
				),
			),
		),

		'permission_add' => array(
			array('a_reputation', true),
			array('f_rs_give', 0),
			array('f_rs_give_negative', 0),
			array('m_rs_moderate', true),
			array('u_rs_give', true),
			array('u_rs_give_negative', true),
			array('u_rs_view', true),
			array('u_rs_ratepost', true),
			array('u_rs_delete', true),
		),

		'permission_set' => array(
			array('ROLE_ADMIN_FULL', 'a_reputation'),
			array('ROLE_FORUM_FULL', array('f_rs_give', 'f_rs_give_negative')),
			array('ROLE_FORUM_STANDARD', array('f_rs_give', 'f_rs_give_negative')),
			array('ROLE_MOD_FULL', 'm_rs_moderate'),
			array('ROLE_USER_FULL', array('u_rs_give', 'u_rs_give_negative', 'u_rs_view', 'u_rs_ratepost', 'u_rs_delete')),
			array('ROLE_USER_STANDARD', array('u_rs_give', 'u_rs_view', 'u_rs_ratepost', 'u_rs_delete')),
		),
		
		'table_add' => array(
			array('phpbb_reputations', array(
					'COLUMNS'		=> array(
						'rep_id'				=> array('UINT', NULL, 'auto_increment'),
						'rep_from'				=> array('UINT', 0),
						'rep_to'				=> array('UINT', 0),
						'action'				=> array('TINT:2', 0),
						'time'					=> array('TIMESTAMP', 0),
						'post_id'				=> array('UINT', 0),
						'point'					=> array('INT:11', 0),
						'comment'				=> array('MTEXT_UNI', ''),
						'bbcode_uid'			=> array('VCHAR:8', ''),
						'bbcode_bitfield'		=> array('VCHAR:255', ''),
						'enable_bbcode'			=> array('BOOL', 1),
						'enable_smilies'		=> array('BOOL', 1),
						'enable_urls'			=> array('BOOL', 1),
					),
					'PRIMARY_KEY'	=> 'rep_id',
					'KEYS'			=> array(
						'rep_from'				=> array('INDEX', 'rep_from'),
						'rep_to'				=> array('INDEX', 'rep_to'),
						'post_id'				=> array('INDEX', 'post_id'),
					),
				),
			),
			array('phpbb_reputations_ranks', array(
					'COLUMNS'		=> array(
						'rank_id'				=> array('UINT', NULL, 'auto_increment'),
						'rank_title'			=> array('VCHAR_UNI', ''),
						'rank_points'			=> array('INT:11', 0),
						'rank_color'			=> array('VCHAR:8', ''),
					),
					'PRIMARY_KEY'	=> 'rank_id',
				),
			),
		),

		'custom' => 'convert',

		'cache_purge' => array(
			'auth',
			'imageset',
			'template',
			'theme',
		),
	),

	'0.4.1' => array(
		'cache_purge' => array(
			'template',
		),
	),

	'0.4.2' => array(
		'config_add' => array(
			array('rs_sort_memberlist', '1', 0),
			array('rs_enable_ban', '0', 0),
			array('rs_ban_shield', '7', 0),
			array('rs_ban_groups', '0', 0),
			array('rs_power_loose_ban', '7', 0),
			array('rs_power_loose_warn', '3', 0),
			array('rs_power_limit_value', '10', 0),
			array('rs_power_limit_time', '12', 0),
			array('rs_min_power', '1', 0),
			array('rs_max_power_ban', '50', 0),
		),

		'permission_add' => array(
			array('m_rs_give', true),
		),

		'permission_set' => array(
			array('ROLE_MOD_FULL', 'm_rs_give'),
		),

		'module_add' => array(
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
					'module_basename'	=> 'reputation',
					'module_langname'	=> 'ACP_REPUTATION_BANS',
					'module_mode'		=> 'bans',
					'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('mcp', 'MCP_REPUTATION',
				array(
					'module_basename'	=> 'reputation',
					'module_langname'	=> 'MCP_REPUTATION_GIVE',
					'module_mode'		=> 'give_point',
					'module_auth'		=> 'acl_m_rs_give',
				),
			),
		),

		'table_add' => array(
			array('phpbb_reputations_bans', array(
					'COLUMNS'		=> array(
						'ban_id'				=> array('UINT', NULL, 'auto_increment'),
						'point'					=> array('INT:11', 0),
						'ban_time'				=> array('TIMESTAMP', 0),
						'ban_type'				=> array('VCHAR:1', 0),
						'ban_reason'			=> array('VCHAR:255', ''),
						'ban_give_reason'		=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> 'ban_id',
				),
			),
		),

		'table_column_add'	=> array(
			array('phpbb_users', 'user_last_rep_ban', array('TIMESTAMP', 0)),
		),
		
		'table_index_add' => array(
			array('phpbb_reputations', 'time', 'time'),
		),

		'cache_purge' => array(
			'template',
			'theme',
		),
	),

	'0.4.3' => array(
		'custom' => 'update_rs_table',

		'config_add' => array(
			array('rs_min_rep_negative', '10', 0),
		),

		'cache_purge' => array(
			'template',
			'theme',
		),
	),

	'0.4.4' => array(
		'config_add' => array(
			array('rs_ranks_path', 'images/reputation', 0),
			array('rs_user_rating_gap', '2', 0),
			array('rs_power_renewal', '5', 0),
			array('rs_power_explain', '1', 0),
		),

		'config_remove' => array(
			array('rs_power_limit_value'),
			array('rs_power_limit_time'),
		),

		'table_column_add' => array(
			array('phpbb_reputations_ranks', 'rank_image', array('VCHAR:255', '')),
		),

		'module_remove' => array(
			array('acp', 'ACP_REPUTATION_SYSTEM', 'ACP_REPUTATION_SETTINGS'),
			array('acp', 'ACP_REPUTATION_SYSTEM', 'ACP_REPUTATION_RANKS'),
			array('acp', 'ACP_REPUTATION_SYSTEM', 'ACP_REPUTATION_GIVE'),
			array('acp', 'ACP_REPUTATION_SYSTEM', 'ACP_REPUTATION_BANS'),
			array('acp', 'ACP_REPUTATION_SYSTEM', 'ACP_REPUTATION_SYNC'),
		),

		'module_add' => array(
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
					'module_basename'	=> 'reputation',
					'module_langname'	=> 'ACP_REPUTATION_OVERVIEW',
					'module_mode'		=> 'overview',
					'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
					'module_basename'	=> 'reputation',
					'module_langname'	=> 'ACP_REPUTATION_SETTINGS',
					'module_mode'		=> 'settings',
					'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
					'module_basename'	=> 'reputation',
					'module_langname'	=> 'ACP_REPUTATION_RANKS',
					'module_mode'		=> 'ranks',
					'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
					'module_basename'	=> 'reputation',
					'module_langname'	=> 'ACP_REPUTATION_BANS',
					'module_mode'		=> 'bans',
					'module_auth'		=> 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
					'module_basename'	=> 'reputation',
					'module_langname'	=> 'ACP_REPUTATION_GIVE',
					'module_mode'		=> 'give_point',
					'module_auth'		=> 'acl_a_reputation',
				),
			),
		),
	),

	'0.4.5' => array(
		'config_add' => array(
			array('rs_comment_max_chars', '150', 0),
		),
	),
);

// Include the UMIL Auto file, it handles the rest
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

function update_rs_table($action)
{
	global $db, $table_prefix, $user;

	if ($action == 'update')
	{
		$sql = 'ALTER TABLE ' . $table_prefix . "reputations ADD action TINYINT( 2 ) NOT NULL DEFAULT '0' AFTER time";
		$db->sql_query($sql);

		$sql = 'UPDATE  ' . $table_prefix . "reputations SET action = 1 WHERE post_id IS NOT NULL";
		$db->sql_query($sql);

		$sql = 'UPDATE  ' . $table_prefix . "reputations SET action = 2 WHERE user = 1";
		$db->sql_query($sql);

		$sql = 'UPDATE  ' . $table_prefix . "reputations SET action = 3 WHERE warning = 1";
		$db->sql_query($sql);

		$sql = 'UPDATE  ' . $table_prefix . "reputations SET action = 4 WHERE warning = 2";
		$db->sql_query($sql);

		$sql = 'ALTER TABLE ' . $table_prefix . "reputations DROP user, DROP warning";
		$db->sql_query($sql);
	}

	return $user->lang['UPDATE_RS_TABLE'];
}

function convert($action)
{
	global $db, $table_prefix;

	if ($action == 'install')
	{
		$convert_data = '';

		if (request_var('thanks', false))
		{
			$sql = 'SELECT * FROM ' . $table_prefix . 'thanks';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$text = '';
				$uid = $bitfield = $options = '';
				$allow_bbcode = $allow_urls = $allow_smilies = true;
				generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

				$sql_data = array(
					'rep_from'			=> $row['user_id'],
					'rep_to'			=> $row['poster_id'],
					'time'				=> $row['thanks_time'],
					'action'			=> 1,
					'post_id'			=> $row['post_id'],
					'point'				=> 1,
					'comment'			=> $text,
					'bbcode_uid'		=> $uid,
					'bbcode_bitfield'	=> $bitfield,
					'enable_bbcode'		=> $allow_bbcode,
					'enable_smilies'	=> $allow_smilies,
					'enable_urls'		=> $allow_urls,
				);

				$db->sql_query('INSERT INTO ' . $table_prefix . 'reputations ' . $db->sql_build_array('INSERT', $sql_data));
			}

			$convert_data .= ($convert_data == '') ? 'Thanks for posts' : ', Thanks for posts';
		}

		if (request_var('karma', false))
		{
			$sql = 'SELECT * FROM ' . $table_prefix . 'karma';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$text = utf8_normalize_nfc($row['comment_text']);
				$uid = $bitfield = $options = '';
				$allow_bbcode = $allow_urls = $allow_smilies = true;
				generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

				$sql_data = array(
					'rep_from'			=> $row['user_id'],
					'rep_to'			=> $row['poster_id'],
					'time'				=> $row['karma_time'],
					'action'			=> 1,
					'post_id'			=> $row['post_id'],
					'point'				=> 1,
					'comment'			=> $text,
					'bbcode_uid'		=> $uid,
					'bbcode_bitfield'	=> $bitfield,
					'enable_bbcode'		=> $allow_bbcode,
					'enable_smilies'	=> $allow_smilies,
					'enable_urls'		=> $allow_urls,
				);

				$db->sql_query('INSERT INTO ' . $table_prefix . 'reputations ' . $db->sql_build_array('INSERT', $sql_data));
			}
			
			$convert_data .= ($convert_data == '') ? 'Karma MOD' : ', Karma MOD';
		}

		if (request_var('helpmod', false))
		{
			$sql = 'SELECT * FROM ' . $table_prefix . 'helpmod';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$text = utf8_normalize_nfc($row['help_reason']);
				$uid = $bitfield = $options = '';
				$allow_bbcode = $allow_urls = $allow_smilies = true;
				generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);

				$sql_data = array(
					'rep_from'			=> $row['help_from'],
					'rep_to'			=> $row['help_to'],
					'time'				=> $row['help_time'],
					'action'			=> 1,
					'post_id'			=> $row['post_id'],
					'point'				=> 1,
					'comment'			=> $text,
					'bbcode_uid'		=> $uid,
					'bbcode_bitfield'	=> $bitfield,
					'enable_bbcode'		=> $allow_bbcode,
					'enable_smilies'	=> $allow_smilies,
					'enable_urls'		=> $allow_urls,
				);

				$db->sql_query('INSERT INTO ' . $table_prefix . 'reputations ' . $db->sql_build_array('INSERT', $sql_data));
			}

			$convert_data .= ($convert_data == '') ? 'HelpMod' : ', HelpMod';
		}

		if (!empty($convert_data))
		{
			return array(
				'command'	=> array(
					'CONVERT_DATA',
					$convert_data,
					$convert_data,
				),
			);
		}
	}
}

?>