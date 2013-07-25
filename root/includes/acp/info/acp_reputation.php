<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2013
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/
class acp_reputation_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_reputation',
			'title'		=> 'ACP_REPUTATION_SYSTEM',
			'version'	=> '0.7.0',
			'modes'		=> array(
				'overview'		=> array('title' => 'ACP_REPUTATION_OVERVIEW', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'settings'		=> array('title' => 'ACP_REPUTATION_SETTINGS', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'give_point'	=> array('title' => 'ACP_REPUTATION_GIVE', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'ranks'			=> array('title' => 'ACP_REPUTATION_RANKS', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
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