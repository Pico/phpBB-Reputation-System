<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\acp;

class reputation_info
{
	function module()
	{
		return array(
			'filename'	=> '\pico\reputation\acp\reputation_module',
			'title'		=> 'ACP_REPUTATION_SYSTEM',
			'modes'		=> array(
				'overview'		=> array('title' => 'ACP_REPUTATION_OVERVIEW', 'auth' => 'ext_pico/reputation && acl_a_reputation', 'cat' => array('ACP_REPUTATION_SYSTEM')),
				'settings'		=> array('title' => 'ACP_REPUTATION_SETTINGS', 'auth' => 'ext_pico/reputation && acl_a_reputation', 'cat' => array('ACP_REPUTATION_SYSTEM')),
				'rate'			=> array('title' => 'ACP_REPUTATION_RATE', 'auth' => 'ext_pico/reputation && acl_a_reputation', 'cat' => array('ACP_REPUTATION_SYSTEM')),
				'sync'			=> array('title' => 'ACP_REPUTATION_SYNC', 'auth' => 'ext_pico/reputation && acl_a_reputation', 'display' => false, 'cat' => array('ACP_REPUTATION_SYSTEM')),
			),
		);
	}
}
