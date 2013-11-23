<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\migrations;

class v_0_7_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rs_version']) && version_compare($this->config['rs_version'], '0.7.0', '>=');
	}

	static public function depends_on()
	{
			return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'reputations'	=> array(
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
					),
					'PRIMARY_KEY'	=> 'rep_id',
					'KEYS'			=> array(
						'rep_from'				=> array('INDEX', 'rep_from'),
						'rep_to'				=> array('INDEX', 'rep_to'),
						'post_id'				=> array('INDEX', 'post_id'),
						'time'					=> array('INDEX', 'time'),
					),
				),
			),
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'enable_reputation'				=> array('BOOL', 0),
				),
				$this->table_prefix . 'users'	=> array(
					'user_reputation'				=> array('INT:11', 0),
					'user_rep_new'					=> array('INT:4', 0),
					'user_rep_last'					=> array('INT:11', 0),
					'user_rs_notification'			=> array('BOOL', 1),
					'user_rs_default_power'			=> array('INT:4', 0),
				),
				$this->table_prefix . 'posts'	=> array(
					'post_reputation'				=> array('INT:11', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'			=> array(
					'enable_reputation',
				),
				$this->table_prefix . 'users'		=> array(
					'user_reputation',
					'user_rep_new',
					'user_rep_last',
					'user_rs_notification',
					'user_rs_default_power',
				),
				$this->table_prefix . 'posts'		=> array(
					'post_reputation',
				),
			),
			'drop_tables'		=> array(
				$this->table_prefix . 'reputations',
			),
		);
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('rs_enable', '0')),
			array('config.add', array('rs_sync_step', '0', '1')),
			array('config.add', array('rs_negative_point', '1')),
			array('config.add', array('rs_min_rep_negative', '0')),
			array('config.add', array('rs_notification', '1')),
			array('config.add', array('rs_min_point', '0')),
			array('config.add', array('rs_max_point', '0')),
			array('config.add', array('rs_prevent_perc', '80')),
			array('config.add', array('rs_prevent_num', '20')),
			array('config.add', array('rs_per_page', '15')),
			array('config.add', array('rs_display_avatar', '1')),
			array('config.add', array('rs_point_type', '0')),
			array('config.add', array('rs_post_rating', '0')),
			array('config.add', array('rs_anti_time', '0')),
			array('config.add', array('rs_anti_post', '0')),
			array('config.add', array('rs_anti_method', '0')),
			array('config.add', array('rs_user_rating', '0')),
			array('config.add', array('rs_user_rating_gap', '2')),
			array('config.add', array('rs_enable_comment', '1')),
			array('config.add', array('rs_force_comment', '0')),
			array('config.add', array('rs_comment_max_chars', '255')),
			array('config.add', array('rs_enable_power', '1')),
			array('config.add', array('rs_power_renewal', '0')),
			array('config.add', array('rs_min_power', '1')),
			array('config.add', array('rs_max_power', '3')),
			array('config.add', array('rs_power_explain', '1')),
			array('config.add', array('rs_total_posts', '0')),
			array('config.add', array('rs_membership_days', '80')),
			array('config.add', array('rs_power_rep_point', '10')),
			array('config.add', array('rs_power_lose_warn', '3')),
			array('config.add', array('rs_enable_toplist', '0')),
			array('config.add', array('rs_toplist_direction', '0')),
			array('config.add', array('rs_toplist_num', '5')),

			// Current version
			array('config.add', array('rs_version', '0.7.0')),

			// Add ACP modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_REPUTATION_SYSTEM')),
			array('module.add', array('acp', 'ACP_REPUTATION_SYSTEM', array(
					'module_basename'	=> '\pico88\reputation\acp\reputation_module',
					'module_langname'	=> 'ACP_REPUTATION_OVERVIEW',
					'module_mode'		=> 'overview',
					'module_auth'		=> 'acl_a_reputation',
			))),
			array('module.add', array('acp', 'ACP_REPUTATION_SYSTEM', array(
					'module_basename'	=> '\pico88\reputation\acp\reputation_module',
					'module_langname'	=> 'ACP_REPUTATION_SETTINGS',
					'module_mode'		=> 'settings',
					'module_auth'		=> 'acl_a_reputation',
			))),
			array('module.add', array('acp', 'ACP_REPUTATION_SYSTEM', array(
					'module_basename'	=> '\pico88\reputation\acp\reputation_module',
					'module_langname'	=> 'ACP_REPUTATION_GIVE',
					'module_mode'		=> 'give_point',
					'module_auth'		=> 'acl_a_reputation',
			))),

			// Add MCP modules
			array('module.add', array('mcp', false, 'MCP_REPUTATION')),
			array('module.add', array('mcp', 'MCP_REPUTATION', array(
					'module_basename'	=> '\pico88\reputation\mcp\reputation_module',
					'module_langname'	=> 'MCP_REPUTATION_FRONT',
					'module_mode'		=> 'front',
					'module_auth'		=> 'acl_m_rs_moderate',
			))),
			array('module.add', array('mcp', 'MCP_REPUTATION', array(
					'module_basename'	=> '\pico88\reputation\mcp\reputation_module',
					'module_langname'	=> 'MCP_REPUTATION_LIST',
					'module_mode'		=> 'list',
					'module_auth'		=> 'acl_m_rs_moderate',
			))),
			array('module.add', array('mcp', 'MCP_REPUTATION', array(
					'module_basename'	=> '\pico88\reputation\mcp\reputation_module',
					'module_langname'	=> 'MCP_REPUTATION_GIVE',
					'module_mode'		=> 'give_point',
					'module_auth'		=> 'acl_m_rs_give',
			))),

			// Add UCP modules
			array('module.add', array('ucp', false, 'UCP_REPUTATION')),
			array('module.add', array('ucp', 'UCP_REPUTATION', array(
					'module_basename'	=> '\pico88\reputation\ucp\reputation_module',
					'module_langname'	=> 'UCP_REPUTATION_FRONT',
					'module_mode'		=> 'front',
					'module_auth'		=> 'cfg_rs_enable',
			))),
			array('module.add', array('ucp', 'UCP_REPUTATION', array(
					'module_basename'	=> '\pico88\reputation\ucp\reputation_module',
					'module_langname'	=> 'UCP_REPUTATION_LIST',
					'module_mode'		=> 'list',
					'module_auth'		=> 'cfg_rs_enable',
			))),
			array('module.add', array('ucp', 'UCP_REPUTATION', array(
					'module_basename'	=> '\pico88\reputation\ucp\reputation_module',
					'module_langname'	=> 'UCP_REPUTATION_GIVEN',
					'module_mode'		=> 'given',
					'module_auth'		=> 'cfg_rs_enable',
			))),

			// Add permissions
			array('permission.add', array('a_reputation', true)),
			array('permission.add', array('f_rs_give', false)),
			array('permission.add', array('f_rs_give_negative', false)),
			array('permission.add', array('m_rs_moderate', true)),
			array('permission.add', array('m_rs_give', true)),
			array('permission.add', array('u_rs_give', true)),
			array('permission.add', array('u_rs_give_negative', true)),
			array('permission.add', array('u_rs_view', true)),
			array('permission.add', array('u_rs_ratepost', true)),
			array('permission.add', array('u_rs_delete', true)),

			// Set permissions
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_reputation')),
			array('permission.permission_set', array('ROLE_FORUM_FULL', array('f_rs_give', 'f_rs_give_negative'))),
			array('permission.permission_set', array('ROLE_FORUM_STANDARD', array('f_rs_give', 'f_rs_give_negative'))),
			array('permission.permission_set', array('ROLE_MOD_FULL', array('m_rs_moderate', 'm_rs_give'))),
			array('permission.permission_set', array('ROLE_USER_FULL', array('u_rs_give', 'u_rs_give_negative', 'u_rs_view', 'u_rs_ratepost', 'u_rs_delete'))),
			array('permission.permission_set', array('ROLE_USER_STANDARD', array('u_rs_give', 'u_rs_view', 'u_rs_ratepost', 'u_rs_delete'))),
		);
	}
}