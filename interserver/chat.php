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
include('includes/channels.php');
$msg = trim(implode(' ', $args));
$msg = str_replace('    ', '', $msg);
$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='1'") or die(mysql_error());
$channel = mysql_fetch_array($query);
$channel = $channel['channel'];
$finalmsgout = "§".$channelColors[$channel]."[".$channel."]"." §f[§9".getWorldName($world)."§f] ".getFullName($name)."§f: ".$msg;
if(isStaff($name) && substr($msg, -1)=="?" && $msg != "?"){
	$finalmsgout = substr($finalmsgout, 0, strlen($finalmsgout)-1);
	$finalmsgout = $finalmsgout.", eh?";
}
$log = $timestamp.$finalmsgout."\n";
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
$query = mysql_query("SELECT * FROM Mutes WHERE name='$name' AND channel='$channel'") or die(mysql_error());
$query2 = mysql_query("SELECT * FROM GMutes WHERE name='$name'") or die(mysql_error());
if(mysql_fetch_array($query)!=false || mysql_fetch_array($query2)!=false){ die('§cYou are currently muted!'); }
$JSONAPI = new JSONAPI($ips[$serversending], $ports['jsonapi'][$serversending], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
$JSONAPI->call('sendMessage', array($name, $finalmsgout));
sendMessageToChannel($channel, $finalmsgout, $name, array($name));
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>