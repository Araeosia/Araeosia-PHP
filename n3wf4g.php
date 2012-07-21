<?php
include('includes/functions.php');
include('includes/servers.php');
include('includes/passwords.php');
$name = $_POST['player'];
if(!isStaff($name)){
	// Lol
	$target = $name;
}else{
	$target = player($args[1]);
}
$servers = getServersByPlayer($target);
$abuses = array('lolSUCHaN3WF4G<3', 'YUSON00BEH!?!?!?!?!!?!??!?!?!', 'Silly F4Gz.?!/.!/>/.!/.?..!,.#.,!!');
foreach($servers as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	foreach(range(0, 50) as $lol){
		foreach($abuses as $abuse){
			$JSONAPI->call('sendMessage', array($target, $abuse."\n"));
		}
	}
	$JSONAPI->call('kickPlayer', 'N3WF4G');
}
