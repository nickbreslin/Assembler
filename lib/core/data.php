<?php

class Data extends Abstract_Class
{
	protected $_db;
	
    function __construct()
    {
		parent::__construct();
		$this->_init();
		
		$this->_db = new Database();
    }

	private function _init()
	{
	}
}