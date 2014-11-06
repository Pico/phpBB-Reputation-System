<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\migrations\converter;

class c4_remove_modules extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !isset($this->config['rs_version']);
	}

	public function update_data()
	{
		return array(
			array('if', array(
				array('module.exists', array('acp', false, 'ACP_REPUTATION_SYSTEM')),
				array('module.remove', array('acp', false, 'ACP_REPUTATION_OVERVIEW')),
				array('module.remove', array('acp', false, 'ACP_REPUTATION_SETTINGS')),
				array('module.remove', array('acp', false, 'ACP_REPUTATION_RANKS')),
				array('module.remove', array('acp', false, 'ACP_REPUTATION_GIVE')),
				array('module.remove', array('acp', false, 'ACP_REPUTATION_SYSTEM')),
			)),
			array('if', array(
				array('module.exists', array('mcp', false, 'MCP_REPUTATION')),
				array('module.remove', array('mcp', false, 'MCP_REPUTATION_FRONT')),
				array('module.remove', array('mcp', false, 'MCP_REPUTATION_LIST')),
				array('module.remove', array('mcp', false, 'MCP_REPUTATION_GIVE')),
				array('module.remove', array('mcp', false, 'MCP_REPUTATION')),
			)),
			array('if', array(
				array('module.exists', array('ucp', false, 'UCP_REPUTATION')),
				array('module.remove', array('ucp', false, 'UCP_REPUTATION_FRONT')),
				array('module.remove', array('ucp', false, 'UCP_REPUTATION_LIST')),
				array('module.remove', array('ucp', false, 'UCP_REPUTATION_GIVEN')),
				array('module.remove', array('ucp', false, 'UCP_REPUTATION_SETTING')),
				array('module.remove', array('ucp', false, 'UCP_REPUTATION')),
			)),
		);
	}
}
