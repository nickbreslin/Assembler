<?php

class Dbchange extends Data
{	
    function __construct()
    {
		parent::__construct();
		$this->_init();
    }

	private function _init()
	{
	}
	
	protected function _execute($queries)
	{
		$result = true;
			
		foreach($queries as $query)
		{
			if($response = $this->_db->query($query))
			{
				Debug::info("Applied: ".get_class($this));
			}
			else
			{
				Debug::error("Error: ".get_class($this));
				$result = false;
			}
		}
		
		return $result;
	}
}