<?php

class CodAPI
{
	private $endpoints = [
		'validate' => 'https://callofdutytracker.com/api/validate/%s/%s/%s',
		'userstats' => 'https://callofdutytracker.com/api/stats/%s/%s/%s',
		'leaderboard' => 'https://callofdutytracker.com/api/leaderboard/%s/%s/%s?rows=%s',
	];

	public function validateUser($username = '', $game = '', $platform = '')
	{
		$data = $this->get('validate', $username, $game, $platform);

		if(isset($data->username) && strtolower($data->username) == strtolower($username)) 
		{
			return true;
		} 
		else
		{
			return false;
		}
	}

	public function getStats($username = '', $game = '', $platform = '')
	{
		$data = $this->get('userstats', $username, $game, $platform);

		if(isset($data->status) && $data->status == 'error') 
		{
			return $data->msg;
		}
		else
		{
			return $data;
		}
	}

	public function getLeaderboard($game = '', $platform = '', $scope = '', $rows = 100)
	{
		$data = $this->get('leaderboard', $game, $platform, $scope, $rows);

		if(isset($data->status) && $data->status == 'error') 
		{
			return $data->msg;
		}
		else
		{
			return $data;
		}
	}

	private function get($endpoint, $fields, $username = '', $game = '', $platform = '', $extra = '')
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, sprintf($this->endpoints[$endpoint], $game, $username, $platform, $extra));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Length: ' . strlen($fields),
		));

		$output = curl_exec($ch);

		curl_close($ch);

		return $output;
	}
}

?>
