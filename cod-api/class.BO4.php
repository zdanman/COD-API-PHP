<?php

class CodAPI
{
	private $endpoints = [
		'validate' => 'https://cod-api.theapinetwork.com/api/validate/%s/%s/%s',
		'userstats_pvp' => 'https://cod-api.theapinetwork.com/api/stats/%s/%s/%s',
		'userstats_mp' => 'https://cod-api.theapinetwork.com/api/stats/%s/%s/%s?type=blackout',
		'recentmatches' => 'https://cod-api.theapinetwork.com/api/matches/recent?rows=%s',
		'uidtousername' => 'https://cod-api.theapinetwork.com/api/users/ids?id=:id1:&id=:id2',
		'smartsearch' => 'https://cod-api.theapinetwork.com/api/users/smartSearch?username=%s&platform=%s&game=%s',
		'insights' => 'https://cod-api.theapinetwork.com/api/worldwide/stats',
		'leaderboard' => 'https://cod-api.theapinetwork.com/api/leaderboard/%s/%s/%s?rows=%s',
	];
	public function validateUser($username = '', $game = '', $platform = '')
	{
		$data = json_decode($this->get('validate', $username, $game, $platform));
		if(isset($data->username) && strtolower($data->username) == strtolower($username)) 
		{
			return true;
		} 
		else
		{
			return false;
		}
	}
	public function getStats($username = '', $game = '', $platform = '', $type = 'mp')
	{
		$data = json_decode($this->get('userstats_' . $type, $username, $game, $platform));
		if(isset($data->status) && $data->status == 'error') 
		{
			return $data->msg;
		}
		else
		{
			return $data;
		}
	}
	public function getRecentMatches($rows = 100)
	{
		$data = json_decode($this->get('recentmatches', $rows));
		if(isset($data->status) && $data->status == 'error') 
		{
			return $data->msg;
		}
		else
		{
			return $data;
		}
	}
	public function getInsights()
	{
		$data = json_decode($this->get('insights'));
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
		$data = json_decode($this->get('leaderboard', $game, $platform, $scope, $rows));
		if(isset($data->status) && $data->status == 'error') 
		{
			return $data->msg;
		}
		else
		{
			return $data;
		}
	}
	private function get($endpoint, $username = '', $game = '', $platform = '', $extra = '')
	{
		$username = str_replace('#', "%23", $username); //added
		$ch = curl_init();
		$str = sprintf($this->endpoints[$endpoint], $game, $username, $platform, $extra);
		curl_setopt($ch, CURLOPT_URL, $str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		/*curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Length: ' . strlen($fields),
		));*/
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}
?>
