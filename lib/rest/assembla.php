<?php

class Assembla extends Base
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
	
	public function view($projects, $group)
	{
	    $result = "";
	    
		foreach($projects as $project)
		{
			if($group != "user")
			{
				$result .= "<br><br><h3>".$project['name']."</h3>";
			}

			foreach($project['tickets'] as $userId => $tickets)
			{
				if(count($tickets) == 0)
					continue;
					
				$username = "Unassigned";
				foreach($project['users'] as $user)
				{
					if($user['id'] == $userId)
					{
						$username = $user['name'];
						break;
					}
				}
				
				if($group != "project")
				{
					$result .= "<hr><h4>".$username."</h4>";
				}
				
				foreach($tickets as $ticket)
				{
					$label = '';
					if((int)$ticket['priority'] == 1)
					{
						$label = '-danger';
					}
					else if((int)$ticket['priority'] == 2)
					{
						$label = '-warning';
					}
					else if((int)$ticket['priority'] == 3)
					{
						$label = '-success';
					}
					else if((int)$ticket['priority'] == 4)
					{
						$label = '-info';
					}
					else if((int)$ticket['priority'] == 5)
					{
						$label = '-primary';
					}
				
					$result .= "<i class='icon-search cubit btn$label' rel='tooltip' data-original-title=\"".$ticket['summary']."\"></i>";
				}
			}
		}
		
		return $result;
    }
	
	public function loadAllData($status, $timeframe, $group)
	{
		Debug::info($status.":".$timeframe.":".$group);
	//	Debug::info($timeframe);
		
		$spaces = $this->getSpaces();
		$projects = array();

		$users = array();
		$tickets = array();
		$milestones = array();
		$validMilestones = array();
		$spaceholder = "";
		$projectTickets = array();
		foreach($spaces as $space)
		{			
			if($status == "all")
			{
				$projectTickets = $this->getAllTickets($space['id']);
			}
			else if($status == "open")
			{
				$projectTickets= $this->getOpenTickets($space['id']);
			}
			else if($status == "closed")
			{
				$projectTickets = $this->getClosedTickets($space['id']);
			}
			
			$milestones = array_merge($milestones, $this->getMilestones($space['id']));
			$users      = array_merge($users, $this->getUsers($space['id']));
			$tickets    = array_merge($tickets, $projectTickets);
			
			$project['name'] = str_replace('"', "'", $space['name']);
			$project['tickets'] = array();
			$project['users'] = $users;
			
			$projects[(string)$space['id']] = $project;
			$spaceholder = (string)$space['id'];
		}
		
		// Timeframe
		if($timeframe != "all-time")
		{
			$adjust = $timeframe == "next-week" ? "+1 week" : "";
							
			foreach($milestones as $milestone)
			{
				$dt = new DateTime($milestone['due-date']); 
				$ts = $dt->getTimestamp();
			
				if(strtotime("last sunday $adjust") < $ts && strtotime("next sunday $adjust") > $ts)
				{
					$validMilestones[] = (int)$milestone['id'];
				}
			}
		}
		else
		{
			foreach($milestones as $milestone)
			{
				$validMilestones[] = (int)$milestone['id'];
			}
			//Debug::info($validMilestones);
		}
		
		foreach($projects as &$project)
		{
			foreach($users as $user)
			{
				$project['tickets'][(string)$user['id']] = array();
			}
			$project['tickets']['unassigned'] = array();
		}
		
		foreach($tickets as $ticket)
		{
			$tid = $group != 'user' ? (string)$ticket['space-id'] : $spaceholder;
			if($timeframe != "all-time")
			{
				if($ticket['milestone-id'] == "")
				{
					continue;
				}
			
				if(!in_array($ticket['milestone-id'], $validMilestones))
				{
					continue;
				}
			}

			$id = $ticket['assigned-to-id'];
			
			$claimed = false;
			foreach($users as $user)
			{
				if(!strcmp($id,$user['id']))
				{
					$claimed = true;
					$projects[$tid]['tickets'][(string)$user['id']][] = $ticket;
					break;
				}
			}
			
			if(!$claimed)
			{
				$projects[$tid]['tickets']['unassigned'][] = $ticket;
			}	
		}
		
		// Project
		
		// User
			// User Tickets
				// Title
				// Priority
		
		//Debug::info($projects);
		
		return $projects;
	}
	
	
	public function fetchXML($url)
	{
		$results = Curl::fetch($url, true);
		
		$string = simplexml_load_string($results);
		
		if ($string===FALSE)
		{
			Debug::error("Not XML");
			return false;
		}
		else
		{
			$oXML = new SimpleXMLElement($results);
			
			return $oXML;
		}
	}
	
	// http://www.assembla.com/wiki/show/breakoutdocs/Space_REST_API
	public function getSpaces()
	{
		$url = self::$base."/spaces/my_spaces/";

		$results = self::fetchXML($url);
		
		if(!$results)
		{
			return array();
		}
		
		$return = array();
		
		foreach($results as $result)
		{
			$data          = array();
			$data['id']    = $result->{'id'};
			$data['name']  = str_replace('"', "'", $result->{'name'});
			$data['space'] = $result->{'wiki-name'};
			
			$return[] = $data;
		}

		return $return;
	}


	// http://www.assembla.com/spaces/breakoutdocs/wiki/Ticket_REST_API	
	public function getOpenTickets($spaceId)
	{
		// active by milestone
		$url = self::$base."/spaces/$spaceId/tickets/report/1";
		$results = self::fetchXML($url);
		
		if(!$results)
		{
			return array();
		}
		
		$return = array();
		
		foreach($results as $result)
		{
			$data                  = array();
			$data['id']            = $result->{'id'};
			$data['number']        = $result->{'number'};
			$data['priority']      = $result->{'priority'};
			$data['status']        = $result->{'status'};
			$data['assigned-to-id']  = $result->{'assigned-to-id'};
			$data['milestone-id']  = $result->{'milestone-id'};
			$data['working-hours'] = $result->{'working-hours'};
			$data['updated-at']    = $result->{'updated-at'};
			$data['summary']    = str_replace('"', "'", $result->{'summary'});
			$data['space-id']    = $result->{'space-id'};
			$return[] = $data;
		}

		return $return;
	}
	
	public function getClosedTickets($spaceId)
	{
		// active by milestone
		$url = self::$base."/spaces/$spaceId/tickets/report/4";
		$results = self::fetchXML($url);
		
		if(!$results)
		{
			return array();
		}
		
		$return = array();
		
		foreach($results as $result)
		{
			$data                  = array();
			$data['id']            = $result->{'id'};
			$data['number']        = $result->{'number'};
			$data['priority']      = $result->{'priority'};
			$data['status']        = $result->{'status'};
			$data['assigned-to-id']  = $result->{'assigned-to-id'};
			$data['milestone-id']  = $result->{'milestone-id'};
			$data['working-hours'] = $result->{'working-hours'};
			$data['updated-at']    = $result->{'updated-at'};
			$data['summary']    = str_replace('"', "'", $result->{'summary'});
			$data['space-id']    = $result->{'space-id'};
			

			$return[] = $data;
		}

		return $return;
	}

	// http://www.assembla.com/spaces/breakoutdocs/wiki/Ticket_REST_API	
	public function getAllTickets($spaceId)
	{
		// active by milestone
		$url = self::$base."/spaces/$spaceId/tickets/report/0";
		$results = self::fetchXML($url);
		
		if(!$results)
		{
			return array();
		}
		
		$return = array();
		
		foreach($results as $result)
		{
			$data                  = array();
			$data['id']            = $result->{'id'};
			$data['number']        = $result->{'number'};
			$data['priority']      = $result->{'priority'};
			$data['status']        = $result->{'status'};
			$data['assigned-to-id']  = $result->{'assigned-to-id'};
			$data['milestone-id']  = $result->{'milestone-id'};
			$data['working-hours'] = $result->{'working-hours'};
			$data['updated-at']    = $result->{'updated-at'};
			$data['summary']    = str_replace('"', "'", $result->{'summary'});
			$data['space-id']    = $result->{'space-id'};
			

			$return[] = $data;
		}

		return $return;
	}

	// http://www.assembla.com/spaces/breakoutdocs/wiki/Milestone_REST_API
	public function getMilestones($spaceId)
	{
		// active by milestone
		$url = self::$base."/spaces/$spaceId/milestones";
		$results = self::fetchXML($url);
		
		if(!$results)
		{
			return array();
		}
		
		$return = array();
		
		foreach($results as $result)
		{
			$data                  = array();
			$data['id']            = $result->{'id'};
			$data['title']        = $result->{'title'};
			$data['due-date']      = $result->{'due-date'};
			$data['space-id']    = $result->{'space-id'};

			$return[] = $data;
		}

		return $return;
	}

	// http://www.assembla.com/spaces/breakoutdocs/wiki/User_REST_API
	public function getUser($userId)
	{
		// active by milestone
		$url = "http://www.assembla.com/user/best_profile/$userId";
		//$url = self::$base."/spaces/$spaceId/milestones";
		$results = self::fetchXML($url);

		if(!$results)
		{
			return array();
		}
		
		
		$data          = array();
		$data['id']    = $result->{'id'};
		$data['name']  = $result->{'name'};
		$data['email'] = $result->{'email'};
		$data['tickets'] = array();

		return $data;
	}
	
	// http://www.assembla.com/spaces/breakoutdocs/wiki/User_REST_API
	public function getUsers($spaceId)
	{
		// active by milestone
		$url = self::$base."/spaces/$spaceId/users";
		$results = self::fetchXML($url);

		if(!$results)
		{
			return array();
		}
		
		$return = array();
		
		foreach($results as $result)
		{
			$data          = array();
			$data['id']    = $result->{'id'};
			$data['name']  = $result->{'name'};
			$data['email'] = $result->{'email'};
			$data['tickets'] = array();

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