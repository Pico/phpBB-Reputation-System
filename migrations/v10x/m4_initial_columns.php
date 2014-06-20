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
* Migration stage 4: Initial columns
*/
class m4_initial_columns extends \phpbb\db\migration\migration
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
		return array('\pico\reputation\migrations\v10x\m3_initial_modules');
	}

	/**
	* Add the columns schema to the tables
	*
	* @return array Array of columns schema
	* @access public
	*/
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'reputation_enabled'	=> array('BOOL', 0),
				),
				$this->table_prefix . 'users'	=> array(
					'user_reputation'		=> array('INT:11', 0),
					'user_last_reputation'	=> array('INT:11', 0),
				),
				$this->table_prefix . 'posts'	=> array(
					'post_reputation'		=> array('INT:11', 0),
				),
			),
		);
	}

	/**
	* Drop the columns schema from the tables
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'reputation_enabled',
				),
				$this->table_prefix . 'users'	=> array(
					'user_reputation',
					'user_last_reputation',
				),
				$this->table_prefix . 'posts'	=> array(
					'post_reputation',
				),
			),
		);
	}
}
