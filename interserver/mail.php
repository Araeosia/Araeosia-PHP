<?php
$name = $_POST['player'];
$serverSending = $_GET['s'];
$args = $_POST['args'];
$arg1 = strtoupper($args[1]);
if($name!='AgentKid'){ die('This function isn\'t ready yet!'); }

include('includes/functions.php');
include('includes/mysql.php');

switch($arg1){
	case "SEND":
	case "WRITE":
	case "CREATE":
		echo "Create a new message!";
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