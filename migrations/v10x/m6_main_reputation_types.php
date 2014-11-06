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

class m6_main_reputation_types extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT * FROM ' . $this->table_prefix . 'reputation_types';
		$result = $this->db->sql_query_limit($sql, 1);
		$row = $this->db->sql_fetchrow($result);

		return $row != false;
	}

	static public function depends_on()
	{
		return array('\pico\reputation\migrations\v10x\m1_initial_schema');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'insert_reputation_types'))),
		);
	}

	public function insert_reputation_types()
	{
		$reputation_types = array(
			array('reputation_type_name' => 'post'),
			array('reputation_type_name' => 'user'),
			array('reputation_type_name' => 'warning'),
		);

		$this->db->sql_multi_insert($this->table_prefix . 'reputation_types', $reputation_types);
	}
}
