<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace pico\reputation\core;

/**
* Reputation power
*/
class reputation_helper
{
	/**
	* Constants for avatar dimensions (width and height)
	*	small = 40px
	*	medium = 60 px
	*/
	const SMALL = 40;
	const MEDIUM = 60;

	/**
	* Reputation class 
	*
	* @param $points Rating points
	* @static
	* @access public
	* @return string String value of CSS class for voting placeholder
	*/
	static public function reputation_class($points)
	{
		if ($points > 0) 
		{
			return 'positive';
		}
		else if ($points < 0) 
		{
			return 'negative';
		}
		else
		{
			return 'neutral';
		}
	}

	/**
	* Avatar dimensions
	*
	* @param string $size Avatar size
	* @static
	* @access public
	* @return int Avatar dimension
	*/
	static public function avatar_dimensions($size)
	{
		switch ($size)
		{
			case 'small':
				return self::SMALL;
			break;

			case 'medium':
				return self::MEDIUM;
			break;
		}
	}
}
