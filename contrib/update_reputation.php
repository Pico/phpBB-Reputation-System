<?php
/**
*
* @package	Reputation System
* @author	Pico88 (Pico) (http://www.modsteam.tk)
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

if (isset($config['thanks_mod_version']))
{
	$options = array(
		'thanks'	=> array('lang' => 'THANKS', 'type' => 'radio:yes_no', 'default' => false),
	);
}

@set_time_limit(1200);
@set_time_limit(0);
	
$versions = array(
	'0.1.0' => array(
		'config_add' => array(
			array('rs_enable', '0', 0),
			array('rs_negative_point', '1', 0),
			array('rs_warning', '1', 0),
			array('rs_user_rating', '1', 0),
			array('rs_post_rating', '1', 0),
			array('rs_post_detail', '1', 0),
			array('rs_pm_notify', '1', 0),
			array('rs_per_page', '15', 0),
			array('rs_enable_comment', '1', 0),
			array('rs_force_comment', '0', 0),
			array('rs_enable_power', '1', 0),
			array('rs_total_posts', '0', 0),
			array('rs_membership_days', '0', 0),
			array('rs_power_rep_point', '0', 0),
			array('rs_max_power', '5', 0),
			array('rs_max_power_warning', '10', 0),
			array('rs_enable_toplist', '0', 0),
			array('rs_toplist_num', '5', 0),
		),
		
		'table_column_add'	=> array(
			array('phpbb_users', 'user_reputation', array('INT:11', 0)),
			array('phpbb_groups', 'group_reputation_power', array('UINT', 0)),
			array('phpbb_posts', 'post_rep_positive', array('VCHAR', '0')),
			array('phpbb_posts', 'post_rep_negative', array('VCHAR', '0')),
		),

		'module_add' => array(
			array('acp', 'ACP_CAT_DOT_MODS', 'ACP_REPUTATION_SYSTEM'),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'ACP_REPUTATION_SETTINGS',
				'module_mode'       => 'settings',
				'module_auth'       => 'acl_a_reputation',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'ACP_REPUTATION_SYNC',
				'module_mode'       => 'sync',
				'module_auth'       => 'acl_a_reputation',
				),
			),
		),
		
		'permission_add' => array(
			array('a_reputation', true),
			array('m_rs_moderate', true),
			array('u_rs_give', true),
			array('u_rs_give_negative', true),
			array('u_rs_view', true),
			array('f_rs_give', 0),
			array('f_rs_give_negative', 0),
		),
		
		'permission_set' => array(
			array('ROLE_ADMIN_FULL', 'a_reputation'),
			array('ROLE_MOD_FULL', 'm_rs_moderate'),
			array('ROLE_USER_FULL', array('u_rs_give', 'u_rs_give_negative', 'u_rs_view')),
			array('ROLE_USER_STANDARD', array('u_rs_give', 'u_rs_view')),
			array('ROLE_FORUM_FULL', array('f_rs_give', 'f_rs_give_negative')),
			array('ROLE_FORUM_STANDARD', array('f_rs_give', 'f_rs_give_negative')),
		),
		
		'table_add' => array(
			array('phpbb_reputations', array(
					'COLUMNS'		=> array(
						'rep_id'				=> array('UINT', NULL, 'auto_increment'),
						'rep_from'				=> array('UINT', 0),
						'rep_to'				=> array('UINT', 0),
						'time'					=> array('TIMESTAMP', 0),
						'post_id'				=> array('UINT', 0),
						'user'					=> array('BOOL', 1),
						'warning'				=> array('BOOL', 1),
						'point'					=> array('INT:11', 0),
						'comment'				=> array('MTEXT_UNI', ''),
						'bbcode_uid'			=> array('VCHAR:8', ''),
						'bbcode_bitfield'		=> array('VCHAR:255', ''),
						'enable_bbcode'			=> array('BOOL', 1),
						'enable_smilies'		=> array('BOOL', 1),
						'enable_urls'			=> array('BOOL', 1),
					),
					'PRIMARY_KEY'	=> 'rep_id',
					),
			),
		),
	),
	
	'0.1.1' => array(),
	
	'0.1.2' => array(),
	
	'0.2.0' => array(
		'module_add' => array(
			array('mcp', false, 'MCP_REPUTATION'),
			array('mcp', 'MCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'MCP_REPUTATION_FRONT',
				'module_mode'       => 'front',
				'module_auth'       => 'acl_m_rs_moderate',
				),
			),
			array('mcp', 'MCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'MCP_REPUTATION_LIST',
				'module_mode'       => 'list',
				'module_auth'       => 'acl_m_rs_moderate',
				),
			),
		),
		
		'permission_add' => array(
			array('u_rs_rateuser', true),
		),
		
		'permission_set' => array(
			array('ROLE_USER_FULL', 'u_rs_rateuser'),
			array('ROLE_USER_STANDARD', 'u_rs_rateuser'),
		),
	),
	
	'0.2.2' => array(
		'module_add' => array(
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'ACP_REPUTATION_GIVE',
				'module_mode'       => 'give_point',
				'module_auth'       => 'acl_a_reputation',
				),
			),
		),
	),
	
	'0.2.7' => array(
		'config_add' => array(
			array('rs_notification', '1', 0),
			array('rs_min_point', '0', 0),
			array('rs_max_point', '0', 0),
			array('rs_ranks', '0', 0),
			array('rs_toplist_direction', '0', 0),
		),
		
		'module_add' => array(
			array('ucp', false, 'UCP_REPUTATION'),
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'UCP_REPUTATION_FRONT',
				'module_mode'       => 'front',
				'module_auth'       => 'cfg_rs_enable',
				),
			),
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'UCP_REPUTATION_LIST',
				'module_mode'       => 'list',
				'module_auth'       => 'cfg_rs_enable',
				),
			),
			array('acp', 'ACP_REPUTATION_SYSTEM',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'ACP_REPUTATION_RANKS',
				'module_mode'       => 'ranks',
				'module_auth'       => 'acl_a_reputation',
				),
			),
		),

		'table_column_add'	=> array(
			array('phpbb_forums', 'enable_reputation', array('BOOL', 0)),
			array('phpbb_users', 'user_rep_new', array('INT:4', 0)),
			array('phpbb_users', 'user_rep_last', array('INT:11', 0)),
		),

		'permission_remove' => array(
			array('u_rs_rateuser', true),
		),

		'table_add' => array(
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

		'permission_add' => array(
			array('u_rs_ratepost', true),
			array('u_rs_delete', true),
		),
		
		'permission_set' => array(
			array('ROLE_USER_STANDARD', array('u_rs_ratepost', 'u_rs_delete')),
			array('ROLE_USER_FULL', array('u_rs_ratepost', 'u_rs_delete')),
		),
	),
	
	'0.4.0' => array(
		'table_index_add' => array(
			array('phpbb_reputations', 'rep_from', 'rep_from'),
			array('phpbb_reputations', 'rep_to', 'rep_to'),
			array('phpbb_reputations', 'post_id', 'post_id'),
		),
		
		'config_add' => array(
			array('rs_ajax_enable', '0', 0),
			array('rs_point_type', '0', 0),
			array('rs_per_popup', '5', 0),
			array('rs_post_display', '1', 0),
			array('rs_hide_post', '0', 0),
			array('rs_anti_time', '0', 0),
			array('rs_anti_post', '0', 0),
			array('rs_anti_method', '0', 0),
		),
		
		'table_column_add'	=> array(
			array('phpbb_posts', 'post_reputation', array('INT:11', 0)),
			array('phpbb_posts', 'post_rs_count', array('INT:11', 0)),
			array('phpbb_users', 'user_rs_notification', array('BOOL', 1)),
			array('phpbb_users', 'user_rs_default_power', array('INT:11', 0)),
			array('phpbb_users', 'user_rs_comment_pos', array('VCHAR:255', '')),
			array('phpbb_users', 'user_rs_comment_neg', array('VCHAR:255', '')),
		),
				
		'table_column_remove' => array(
			array('phpbb_posts', 'post_rep_positive'),
			array('phpbb_posts', 'post_rep_negative'),
		),

		'module_add' => array(
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'UCP_REPUTATION_GIVEN',
				'module_mode'       => 'given',
				'module_auth'       => 'cfg_rs_enable',
				),
			),
			array('ucp', 'UCP_REPUTATION',
				array(
				'module_basename'	=> 'reputation',
				'module_langname'   => 'UCP_REPUTATION_SETTING',
				'module_mode'       => 'setting',
				'module_auth'       => 'cfg_rs_enable',
				),
			),
		),
		
		'cache_purge' => array(
			'imageset',
			'template',
			'theme',
		),
	),
);

// Include the UMIL Auto file, it handles the rest
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>