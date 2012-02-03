<?php
//----------------------------------------------------------------------------
// Bootstrap
//----------------------------------------------------------------------------

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../').'/');
require ROOT_PATH.'lib/boot/bootstrap.php';

$theme[] = "base";
$assembla = new Assembla();
$spaces = $assembla->getSpaces();
$projects = array();

$sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : "all";
foreach($spaces as $space)
{
	$project = array();
	
	//$project['users'] = $assembla->getUsers($space['id']);
	if($sort == "all")
	{
		//$project['tickets'] = $assembla->getAllTickets($space['id']);
	}
	else if($sort == "active")
	{
		//$project['tickets'] = $assembla->getActiveTickets($space['id']);
	}
	else if($sort == "closed")
	{
		//$project['tickets'] = $assembla->getClosedTickets($space['id']);
	}
	$project['space'] = $space;
	//$project['milestones'] = $assembla->getMilestones($space['id']);
	$projects[] = $project;
}
?>
<html>
	<head>
		<title>Assembler</title>
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Droid+Serif:400,700' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
		
		<!--css-->
		<link rel="stylesheet" href="/css/bootstrap.css" type="text/css" charset="utf-8"/>
		<link rel="stylesheet" href="/css/bootstrap.responsive" type="text/css" charset="utf-8"/>
		<!--js-->
		<script type="text/javascript" src="/js/notifier.js"></script>
		<script type="text/javascript" src="/js/bootstrap.js"></script>
		<script type="text/javascript" src="/js/bootstrap-modal.js"></script>
		<script type="text/javascript" src="/js/bootstrap-tooltip.js"></script>
		<!-- overrides -->
		<link rel="stylesheet" href="/css/style.css" type="text/css" charset="utf-8"/>
		
		<!-- map -->
		
	</head>
	<body>		
		<div class="container-fluid">
		<h1>Assembler<span style='font-size:50%'> for Assembla</span></h1>
		  <div class="row-fluid">
		    <div class="span2 sidebar well">
		      <!--Sidebar content-->
			<ul class="nav nav-pills nav-stacked">
				<li <?php if($sort=="all") { ?>class="active"<? } ?> >
				    <a href="?sort=all">All</a>
				  </li>
				  <li <?php if($sort=="active") { ?>class="active"<? } ?>><a href="?sort=active">Open</a></li>
				  <li <?php if($sort=="closed") { ?>class="active"<? } ?>><a href="?sort=closed">Closed</a></li>
			</ul>
			<h4>Split</h4>
			<p><input type="checkbox" id="split-by-project" value="1">&nbsp;By Project</p>
			<p><input type="checkbox" id="split-by-assignment" value="1">&nbsp;By Assignment</p>
		    </div>
		    <div class="span8 main well">
			<div id='loading-announcement'>
				<h2>Loading...</h2>
				<div class="progress progress-success
				     progress-striped active">
				  <div class="bar"
				       style="width: 100%;"></div>
				</div>
				<h3>Retrieving Projects, Milestones, Tickets and Users</h3>
			</div>
		      <!--Body content-->
				<?php /*
				$startTs = 0;
				$endTs = 0;
				foreach($projects as $project)
				{
					echo "<h2>".$project['space']['name']."</h2>";
					if(isset($project['tickets']))
					{
						foreach($project['tickets'] as $ticket)
						{
							//Debug::info(
							$dt = new DateTime($ticket['updated-at']); 
							$ts = $dt->getTimestamp();
							
							if(strtotime("last sunday -1 week ") > $ts || strtotime("next sunday -1 week") < $ts)
							{
								continue;
							}
							
							if($startTs == 0 || $ts < $startTs) { $startTs = $ts; }
							if($endTs == 0 || $ts > $endTs) { $endTs = $ts; }
							$label = '';
							if($ticket['priority'] == 1)
							{
								$label = '-danger';
							}
							else if($ticket['priority'] == 2)
							{
								$label = '-warning';
							}
							else if($ticket['priority'] == 3)
							{
								$label = '-success';
							}
							else if($ticket['priority'] == 4)
							{
								$label = '-info';
							}
							else if($ticket['priority'] == 5)
							{
								$label = '-primary';
							}
						
							echo "<i class='icon-search cubit btn$label' rel='tooltip' data-original-title=\"".$ticket['summary']."\"></i>";
						}
					}
				}
				echo "<br/><br/><p>From ".date("Y-m-d", $startTs)." to ".date("Y-m-d", $endTs).".</p>";
				*/
				?>
				
		    </div>
		</div>
			<?php if(isset($_REQUEST['debug'])) { echo Debug::display();} ?>
		</div>
		<a href="http://github.com/nickbreslin/Assembler"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/7afbc8b248c68eb468279e8c17986ad46549fb71/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub"></a>
	<script type="text/javascript" src="/js/script.js"></script>
	</body>
</html>