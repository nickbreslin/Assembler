<?php
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../').'/');
require ROOT_PATH.'lib/boot/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];

setParams(array('method' => $method));

if ($collection = getParam("admin"))
{
	if (!strcmp($collection,"dbupdate"))
	{
		require ROOT_PATH.'lib/core/dbupdate.php';
		require ROOT_PATH.'lib/core/dbchange.php';

		$dbupdate = new Dbupdate();
		$dbupdate->run();
	}
	else
	{
		Debug::warning("Route: [admin] : No Element");
	}
}
else if ($collection = getParam("views"))
{
	if (!strcmp($collection,"dbupdate"))
	{
		require ROOT_PATH.'lib/core/dbupdate.php';
		require ROOT_PATH.'lib/core/dbchange.php';

		$dbupdate = new Dbupdate();
		$dbupdate->run();
	}
	else
	{
		Debug::warning("Route: [views] : No Element");
	}
}
else
{
	Debug::warning("Route: No Collection");
}

echo $response->encode();