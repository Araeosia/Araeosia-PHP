<?php
$name = $_POST['player'];
if($name!='AgentKid'){die('§4This is still a work-in-progress.'); }
include('includes/mysql.php');
include('includes/functions.php');
include('includes/passwords.php');
$args = $_POST['args'];
switch($args[1]){
	case "help":
		break;
	case "enter":
	case "join":
		break;
	case "quit":
	case "exit":
	case "leave":
		break;
	case "list":
		break;
}
?>