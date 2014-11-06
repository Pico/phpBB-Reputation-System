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

class m3_initial_permissions extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
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

			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_reputation')),
			array('permission.permission_set', array('ROLE_FORUM_FULL', array('f_rs_rate', 'f_rs_rate_negative'))),
			array('permission.permission_set', array('ROLE_FORUM_STANDARD', array('f_rs_rate', 'f_rs_rate_negative'))),
			array('permission.permission_set', array('ROLE_MOD_FULL', array('m_rs_moderate', 'm_rs_rate'))),
			array('permission.permission_set', array('ROLE_USER_FULL', array('u_rs_rate', 'u_rs_rate_negative', 'u_rs_view', 'u_rs_rate_post', 'u_rs_delete'))),
			array('permission.permission_set', array('ROLE_USER_STANDARD', array('u_rs_rate', 'u_rs_view', 'u_rs_rate_post', 'u_rs_delete'))),
		);
	}
}
