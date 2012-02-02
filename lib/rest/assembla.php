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
	
	public function mySpacesList()
	{
		
		$url = "http://".config('assembla.username').":".config('assembla.password')."@www.assembla.com/spaces/my_spaces";
		Debug::info($url);
		/*
		$ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL,$url);
		
		        curl_setopt($ch, CURLOPT_FAILONERROR,1);
		        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
				curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Accept: application/xml")); 
		
		        $retValue = curl_exec($ch);                      
		        curl_close($ch);
	
		//$oXML = new SimpleXMLElement($retValue);

		//foreach($oXML->entry as $oEntry){
		//       echo "Here";
		//}
		*/
		
		$retValue = Curl::fetch($url);
		$a = simplexml_load_string($retValue);
		if($a===FALSE) {
		//It was not an XML string
		Debug::error("Not XML");
		} else {
			$oXML = new SimpleXMLElement($retValue);
			foreach($oXML->entry as $oEntry){
			       echo "Here";
			}
			foreach($oXML as $oEntry){
			       echo $oEntry->name;
			}
			
		}
	//	return $retValue;
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