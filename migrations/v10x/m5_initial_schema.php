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
* Migration stage 5: Initial schema
*/
class m5_initial_schema extends \phpbb\db\migration\migration
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
		return array('\pico\reputation\migrations\v10x\m4_initial_columns');
	}

	/**
	* Add the reputations table schema to the database:
	*	reputations:
	*		reputation_id Rule identifier
	*		user_id_from Giving user identifier
	*		user_id_to Receiving user identifier
	*		reputation_time Voting time
	*		reputation_type_id Action type identifier
	*		reputation_item_id Action item identifier
	*		reputation_points Reputation points
	*		reputation_comment Reputation comment
	*
	* @return array Array of table schema
	* @access public
	*/
	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'reputations'	=> array(
					'COLUMNS'		=> array(
						'reputation_id'				=> array('UINT', null, 'auto_increment'),
						'user_id_from'				=> array('UINT', 0),
						'user_id_to'				=> array('UINT', 0),
						'reputation_time'			=> array('TIMESTAMP', 0),
						'reputation_type_id'		=> array('USINT', 0),
						'reputation_item_id'		=> array('UINT', 0),
						'reputation_points'			=> array('INT:11', 0),
						'reputation_comment'		=> array('TEXT_UNI', ''),
					),
					'PRIMARY_KEY'	=> 'reputation_id',
					'KEYS'			=> array(
						'user_id_from'			=> array('INDEX', 'user_id_from'),
						'user_id_to'			=> array('INDEX', 'user_id_to'),
						'item_id'				=> array('INDEX', 'reputation_item_id'),
					),
				),
				$this->table_prefix . 'reputation_types'	=> array(
					'COLUMNS'		=> array(
						'reputation_type_id'		=> array('UINT', null, 'auto_increment'),
						'reputation_type_name'		=> array('VCHAR:20', ''),
					),
					'PRIMARY_KEY'	=> 'reputation_type_id',
					'KEYS'			=> array(
						'type'			=> array('INDEX', 'reputation_type_name'),
					),
				),
			),
		);
	}

	/**
	* Drop the reputations table schema from the database
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'reputations',
			),
		);
	}
}
