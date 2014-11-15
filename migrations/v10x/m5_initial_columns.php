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

class m5_initial_columns extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\pico\reputation\migrations\converter\c3_remove_columns');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'reputation_enabled'	=> array('BOOL', 0),
				),
				$this->table_prefix . 'groups'	=> array(
					'group_reputation_power'	=> array('USINT', 0),
				),
				$this->table_prefix . 'users'	=> array(
					'user_reputation'		=> array('INT:11', 0),
				),
				$this->table_prefix . 'posts'	=> array(
					'post_reputation'		=> array('INT:11', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'reputation_enabled',
				),
				$this->table_prefix . 'groups'	=> array(
					'group_reputation_power',
				),
				$this->table_prefix . 'users'	=> array(
					'user_reputation',
				),
				$this->table_prefix . 'posts'	=> array(
					'post_reputation',
				),
			),
		);
	}
}
