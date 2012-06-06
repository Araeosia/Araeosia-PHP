<?php
$name = $_POST[player];
$world = $_POST[playerWorld];
// World handling
switch($world){
	case "Main_nether":
		$worldname = "The Nether";
		break;
	case "Main_the_end":
		$worldname = "The End";
		break;
	default:
		$worldname = $world;
		break;
}

$online = $_POST[onlinePlayers];
$onlinect = count($online);
$online = implode($online, '§e, §b');

$msg = "§bWelcome to the Araeosia Freebuild Server, ".$name."!\n";
$msg = $msg."§3You are currently in ".$worldname."§3.\n";
$msg = $msg."§eOnline (".$onlinect."/512): §b".$online."§e.\n";
$msg = $msg."§cNews: §e/news\n";
$msg = $msg."§4Rules: §e/rules - Updated 2-4-2012\n";
echo $msg;
?>