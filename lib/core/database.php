<?php

class Database extends Abstract_Class
{    
    private $_mysql     = false;
	private $_connected = false;
	private $_settings;

    function __construct()
    {
		parent::__construct();
		$this->_init();
		$this->_connected = $this->_connect();
    }

	function _destruct()
	{
		$this->mysql->_mysqli_close();
	}

	private function _init()
	{
		global $config;
		
		if (!isset($config))
		{
			Debug::error("No Settings");
			exit();
		}
		else
		{
			$this->_config = $config;
		}
	}
	
	public function debug()
	{
		Debug::dump($this->_mysql);
	}
	
	public function isConnected()
	{
		return $this->_connected;
	}
    
    private function _connect()
    {
		$this->_mysql = new mysqli(
			 $this->_config['database.host']
			,$this->_config['database.user']
			,$this->_config['database.password']
			,$this->_config['database.name']
		);
		
		
        if (!$this->_mysql || mysqli_connect_errno()) {
			Debug::error("No Connection. ".mysqli_connect_error());
            return false;
        }	
    
        return true;
    }

	public function query($query)
	{	
		//echo "<br>".$query;
		if ($result = $this->_query($query))
		{
			$data = array();
			//echo "<br>1";
			//echo print_r($result, true);
			if (isset($result->num_rows) && $result->num_rows > 0)
			{
				//echo "<br>2A-";
				//$data = array();
				
				while ($row = $result->fetch_array(MYSQLI_ASSOC))
				{
					//echo "<br>3A-";
					$data[] = $row;
				}
				
				return $data;
			}
			else
			{
				//echo "<br>2B-";
				//$data = array();
				while ($row = $result->fetch_array(MYSQLI_ASSOC))
				{
					//echo print_r($row, true);
					//echo "<br>3B-";
					$data[] = $row;
					
				}
				//echo print_r($result->fetch_assoc(), true);
			}
			
			return $data;
		}
		
		return false;
	}
	
	public function custom($query)
	{	
		return $result = $this->_query($query);
	}

	public function insert($query)
	{	
		if ($result = $this->_query($query))
		{
			if($this->_mysql->affected_rows)
			{
				return $this->_mysql->insert_id;
			}
		}
		
		return false;
	}
	
	private function _query($query)
	{
		if(!$this->_connected)
		{
			return false;
		}
						
		$result = $this->_mysql->query($query);
		//print_r($result->fetch_object());
		//exit();
		if($result)
		{
			return $result;
		}
		else
		{
			Debug::error($this->_mysql->error);
			return false;
		}
	}
	
	public function mysqlEscapeString($string)
	{
        $num = $this->_mysql->real_escape_string($string);
        return $num;
    }
}