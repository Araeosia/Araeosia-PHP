<?php
$name = $_POST['player'];
$serversending = $_GET['s'];
$args = $_POST['args'];
include('includes/mysql.php');
include('includes/servers.php');
include('includes/functions.php');
include('includes/passwords.php');
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
$log = "(".$name." to ".player($recipient)."): ".$msg."\n";
echo $finalMessageToSender;
tellPlayer($recipient, $finalMessageToRecipient);
$logHandle = new Logger('chat');
$logHandle->addLog($log);
?>