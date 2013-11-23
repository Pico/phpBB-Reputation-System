<?php
/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace pico88\reputation\core;

class power
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var string The table we use to store our reputations.*/
	protected $reputation_table;

	/** @var array Reputation power explanations */
	private $explain;

	/**
	* Constructor
	* 
	* @param \phpbb\config\config $config
	* @param \phpbb\db\driver\driver $db
	* @param string $reputation_table Name of the table uses to store reputations
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver $db, $reputation_table)
	{
		$this->config = $config;
		$this->db = $db;
		$this->reputation_table = $reputation_table;
	}

	/**
	* Function returns maximum reputation power of one user
	*
	* @param int $posts User posts
	* @param timestamp $regdate User registration date
	* @param int $reputation User reputation
	* @param int $warnings User warnings
	* @return int User power reputation
	* @access public
	*/
	public function get($posts, $regdate, $reputation, $warnings)
	{
		$now = time();
		$user_power = array();

		//Increasing power for number of posts
		if ($this->config['rs_total_posts'])
		{
			$user_power['FOR_NUMBER_OF_POSTS'] = intval($posts / $this->config['rs_total_posts']);
		}

		//Increasing power for the age of the user
		if ($this->config['rs_membership_days'])
		{
			$user_power['FOR_USER_AGE'] = intval(intval(($now - $regdate) / 86400) / $this->config['rs_membership_days']);
		}

		//Increasing power for total reputation
		if ($this->config['rs_power_rep_point'])
		{
			$user_power['FOR_REPUTATION'] = intval($reputation / $this->config['rs_power_rep_point']);
		}

		//Decreasing power for warnings
		if ($this->config['rs_power_lose_warn'] > 0)
		{
			$user_power['FOR_WARNINGS'] = -$warnings * $this->config['rs_power_lose_warn'];
		}

		//Max user power
		if (empty($user_power))
		{
			$user_max_power = $this->config['rs_max_power'];
		}
		else
		{
			$user_max_power = array_sum($user_power);
			$user_max_power = $user_max_power + $this->config['rs_min_power'];
		}

		//Check min power - if it is set, inform about it
		if ($this->config['rs_min_power'])
		{
			$user_power['MINIMUM_VOTING_POWER'] = $this->config['rs_min_power'];
		}

		//Checking if user power is not lower than minimum power set in ACP
		if ($user_max_power < $this->config['rs_min_power'])
		{
			$user_max_power = max($this->config['rs_min_power'], $user_max_power);
		}

		//Checking if user power is not higher than maximum power set in ACP
		if ($user_max_power > $this->config['rs_max_power'])
		{
			$user_power['MAXIMUM_VOTING_POWER'] = $this->config['rs_max_power'];
			$user_max_power = min($this->config['rs_max_power'], $user_max_power);
		}

		//Put the structure of the user power into $this->explain
		$this->explain = $user_power;

		return $user_max_power;
	}

	/**
	* Function return an array explaining structure of the user power
	*
	* @return array|int
	* @access public
	*/
	public function explain()
	{
		return $this->explain;
	}

	/**
	* Function returns used reputation power
	*
	* @param $user_id User ID
	* @return array used reputation power
	* @access public
	*/
	public function used($user_id)
	{
		$used_power = 0;

		if ($this->config['rs_power_renewal'])
		{
			//Until what timestamp should we count user votes
			$renewal_timeout = time() - $this->config['rs_power_renewal'] * 3600;

			//Let's get all voting data on this user.
			$sql = 'SELECT point
				FROM ' . $this->reputation_table . "
				WHERE rep_from = int {$user_id}
					AND (action = 1 OR action = 2)
					AND time > $renewal_timeout";
			$result = $this->db->sql_query($sql);

			//Let's run through the rows and make statistics
			while($renewal = $this->db->sql_fetchrow($result))
			{
				//How much power a user spent in a specified period of time
				$used_power += abs($renewal['point']);
			}
			$this->db->sql_freeresult($result);
		}

		return $used_power;
	}
}