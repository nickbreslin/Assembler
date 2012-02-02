<?php

class Log
{
	private static $accessLog = "logs/access.log";
	private static $errorLog  = "logs/error.log";
	
	static function checkFiles()
	{
		if(!defined('ROOT_PATH'))
		{
			echo "Root Path undefined.";
			exit();
		}
		
		$fh = fopen(ROOT_PATH . self::$accessLog, 'a') or die("Missing Access Log");
		fclose($fh);
		$fh = fopen(ROOT_PATH . self::$errorLog, 'a') or die("Missing Error Log");
		fclose($fh);
	}
	
    static public function access($string)
    {
		$fh = fopen(ROOT_PATH . self::$accessLog, 'a');
		fwrite($fh, $string."\n");
		fwrite($fh, $string."\n");
		fclose($fh);
    }

    static public function error($string)
    {
		$fh = fopen(ROOT_PATH . self::$errorLog, 'a');
		fwrite($fh, $string."\n");
		fwrite($fh, $string."\n");
		fclose($fh);
    }

	static function test()
	{
		Log::access("Testing: ".time());
		Log::error("Testing: ".time());
		echo "Tested";
	}
}