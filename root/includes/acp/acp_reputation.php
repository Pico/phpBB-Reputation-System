<?php
/**
*
* @package		Reputation System
* @author		Pico88 (Pico) (http://www.modsteam.tk)
* @co-author	Versusnja
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package acp
*/
class acp_reputation
{
	var $u_action;

	function main($id, $mode)
	{
		global $cache, $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpEx, $phpbb_admin_path;

		$form_key = 'acp_reputation';
		add_form_key($form_key);

		$submit = (isset($_POST['submit']) || isset($_POST['enable_reputation'])) ? true : false;
		$action = request_var('action', '');

		$this->tpl_name = 'acp_reputation';

		switch ($mode)
		{
			case 'settings':
				$display_vars = array(
					'title'	=> 'ACP_REPUTATION_SYSTEM',
					'vars'	=> array(
						'legend1'				=> 'ACP_RS_MAIN',
						'rs_enable'				=> array('lang' => 'RS_ENABLE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
						'rs_ajax_enable'		=> array('lang' => 'RS_AJAX_ENABLE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_per_popup'			=> array('lang' => 'RS_PER_POPUP', 'validate' => 'int:1:10', 'type' => 'text:4:5', 'explain' => true),
						'rs_sort_memberlist'	=> array('lang' => 'RS_SORT_MEMBERLIST_BY_REPO', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_negative_point'		=> array('lang' => 'RS_NEGATIVE_POINT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_warning'			=> array('lang' => 'RS_WARNING', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_user_rating'		=> array('lang' => 'RS_USER_RATING', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
						'rs_post_rating'		=> array('lang' => 'RS_POST_RATING', 'validate' => 'bool', 'type' => 'custom', 'method' => 'post_rating', 'explain' => false),
						'rs_notification'		=> array('lang' => 'RS_NOTIFICATION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_pm_notify'			=> array('lang' => 'RS_PM_NOTIFY', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_ranks'				=> array('lang' => 'RS_RANK_ENABLE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => false),
						'rs_point_type'			=> array('lang' => 'RS_POINT_TYPE', 'validate' => 'bool', 'type' => 'custom', 'method' => 'point_type', 'explain' => true),
						'rs_min_point'			=> array('lang' => 'RS_MIN_POINT', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_point'			=> array('lang' => 'RS_MAX_POINT', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_per_page'			=> array('lang' => 'RS_PER_PAGE', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),

						'legend2'				=> 'ACP_RS_POST_RATING',
						'rs_post_display'		=> array('lang' => 'RS_POST_DISPLAY', 'validate' => 'bool', 'type' => 'custom', 'method' => 'post_display', 'explain' => true),
						'rs_post_detail'		=> array('lang' => 'RS_POST_DETAIL', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_hide_post'			=> array('lang' => 'RS_HIDE_POST', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_anti_time'			=> array('lang' => 'RS_ANTISPAM', 'validate' => 'int:0:180', 'type' => false, 'method' => false, 'explain' => false,),
						'rs_anti_post'			=> array('lang' => 'RS_ANTISPAM', 'validate' => 'int:0', 'type' => 'custom:0:180', 'method' => 'antispam', 'explain' => true),
						'rs_anti_method'		=> array('lang' => 'RS_ANTISPAM_METHOD', 'validate' => 'bool', 'type' => 'custom', 'method' => 'antimethod', 'explain' => true),

						'legend3'				=> 'ACP_RS_COMMENT',
						'rs_enable_comment'		=> array('lang' => 'RS_ENABLE_COMMENT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_force_comment'		=> array('lang' => 'RS_FORCE_COMMENT', 'validate' => 'int:0:3', 'type' => 'custom', 'method' => 'select_comment', 'explain' => true),
						
						'legend4'				=> 'ACP_RS_POWER',
						'rs_enable_power'		=> array('lang' => 'RS_ENABLE_POWER', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_power_limit_time'	=> array('lang' => 'RS_POWER_LIMIT', 'validate' => 'int:0', 'type' => false, 'method' => false, 'explain' => false,),
						'rs_power_limit_value'	=> array('lang' => 'RS_POWER_LIMIT', 'validate' => 'int:0', 'type' => 'custom:0:180', 'method' => 'powermethod', 'explain' => true),
						'rs_min_power'			=> array('lang' => 'RS_MIN_POWER', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_power'			=> array('lang' => 'RS_MAX_POWER', 'validate' => 'int:1:20', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_power_warning'	=> array('lang' => 'RS_MAX_POWER_WARNING', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_power_ban'		=> array('lang' => 'RS_MAX_POWER_BAN', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_total_posts'		=> array('lang' => 'RS_TOTAL_POSTS', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_membership_days'	=> array('lang' => 'RS_MEMBERSHIP_DAYS', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_power_rep_point'	=> array('lang' => 'RS_POWER_REP_POINT', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_power_loose_warn'	=> array('lang' => 'RS_LOOSE_POWER_WARN', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_power_loose_ban'	=> array('lang' => 'RS_LOOSE_POWER_BAN', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),

						'legend5'				=> 'ACP_RS_TOPLIST',
						'rs_enable_toplist'		=> array('lang' => 'RS_ENABLE_TOPLIST', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_toplist_direction'	=> array('lang' => 'RS_TOPLIST_DIRECTION', 'validate' => 'bool', 'type' => 'custom', 'method' => 'toplist_direction', 'explain' => true),
						'rs_toplist_num'		=> array('lang' => 'RS_TOPLIST_NUM', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),

						'legend6'				=> 'ACP_RS_BAN',
						'rs_enable_ban'			=> array('lang' => 'RS_ENABLE_BAN', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_ban_shield'			=> array('lang' => 'RS_BAN_SHIELD', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true, 'append' => ' ' . $user->lang['DAYS']),
						'rs_ban_groups'			=> array('lang' => 'RS_BAN_GROUPS', 'validate' => 'string', 'type' => 'custom', 'method' => 'group_exclude', 'explain' => true),
					)
				);

				if (isset($display_vars['lang']))
				{
					$user->add_lang($display_vars['lang']);
				}

				$this->new_config = $config;
				$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
				$error = array();

				// We validate the complete config if whished
				validate_config_vars($display_vars['vars'], $cfg_array, $error);

				if ($submit && !check_form_key($form_key))
				{
					$error[] = $user->lang['FORM_INVALID'];
				}
				// Do not write values if there is an error
				if (sizeof($error))
				{
					$submit = false;
				}

				// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
				foreach ($display_vars['vars'] as $config_name => $null)
				{
					if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
					{
						continue;
					}

					$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

					if ($submit)
					{
						set_config($config_name, $config_value);
						
						if ($config_name == 'rs_post_rating' && isset($_POST['enable_reputation']))
						{
							$this->enable_reputation();
						}

						//Excluded groups
						$group_ids = implode(',', request_var('rs_ban_groups', array(0)));
						$group_ids_data = (isset($group_ids) && trim($group_ids) != '') ? $group_ids : 0;

						set_config('rs_ban_groups', $group_ids_data);
					}
				}

				if ($submit)
				{
					add_log('admin', 'LOG_REPUTATION_SETTING');

					trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
				}

				$this->page_title = $display_vars['title'];

				$template->assign_vars(array(
					'L_TITLE'			=> $user->lang[$display_vars['title']],
					'L_TITLE_EXPLAIN'	=> $user->lang[$display_vars['title'] . '_EXPLAIN'],

					'S_RS_SET'			=> true,
					'S_ERROR'			=> (sizeof($error)) ? true : false,
					'ERROR_MSG'			=> implode('<br />', $error),
					
					'CURRENT_VERSION'	=> $config['rs_version'],
					'LATEST_VERSION'	=> $this->latest_version(),

					'U_ACTION'			=> $this->u_action)
				);

				// Output relevant page
				foreach ($display_vars['vars'] as $config_key => $vars)
				{
					if (!is_array($vars) && strpos($config_key, 'legend') === false)
					{
						continue;
					}

					if (strpos($config_key, 'legend') !== false)
					{
						$template->assign_block_vars('options', array(
							'S_LEGEND'		=> true,
							'LEGEND'		=> (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
						);

						continue;
					}

					$type = explode(':', $vars['type']);

					$l_explain = '';
					if ($vars['explain'] && isset($vars['lang_explain']))
					{
						$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
					}
					else if ($vars['explain'])
					{
						$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
					}

					$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

					if (empty($content))
					{
						continue;
					}

					$template->assign_block_vars('options', array(
						'KEY'			=> $config_key,
						'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
						'S_EXPLAIN'		=> $vars['explain'],
						'TITLE_EXPLAIN'	=> $l_explain,
						'CONTENT'		=> $content,
						)
					);

					unset($display_vars['vars'][$config_key]);
				}
			break;

			case 'sync':
				$this->page_title = 'RS_SYNC';
				$template->assign_var('S_RS_SYNC', true);

				if (!$cache->get('_reputation') || $cache->get('_reputation') == 0)
				{
					$refresh = request_var('refresh', false);
					if ($refresh)
					{
						$cache->put('_reputation', $step_sync = 1);
					}
					else
					{
						$cache->put('_reputation', $step_sync = 0);
					}
				}
				$step_sync = $cache->get('_reputation');

				switch ($step_sync)
				{
					case '0':
						$template->assign_vars(array(
							'S_REFRESH'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_START'],
						));
						return;
					break;

					case '1':
						$template->assign_vars(array(
							'S_REFRESH'		=> false,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_DEL'],
						));

						$sql = 'SELECT rep_to
							FROM ' . REPUTATIONS_TABLE . '
							GROUP BY rep_to';
						$result = $db->sql_query($sql);

						if ($row = $db->sql_fetchrow($result))
						{
							do
							{
								$this->sync_reputation($row['rep_to']);
							}
							while ($row = $db->sql_fetchrow($result));
						}
						$db->sql_freeresult($result);

						$step_sync = $step_sync + 1;
						$cache->put('_reputation', $step_sync);
						meta_refresh(1, append_sid($this->u_action));
						return;
					break;

					case '2':
						$template->assign_vars(array(
							'S_REFRESH'		=> false,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_USER'],
						));

						$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_reputation = 0');

						$sql = 'SELECT SUM(point) AS rep_points, rep_to
							FROM ' . REPUTATIONS_TABLE . '
							WHERE action != 5
							GROUP BY rep_to';
						$result = $db->sql_query($sql);

						if ($row = $db->sql_fetchrow($result))
						{
							do
							{
								$user_point = 0;
								if ($row['rep_points'] > 0)
								{
									$user_point = ($config['rs_max_point'] && ($row['rep_points'] > $config['rs_max_point'])) ? $config['rs_max_point'] : $row['rep_points'];
								}
								else if ($row['rep_points'] < 0)
								{
									$user_point =($config['rs_min_point'] && ($row['rep_points'] < $config['rs_min_point'])) ? $config['rs_min_point'] : $row['rep_points'];
								}
									
								$sql = 'UPDATE ' . USERS_TABLE . "
									SET user_reputation = user_reputation + $user_point
									WHERE user_id = {$row['rep_to']}";
								$db->sql_query($sql);
							}
							while ($row = $db->sql_fetchrow($result));
						}
						$db->sql_freeresult($result);

						$step_sync = $step_sync + 1;
						$cache->put('_reputation', $step_sync);
						meta_refresh(1, append_sid($this->u_action));
						return;
					break;

					case '3':
						$template->assign_vars(array(
							'S_REFRESH'		=> false,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_POST_1'],
						));

						$db->sql_query('UPDATE ' . POSTS_TABLE . ' SET post_reputation = 0');

						$sql = 'SELECT SUM(point) AS rep_points, post_id
							FROM ' . REPUTATIONS_TABLE . '
							WHERE post_id != 0
							GROUP BY post_id';
						$result = $db->sql_query($sql);

						if ($row = $db->sql_fetchrow($result))
						{
							do
							{	
								$sql = 'UPDATE ' . POSTS_TABLE . "
									SET post_reputation = post_reputation + {$row['rep_points']}
									WHERE post_id = {$row['post_id']}";
								$db->sql_query($sql);
							}
							while ($row = $db->sql_fetchrow($result));
						}
						$db->sql_freeresult($result);

						$step_sync = $step_sync + 1;
						$cache->put('_reputation', $step_sync);
						meta_refresh(1, append_sid($this->u_action));
						return;
					break;

					case '4':
						$template->assign_vars(array(
							'S_REFRESH'		=> false,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_POST_2'],
						));
						
						$db->sql_query('UPDATE ' . POSTS_TABLE . ' SET post_rs_count = 0');

						$sql = 'SELECT COUNT(rep_from) AS p_users, post_id
							FROM ' . REPUTATIONS_TABLE . '
							WHERE point > 0
							GROUP BY post_id';
						$result = $db->sql_query($sql);

						if ($row = $db->sql_fetchrow($result))
						{
							do
							{
								$sql = 'UPDATE ' . POSTS_TABLE . "
									SET post_rs_count = post_rs_count + {$row['p_users']}
									WHERE post_id = {$row['post_id']}";
								$db->sql_query($sql);
							}
							while ($row = $db->sql_fetchrow($result));
						}
						$db->sql_freeresult($result);

						$sql = 'SELECT COUNT(rep_from) AS n_users, post_id
							FROM ' . REPUTATIONS_TABLE . '
							WHERE point < 0
							GROUP BY post_id';
						$result = $db->sql_query($sql);

						if ($row = $db->sql_fetchrow($result))
						{
							do
							{
								$sql = 'UPDATE ' . POSTS_TABLE . "
									SET post_rs_count = post_rs_count - {$row['n_users']}
									WHERE post_id = {$row['post_id']}";
								$db->sql_query($sql);
							}
							while ($row = $db->sql_fetchrow($result));
						}
						$db->sql_freeresult($result);

						$step_sync = $step_sync + 1;
						$cache->put('_reputation', $step_sync);
						meta_refresh(1, append_sid($this->u_action));
						return;
					break;

					case '5':
						$template->assign_vars(array(
							'S_REFRESH'	=> true,
							'DONE'		=> true,
						));
						$cache->destroy('_reputation');

						add_log('admin', 'LOG_REPUTATION_SYNC');

						return;
					break;
				}
			break;

			case 'give_point':
				$user->add_lang('mods/reputation_system');

				$username	= utf8_normalize_nfc(request_var('username', '', true));
				$notify		= request_var('notify_user', '');
				$comment	= utf8_normalize_nfc(request_var('comment', '', true));
				$rep_power	= request_var('rep_power', '');

				if ($submit)
				{
					if (!check_form_key($form_key))
					{
						trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
					}

					if	(!is_numeric($rep_power))
					{
						trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
					}

					include($phpbb_root_path . '/includes/functions_reputation.' . $phpEx);
					$reputation = new reputation();

					$sql = 'SELECT user_id
						FROM ' . USERS_TABLE . "
						WHERE username_clean = '" . $db->sql_escape(utf8_clean_string($username)) . "'";
					$result = $db->sql_query($sql);
					$user_id = (int) $db->sql_fetchfield('user_id');
					$db->sql_freeresult($result);

					if (!$user_id)
					{
						trigger_error($user->lang['NO_USER'] . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$reputation->give_point($user_id, 0, $comment, $notify, $rep_power, 'user');

					trigger_error($user->lang['RS_SENT']. adm_back_link($this->u_action));
				}

				$this->page_title = 'ACP_REPUTATION_GIVE';

				$template->assign_vars(array(
					'U_ACTION'			=> $this->u_action,
					'U_FIND_USERNAME'	=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=acp_reputation&amp;field=username&amp;select_single=true'),
					'S_RS_GIVE'			=> true,
					)
				);
			break;

			case 'ranks':
				$action = (isset($_POST['add'])) ? 'add' : $action;
				$action = (isset($_POST['save'])) ? 'save' : $action;
				$rank_id = request_var('id', 0);

				$template->assign_var('S_RS_RANKS', true);

				switch ($action)
				{
					case 'save':

						if (!check_form_key($form_key))
						{
							trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
						}
						$rank_title = utf8_normalize_nfc(request_var('title', '', true));
						$min_points = request_var('min_points', 0);
						$rank_color = utf8_normalize_nfc(request_var('color', '', true));

						if (!$rank_title)
						{
							trigger_error($user->lang['RS_NO_RANK_TITLE'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						$sql_ary = array(
							'rank_title'		=> $rank_title,
							'rank_points'		=> $min_points,
							'rank_color'		=> $rank_color
						);

						if ($rank_id)
						{
							$sql = 'UPDATE ' . REPUTATIONS_RANKS_TABLE . '
								SET ' . $db->sql_build_array('UPDATE', $sql_ary) . "
								WHERE rank_id = $rank_id";
							$message = $user->lang['RS_RANK_UPDATED'];

							add_log('admin', 'LOG_RS_RANK_UPDATED', $rank_title);
						}
						else
						{
							$sql = 'INSERT INTO ' . REPUTATIONS_RANKS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
							$message = $user->lang['RS_RANK_ADDED'];

							add_log('admin', 'LOG_RS_RANK_ADDED', $rank_title);
						}
						$db->sql_query($sql);
						
						$cache->destroy('_rs_ranks');

						trigger_error($message . adm_back_link($this->u_action));

					break;

					case 'delete':

						if (!$rank_id)
						{
							trigger_error($user->lang['RS_MUST_SELECT_RANK'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						if (confirm_box(true))
						{
							$sql = 'SELECT rank_title
								FROM ' . REPUTATIONS_RANKS_TABLE . "
								WHERE rank_id = $rank_id";
							$result = $db->sql_query($sql);
							$rank_title = (string) $db->sql_fetchfield('rank_title');
							$db->sql_freeresult($result);

							$sql = 'DELETE FROM ' . REPUTATIONS_RANKS_TABLE . "
								WHERE rank_id = $rank_id";
							$db->sql_query($sql);
							
							$cache->destroy('_rs_ranks');

							add_log('admin', 'LOG_RS_RANK_REMOVED', $rank_title);
						}
						else
						{
							confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
								'i'			=> $id,
								'mode'		=> $mode,
								'rank_id'	=> $rank_id,
								'action'	=> 'delete',
							)));
						}

					break;

					case 'edit':
					case 'add':

						if ($action == 'edit')
						{
							$sql = 'SELECT *
								FROM ' . REPUTATIONS_RANKS_TABLE . "
								WHERE rank_id = $rank_id";
							$result = $db->sql_query($sql);
							$row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);
						}

						$template->assign_vars(array(
							'S_EDIT'			=> true,
							'U_BACK'			=> $this->u_action,
							'U_ACTION'			=> $this->u_action . '&amp;id=' . $rank_id,
							'RANK_TITLE'		=> (isset($row['rank_title'])) ? $row['rank_title'] : '',
							'MIN_POINTS'		=> (isset($row['rank_points'])) ? $row['rank_points'] : 0,
							'RANK_COLOR'		=> (isset($row['rank_color'])) ? $row['rank_color'] : '',
							'S_NEUTRAL'			=> (isset($row['rank_color']) && $row['rank_color']== 'zero'),
							'S_POSITIVE'		=> (isset($row['rank_color']) && $row['rank_color'] == 'positive'),
							'S_NEGATIVE'		=> (isset($row['rank_color']) && $row['rank_color'] == 'negative')
						));
						
						return;

					break;
				}

				$this->page_title = 'ACP_REPUTATION_RANKS';

				$template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
				));

				$sql = 'SELECT *
					FROM ' . REPUTATIONS_RANKS_TABLE . '
					ORDER BY rank_points DESC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('ranks', array(
						'RANK_TITLE'		=> $row['rank_title'],
						'MIN_POINTS'		=> $row['rank_points'],
						'U_EDIT'			=> $this->u_action . '&amp;action=edit&amp;id=' . $row['rank_id'],
						'U_DELETE'			=> $this->u_action . '&amp;action=delete&amp;id=' . $row['rank_id'],
						'S_POSITIVE'		=> $row['rank_color'] == 'positive',
						'S_NEGATIVE'		=> $row['rank_color'] == 'negative'
					));	
				}
				$db->sql_freeresult($result);
			break;

			case 'bans':
				$user->add_lang(array('acp/ban', 'acp/users'));

				$action = (isset($_POST['add'])) ? 'add' : $action;
				$action = (isset($_POST['save'])) ? 'save' : $action;
				$ban_id = request_var('id', 0);
				$point = request_var('point', '');
				$ban_len = request_var('banlength', 0);
				$ban_len_other = request_var('banlengthother', '');
				$ban_reason = utf8_normalize_nfc(request_var('banreason', '', true));
				$ban_give_reason = utf8_normalize_nfc(request_var('bangivereason', '', true));
				$ban_type = utf8_normalize_nfc(request_var('bantype','m', true));

				$template->assign_var('S_RS_BANS', true);

				switch ($action)
				{
					case 'save':

						if (!check_form_key($form_key) || ($point > -1))
						{
							trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
						}

						$sql = 'SELECT ban_id
							FROM ' . REPUTATIONS_BANS_TABLE . "
							WHERE point = $point";
						$result = $db->sql_query($sql);
						$check_value = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if ($check_value && !$ban_id)
						{
							trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
						}

						if ($ban_type == 'h') $ban_len_other = $ban_len_other * 60;
						if ($ban_type == 'd') $ban_len_other = $ban_len_other * 1440;

						$sql_ary = array(
							'point'				=> $point,
							'ban_time'			=> ($ban_len == -1) ? $ban_len_other : $ban_len,
							'ban_type'			=> ($ban_len == -1) ? $ban_type : 0,
							'ban_reason'		=> $ban_reason,
							'ban_give_reason'	=> $ban_give_reason
						);

						if ($ban_id)
						{
							$sql = 'UPDATE ' . REPUTATIONS_BANS_TABLE . '
								SET ' . $db->sql_build_array('UPDATE', $sql_ary) . "
								WHERE ban_id = $ban_id";
							$message = $user->lang['RS_BAN_UPDATED'];

							add_log('admin', 'LOG_RS_BAN_UPDATED');
						}
						else
						{
							$sql = 'INSERT INTO ' . REPUTATIONS_BANS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
							$message = $user->lang['RS_BAN_ADDED'];

							add_log('admin', 'LOG_RS_BAN_ADDED');
						}
						$db->sql_query($sql);

						set_config('rs_ban_type', $ban_type);

						trigger_error($message . adm_back_link($this->u_action));

						break;

					case 'delete':

						if (!$ban_id)
						{
							trigger_error($user->lang['RS_MUST_SELECT_BAN'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						if (confirm_box(true))
						{
							$sql = 'DELETE FROM ' . REPUTATIONS_BANS_TABLE . "
								WHERE ban_id = $ban_id";
							$db->sql_query($sql);

							add_log('admin', 'LOG_RS_BAN_REMOVED');
						}
						else
						{
							confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
								'i'			=> $id,
								'mode'		=> $mode,
								'rank_id'	=> $ban_id,
								'action'	=> 'delete',
							)));
						}

						break;

					case 'edit':
					case 'add':

						if ($action == 'edit')
						{
							$sql = 'SELECT *
								FROM ' . REPUTATIONS_BANS_TABLE . "
								WHERE ban_id = $ban_id";
							$result = $db->sql_query($sql);
							$row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);
						}

						$ban_end_options = '';
						$ban_end_text = array(0 => $user->lang['PERMANENT'], 30 => $user->lang['30_MINS'], 60 => $user->lang['1_HOUR'], 360 => $user->lang['6_HOURS'], 1440 => $user->lang['1_DAY'], 10080 => $user->lang['7_DAYS'], 20160 => $user->lang['2_WEEKS'], 40320 => $user->lang['1_MONTH']);

						foreach ($ban_end_text as $length => $text)
						{
							$ban_length_check = (isset($row['ban_time']) && ($row['ban_time'] == $length) && ($row['ban_type'] == '0')) ? 'selected="selected"' : '';
							$ban_end_options .= '<option value="' . $length . '"' . $ban_length_check .'>' . $text . '</option>';
						}

						$ban_end_time = array(0, 30, 60, 360, 1440, 10080, 20160, 40320);
						$ban_length_check_other = (isset($row['ban_time']) && !in_array($row['ban_time'], $ban_end_time)) ? 'selected="selected"' : '';
						$ban_end_options .= '<option value="-1"' . $ban_length_check_other .'>' . $user->lang['RS_OTHER'] . '</option>';

						$s_ban_type = '';
						$types = array('m' => 'RS_MINUTES', 'h' => 'RS_HOURS', 'd' => 'RS_DAYS');
						foreach ($types as $type => $lang)
						{
							$selected = (isset($row['ban_type']) && $row['ban_type'] == $type) ? ' selected="selected"' : '';
							$s_ban_type .= '<option value="' . $type . '"' . $selected . '>' . $user->lang[$lang] . '</option>';
						}

						$ban_len_other = isset($row['ban_time']) ? $row['ban_time'] : '';
						if (isset($row['ban_type']) && ($row['ban_type'] == 'h')) $ban_len_other = $row['ban_time'] / 60;
						if (isset($row['ban_type']) && ($row['ban_type'] == 'd')) $ban_len_other = $row['ban_time'] / 1440;

						$template->assign_vars(array(
							'S_EDIT'			=> true,
							'U_BACK'			=> $this->u_action,
							'U_ACTION'			=> $this->u_action . '&amp;id=' . $ban_id,
							'POINT'				=> (isset($row['point'])) ? $row['point'] : '',
							'BAN_LENGTH'		=> (isset($row['ban_time']) && ($row['ban_type'] != '0')) ? $ban_len_other : '',
							'BAN_REASON'		=> (isset($row['ban_reason'])) ? $row['ban_reason'] : $user->lang['RS_AUTO_BAN_REASON'],
							'BAN_GIVE_REASON'	=> (isset($row['ban_give_reason'])) ? $row['ban_give_reason'] : '',
							'S_BAN_END_OPTIONS'	=> $ban_end_options,
							'S_BAN_TYPE'		=> $s_ban_type,
							'S_BAN_LENGTH'	 	=> empty($ban_length_check_other) ? true : false,
						));

						return;

						break;
				}

				$this->page_title = 'ACP_REPUTATION_BANS';

				$template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
				));

				$sql = 'SELECT *
					FROM ' . REPUTATIONS_BANS_TABLE . '
					ORDER BY point DESC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$ban_time = $row['ban_time'];
					if ($row['ban_type'] == '0')
					{
						if ($row['ban_time'] == '0') $ban_time = $user->lang['PERMANENT'];
						if ($row['ban_time'] == '30') $ban_time = $user->lang['30_MINS'];
						if ($row['ban_time'] == '60') $ban_time = $user->lang['1_HOUR'];
						if ($row['ban_time'] == '360') $ban_time = $user->lang['6_HOURS'];
						if ($row['ban_time'] == '1440') $ban_time = $user->lang['1_DAY'];
						if ($row['ban_time'] == '10080') $ban_time = $user->lang['7_DAYS'];
						if ($row['ban_time'] == '20160') $ban_time = $user->lang['2_WEEKS'];
						if ($row['ban_time'] == '40320') $ban_time = $user->lang['1_MONTH'];
					}

					$ban_type = '';
					if ($row['ban_type'] == 'm') $ban_type = '&nbsp;' . $user->lang['RS_MINUTES'];
					if ($row['ban_type'] == 'h')
					{
						$ban_type = '&nbsp;' . $user->lang['RS_HOURS'];
						$ban_time = $row['ban_time'] / 60;
					}
					if ($row['ban_type'] == 'd')
					{
						$ban_type = '&nbsp;' . $user->lang['RS_DAYS'];
						$ban_time = $row['ban_time'] / 1440;
					}

					$template->assign_block_vars('bans', array(
						'POINT'				=> $row['point'],
						'BAN_TIME'			=> $ban_time . $ban_type,
						'BAN_REASON'		=> $row['ban_reason'],
						'BAN_GIVE_REASON'	=> $row['ban_give_reason'],
						'U_EDIT'			=> $this->u_action . '&amp;action=edit&amp;id=' . $row['ban_id'],
						'U_DELETE'			=> $this->u_action . '&amp;action=delete&amp;id=' . $row['ban_id'],
					));
				}
				$db->sql_freeresult($result);
			break;
		}	
	}

	function version_check()
	{
		global $cache;

		$version = $cache->get('reputation_version');
		if ($version === false)
		{
			if (!function_exists('get_remote_file'))
			{
				global $phpbb_root_path, $phpEx;
				include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
			}

			$errstr = $errno = '';
			$version = get_remote_file('modsteam.tk', '/updatecheck', 'reputation_system_version.txt', $errstr, $errno, 80, 1);

			if ($version !== false)
			{
				$cache->put('reputation_version', $version, 3600);
			}
		}

		return $version;
	}

	function latest_version()
	{
		global $user, $config;

		$latest_version = $this->version_check();
		if ($latest_version === false)
		{
			$version = $user->lang['NOT_AVAILABLE'];
			$version .= '<br />' . sprintf($user->lang['RS_CLICK_CHECK_NEW_VERSION'], '<a href="http://www.phpbb.com/community/viewtopic.php?t=2147118">', '</a>');
		}
		else
		{
			$version = $latest_version;
			if (version_compare($config['rs_version'], $latest_version, '<'))
			{
				$version = '<span style="color: #BC2A4D;">' . $latest_version . '</span><br />' . sprintf($user->lang['RS_CLICK_GET_NEW_VERSION'], '<a href="http://modsteam.tk/viewtopic.php?f=16&t=64#p241">', '</a>');
			}
		}

		return $version;
	}

	function post_rating($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		return h_radio('config[rs_post_rating]', $radio_ary, $value) .
			'<br /><input class="button2" type="submit" id="enable_reputation" name="enable_reputation" value="' . $user->lang['RS_ALLOW_REPUTATION_BUTTON'] . '" />';
	}

	function enable_reputation()
	{
		global $db;

		$sql = 'UPDATE ' . FORUMS_TABLE . "
			SET enable_reputation = 1";
		$db->sql_query($sql);
	}

	function point_type($value, $key)
	{
		global $user, $config;

		$radio_ary = array(
			0	=> 'RS_POINT_VALUE',
			1	=> 'RS_POINT_IMG',
		);

		$radio_text = h_radio('config[rs_point_type]', $radio_ary, $value, 'rs_point_type', $key);

		return $radio_text;
	}

	function post_display($value, $key)
	{
		global $user, $config;

		$radio_ary = array(
			0	=> 'RS_USER_METHOD',
			1	=> 'RS_POINT_METHOD',
		);

		$radio_text = h_radio('config[rs_post_display]', $radio_ary, $value, 'rs_post_display', $key);

		return $radio_text;
	}

	function antimethod($value, $key)
	{
		global $user, $config;

		$radio_ary = array(
			0	=> 'RS_SAME_USER',
			1	=> 'RS_ALL_USERS',
		);

		$radio_text = h_radio('config[rs_anti_method]', $radio_ary, $value, 'rs_anti_method', $key);

		return $radio_text;
	}

	function antispam($value, $key = '')
	{
		global $user;

		return $user->lang['RS_POSTS'] . '&nbsp;<input id="' . $key . '" type="text" size="3" maxlength="3" name="config[rs_anti_post]" value="' . $value . '" /> ' . $user->lang['RS_HOURS'] . '&nbsp;<input type="text" size="3" maxlength="3" name="config[rs_anti_time]" value="' . $this->new_config['rs_anti_time'] . '" />';
	}

	function powermethod($value, $key = '')
	{
		global $user;

		return $user->lang['RS_POWER_LIMIT_VALUE'] . '&nbsp;<input id="' . $key . '" type="text" size="3" maxlength="3" name="config[rs_power_limit_value]" value="' . $value . '" /> ' . $user->lang['RS_POWER_LIMIT_TIME'] . '&nbsp;<input type="text" size="3" maxlength="3" name="config[rs_power_limit_time]" value="' . $this->new_config['rs_power_limit_time'] . '" />' . $user->lang['RS_POWER_LIMIT_HOURS'];
	}		

	function select_comment($value, $key)
	{
		global $user, $config;

		$radio_ary = array(
			0	=> 'RS_COMMENT_NO',
			1	=> 'RS_COMMENT_BOTH',
			2	=> 'RS_COMMENT_POST',
			3	=> 'RS_COMMENT_USER',
		);

		$radio_text = h_radio('config[rs_force_comment]', $radio_ary, $value, 'rs_force_comment', $key);

		return $radio_text;
	}

	function toplist_direction($value, $key)
	{
		global $user, $config;

		$radio_ary = array(
			0	=> 'RS_TL_HORIZONTAL',
			1	=> 'RS_TL_VERTICAL',
		);

		$radio_text = h_radio('config[rs_toplist_direction]', $radio_ary, $value, 'rs_toplist_direction', $key);

		return $radio_text;
	}

	function group_exclude($value, $key)
	{
		global $config, $user, $db;

		$select_id = explode(',', $config['rs_ban_groups']);

		$sql = 'SELECT group_id, group_name, group_type
			FROM ' . GROUPS_TABLE . '
			ORDER BY group_type DESC, group_name ASC';
		$result = $db->sql_query($sql);

		$group_options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		while ($row = $db->sql_fetchrow($result))
		{
			$selected = (is_array($select_id)) ? ((in_array($row['group_id'], $select_id)) ? ' selected="selected"' : '') : (($row['group_id'] == $select_id) ? ' selected="selected"' : '');
			$group_options .= '<option value="' . $row['group_id'] . '"' . $selected . '>' . ucfirst(strtolower((($row['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $row['group_name']] : $row['group_name']))) . '</option>';
		}
		$db->sql_freeresult($result);
		$group_options .= '</select>';

		return $group_options;
	}

	function sync_reputation($rep_to)
	{
		global $db;

		$sql = 'SELECT user_id
			FROM ' . USERS_TABLE . "
			WHERE user_id = $rep_to";
		$result = $db->sql_query($sql);
		$users = $db->sql_fetchfield('user_id');
		$db->sql_freeresult($result);

		if (empty($users))
		{
			$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . "
						WHERE rep_to = " . $rep_to;
			$db->sql_query($sql);
		}
	}
}

?>