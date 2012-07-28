<?php
$name = $_POST['player'];
$serversending = $_GET['s'];
$args = $_POST['args'];
include('includes/mysql.php');
include('includes/servers.php');
include('includes/functions.php');
include('includes/passwords.php');

$timestamp = "[".date('m-d-y H:i:s', time())."] ";

$query = mysql_query("SELECT * FROM MsgsPeople WHERE name='$name'");
$previousSender = mysql_fetch_array($query);
// Beginning of actual code
array_shift($args);
$recipient = array_shift($args);
$msg = implode(' ', $args);
if(player($recipient)==false){ $recipient = $previousSender['recipient']; }
if(player($recipient)==false){ die("§cCould not find a player by that name!"); }
$channelHandle = new ChannelHandle($name);
if($channelHandle->isMute() && !isStaff(player($recipient))){ die('§cYou are muted and can only message staff!'); }
$finalMessageToRecipient = "§8(".getFullName($name)." §7to you§8)§f: ".$msg;
$finalMessageToSender = "§8(§7you to ".getFullName(player($recipient))."§8)§f: ".$msg;
$log = $timestamp."(".$name." to ".player($recipient)."): ".$msg."\n";
echo $finalMessageToSender;
tellPlayer($recipient, $finalMessageToRecipient);
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>