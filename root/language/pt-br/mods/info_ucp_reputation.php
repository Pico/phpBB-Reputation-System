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

$lang = array_merge($lang, array(
	'UCP_REPUTATION'				=> 'Reputação',
	'UCP_REPUTATION_FRONT'			=> 'Página inicial',
	'UCP_REPUTATION_LIST'			=> 'Pontos recebidos',
	'UCP_REPUTATION_GIVEN'			=> 'Conceder pontos',
	'UCP_REPUTATION_SETTING'		=> 'Preferências',

	'RS_CATCHUP'						=> 'Recuperar novas tags',
	'RS_REPUTATION_LISTS_UCP'			=> 'Esta é uma lista de pontos de reputação. Aqui você vai encontrar todos os pontos de reputação que você recebeu de outros membros.',
	'RS_NEW'							=> 'Novo!',
	'RS_REPUTATION_GIVEN_LISTS_UCP'		=> 'Esta é uma lista de pontos de reputação. Aqui você vai encontrar todos os pontos que você concedeu a outros membros.',
	'RS_REPUTATION_SETTINGS_UCP'		=> 'Configurações de reputação',
	'RS_DEFAULT_POWER'					=> 'Default power',
	'RS_DEFAULT_POWER_EXPLAIN'			=> 'Aqui você vai configurar o número padrão de pontos que você quer conceder.',
	'RS_EMPTY'							=> 'Sem padrões',
	'RS_DEF_POINT'						=> 'ponto',
	'RS_DEF_POINTS'						=> 'pontos',
	'RS_NOTIFICATION'					=> 'Notificações',
	'RS_NOTIFICATION_EXPLAIN'			=> 'Habilitar notificações para novos pontos de reputação ( isto é independente das suas outras preferências de PM).',
	'RS_DISPLAY_REPUTATIONS'			=> 'Mostrar pontos de reputação a partir do anterior',
));

?>
