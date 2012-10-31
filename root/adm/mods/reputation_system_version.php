<?php
/**
*
* @package	Reputation System
* @author	Pico88 (http://www.modsteam.tk)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package reputation_system
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

class reputation_system_version
{
	function version()
	{
		return array(
			'author'	=> 'Pico88',
			'title'		=> 'Reputation System',
			'tag'		=> 'reputation_system',
			'version'	=> '0.6.0',
			'file'		=> array('modsteam.tk', 'updatecheck', 'reputation.xml'),
		);
	}
}

?>