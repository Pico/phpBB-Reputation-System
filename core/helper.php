<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\core;

class helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $controller_helper
	* @param string $phpbb_root_path Root path
	* @param string $php_ext Php ext
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $controller_helper, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->controller_helper = $controller_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Generate a URL - depends on ajax request
	* 
	* @param string		$url		The url to go
	* @param bool		$is_ajax	Is url using ajax: true or false
	* @return string	The URL
	* @access public
	*/
	public function generate_url($url, $is_ajax)
	{
		if ($is_ajax)
		{
			// If enable_mod_rewrite is false, we need to include app.php
			$route_prefix = (empty($this->config['enable_mod_rewrite'])) ? 'app.' . $this->php_ext . '/' : '';

			return $this->phpbb_root_path . $route_prefix . $url;
		}
		else
		{
			return $this->controller_helper->url($url);
		}
	}

	/**
	* Generate a URL - depends on ajax request
	* 
	* @param string		$url		The url to go
	* @param bool		$is_ajax	Is url using ajax: true or false
	* @return string	The URL
	* @access public
	*/
	public function path($path, $is_ajax)
	{
		if ($is_ajax)
		{
			return $this->phpbb_root_path . $path;
		}
		else
		{
			return $this->controller_helper->url($path);
		}
	}
}
