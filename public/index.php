<?php
//----------------------------------------------------------------------------
// Bootstrap
//----------------------------------------------------------------------------

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../').'/');
require ROOT_PATH.'lib/boot/bootstrap.php';

$theme[] = "base";
$assembla = new Assembla();
$spaces = $assembla->mySpacesList();
foreach($spaces as $space)
{
	$tickets = $assembla->getUsers($space['id']);
	Debug::info(count($tickets));
break;
}
//$assembla->getUser('cIQZpQ_Ner4l_XacwqjQXA');
?>
<html>
	<head>
		<title>Assembler</title>
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
		
		<!--css-->
		<link rel="stylesheet" href="/css/bootstrap.css" type="text/css" charset="utf-8"/>
		<link rel="stylesheet" href="/css/bootstrap.responsive" type="text/css" charset="utf-8"/>
		<!--js-->
		<script type="text/javascript" src="/js/notifier.js"></script>
		<script type="text/javascript" src="/js/bootstrap.js"></script>
		<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
		<!-- overrides -->
		<link rel="stylesheet" href="/css/style.css" type="text/css" charset="utf-8"/>
		<script type="text/javascript" src="/js/script.js"></script>
		
		<!-- map -->
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Droid+Serif:400,700' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<h1>Assembler<span style='font-size:50%'> for Assembla</span></h1>
<!--		
		<div class="progress progress-danger
		     progress-striped active">
		  <div class="bar"
		       style="width: 100%;"></div>
		</div>
	-->	
		<!--<li><a onclick="Notifier.success('Hi!', 'Welcome.')">Success with title</a></li>-->
		<!-- success, info, warning, error-->
		
		<div class="container-fluid">
		  <div class="row-fluid">
		    <div class="span2 sidebar well">
		      <!--Sidebar content-->
				Nav.
		    </div>
		    <div class="span8 main well">
		      <!--Body content-->
				<?php  ?>
		    </div>
		</div>
			<?php echo Debug::display(); ?>
		</div>
		<a href="http://github.com/nickbreslin/Assembler"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/7afbc8b248c68eb468279e8c17986ad46549fb71/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub"></a>
	</body>
</html>