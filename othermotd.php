<?php
// This file handles the MotD for the other Araeosia servers, since they don't need the complex code for the RPG server.
$name = $_POST['player'];
$world = $_POST['playerWorld'];
include('includes/mysql.php');
include('includes/staff.php');
include('includes/functions.php');
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
if(mysql_fetch_array(mysql_query("SELECT * FROM ChannelsIn WHERE name='$name'"))===false){
	mysql_query("INSERT INTO ChannelsIn (id, name, channel, type) VALUES ('NULL', '$name', 'A', '1')");
	echo "§aYou joined the §eAraeosia §achannel!\n";
}
if(mysql_fetch_array(mysql_query("SELECT * FROM TrueGroups WHERE name='$name'"))==false){
	mysql_query("INSERT INTO TrueGroups VALUES ('NULL', '$name', 'Default')") or die(mysql_error());
}
// Go ahead and cache the user in FishBans, in case we want to look them up later.
$fishBans = new FishBans();
$fishBans->isCached($name);

$online = $_POST['onlinePlayers'];
$playersfinal = array();
foreach($online as $player){ array_push($playersfinal, getFullName($player)); }
$onlinect = count($online);
$online = implode($playersfinal, '§e, ');

$msg = "§bWelcome to the Araeosia Freebuild Server, ".$name."!\n";
$msg = $msg."§3You are currently in ".$worldname."§3.\n";
$msg = $msg."§eOnline (".$onlinect."/512): §b".$online."§e.\n";
$msg = $msg."§cNews: §e/news\n";
$msg = $msg."§4Araeosia has adopted a No-Mercy policy. §e/nomercy§4 for info.\n";
$msg = $msg."§4Rules: §e/rules - Updated 2-4-2012\n";
if(date("j F Y")=="4 July 2012"){ $msg = $msg."§fHappy §3Fourth of §4July!"; }
$msg = $msg."§aIn case you didn't hear, §4AgentKid §ais on vacation right now. If you need help, talk to a moderator. §cInfo about his vacation: \n§fhttp://forums.araeosia.com/threads/111/";
echo $msg;
?>