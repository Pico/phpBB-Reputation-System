<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package module_install
*/
class mcp_reputation_info
{
	function module()
	{
		return array(
			'filename'	=> 'mcp_reputation',
			'title'		=> 'MCP_REPUTATION',
			'version'	=> '0.6.1',
			'modes'		=> array(
				'front'				=> array('title' => 'MCP_REPUTATION_FRONT', 'auth' => 'acl_m_rs_moderate', 'cat' => array('MCP_REPUTATION')),
				'list'				=> array('title' => 'MCP_REPUTATION_LIST', 'auth' => 'acl_m_rs_moderate', 'cat' => array('MCP_REPUTATION')),
				'give_point'		=> array('title' => 'MCP_REPUTATION_GIVE', 'auth' => 'acl_m_rs_give', 'cat' => array('MCP_REPUTATION')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>