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
* Migration stage 6: Install main reputation types
*/
class m6_main_reputation_types extends \phpbb\db\migration\migration
{
	/**
	* Check if the reputation types table contains any data
	*
	* @return bool True if data exists, false otherwise
	* @access public
	*/
	public function effectively_installed()
	{
		$sql = 'SELECT * FROM ' . $this->table_prefix . 'reputation_types';
		$result = $this->db->sql_query_limit($sql, 1);
		$row = $this->db->sql_fetchrow($result);

		return $row != false;
	}

	/**
	* Assign migration file dependencies for this migration
	*
	* @return array Array of migration files
	* @static
	* @access public
	*/
	static public function depends_on()
	{
		return array('\pico\reputation\migrations\v10x\m5_initial_schema');
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
			array('custom', array(array($this, 'insert_reputation_types'))),
		);
	}

	/**
	* Custom function to install reputation types data 
	*
	* @return null
	* @access public
	*/
	public function insert_reputation_types()
	{
		$reputation_types = array(
			array('reputation_type_name' => 'post'),
			array('reputation_type_name' => 'user'),
		);

		$this->db->sql_multi_insert($this->table_prefix . 'reputation_types', $reputation_types);
	}
}
