<?php
$name = $_POST['player'];
$serversending = $_GET['s'];
$args = $_POST['args'];
include('includes/mysql.php');
include('includes/servers.php');
include('includes/functions.php');
include('includes/passwords.php');

$timestamp = "[".date('m-d-y H:i:s', time())."] ";

// Beginning of actual code
array_shift($args);
$recipient = array_shift($args);
$msg = implode(' ', $args);
if(player($recipient)==false){ die("§cCould not find a player by that name!"); }
$finalMessageToRecipient = "§8(".getFullName($name)." §7to you§8)§f: ".$msg;
$finalMessageToSender = "§8(§7you to ".getFullName(player($recipient))."§8)§f: ".$msg;

$log = $timestamp."(".$name." to ".player($recipient)."): ".$msg."\n";

echo $finalMessageToSender;

foreach(getServersByPlayer(player($recipient)) as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$JSONAPI->call('sendMessage', array(player($recipient), $finalMessageToRecipient));
}
$logfile = fopen('/home/agentkid/logs/chat.log', 'a');
fwrite($logfile, str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $log));
fclose($logfile);
?>