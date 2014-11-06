<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\migrations\converter;

class c5_remove_permissions extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !isset($this->config['rs_version']);
	}

	public function update_data()
	{
		return array(
			array('if', array(
				array('permission.exists', array('f_rs_rate', false)),
				array('permission.remove', array('f_rs_rate')),
			)),
			array('if', array(
				array('permission.exists', array('f_rs_give_negative', false)),
				array('permission.remove', array('f_rs_rate')),
			)),
			array('if', array(
				array('permission.exists', array('m_rs_give', true)),
				array('permission.remove', array('m_rs_give')),
			)),
			array('if', array(
				array('permission.exists', array('u_rs_give', true)),
				array('permission.remove', array('u_rs_give')),
			)),
						array('if', array(
				array('permission.exists', array('u_rs_give_negative', true)),
				array('permission.remove', array('u_rs_give_negative')),
			)),
		);
	}
}
