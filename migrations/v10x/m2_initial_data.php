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

class m2_initial_data extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('config.add', array('rs_enable', '0')),
			array('config.add', array('rs_sync_step', '0', '1')),
			array('config.add', array('rs_negative_point', '1')),
			array('config.add', array('rs_min_rep_negative', '0')),
			array('config.add', array('rs_min_point', '0')),
			array('config.add', array('rs_max_point', '0')),
			array('config.add', array('rs_prevent_perc', '80')),
			array('config.add', array('rs_prevent_num', '20')),
			array('config.add', array('rs_per_page', '15')),
			array('config.add', array('rs_display_avatar', '1')),
			array('config.add', array('rs_point_type', '0')),
			array('config.add', array('rs_warning', '0')),
			array('config.add', array('rs_post_rating', '0')),
			array('config.add', array('rs_anti_time', '0')),
			array('config.add', array('rs_anti_post', '0')),
			array('config.add', array('rs_anti_method', '0')),
			array('config.add', array('rs_user_rating', '0')),
			array('config.add', array('rs_user_rating_gap', '2')),
			array('config.add', array('rs_enable_comment', '1')),
			array('config.add', array('rs_force_comment', '0')),
			array('config.add', array('rs_comment_max_chars', '255')),
			array('config.add', array('rs_enable_power', '1')),
			array('config.add', array('rs_power_renewal', '0')),
			array('config.add', array('rs_min_power', '1')),
			array('config.add', array('rs_max_power', '3')),
			array('config.add', array('rs_max_power_warning', '10')),
			array('config.add', array('rs_power_explain', '1')),
			array('config.add', array('rs_total_posts', '0')),
			array('config.add', array('rs_membership_days', '80')),
			array('config.add', array('rs_power_rep_point', '10')),
			array('config.add', array('rs_power_lose_warn', '3')),
			array('config.add', array('rs_enable_toplist', '0')),
			array('config.add', array('rs_toplist_direction', '0')),
			array('config.add', array('rs_toplist_num', '5')),
		);
	}
}
