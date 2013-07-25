<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2013
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
			'version'	=> '0.7.0',
			'file'		=> array('pico88.github.io', 'phpBB-Reputation-System', 'reputation.xml'),
		);
	}
}

?>