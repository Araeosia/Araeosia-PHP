<?php
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$serversending = $_GET['s'];
$msg = $_POST['args'];
array_shift($args);
include('includes/servers.php');
include('includes/staff.php');
include('includes/functions.php');
include('includes/passwords.php');
include('includes/mysql.php');
include('includes/channels.php');
$msg = trim(implode(' ', $args));
$msg = str_replace('    ', '', $msg);
$channelHandle = new ChannelHandle($name);
$channel = $channelHandle->currentChannel;
$log = "[".date('m-d-y H:i:s', time())."] ".getChannelColor($channel)."[".$channel."]"." §f[§9".getWorldName($world)."§f] ".getFullName($name)."§f: ".$msg."\n";
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
if($channelHandle->isMute($channel) || $channelHandle->isMute()){ die('§cYou are currently muted!'); }
echo formatOutput($channel, $name, $msg, $world, $channelHandle->style);
flush();
sendMessageToChannel($channel, $name, $msg, $world, array($name));
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>