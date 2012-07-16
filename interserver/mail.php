<?php
$name = $_POST['player'];
$serverSending = $_GET['s'];
$args = $_POST['args'];
$arg1 = strtoupper($args[1]);
if($name!='AgentKid'){ die('This function isn\'t ready yet!'); }
$time = time();

include('includes/functions.php');
include('includes/mysql.php');

switch($arg1){
	case "SEND":
	case "WRITE":
	case "CREATE":
		if(offlinePlayer($recipient)==false){ die('§cA player by that name has never logged into an Araeosia server before!'); }
#		if(player($name)!=false){ sendMessage($recipient, $msg)}
		$msg = $args;
		array_shift($msg);
		array_shift($msg);
		$msg = htmlspecialchars(implode(' ', $msg));
		$msgid = rand(100000000, 999999999);
		mysql_query("INSERT INTO Mail (id, msgid, time, name, recipient, message, status) VALUES ('NULL', '$msgid', '$time', '$name', '$recipient', '$msg', '1')");
		if(player($recipient)!=false){ readMessage($msgid, $recipient); }
		break;
	case "READ":
	case "OPEN":
		echo "Recieved an old message!";
		break;
	default:
		echo "HELP MESSAGE GOES HERE!";
		break;
}




?>