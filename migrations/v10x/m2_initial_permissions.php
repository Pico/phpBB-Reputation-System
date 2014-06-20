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
* Migration stage 2: Initial permissions
*/
class m2_initial_permissions extends \phpbb\db\migration\migration
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
		return array('\pico\reputation\migrations\v10x\m1_initial_data');
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
			// Add permissions
			array('permission.add', array('a_reputation', true)),
			array('permission.add', array('f_rs_rate', false)),
			array('permission.add', array('f_rs_rate_negative', false)),
			array('permission.add', array('m_rs_moderate', true)),
			array('permission.add', array('m_rs_rate', true)),
			array('permission.add', array('u_rs_rate', true)),
			array('permission.add', array('u_rs_rate_negative', true)),
			array('permission.add', array('u_rs_view', true)),
			array('permission.add', array('u_rs_rate_post', true)),
			array('permission.add', array('u_rs_delete', true)),

			// Set permissions for the board roles
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_reputation')),
			array('permission.permission_set', array('ROLE_FORUM_FULL', array('f_rs_rate', 'f_rs_rate_negative'))),
			array('permission.permission_set', array('ROLE_FORUM_STANDARD', array('f_rs_rate', 'f_rs_rate_negative'))),
			array('permission.permission_set', array('ROLE_MOD_FULL', array('m_rs_moderate', 'm_rs_rate'))),
			array('permission.permission_set', array('ROLE_USER_FULL', array('u_rs_rate', 'u_rs_rate_negative', 'u_rs_view', 'u_rs_rate_post', 'u_rs_delete'))),
			array('permission.permission_set', array('ROLE_USER_STANDARD', array('u_rs_rate', 'u_rs_view', 'u_rs_rate_post', 'u_rs_delete'))),
		);
	}
}
