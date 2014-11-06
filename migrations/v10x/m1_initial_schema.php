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

class m1_initial_schema extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\pico\reputation\migrations\converter\c1_convert_table');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'reputations'	=> array(
					'COLUMNS'		=> array(
						'reputation_id'			=> array('UINT', null, 'auto_increment'),
						'user_id_from'			=> array('UINT', 0),
						'user_id_to'			=> array('UINT', 0),
						'reputation_time'		=> array('TIMESTAMP', 0),
						'reputation_type_id'	=> array('USINT', 0),
						'reputation_item_id'	=> array('UINT', 0),
						'reputation_points'		=> array('INT:11', 0),
						'reputation_comment'	=> array('TEXT_UNI', ''),
					),
					'PRIMARY_KEY'	=> 'reputation_id',
					'KEYS'			=> array(
						'user_id_from'	=> array('INDEX', 'user_id_from'),
						'user_id_to'	=> array('INDEX', 'user_id_to'),
						'item_id'		=> array('INDEX', 'reputation_item_id'),
					),
				),
				$this->table_prefix . 'reputation_types'	=> array(
					'COLUMNS'		=> array(
						'reputation_type_id'	=> array('UINT', null, 'auto_increment'),
						'reputation_type_name'	=> array('VCHAR:20', ''),
					),
					'PRIMARY_KEY'	=> 'reputation_type_id',
					'KEYS'			=> array(
						'type'	=> array('INDEX', 'reputation_type_name'),
					),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'reputations',
				$this->table_prefix . 'reputation_types',
			),
		);
	}
}
