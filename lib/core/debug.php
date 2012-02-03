<?php

class Debug
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
	
	static public function display()
	{
		$html  = '
		<div class="row-fluid">
		<div class="span10">
		<table class="table">
		  <tbody>
		';
		foreach( Debug::$messages as $msg)
		{
			$html .='
		    <tr class="'.($msg['type'] == "warning" ? "alert": "alert-".$msg['type'] ).'">
		      <td><span class="label label-'.$msg['type'].'">'.$msg['type'].'</span></td>
		      <td>'.$msg['message'].'</td>
		    </tr>
		';
	}
	$html .= '
		  </tbody>
		</table>
		</div>
		</div>
		';
		
		
		return $html;
	}
}