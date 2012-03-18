<?php
//----------------------------------------------------------------------------
// Bootstrap
//----------------------------------------------------------------------------

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../').'/');
require ROOT_PATH.'lib/boot/bootstrap.php';

$status    = getParam('status',    'open');
$timeframe = getParam('timeframe', 'this-week');
$group     = getParam('group',     'both');

$urlStatus    = "&status=$status";
$urlTimeframe = "&timeframe=$timeframe";
$urlGroup     = "&group=$group";

$assembla = new Assembla();
$projects = $assembla->loadAllData($status, $timeframe, $group);

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
		<script type="text/javascript" src="/js/script.js"></script>
		
		<!-- map -->
		
	</head>
	<body>		
		<div class="container-fluid">
		<h1>Assembler<span style='font-size:50%'> for Assembla</span></h1>
		  <div class="row-fluid">
			<div class="span2">
		    <div class="sidebar well">
		      <!--Sidebar content-->
		<h3>Tickets</h3>
			<ul class="nav nav-pills nav-stacked">
				<li <?php if($status=="all")   { ?>class="active"<? } ?>><a href="?status=all<?php echo $urlTimeframe.$urlGroup; ?>">All</a></li>
				<li <?php if($status=="open") { ?>class="active"<? } ?>><a href="?status=open<?php echo $urlTimeframe.$urlGroup; ?>">Open</a></li>
				<li <?php if($status=="closed") { ?>class="active"<? } ?>><a href="?status=closed<?php echo $urlTimeframe.$urlGroup; ?>">Closed</a></li>
			</ul>
			<hr>
			<h3>Timeframe</h3>
			<ul class="nav nav-pills nav-stacked">
				<li <?php if($timeframe=="all-time")  { ?>class="active"<? } ?>><a href="?timeframe=all-time<?php echo $urlStatus.$urlGroup; ?>">All Time</a></li>
				<li <?php if($timeframe=="this-week") { ?>class="active"<? } ?>><a href="?timeframe=this-week<?php echo $urlStatus.$urlGroup; ?>">This Week</a></li>
				<li <?php if($timeframe=="next-week") { ?>class="active"<? } ?>><a href="?timeframe=next-week<?php echo $urlStatus.$urlGroup; ?>">Next Week</a></li>
			</ul>
			<hr>
			
			<h3>Group By</h3>
		    <ul class="nav nav-pills nav-stacked">
				<li <?php if($group=="user")   { ?>class="active"<? } ?>><a href="?group=user<?php echo $urlTimeframe.$urlStatus; ?>">User</a></li>
				<li <?php if($group=="project") { ?>class="active"<? } ?>><a href="?group=project<?php echo $urlTimeframe.$urlStatus; ?>">Project</a></li>
				<li <?php if($group=="both") { ?>class="active"<? } ?>><a href="?group=both<?php echo $urlTimeframe.$urlStatus; ?>">Both</a></li>
			</ul>
		</div>&nbsp;
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
				<?php
				foreach($projects as $project)
				{
					if($group != "user")
					{
						echo "<br><br><h3>".$project['name']."</h3>";
					}

					foreach($project['tickets'] as $userId => $tickets)
					{
						if(count($tickets) == 0)
							continue;
							
						$username = "Unassigned";
						foreach($project['users'] as $user)
						{
							if($user['id'] == $userId)
							{
								$username = $user['name'];
								break;
							}
						}
						
						if($group != "project")
						{
							echo "<hr><h4>".$username."</h4>";
						}
						
						foreach($tickets as $ticket)
						{
							$label = '';
							if((int)$ticket['priority'] == 1)
							{
								$label = '-danger';
							}
							else if((int)$ticket['priority'] == 2)
							{
								$label = '-warning';
							}
							else if((int)$ticket['priority'] == 3)
							{
								$label = '-success';
							}
							else if((int)$ticket['priority'] == 4)
							{
								$label = '-info';
							}
							else if((int)$ticket['priority'] == 5)
							{
								$label = '-primary';
							}
						
							echo "<i class='icon-search cubit btn$label' rel='tooltip' data-original-title=\"".$ticket['summary']."\"></i>";
						}
					}
				}

				?>
				
		    </div>
		</div>
			<?php if(isset($_REQUEST['debug'])) { echo Debug::display();} ?>
		</div>
		<a href="http://github.com/nickbreslin/Assembler"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/7afbc8b248c68eb468279e8c17986ad46549fb71/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub"></a>
        <div id="myModal" class="modal hide fade">
          <div class="modal-header">
            <h2>Loading...</h2>
          </div>
          <div class="modal-body">
				<div class="progress progress-success
				     progress-striped active">
				  <div class="bar"
				       style="width: 100%;"></div>
				</div>
				<h3>Retrieving Projects, Milestones, Tickets and Users</h3>
        </div>
<div class="modal-footer">Please be patient, do not interrupt the loading...</div>
	</body>
</html>