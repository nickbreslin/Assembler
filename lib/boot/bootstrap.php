<?php
//----------------------------------------------------------------------------
// Php Ini Settings
//----------------------------------------------------------------------------
error_reporting(E_ALL ^ E_STRICT);
date_default_timezone_set('UTC');

ini_set('display_errors',1);
ini_set('auto_detect_line_endings', true);
ini_set('magic_quotes_gpc', false);

//----------------------------------------------------------------------------
// Defines
//----------------------------------------------------------------------------
define('SETTINGS_FILE',  ROOT_PATH . 'config/settings.ini');
define('IS_PRODUCTION',  ROOT_PATH . 'config/.production');

//----------------------------------------------------------------------------
// Logging
//----------------------------------------------------------------------------
require ROOT_PATH . 'lib/core/log.php';
Log::checkFiles();


//----------------------------------------------------------------------------
// Debug
//----------------------------------------------------------------------------
require ROOT_PATH . 'lib/core/debug.php';


//----------------------------------------------------------------------------
// Settings File
//----------------------------------------------------------------------------
$params = array();

function getParam($key, $default = false)
{
    global $params;
    return isset($params[$key]) ? $params[$key] : $default;
}

function setParams($newParams)
{
    if (is_array($newParams))
    {
        global $params;
        $params = array_merge($params, $newParams);
    }
    else
    {
        Debug::error("No params.");
    }
}


if (!file_exists(SETTINGS_FILE))
{
	echo "no settings";
	Debug::error("No Settings File Found");
	exit();
}

$environment = file_exists(IS_PRODUCTION) ? "production" : "development";

$iniSettings = parse_ini_file(SETTINGS_FILE, true);
$configEnvironment = $iniSettings[$environment];
$configDefault     = $iniSettings['default'];

$config = array_merge($configDefault, $configEnvironment);

function config($key, $default = false)
{
    global $config;
    return isset($config[$key]) ? $config[$key] : $default;
}

function setConfig($key, $value)
{
	global $config;
	$config[$key] = $value;
}

function fp()
{
	echo time();
}


//----------------------------------------------------------------------------
// Bootstrap
//----------------------------------------------------------------------------
require ROOT_PATH . 'lib/core/abstract.php';
require ROOT_PATH . 'lib/core/curl.php';
require ROOT_PATH . 'lib/core/data.php';
require ROOT_PATH . 'lib/core/database.php';
require ROOT_PATH . 'lib/core/response.php';


require ROOT_PATH . 'lib/rest/assembla.php';

$response = new Response();

