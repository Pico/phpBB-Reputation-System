<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2013
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\mcp;

class reputation_info
{
	function module()
	{
		return array(
			'filename'	=> '\pico88\reputation\mcp\reputation_module',
			'title'		=> 'MCP_REPUTATION',
			'version'	=> '0.7.0',
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