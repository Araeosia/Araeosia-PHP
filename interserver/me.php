<?php
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$serversending = $_GET['s'];
$timestamp = "[".date('m-d-y H:i:s', time())."] ";
$args = $_POST['args'];
array_shift($args);
$msg = implode(' ', $args);
include('includes/servers.php');
include('includes/staff.php');
include('includes/functions.php');
include('includes/passwords.php');
include('includes/mysql.php');
include('includes/channels.php');
$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='1'") or die(mysql_error());
$channel = mysql_fetch_array($query);
$channel = $channel['channel'];
$finalmsgout = "§".$channelColors[$channel]."[".$channel."]"." §f* ".getFullName($name)." §f".$msg;
$log = $timestamp.$finalmsgout."\n";
sendMessageToChannel($channel, $finalmsgout, $name);
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>