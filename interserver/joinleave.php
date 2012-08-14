<?php
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$serversending = $_GET['s'];
$timestamp = "[".date('m-d-y H:i:s', time())."] ";
include('includes/servers.php');
include('includes/staff.php');
include('includes/functions.php');
include('includes/passwords.php');
include('includes/mysql.php');
$type = $args[0];
if($type=="left"){ mysql_query("DELETE FROM optouts WHERE name='$name'"); }
unset($servers[$serversending]);
$finalmsgout = getFullName($name)." §e".$type." §6Araeosia-".$serversending."§e.";
foreach($servers as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$JSONAPI->call('broadcast', array($finalmsgout));
}
$logHandle = new Logger('chat');
$logHandle->addLog($finalmsgout);
?>