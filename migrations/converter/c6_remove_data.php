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

class c6_remove_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !isset($this->config['rs_version']);
	}

	static public function depends_on()
	{
		return array(
			'\pico\reputation\migrations\converter\c3_remove_columns',
			'\pico\reputation\migrations\converter\c4_remove_modules',
		);
	}

	public function update_data()
	{
		return array(
			array('config.remove', array('rs_post_highlight')),
			array('config.remove', array('rs_ranks_path')),
			array('config.remove', array('rs_ranks')),
			array('config.remove', array('rs_pm_notify')),
			array('config.remove', array('rs_notification')),
			array('config.remove', array('rs_hide_post')),
			array('config.remove', array('rs_hide_post')),
			array('config.remove', array('rs_hide_post')),
			array('config.remove', array('rs_version')),
		);
	}
}
