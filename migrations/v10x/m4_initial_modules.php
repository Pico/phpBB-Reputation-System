<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\migrations\v10x;

class m4_initial_modules extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'\pico\reputation\migrations\v10x\m3_initial_permissions',
			'\pico\reputation\migrations\converter\c4_remove_modules',
		);
	}

	public function update_data()
	{
		return array(
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_REPUTATION_SYSTEM')),
			array('module.add', array(
				'acp', 'ACP_REPUTATION_SYSTEM', array(
					'module_basename'	=> '\pico\reputation\acp\reputation_module',
					'modes'				=> array('overview', 'settings', 'rate', 'sync'),
				),
			)),
		);
	}
}
