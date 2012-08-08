<?php
$name = $_POST['player'];
$world = $_POST['playerWorld'];
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
if($channelHandle->isMute($channel) || $channelHandle->isMute()){ die('§cYou are currently muted!'); }
echo formatOutput($channel, $name, $msg, $world, $channelHandle->style);
sendMessageToChannel($channel, $name, $msg, $world, array($name));
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>