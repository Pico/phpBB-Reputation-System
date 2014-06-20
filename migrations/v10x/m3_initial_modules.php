<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\migrations\v10x;

/**
* Migration stage 3: Initial modules
*/
class m3_initial_modules extends \phpbb\db\migration\migration
{
	/**
	* Assign migration file dependencies for this migration
	*
	* @return array Array of migration files
	* @static
	* @access public
	*/
	static public function depends_on()
	{
		return array('\pico\reputation\migrations\v10x\m2_initial_permissions');
	}

	/**
	* Add or update data in the database
	*
	* @return array Array of table data
	* @access public
	*/
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
