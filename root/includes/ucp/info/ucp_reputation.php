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
class ucp_reputation_info
{
	function module()
	{
		return array(
			'filename'	=> 'ucp_reputation',
			'title'		=> 'UCP_REPUTATION',
			'version'	=> '0.6.1',
			'modes'		=> array(
				'front'				=> array('title' => 'UCP_REPUTATION_FRONT', 'auth' => 'cfg_rs_enable', 'cat' => array('UCP_REPUTATION')),
				'list'				=> array('title' => 'UCP_REPUTATION_LIST', 'auth' => 'cfg_rs_enable', 'cat' => array('UCP_REPUTATION')),
				'given'				=> array('title' => 'UCP_REPUTATION_GIVEN', 'auth' => 'cfg_rs_enable', 'cat' => array('UCP_REPUTATION')),
				'setting'			=> array('title' => 'UCP_REPUTATION_SETTING', 'auth' => 'cfg_rs_enable', 'cat' => array('UCP_REPUTATION')),
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