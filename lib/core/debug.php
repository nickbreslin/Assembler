<?php

class Debug extends Base
{   
	static public $messages = array();
	
    static public function php()
    {
        phpinfo();
    }

	static public function log($type, $message)
	{
		if(is_array($message))
		{
			$message = "<pre>".print_r($message, true)."</pre>";
		}
		
		if(class_exists("Log"))
		{
			$r = 8 - strlen($type);
			$spaces = "";
			for($r; $r > 0; $r--)
			{
				$spaces .= " ";
			}
				
			Log::error("[".strtoupper($type)."]$spaces".$message);
		}
		
		self::$messages[] = array('type'=>$type, 'message'=>$message);
	}
	
	static public function info($message)
	{
		Debug::log('info', $message);
	}

	static public function success($message)
 	{
		Debug::log('success', $message);
	}
	
	static public function error($message)
 	{
		Debug::log('error', $message);
	}

	static public function warning($message)
	{
		Debug::log('warning', $message);
	}
}