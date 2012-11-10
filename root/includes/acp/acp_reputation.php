<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
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
			case 'overview':
				$this->page_title = 'ACP_REPUTATION_OVERVIEW';
				$template->assign_var('S_RS_OVERVIEW', true);

				$reputation_enable = request_var('reputation_enable', $config['rs_enable']);
				$step_sync = $cache->get('_reputation');

				if (!confirm_box(true))
				{
					$confirm = false;
					switch ($action)
					{
						case 'sync':
							$confirm = true;
							$confirm_lang = 'RS_RESYNC_REPUTATION_CONFIRM';
						break;

						case 'truncate':
							$confirm = true;
							$confirm_lang = 'RS_TRUNCATE_CONFIRM';
						break;
					}

					if ($confirm)
					{
						confirm_box(false, $user->lang[$confirm_lang], build_hidden_fields(array(
							'mode'		=> $mode,
							'action'	=> $action,
					)));
					}
				}
				else
				{
					switch ($action)
					{
						case 'sync':
							$cache->put('_reputation', $step_sync = 1);
						break;
						
						case 'truncate':
							$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_reputation = 0');
							$db->sql_query('UPDATE ' . POSTS_TABLE . ' SET post_reputation = 0');
							$db->sql_query('UPDATE ' . POSTS_TABLE . ' SET post_rs_count = 0');
							$db->sql_query('TRUNCATE ' . REPUTATIONS_TABLE);

							add_log('admin', 'LOG_REPUTATION_TRUNCATE');
							trigger_error($user->lang['RS_TRUNCATE_DONE'] . adm_back_link($this->u_action));
						break;
					}
				}

				switch ($step_sync)
				{
					case '1':
						$template->assign_vars(array(
							'S_RS_SYNC'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_DEL'],
						));

						$users_to_ids = $users_from_ids = array();

						$sql_array = array(
							'SELECT'	=> 'r.rep_to AS user_to_check, u.user_id AS user_exist',
							'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
							'LEFT_JOIN' => array(
								array(
									'FROM'	=> array(USERS_TABLE => 'u'),
									'ON'	=> 'r.rep_to = u.user_id',
								),
							),
							'GROUP_BY'		=> 'r.rep_to',
						);
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							if (empty($row['user_exist']))
							{
								$users_to_ids[] = $row['user_to_check'];
							}
						}
						unset($row);

						$sql_array = array(
							'SELECT'	=> 'r.rep_from AS user_from_check, u.user_id AS user_exist',
							'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
							'LEFT_JOIN' => array(
								array(
									'FROM'	=> array(USERS_TABLE => 'u'),
									'ON'	=> 'r.rep_from = u.user_id',
								),
							),
							'GROUP_BY'		=> 'r.rep_from',
						);
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							if (empty($row['user_exist']))
							{
								$users_from_ids[] = $row['user_from_check'];
							}
						}
						unset($row);

						$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . '
							WHERE ' . $db->sql_in_set('rep_to', $users_to_ids, false, true) . '
								OR ' . $db->sql_in_set('rep_from', $users_from_ids, false, true);
						$db->sql_query($sql);

						$cache->put('_reputation', 2);
						meta_refresh(2, append_sid($this->u_action));
						return;
					break;

					case '2':
						$template->assign_vars(array(
							'S_RS_SYNC'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_POSTS_DEL'],
						));

						$posts_ids = array();

						$sql_array = array(
							'SELECT'	=> 'r.post_id AS post_to_check, p.post_id AS post_exist',
							'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
							'LEFT_JOIN' => array(
								array(
									'FROM'	=> array(POSTS_TABLE => 'p'),
									'ON'	=> 'r.post_id = p.post_id',
								),
							),
							'GROUP_BY'		=> 'r.post_id',
						);
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							if (empty($row['post_exist']))
							{
								$posts_ids[] = $row['post_to_check'];
							}
						}

						$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . '
							WHERE ' . $db->sql_in_set('post_id', $posts_ids, false, true);
						$db->sql_query($sql);

						$cache->put('_reputation', 3);
						meta_refresh(2, append_sid($this->u_action));
						return;
					break;

					case '3':
						$template->assign_vars(array(
							'S_RS_SYNC'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_REPS_DEL'],
						));

						$reps_ids = array();

						$sql = 'SELECT rep_id, action, point
							FROM ' . REPUTATIONS_TABLE;
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							if (!$config['rs_negative_point'] && ($row['action'] == 1 || $row['action'] == 2 || $row['action'] == 5) && ($row['point'] < 0))
							{
								$reps_ids[] = $row['rep_id'];
							}
							if (!$config['rs_warning'] && ($row['action'] == 3))
							{
								$reps_ids[] = $row['rep_id'];
							}
							if (!$config['rs_max_power_ban'] && !$config['rs_enable_ban'] && ($row['action'] == 4))
							{
								$reps_ids[] = $row['rep_id'];
							}
						}

						$sql = 'DELETE FROM ' . REPUTATIONS_TABLE . '
							WHERE ' . $db->sql_in_set('rep_id', $reps_ids, false, true);
						$db->sql_query($sql);

						$cache->put('_reputation', 4);
						meta_refresh(2, append_sid($this->u_action));
						return;
					break;

					case '4':
						$template->assign_vars(array(
							'S_RS_SYNC'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_POST_AUTHOR'],
						));

						$sql_array = array(
							'SELECT'	=> 'r.rep_id, r.rep_to, p.poster_id',
							'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
							'LEFT_JOIN' => array(
								array(
									'FROM'	=> array(POSTS_TABLE => 'p'),
									'ON'	=> 'r.post_id = p.post_id',
								),
							),
							'WHERE'		=> 'r.post_id IS NOT NULL',
						);
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							if ($row['rep_to'] != $row['poster_id'])
							{
								$sql = 'UPDATE ' . REPUTATIONS_TABLE . "
									SET rep_to = {$row['poster_id']}
									WHERE rep_id = {$row['rep_id']}";
								$db->sql_query($sql);
							}
						}

						$cache->put('_reputation', 5);
						meta_refresh(2, append_sid($this->u_action));
						return;
					break;

					case '5':
						$template->assign_vars(array(
							'S_RS_SYNC'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_FORUM'],
						));

						$sql_array = array(
							'SELECT'	=> 'r.rep_id, r.action, f.enable_reputation',
							'FROM'		=> array(REPUTATIONS_TABLE => 'r'),
							'LEFT_JOIN' => array(
								array(
									'FROM'	=> array(POSTS_TABLE => 'p'),
									'ON'	=> 'r.post_id = p.post_id',
								),
								array(
									'FROM'	=> array(FORUMS_TABLE => 'f'),
									'ON'	=> 'p.forum_id = f.forum_id',
								),
							),
							'WHERE'		=> 'r.post_id IS NOT NULL',
						);
						$sql = $db->sql_build_query('SELECT', $sql_array);
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							if (($row['enable_reputation'] == 2) && ($row['action'] == 1))
							{
								$sql = 'UPDATE ' . REPUTATIONS_TABLE . "
									SET action = 5
									WHERE rep_id = {$row['rep_id']}";
								$db->sql_query($sql);
							}
							else if (($row['enable_reputation'] == 1) && ($row['action'] == 5))
							{
								$sql = 'UPDATE ' . REPUTATIONS_TABLE . "
									SET action = 1
									WHERE rep_id = {$row['rep_id']}";
								$db->sql_query($sql);
							}
						}

						$cache->put('_reputation', 6);
						meta_refresh(2, append_sid($this->u_action));
						return;
					break;

					case '6':
						$template->assign_vars(array(
							'S_RS_SYNC'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_USER'],
						));

						$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_reputation = 0');

						$sql = 'SELECT SUM(point) AS rep_points, rep_to
							FROM ' . REPUTATIONS_TABLE . "
							WHERE action != 5
							GROUP BY rep_to";
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
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
								SET user_reputation = $user_point
								WHERE user_id = {$row['rep_to']}";
							$db->sql_query($sql);
						}

						$cache->put('_reputation', 7);
						meta_refresh(2, append_sid($this->u_action));
						return;
					break;

					case '7':
						$template->assign_vars(array(
							'S_RS_SYNC'		=> true,
							'PROGRESS'		=> true,
							'L_PROGRESS'	=> $user->lang['RS_SYNC_STEP_POSTS'],
						));

						$db->sql_query('UPDATE ' . POSTS_TABLE . ' SET post_reputation = 0');

						$sql = 'SELECT SUM(point) AS rep_points, post_id
							FROM ' . REPUTATIONS_TABLE . "
							WHERE post_id != 0
							GROUP BY post_id";
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							$sql = 'UPDATE ' . POSTS_TABLE . "
								SET post_reputation = {$row['rep_points']}
								WHERE post_id = {$row['post_id']}";
							$db->sql_query($sql);
						}

						$cache->put('_reputation', 8);
						meta_refresh(2, append_sid($this->u_action));
						return;
					break;

					case '8':
						$template->assign_vars(array(
							'S_RS_SYNC'	=> true,
							'DONE'		=> true,
						));
						$cache->destroy('_reputation');

						add_log('admin', 'LOG_REPUTATION_SYNC');
						meta_refresh(3, append_sid($this->u_action));
						return;
					break;
				}

				$errstr = '';
				$errno = 0;
				$return_version = true;
				$mod_version = '0.0.0';

				if (file_exists($phpbb_root_path . 'adm/mods/reputation_system_version.' . $phpEx))
				{
					$return_version = false;

					$class_functions = array();
					if (!class_exists('reputation_system_version'))
					{
						include($phpbb_root_path . 'adm/mods/reputation_system_version.' . $phpEx);
					}

					if (!function_exists('get_remote_file'))
					{
						include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
					}

					$var = reputation_system_version::version();

					if (($file = $cache->get('_reputation_version')) === false)
					{
						$file = get_remote_file($var['file'][0], '/' . $var['file'][1], $var['file'][2], $errstr, $errno);

						if ($file !== false)
						{
							$cache->put('_reputation_version', $file, 3600);
						}
					}

					if ($file)
					{
						// let's not stop the page from loading if a mod author messed up their mod check file
						// also take care of one of the easiest ways to mess up an xml file: "&"
						$mod = @simplexml_load_string(str_replace('&', '&amp;', $file));
						if (isset($mod->$var['tag']))
						{
							$row = $mod->$var['tag'];
							$mod_version = $row->mod_version->major . '.' . $row->mod_version->minor . '.' . $row->mod_version->revision . $row->mod_version->release;

							$data = array(
								'title'			=> $row->title,
								'download'		=> $row->download,
								'announcement'	=> $row->announcement,
							);
						}
					}
				}

				if ($return_version)
				{
					$mod_version = $user->lang['NO_INFO'];
					$data = array(
						'title'			=> $user->lang['REPUTATION_SYSTEM'],
						'download'		=> $user->lang['NO_INFO'],
						'announcement'	=> $user->lang['NO_INFO'],
					);
				}

				$version_compare = (version_compare($config['rs_version'], $mod_version, '<')) ? false : true;

				if ($submit)
				{
					if (!check_form_key($form_key))
					{
						trigger_error($user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
					}

					set_config('rs_enable', $reputation_enable);

					add_log('admin', 'LOG_REPUTATION_SETTING');

					trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
				}

				$template->assign_vars(array(
					'ANNOUNCEMENT'		=> $data['announcement'],
					'CURRENT_VERSION'	=> $config['rs_version'],
					'DOWNLOAD'			=> $data['download'],
					'LATEST_VERSION'	=> $mod_version,
					'TITLE'				=> $data['title'],
					'UP_TO_DATE'		=> sprintf((!$version_compare) ? $user->lang['NOT_UP_TO_DATE'] : $user->lang['UP_TO_DATE'], $data['title']),

					'S_UP_TO_DATE'		=> $version_compare,
					'S_RS_ENABLE'		=> $config['rs_enable'],
					'S_FOUNDER'			=> ($user->data['user_type'] == USER_FOUNDER) ? true : false,

					'U_ACTION'			=> $this->u_action
				));
			break;

			case 'settings':

				$display_vars = array(
					'title'	=> 'ACP_REPUTATION_SETTINGS',
					'vars'	=> array(
						'legend1'				=> array('lang' => 'ACP_RS_MAIN', 'tab' => 'main'),
						'rs_negative_point'		=> array('lang' => 'RS_NEGATIVE_POINT', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_min_rep_negative'	=> array('lang' => 'RS_MIN_REP_NEGATIVE', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_warning'			=> array('lang' => 'RS_WARNING', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_notification'		=> array('lang' => 'RS_NOTIFICATION', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_pm_notify'			=> array('lang' => 'RS_PM_NOTIFY', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_min_point'			=> array('lang' => 'RS_MIN_POINT', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_point'			=> array('lang' => 'RS_MAX_POINT', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),

						'legend2'				=> array('lang' => 'ACP_RS_DISPLAY', 'tab' => 'display'),
						'rs_per_page'			=> array('lang' => 'RS_PER_PAGE', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_display_avatar'		=> array('lang' => 'RS_DISPLAY_AVATAR', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_sort_memberlist'	=> array('lang' => 'RS_SORT_MEMBERLIST_BY_REPO', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_point_type'			=> array('lang' => 'RS_POINT_TYPE', 'validate' => 'bool', 'type' => 'custom', 'method' => 'point_type', 'explain' => true),

						'legend3'				=> array('lang' => 'ACP_RS_POSTS_RATING', 'tab' => 'post_rating', 'option' => 'post_rating'),
						'rs_post_rating'		=> array('lang' => 'RS_POST_RATING', 'validate' => 'bool', 'type' => 'custom', 'method' => 'post_rating', 'explain' => false),
						'rs_post_highlight'		=> array('lang' => 'RS_HIGHLIGHT_POST', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_hide_post'			=> array('lang' => 'RS_HIDE_POST', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_anti_time'			=> array('lang' => 'RS_ANTISPAM', 'validate' => 'int:0:180', 'type' => false, 'method' => false, 'explain' => false,),
						'rs_anti_post'			=> array('lang' => 'RS_ANTISPAM', 'validate' => 'int:0', 'type' => 'custom:0:180', 'method' => 'antispam', 'explain' => true),
						'rs_anti_method'		=> array('lang' => 'RS_ANTISPAM_METHOD', 'validate' => 'bool', 'type' => 'custom', 'method' => 'antimethod', 'explain' => true),

						'legend4'				=> array('lang' => 'ACP_RS_USERS_RATING', 'tab' => 'user_rating', 'option' => 'user_rating'),
						'rs_user_rating'		=> array('lang' => 'RS_USER_RATING', 'validate' => 'bool', 'type' => 'custom', 'method' => 'user_rating', 'explain' => false),
						'rs_user_rating_gap'	=> array('lang' => 'RS_USER_RATING_GAP', 'validate' => 'string', 'type' => 'text:4:5', 'explain' => true, 'append' => ' ' . $user->lang['DAYS']),

						'legend5'				=> array('lang' => 'ACP_RS_COMMENT', 'tab' => 'comment', 'option' => 'comment'),
						'rs_enable_comment'		=> array('lang' => 'RS_ENABLE_COMMENT', 'validate' => 'bool', 'type' => 'custom', 'method' => 'comment', 'explain' => true),
						'rs_force_comment'		=> array('lang' => 'RS_FORCE_COMMENT', 'validate' => 'int:0:3', 'type' => 'custom', 'method' => 'select_comment', 'explain' => true),
						'rs_comment_max_chars'	=> array('lang' => 'RS_COMMEN_LENGTH', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),

						'legend6'				=> array('lang' => 'ACP_RS_POWER', 'tab' => 'power', 'option' => 'power'),
						'rs_enable_power'		=> array('lang' => 'RS_ENABLE_POWER', 'validate' => 'bool', 'type' => 'custom', 'method' => 'power', 'explain' => true),
						'rs_power_renewal'		=> array('lang' => 'RS_POWER_RENEWAL', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true, 'append' => ' ' . $user->lang['HOURS']),
						'rs_min_power'			=> array('lang' => 'RS_MIN_POWER', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_power'			=> array('lang' => 'RS_MAX_POWER', 'validate' => 'int:1:20', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_power_warning'	=> array('lang' => 'RS_MAX_POWER_WARNING', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_max_power_ban'		=> array('lang' => 'RS_MAX_POWER_BAN', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),
						'rs_power_explain'		=> array('lang' => 'RS_POWER_EXPLAIN', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'rs_total_posts'		=> array('lang' => 'RS_TOTAL_POSTS', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_membership_days'	=> array('lang' => 'RS_MEMBERSHIP_DAYS', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_power_rep_point'	=> array('lang' => 'RS_POWER_REP_POINT', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_power_lose_warn'	=> array('lang' => 'RS_LOSE_POWER_WARN', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),
						'rs_power_lose_ban'		=> array('lang' => 'RS_LOSE_POWER_BAN', 'validate' => 'int:0', 'type' => 'text:4:5', 'explain' => true),

						'legend7'				=> array('lang' => 'ACP_RS_RANKS', 'tab' => 'rank', 'option' => 'rank'),
						'rs_ranks'				=> array('lang' => 'RS_RANKS_ENABLE', 'validate' => 'bool', 'type' => 'custom', 'method' => 'rank', 'explain' => false),
						'rs_ranks_path'			=> array('lang' => 'RS_RANKS_PATH', 'validate' => 'rpath', 'type' => 'text:20:255', 'explain' => true),

						'legend8'				=> array('lang' => 'ACP_RS_TOPLIST', 'tab' => 'toplist', 'option' => 'toplist'),
						'rs_enable_toplist'		=> array('lang' => 'RS_ENABLE_TOPLIST', 'validate' => 'bool', 'type' => 'custom', 'method' => 'toplist', 'explain' => true),
						'rs_toplist_direction'	=> array('lang' => 'RS_TOPLIST_DIRECTION', 'validate' => 'bool', 'type' => 'custom', 'method' => 'toplist_direction', 'explain' => true),
						'rs_toplist_num'		=> array('lang' => 'RS_TOPLIST_NUM', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true),

						'legend9'				=> array('lang' => 'ACP_RS_BAN', 'tab' => 'ban', 'option' => 'ban'),
						'rs_enable_ban'			=> array('lang' => 'RS_ENABLE_BAN', 'validate' => 'bool', 'type' => 'custom', 'method' => 'ban', 'explain' => true),
						'rs_ban_shield'			=> array('lang' => 'RS_BAN_SHIELD', 'validate' => 'int', 'type' => 'text:4:5', 'explain' => true, 'append' => ' ' . $user->lang['DAYS']),
						'rs_ban_groups'			=> array('lang' => 'RS_BAN_GROUPS', 'validate' => 'string', 'type' => 'custom', 'method' => 'group_exclude', 'explain' => true),
					),
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

					'U_ACTION'			=> $this->u_action
				));

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
							'LEGEND'		=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
							'TAB'			=> $vars['tab'],
							'OPTION'		=> isset($vars['option']) ? $vars['option'] : ''
							)
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
						$rank_image = request_var('rank_image', '');

						if (!$rank_title)
						{
							trigger_error($user->lang['RS_NO_RANK_TITLE'] . adm_back_link($this->u_action), E_USER_WARNING);
						}

						// The rank image has to be a jpg, gif or png
						if ($rank_image != '' && !preg_match('#(\.gif|\.png|\.jpg|\.jpeg)$#i', $rank_image))
						{
							$rank_image = '';
						}

						$sql_ary = array(
							'rank_title'		=> $rank_title,
							'rank_points'		=> $min_points,
							'rank_color'		=> $rank_color,
							'rank_image'		=> htmlspecialchars_decode($rank_image)
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

						$data = $ranks = $existing_imgs = array();

						$sql = 'SELECT *
							FROM ' . REPUTATIONS_RANKS_TABLE . '
							ORDER BY rank_points ASC';
						$result = $db->sql_query($sql);

						while ($row = $db->sql_fetchrow($result))
						{
							$existing_imgs[] = $row['rank_image'];

							if ($action == 'edit' && $rank_id == $row['rank_id'])
							{
								$ranks = $row;
							}
						}
						$db->sql_freeresult($result);

						$imglist = filelist($phpbb_root_path . $config['rs_ranks_path'], '');
						$edit_img = $filename_list = '';

						foreach ($imglist as $path => $img_ary)
						{
							sort($img_ary);

							foreach ($img_ary as $img)
							{
								$img = $path . $img;

								if ($ranks && $img == $ranks['rank_image'])
								{
									$selected = ' selected="selected"';
									$edit_img = $img;
								}
								else
								{
									$selected = '';
								}

								if (strlen($img) > 255)
								{
									continue;
								}

								$filename_list .= '<option value="' . htmlspecialchars($img) . '"' . $selected . '>' . $img . ((in_array($img, $existing_imgs)) ? ' ' . $user->lang['RS_IMAGE_IN_USE'] : '') . '</option>';
							}
						}

						$filename_list = '<option value=""' . (($edit_img == '') ? ' selected="selected"' : '') . '>----------</option>' . $filename_list;
						unset($existing_imgs, $imglist);

						$template->assign_vars(array(
							'S_EDIT'			=> true,
							'U_BACK'			=> $this->u_action,
							'RANKS_PATH'		=> $phpbb_root_path . $config['rs_ranks_path'],
							'U_ACTION'			=> $this->u_action . '&amp;id=' . $rank_id,

							'RANK_TITLE'		=> (isset($ranks['rank_title'])) ? $ranks['rank_title'] : '',
							'S_FILENAME_LIST'	=> $filename_list,
							'RANK_IMAGE'		=> ($edit_img) ? $phpbb_root_path . $config['rs_ranks_path'] . '/' . $edit_img : $phpbb_admin_path . 'images/spacer.gif',
							'MIN_POINTS'		=> (isset($ranks['rank_points'])) ? $ranks['rank_points'] : 0,
							'RANK_COLOR'		=> (isset($ranks['rank_color'])) ? $ranks['rank_color'] : '',
							'S_NEUTRAL'			=> (isset($ranks['rank_color']) && $ranks['rank_color']== 'zero'),
							'S_POSITIVE'		=> (isset($ranks['rank_color']) && $ranks['rank_color'] == 'positive'),
							'S_NEGATIVE'		=> (isset($ranks['rank_color']) && $ranks['rank_color'] == 'negative'),
						));

						return;

					break;
				}

				$this->page_title = 'ACP_REPUTATION_RANKS';

				$template->assign_vars(array(
					'RANKS_ENABLE'	=> $config['rs_ranks'] ? $user->lang['RS_RANKS_ON'] : $user->lang['RS_RANKS_OFF'],
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
						'RANK_IMAGE'		=> $phpbb_root_path . $config['rs_ranks_path'] . '/' . $row['rank_image'],
						'MIN_POINTS'		=> $row['rank_points'],
						'U_EDIT'			=> $this->u_action . '&amp;action=edit&amp;id=' . $row['rank_id'],
						'U_DELETE'			=> $this->u_action . '&amp;action=delete&amp;id=' . $row['rank_id'],
						'S_RANK_IMAGE'		=> ($row['rank_image']) ? true : false,
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

	function post_rating($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		$option = $this->h_rsradio('config[rs_post_rating]', $radio_ary, $value, 'post_rating', $key, 'init_check(\'post_rating\')');
		$option .= '<br /><input class="button2" type="submit" id="enable_reputation" name="enable_reputation" value="' . $user->lang['RS_ALLOW_REPUTATION_BUTTON'] . '" />';

		return $option;
	}

	function user_rating($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		$option = $this->h_rsradio('config[rs_user_rating]', $radio_ary, $value, 'user_rating', $key, 'init_check(\'user_rating\')');

		return $option;
	}

	function comment($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		$option = $this->h_rsradio('config[rs_enable_comment]', $radio_ary, $value, 'comment', $key, 'init_check(\'comment\')');

		return $option;
	}

	function power($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		$option = $this->h_rsradio('config[rs_enable_power]', $radio_ary, $value, 'power', $key, 'init_check(\'power\')');

		return $option;
	}

	function rank($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		$option = $this->h_rsradio('config[rs_ranks]', $radio_ary, $value, 'rank', $key, 'init_check(\'rank\')');

		return $option;
	}

	function toplist($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		$option = $this->h_rsradio('config[rs_enable_toplist]', $radio_ary, $value, 'toplist', $key, 'init_check(\'toplist\')');

		return $option;
	}

	function ban($value, $key)
	{
		global $user;

		$radio_ary = array(1 => 'YES', 0 => 'NO');

		$option = $this->h_rsradio('config[rs_enable_ban]', $radio_ary, $value, 'ban', $key, 'init_check(\'ban\')');

		return $option;
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

	function h_rsradio($name, $input_ary, $input_default = false, $id = false, $key = false, $onclick = false, $separator = '')
	{
		global $user;

		$html = '';
		$id_assigned = false;
		foreach ($input_ary as $value => $title)
		{
			$selected = ($input_default !== false && $value == $input_default) ? ' checked="checked"' : '';
			$html .= '<label><input type="radio" name="' . $name . '"' . (($id && !$id_assigned) ? ' id="' . $id . '"' : '') . (($onclick) ? ' onclick="' . $onclick . ';"' : '') . ' value="' . $value . '"' . $selected . (($key) ? ' accesskey="' . $key . '"' : '') . ' class="radio" /> ' . $user->lang[$title] . '</label>' . $separator;
			$id_assigned = true;
		}

		return $html;
	}
}

?>