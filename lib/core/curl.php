<?php
//client URL
class Curl extends Base
{	
    
    static $instance;
    
    function __construct()
    {
		parent::__construct();
		$this->_init();
    }

	private function _init()
	{
		if(!function_exists('curl_init'))
		{
			Debug::error("No Curl Installed!");
		}
	}
	
	private function xml($url)
	{
		/*
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, config('assembla.username').":".config('assembla.password'));
		$results = curl_exec($ch);
		curl_close($ch);
		
		return $results;
		*/
		//$ch - cURL Handle
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL,            $url);		// URL to Scrape
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	// Return string from curl_exec
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_HEADER,         false); // Include the header info
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Accept: application/xml")); 
		// Used for POST, instead of GET
	    if(!empty($fields))
		{
	        curl_setopt($ch,CURLOPT_POST,count($fields)); // HTTP Post
	        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields); // When posting a file, add @ with full path, otherwise as array
	    }

	    $results = curl_exec($ch);

	    curl_close($ch);

	    return $results;
	}

	private function curl($url, $fields = false)
	{    
		//$ch - cURL Handle
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL,            $url);		// URL to Scrape
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	// Return string from curl_exec
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_HEADER,         false); // Include the header info
		// Used for POST, instead of GET
	    if(!empty($fields))
		{
	        curl_setopt($ch,CURLOPT_POST,count($fields)); // HTTP Post
	        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields); // When posting a file, add @ with full path, otherwise as array
	    }

	    $results = curl_exec($ch);

	    curl_close($ch);

	    return $results;
	}
	
	static public function fetch($url, $xml = false, $fields = false)
	{
		if(empty($url))
		{
			Debug::error("Missing Url for Curl");
			return false;
		}
		
		if(!self::$instance)
		{
		    self::$instance = new self();
	    }
		
		if($xml)
		{
			$results = self::$instance->xml($url, $fields);
		}
		else
		{
			$results = self::$instance->curl($url, $fields);
		}
		
		return $results;
	}	
}