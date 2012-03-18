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
else if ($collection = getParam("assembla"))
{
	if (!strcmp($collection,"query"))
	{
		require ROOT_PATH.'lib/rest/assembla.php';

        $status    = getParam('status',    'open');
        $timeframe = getParam('timeframe', 'this-week');
        $group     = getParam('group',     'both');

        $assembla = new Assembla();
        $result   = $assembla->loadAllData($status, $timeframe, $group);

        $response->setData($result);
	}
	else
	{
		Debug::warning("Route: [assembla] : No Element");
	}
}
else
{
	Debug::warning("Route: No Collection");
}

echo $response->encode();