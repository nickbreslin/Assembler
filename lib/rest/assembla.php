<?php

class Assembla extends Abstract_Class
{	
    function __construct()
    {
		parent::__construct();
		$this->_init();
    }

	private function _init()
	{
	}
	
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
	
	private function _xml($url)
	{
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, config('assembla.username').":".config('assembla.password'));
		$store = curl_exec($ch);
		echo $store;
		curl_close($ch);
	}

	private function _curl($url, $fields = false)
	{    
	    $ci = curl_init();

	    curl_setopt($ci, CURLOPT_URL,            $url);
	    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ci, CURLOPT_HEADER,         false);

	    if($fields) {
	        curl_setopt($ci,CURLOPT_POST,count($fields));
	        curl_setopt($ci,CURLOPT_POSTFIELDS,$fields);
	    }

	    $response = curl_exec($ci);

	    curl_close($ci);

	    return $response;
	}
	
}