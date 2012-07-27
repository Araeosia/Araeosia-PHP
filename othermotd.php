<?php
// This file handles the MotD for the other Araeosia servers, since they don't need the complex code for the RPG server.
$name = $_POST['player'];
$world = $_POST['playerWorld'];
include('includes/mysql.php');
include('includes/staff.php');
include('includes/functions.php');
serverCheck($server, array('Freebuild', 'Modded', 'Vanilla', 'Sandbox'));
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
#$fishBans = new FishBans();
#$fishBans->isCached($name);

$online = rankPlayers(getAllPlayers());
$onlinect = count($online);
$online = implode($online, '§e, ');

$msg = "§bWelcome to the Araeosia Freebuild Server, ".$name."!\n";
$msg = $msg."§3You are currently in ".getWorldName($worldname)."§3.\n";
$msg = $msg."§eOnline (".$onlinect."/512): §b".$online."§e.\n";
$msg = $msg."§cNews: §e/news\n";
$msg = $msg."§4Araeosia has adopted a No-Mercy policy. §e/nomercy§4 for info.\n";
$msg = $msg."§4Rules: §e/rules - Updated 2-4-2012\n";
if(date("j F Y")=="4 July 2012"){ $msg = $msg."§fHappy §3Fourth of §4July!"; }
if(date("j F Y")=="25 July 2012"){ $msg = $msg."§aA new Survival world has been created!\n"; }
#$msg = $msg."§4AgentKid §ahas returned from vacation!";
echo $msg;
?>