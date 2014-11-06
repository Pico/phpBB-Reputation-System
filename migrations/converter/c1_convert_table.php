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

class c1_convert_table extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !$this->db_tools->sql_table_exists($this->table_prefix . 'reputations') || $this->db_tools->sql_column_exists($this->table_prefix . 'reputations', 'reputation_id');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'rename_reputations_table'))),
		);
	}

	public function rename_reputations_table()
	{
		switch($this->db->get_sql_layer())
		{
			case 'mssql':
			case 'mssql_odbc':
			case 'mssqlnative':
				$sql = "EXEC sp_rename '{$this->table_prefix}reputations', '{$this->table_prefix}reputations_mod_backup'";
			break;

			default:
				$sql = "ALTER TABLE {$this->table_prefix}reputations RENAME TO {$this->table_prefix}reputations_mod_backup";
			break;
		}

		$this->db->sql_query($sql);
	}
}
