<?php
//============================================================================
// Response (class)
//----------------------------------------------------------------------------
//
//
//
//============================================================================
class Response extends Base
{
	private $_code;
	private $_events;
	private $_params;
	private $_data;
	
	private $_validCodes = array(0,1);
	

	//------------------------------------------------------------------------
	//
	//------------------------------------------------------------------------
    function __construct()
    {
		parent::__construct();
		$this->_code   = 1;
		$this->_events = array();
		$this->_params = array();
		$this->_data   = array();
    }

	//========================================================================
	// setCode
	//------------------------------------------------------------------------
	// 
	//
	//
	//========================================================================
	public function setCode($code)
	{
		$code = intval($code);
		
		if (!in_array($code, $this->_validCodes))
		{
			Debug::error("Invalid Code Value: ".$code);
			return false;
		}

		if ($this->_code == 0) {
			Debug::warning("Response has already failed. Ignore new code: ".$code);
			return false;
		}
		
		$this->_code = $code;
		
		return true;
	}

	public function trackEvent($event)
	{
		// Event_1, Event_2
		$this->_events[] = $event;
	}

	public function setParams($params)
	{
		$this->_params = $params;
	}

	public function setData($data)
	{
		$this->_data = $data;
	}

	public function encode()
	{
		global $params;
		
		$result['code']   = $this->_code;
		$result['events'] = array(
			'data'  => $this->_events,
			'count' => count($this->_events)
		);
		$result['params'] = $params;
		$result['data']   = $this->_data;
		
		// todo: if dev mode.
		$result['debug']  = array(
			'data'  => Debug::$messages,
			'count' => count(Debug::$messages)
		);
		
		$json = json_encode($result);
		
		return $json;
	}
}