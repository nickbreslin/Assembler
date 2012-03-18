<?php

class Database extends Base
{    
    private $_mysql     = false;
	private $_connected = false;
	private $_settings;

    function __construct()
    {
		parent::__construct();
		$this->_init();
    }

	function _destruct()
	{
		$this->mysql->_mysqli_close();
	}

	private function _init()
	{
		global $config;
		
		$this->_connect($config);
	}
    
    private function _connect($config)
    {
		$this->_mysql = new mysqli(
			 $config['database.host']
			,$config['database.user']
			,$config['database.password']
			,$config['database.name']
		);
		
		
        if (!$this->_mysql || mysqli_connect_errno())
		{
			Debug::error("No Connection. ".mysqli_connect_error());
            return false;
        }	
    
        return true;
    }
	
	public function mysqlEscapeString($string)
	{
        $escapedString = $this->_mysql->real_escape_string($string);
        return $escapedString;
    }

	public function query($query)
	{
		if ($results = $this->_mysql->query($query) or Debug::warning($this->_mysql->error))
		{			
			if ($this->_mysql->affected_rows == 0)
			{
				//Debug::info("No Affected Rows");
			}

			if (isset($results->num_rows))
			{
				$response = array();
				
				while ($result = $results->fetch_assoc())
				{
					//Debug::info("Returning Data");
					$response[] = $result;
		        }
		
				return $response;
			}
			
			if ($this->_mysql->affected_rows > 0)
			{
				if (isset($this->_mysql->insert_id))
				{
					//Debug::info("Returning Id");
					return $this->_mysql->insert_id;
				}
			}
			
			return true;
		}
		else
		{
			return false;
		}
	}
}