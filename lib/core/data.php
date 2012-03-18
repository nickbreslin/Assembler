<?php

class Data extends Base
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