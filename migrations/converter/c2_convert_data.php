<?php
/**
*
* Reputation System extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\migrations\converter;

class c2_convert_data extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return !$this->db_tools->sql_table_exists($this->table_prefix . 'reputations_mod_backup');
	}

	static public function depends_on()
	{
		return array(
			'\pico\reputation\migrations\converter\c1_convert_table',
			'\pico\reputation\migrations\v10x\m1_initial_schema',
			'\pico\reputation\migrations\v10x\m6_main_reputation_types',
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'convert_reputations_data'))),
		);
	}

	public function convert_reputations_data()
	{
		$types = $import_data = array();

		$sql = 'SELECT * FROM ' . $this->table_prefix . 'reputation_types';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$types[(string) $row['reputation_type_name']] = (int) $row['reputation_type_id'];
		}
		$this->db->sql_freeresult($result);

		$actions = array(
			1 	=> 'post',
			2	=> 'user',
			3	=> 'warning',
			4	=> 'ban',
			5	=> 'likes',
		);

		$sql = 'SELECT * FROM ' . $this->table_prefix . 'reputations_mod_backup';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$action = $actions[$row['action']];

			if (!isset($types[$action]))
			{
				continue;
			}

			$import_data[$row['rep_id']]['user_id_from']		= isset($row['rep_from']) ? (int) $row['rep_from'] : 0;
			$import_data[$row['rep_id']]['user_id_to']			= isset($row['rep_to']) ? (int) $row['rep_to'] : 0;
			$import_data[$row['rep_id']]['reputation_time']		= isset($row['time']) ? $row['time'] : 0;
			$import_data[$row['rep_id']]['reputation_type_id']	= isset($row['action']) ? (int) $types[$action] : 0;
			$import_data[$row['rep_id']]['reputation_item_id']	= (isset($row['post_id']) && ($action == 'post' || $action == 'likes')) ? (int) $row['post_id'] : 0;
			$import_data[$row['rep_id']]['reputation_points']	= isset($row['point']) ? $row['point'] : 0;
			$import_data[$row['rep_id']]['reputation_comment']	= isset($row['comment']) ? $row['comment'] : '';
		}
		$this->db->sql_freeresult($result);

		if (!empty($import_data))
		{
			$insert_buffer = new \phpbb\db\sql_insert_buffer($this->db, $this->table_prefix . 'reputations');

			foreach ($import_data as $reputation_data)
			{
				$insert_buffer->insert($reputation_data);
			}

			$insert_buffer->flush();
		}
	}
}
