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
$chatHandle = new ChannelHandle($name);
if(mysql_fetch_array(mysql_query("SELECT * FROM TrueGroups WHERE name='$name'"))==false){
	$newUser = true;
	mysql_query("INSERT INTO TrueGroups VALUES ('NULL', '$name', 'Default')") or die(mysql_error());
}else{ $newUser = false; }
// New code
if($newUser){
	sysMessage("§6Everyone welcome ".getFullName($name)."§6 to the server for the first time!");
	
}else{

// Go ahead and cache the user in FishBans, in case we want to look them up later.
#	$fishBans = new FishBans();
#	$fishBans->isCached($name);
	$online = getAllPlayers();
	if(!in_array($name, $online)){ array_push($online, $name); }
	$online = rankPlayers($online);
	$onlinect = count($online);
	$online = implode($online, '§e, ');

	clearScreen();
	$msg = "§bWelcome to the Araeosia Freebuild Server, ".$name."!\n";
	$msg = $msg."§3You are currently in ".getWorldName($worldname)."§3.\n";
	$msg = $msg."§eOnline (".$onlinect."/512): §b".$online."§e.\n";
	$msg = $msg."§cNews: §e/news\n";
	$msg = $msg."§4Araeosia has adopted a No-Mercy policy. §e/nomercy§4 for info.\n";
	$msg = $msg."§4Rules: §e/rules - Updated 2-4-2012\n";
	if(date("j F Y")=="4 July 2012"){ $msg = $msg."§fHappy §3Fourth of §4July!"; }
#	$msg = $msg."§4AgentKid §ahas returned from vacation!";
	$msg = $msg."§41.3 is VERY buggy right now!\n";
	echo $msg;
}
?>