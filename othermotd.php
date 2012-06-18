<?php
// This file handles the MotD for the other Araeosia servers, since they don't need the complex code for the RPG server.
$name = $_POST['player'];
$world = $_POST['playerWorld'];
include('includes/mysql.php');
include('includes/staff.php');
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
if(mysql_fetch_array(mysql_query("SELECT * FROM ChannelsIn WHERE name='$name'"))==false){ echo "/Command/ExecuteBukkitCommand:/ch join A;\n"; }

$online = $_POST['onlinePlayers'];
$playersfinal = array();
foreach($online as $player){
	if(in_array($player,$staffranks['admin'])){$player="§4".$player;}elseif(in_array($player,$staffranks['moderator'])){$player = "§a".$player;}else{$player = "§b".$player; }
	array_push($playersfinal, $player);
}
$onlinect = count($online);
$online = implode($playersfinal, '§e, ');

$msg = "§bWelcome to the Araeosia Freebuild Server, ".$name."!\n";
$msg = $msg."§3You are currently in ".$worldname."§3.\n";
$msg = $msg."§eOnline (".$onlinect."/512): §b".$online."§e.\n";
$msg = $msg."§cNews: §e/news\n";
$msg = $msg."§4Araeosia has adopted a No-Mercy policy. §e/nomercy§4 for info.\n";
$msg = $msg."§4Rules: §e/rules - Updated 2-4-2012\n";
echo $msg;
?>