<?php
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$serversending = $_GET['s'];
include('includes/servers.php');
include('includes/staff.php');
include('includes/functions.php');
include('includes/passwords.php');
$type = $args[0];
if(in_array($name, $staffranks['admin'])){ $prefix = "§4"; }elseif(in_array($name, $staffranks['moderator'])){ $prefix = "§a"; }else{ $prefix = "§b"; }
unset($servers[$serversending]);
$finalmsgout = $prefix.$name." §e".$type." §6Araeosia-".$serversending;
foreach($servers as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$JSONAPI->call('broadcast', array($finalmsgout));
}
?>