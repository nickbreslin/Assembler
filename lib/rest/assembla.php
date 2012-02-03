<?php

class Assembla extends Abstract_Class
{	
	private static $base = "";
	
    function __construct()
    {
		parent::__construct();
		$this->_init();
    }

	private function _init()
	{
		self::$base = "http://".config('assembla.username').":".config('assembla.password')."@www.assembla.com";
	}
	
	
	public function fetchXML($url)
	{
		$results = Curl::fetch($url);

		$string = simplexml_load_string($results);
		
		if ($string===FALSE)
		{
			Debug::error("Not XML");
		}
		else
		{
			$oXML = new SimpleXMLElement($results);
			
			return $oXML;
		}
	}
	
	// http://www.assembla.com/wiki/show/breakoutdocs/Space_REST_API
	public function mySpacesList()
	{
		$url = self::$base."/spaces/my_spaces";
		
		$results = self::fetchXML($url);
		
		$return = array();
		
		foreach($results as $result)
		{
			$data          = array();
			$data['id']    = $result->{'id'};
			$data['name']  = $result->{'name'};
			$data['space'] = $result->{'wiki-name'};
			
			$return[] = $data;
		}

		return $return;
	}


	// http://www.assembla.com/spaces/breakoutdocs/wiki/Ticket_REST_API	
	public function getTickets($spaceId)
	{
		$url = self::$base."/$spaceId/tickets";
		
		$results = self::fetchXML($url);
		
		$return = array();
		
		foreach($results as $result)
		{
			$data          = array();
			$data['id']    = $result->{'id'};
			//$data['name']  = $result->{'name'};
			//$data['space'] = $result->{'wiki-name'};
			Debug::info($data['id']);
			$return[] = $data;
		}

		return $return;
	}	
	/*
	
	
	
	public function createTicket()
	{
		$url = "http://".config('assembla.username').":".config('assembla.password')."@www.assembla.com/spaces/".config('assembla.space')."/tickets/";
		echo $url;

		$fields['ticket[summary]']     = "test summary";
		$fields['ticket[status]']      = 0;
		$fields['ticket[priority]']    = 5;
		$fields['ticket[description]'] = "moo moo moo";
		$fields['[custom-fields]'] = array(
			"a" => "b");
		
		echo $this->_curl($url, $fields);
	}
	
	public function getTicket($ticketId)
	{
		$cmd = 'curl -i -X GET -H "Accept: application/xml" http://'.config('assembla.username').":".config('assembla.password').'@www.assembla.com/spaces/'.config('assembla.space').'/tickets/'.$ticketId;
		exec($cmd, $output);
		
		return $output;
	}
	
	public function getAllTickets()
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://".config('assembla.username').":".config('assembla.password')."@www.assembla.com/spaces/".config('assembla.space')."/tickets/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Accept: application/xml")); 
		curl_setopt($ci, CURLOPT_HEADER,     true);
	    
        $retValue = curl_exec($ch);  
		$xml = simplexml_load_string($retValue);

        curl_close($ch);
        
		foreach($xml->ticket as $ticket){
		        echo $ticket->number . "\n";
		$this->deleteTicket($ticket->number);
		}
		echo "=============<br>";
		echo "<pre>".print_r($xml, true)."</pre>";
        
		return "";
	}
	
	public function deleteTicket($ticketId)
	{
		$cmd = 'curl -i -X DELETE -H "Accept: application/xml" http://'.config('assembla.username').":".config('assembla.password').'@www.assembla.com/spaces/'.config('assembla.space').'/tickets/'.$ticketId;
		exec($cmd, $output);
		
		return $output;
	}	
	*/
}