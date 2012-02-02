<?php
//requires debug class

class Response
{
	public $code;
	public $events;
	public $params;
	public $data;
	
	// Code | Name | Message
	
    function __construct($autodetect = true)
    {
		$this->code  = 1;
		$this->events = array();
		$this->params = array();
		$this->data   = array();
    }

	public function setCode($code)
	{
		// 1 - Success
		// 0 - Failure
		if($this->code == 0) {
			//once the response fails, nothing should re-approve it.
			return;
		}
		$this->code = $code;
	}

	public function addEvent($debug)
	{
		// Event_1, Event_2
		$this->debug[] = $debug;
	}

	public function setParams($params)
	{
		// key -> value
		// key -> value
		$this->params = $params;
	}

	public function setData($data)
	{
		// different op
		$this->data = $data;
	}

	public function encode()
	{
		$response['code']   = $this->code;
		$response['events'] = array(
			'data'  => $this->events,
			'count' => count($this->events)
		);
		$response['params'] = $this->params;
		$response['data']   = $this->data;
		$response['debug']  = array(
			'data'  => Debug::$messages,
			'count' => count(Debug::$messages)
		);
		
		$json = json_encode($response);
		
		return $json;
	}
}