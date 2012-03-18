<?php

error_log("test");
/*
require $_SERVER['DOCUMENT_ROOT'] . 'lib/core/bootstrap.php';

require $_SERVER['DOCUMENT_ROOT'] . 'lib/model/assembla.php';

$assembla = new Assembla();
$output = array();

if(isset($_REQUEST['del']))
{
	$output = $assembla->deleteTicket($_REQUEST['del']);
}

if(isset($_REQUEST['get']))
{
	$output = $assembla->getTicket($_REQUEST['get']);
}

if(isset($_REQUEST['getall']))
{
	$output = $assembla->getAllTickets();
}

if(isset($_REQUEST['create']))
{
	$output = $assembla->createTicket();
}


echo "<pre>".print_r($output, true)."</pre>";
