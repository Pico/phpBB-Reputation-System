<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\acp;

class reputation_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $user;

		// Add the pages ACP lang file
		$user->add_lang_ext('pico/reputation', 'reputation_acp');

		// Define acp controller
		$acp_controller = $phpbb_container->get('pico.reputation.acp.controller');

		// Send url to acp controller
		$acp_controller->set_page_url($this->u_action);

		switch ($mode)
		{
			case 'overview':
				$this->tpl_name = 'reputation_overview';

				$this->page_title = $user->lang('ACP_REPUTATION_OVERVIEW');

				$acp_controller->display_overview();
			break;

			case 'settings':
				$this->tpl_name = 'reputation_settings';

				$this->page_title = $user->lang('ACP_REPUTATION_SETTINGS');

				$acp_controller->manage_options();
			break;

			case 'rate':
				$this->tpl_name = 'reputation_rate';

				$this->page_title = $user->lang('ACP_REPUTATION_RATE');

				$acp_controller->rate_user();
			break;

			case 'sync':
				// ToDo
				trigger_error('ToDo');
			break;
		}
	}
}
