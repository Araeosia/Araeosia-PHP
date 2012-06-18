<?php
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$serversending = $_GET['s'];
$msg = $_POST['args'];
array_shift($args);
$timestamp = "[".date('m-d-y H:i:s', time())."] ";
include('includes/servers.php');
include('includes/staff.php');
include('includes/functions.php');
include('includes/passwords.php');
include('includes/mysql.php');
$query = mysql_query("SELECT * FROM permissions_inheritance WHERE child='$name'");
$groups = array();
while($row = mysql_fetch_array($query)){array_push($groups, $row['parent']);}
$type = $args[0];
$prefix = "§b";
if(in_array("Veteran", $groups)){ $prefix = "§2"; }
if(in_array("Moderator", $groups)){ $prefix = "§a"; }
if(in_array("Admin", $groups)){ $prefix = "§4"; }
if(in_array("Head-Admin", $groups)){ $prefix = "§4"; }
$msg = implode(' ', $args);
unset($servers[$serversending]);
$finalmsgout = "§e[A] §f[§9".$world."§f] ".$prefix.$name."§f: ".$msg."\n";
$log = $timestamp.$finalmsgout;
if($serversending=="Modded" && strpos($msg, "echo982")!==false){
	$JSONAPI = new JSONAPI($ips["Modded"], $ports['jsonapi']["Modded"], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$JSONAPI->call('setBlockType', array('Industry', 2016, 62, 32, 2));
	$JSONAPI->call('setBlockType', array('Industry', 2016, 62, 33, 2));
	$JSONAPI->call('setBlockType', array('Industry', 2016, 62, 34, 2));
	sleep(10);
	$JSONAPI->call('setBlockType', array('Industry', 2016, 62, 32, 76));
	$JSONAPI->call('setBlockType', array('Industry', 2016, 62, 33, 76));
	$JSONAPI->call('setBlockType', array('Industry', 2016, 62, 34, 76));
	exit;
}
foreach($servers as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$JSONAPI->call('broadcast', array($finalmsgout));
}
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>