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
if(in_array($name, $staffranks['admin'])){ $prefix = "§4"; }elseif(in_array($name, $staffranks['moderator'])){ $prefix = "§a"; }else{ $prefix = "§b"; }
$msg = implode(' ', $args);
unset($servers[$serversending]);
$finalmsgout = "§e[A] §f[§9".$world."§f] ".$prefix.$name."§f: ".$msg."\n";
$log = $timestamp.$finalmsgout;
foreach($servers as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$JSONAPI->call('broadcast', array($finalmsgout));
}
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>