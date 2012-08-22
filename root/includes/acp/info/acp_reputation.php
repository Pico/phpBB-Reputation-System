<?php
/**
*
* @package	Reputation System
* @author	Pico88 (Pico) (http://www.modsteam.tk)
* @copyright (c) 2012
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
			'version'	=> '0.4.3',
			'modes'		=> array(
				'settings'		=> array('title' => 'ACP_REPUTATION_SETTINGS', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'sync'			=> array('title' => 'ACP_REPUTATION_SYNC', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'give_point'	=> array('title' => 'ACP_REPUTATION_GIVE', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'ranks'			=> array('title' => 'ACP_REPUTATION_RANKS', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'bans'			=> array('title' => 'ACP_REPUTATION_BANS', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
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