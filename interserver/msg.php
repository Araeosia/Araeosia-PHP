<?php
$name = $_POST['player'];
$serversending = $_GET['s'];
$args = $_POST['args'];
include('includes/mysql.php');
include('includes/servers.php');
include('includes/functions.php');
include('includes/passwords.php');

// Beginning of actual code
array_shift($args);
$recipient = array_shift($args);
$msg = implode(' ', $args);
if(player($recipient)==false){ die("§cCould not find a player by that name!"); }
$finalMessageToRecipient = "§8(".getFullName($name)." §7to you§8)§f: ".$msg;
$finalMessageToSender = "§8(§7you to ".getFullName(player($recipient))."§8)§f: ".$msg;

echo $finalMessageToSender;

foreach(getServersByPlayer(player($recipient)) as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$JSONAPI->call('sendMessage', array(player($recipient), $finalMessageToRecipient));
}
?>