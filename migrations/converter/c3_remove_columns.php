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

class c3_remove_columns extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !isset($this->config['rs_version']);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'enable_reputation',
				),
				$this->table_prefix . 'users'	=> array(
					'user_reputation',
					'user_rep_new',
					'user_rep_last',
					'user_rs_notification',
					'user_rs_default_power',
				),
				$this->table_prefix . 'posts'	=> array(
					'post_reputation',
				),
			),
		);
	}
}
