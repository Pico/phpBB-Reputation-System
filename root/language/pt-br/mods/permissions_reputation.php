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
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

// Adding new category
$lang['permission_cat']['reputation'] = 'Reputação';

// Adding the permissions
$lang = array_merge($lang, array(
	'acl_a_reputation'			=> array('lang' => 'Pode gerenciar pontos de reputação', 'cat' => 'misc'),
	'acl_m_rs_give'				=> array('lang' => 'Pode conceder pontos adicionais de reputação', 'cat' => 'reputation'),
	'acl_m_rs_moderate'			=> array('lang' => 'Pode moderar pontos de reputação', 'cat' => 'reputation'),
	'acl_u_rs_delete'			=> array('lang' => 'Pode remover pontos de reputação', 'cat' => 'reputation'),
	'acl_u_rs_give'				=> array('lang' => 'Pode avaliar usuários (conceder pontos de reputação)', 'cat' => 'reputation'),
	'acl_u_rs_give_negative'	=> array('lang' => 'Pode conceder pontos negativos (avaliação de usuário)', 'cat' => 'reputation'),
	'acl_u_rs_ratepost'			=> array('lang' => 'Pode avaliar posts', 'cat' => 'reputation'),
	'acl_u_rs_view'				=> array('lang' => 'Pode ver reputação', 'cat' => 'reputation'),
	'acl_f_rs_give'				=> array('lang' => 'Pode avaliar posts (conceder pontos de reputação)', 'cat' => 'reputation'),
	'acl_f_rs_give_negative'	=> array('lang' => 'Pode conceder pontos negativos', 'cat' => 'reputation'),
));

?>
