<?php

class CodAPI
{
	private $endpoints = [
		'validate' => 'https://callofdutytracker.com/ajax/post',
		'userstats' => 'https://callofdutytracker.com/api/stats/%s/%s/%s',
	];

	public function validateUser($username = '', $game = '', $platform = '')
	{
		$data = $this->post('validate', ['type' => 'validateUser', 'username' => $username, 'game' => $game, 'platform' => $platform]);

		if(strtolower($data) == strtolower($username)) 
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
		$data = $this->post('userstats', '', $username, $game, $platform);

		if(isset($data->status) && $data->status == 'error') 
		{
			return $data->msg;
		}
		else
		{
			return $data;
		}
	}

	private function post($endpoint, $fields, $username = '', $game = '', $platform = '')
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, sprintf($this->endpoints[$endpoint], $game, $username, $platform));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_POST, 1); 

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Length: ' . strlen($fields),
		));

		if(is_array($fields)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		}

		$output = curl_exec($ch);

		curl_close($ch);

		return $output;
	}
}

?>
