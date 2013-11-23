<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\acp;

class reputation_info
{
	function module()
	{
		return array(
			'filename'	=> '\pico88\reputation\acp\reputation_module',
			'title'		=> 'ACP_REPUTATION_SYSTEM',
			'version'	=> '0.7.0',
			'modes'		=> array(
				'overview'		=> array('title' => 'ACP_REPUTATION_OVERVIEW', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'settings'		=> array('title' => 'ACP_REPUTATION_SETTINGS', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
				'give_point'	=> array('title' => 'ACP_REPUTATION_GIVE', 'auth' => 'acl_a_reputation', 'cat' => array('ACP_CAT_REPUTATION')),
			),
		);
	}
}

?>