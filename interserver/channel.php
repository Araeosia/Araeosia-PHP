<?php
// Developer notes:
// This file is executed whenever anyone on any server uses the /ch command with any or no arguments.
// Things that need doing in this file: Set up /ch kick, set up /ch SRC.

// Dynamic variables
$name = $_POST['player'];
$server = $_GET['s'];
$args = $_POST['args'];
if(isset($args[2])){ 
	$channel=strtoupper($args[2]);
	if($channel=="ARAEOSIA"){$channel="A";}elseif($channel=="STAFF"){$channel="S";}elseif($channel=="TRADE"){$channel="T";}elseif($channel=="HELP"){$channel="H";}elseif($channel=="LOCAL"){$channel="L";}elseif($channel=="GROUP"){$channel="G";}elseif($channel=="FOREIGNLANGUAGE"){$channel="FL";}elseif($channel=="MODDED"){$channel="M";}elseif($channel=="ROLEPLAY"){$channel="RP";}
}
$arg1 = strtoupper($args[1]);

// Includes
include_once('includes/mysql.php');
include_once('includes/functions.php');
include_once('includes/passwords.php');
include_once('includes/channels.php');
include_once('includes/staff.php');

// Generic queries
// Type 1 means you're speaking in that room, Type 2 means that you're just in that room and listening.
$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='1'");
$query = mysql_fetch_array($query);
$currentChannel = $query['channel'];
$channelsIn = array();
$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='2'") or die(mysql_error());
while($row=mysql_fetch_array($query)){ array_push($channelsIn, $row['channel']); }

// Most of the actual code
if(in_array($arg1, $channels)){
// So they want to focus on that channel specifically.
	if($currentChannel==$arg1){ die('§cYou are already in the §'.$channelColors[$arg1].$channelFullNames[$arg1].' §cchannel!'); }
	if($channel="S" && !isStaff($name)){ die('You do not have permission.'); }
	if(!in_array($arg1, $channelsIn)){
		// Join the room
		mysql_query("INSERT INTO ChannelsIn (id, name, channel, type) VALUES ('NULL', '$name', '$arg1', '2')") or die(mysql_error());
		echo "§aYou joined the §".$channelColors[$arg1].$channelFullNames[$arg1]." §achannel!\n";
	}
	// Set focus on the room
	mysql_query("UPDATE ChannelsIn SET type='2' WHERE name='$name'") or die(mysql_error());
	mysql_query("UPDATE ChannelsIn SET type='1' WHERE name='$name' AND channel='$arg1'") or die(mysql_error());
	echo "§aYou set focus on the §".$channelColors[$arg1].$channelFullNames[$arg1]." §achannel!\n";
}else{
switch($arg1){
	case "HELP":
		echo "§a/ch help §f- §bDisplays this help message.\n§a/ch join [channel] §f- §bJoins the specified channel.\n§a/ch leave [channel] §f- §bLeaves the specified channel.\n§a/ch who §f- §bDisplays online members in your channel.\n§a/ch list §f- §bLists all available channels.\n";
		break;
	case "ENTER":
	case "JOIN":
		if(!in_array($channel, $channels)){ die("§cInvalid channel or usage! Channel list: §a/ch list§c.\n"); }
		if($currentChannel==$channel){ die("§cYou are already focused on the §".$channelColors[$channel].$channelFullNames[$channel]."§c channel!\n"); }
		if($channel="S" && !isStaff($name)){ die('You do not have permission.'); }
		if(!in_array($arg1, $channelsIn) && $currentChannel!=$channel){
			// Join the room
			mysql_query("INSERT INTO ChannelsIn (id, name, channel, type) VALUES ('NULL', '$name', '$channel', '2')") or die(mysql_error());
			echo "§aYou joined the §".$channelColors[$channel].$channelFullNames[$channel]." §achannel!\n";
		}
		// Set focus on the room
		mysql_query("UPDATE ChannelsIn SET type='1' WHERE name='$name'") or die(mysql_error());
		echo "§aYou set focus on the §".$channelColors[$channel].$channelFullNames[$channel]." §achannel!\n";
		break;
	case "QUIT":
	case "EXIT":
	case "LEAVE":
		if(!isset($channel)){ $channel=$currentChannel; }
		if(!in_array($channel, $channels)){ die("§cInvalid channel! Usage: §a/ch leave [channel]\n"); }
		if($channel!=$currentChannel && !in_array($channel, $channelsIn)){ die("§cYou are not in the §".$channelColors[$channel].$channelFullNames[$channel]." §cchannel!\n"); }
		if(count($channelsIn)==0){ die("§cYou cannot leave the only channel you're in! Join another first."); }
		mysql_query("DELETE FROM ChannelsIn WHERE name='$name' AND channel='$channel'");
		echo "§aYou left the §".$channelColors[$channel].$channelFullNames[$channel]." §achannel!\n";
		$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='1'");
		if(mysql_fetch_array($query)==false){
			$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='2'");
			$row = mysql_fetch_array($query);
			$newChannel = $row['channel'];
			mysql_query("UPDATE ChannelsIn SET type='1' WHERE name='$name' AND channel='$newChannel'");
			echo "§aYou joined the §".$channelColors[$newChannel].$channelFullNames[$newChannel]." §achannel!\n";
		}
		break;
	case "WHO":
		$allplayers = array();
		foreach($servers as $server){
			$jsonapi = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
			$output = $jsonapi->call('getPlayerNames', array());
			$players = $output['success'];
			$allplayers = array_merge($allplayers, $players);
		}
		$inChannel=array();
		echo "§".$channelColors[$currentChannel]."------- ".$channelFullNames[$currentChannel]." -------\n";
		$inThisChannel=array();
		$query = mysql_query("SELECT * FROM ChannelsIn WHERE channel='$currentChannel'") or die(mysql_error());
		while($row = mysql_fetch_array($query)){ array_push($inThisChannel, $row['name']); }
		$finalInThisChannel = array();
		foreach($inThisChannel as $pl){ if(in_array($pl, $allplayers)){array_push($finalInThisChannel, getFullName($pl));}}
		echo "§b".implode('§f, ', $finalInThisChannel)."\n";
		foreach($channelsIn as $ch){
			$inThisChannel=array();
			$finalInThisChannel=array();
			$query = mysql_query("SELECT * FROM ChannelsIn WHERE channel='$ch'");
			while($row = mysql_fetch_array($query)){ array_push($inThisChannel, $row['name']); }
			if(count($inThisChannel!=1)){
				echo "§".$channelColors[$ch]."------- ".$channelFullNames[$ch]." -------\n";
				foreach($inThisChannel as $pl){ if(in_array($pl, $allplayers)){array_push($finalInThisChannel, getFullName($pl));}}
				echo "§b".implode('§f, §b', $finalInThisChannel)."\n";
			}
		}
		break;
	case "LIST":
		echo "-------- Channels --------\n§eAraeosia - A - The main channel\n§aStaff - S - The staff channel\n§bTrade - T - The trade channel\n§9Help - H - The help channel\n§cLocal - L - The Local channel\n§6Group - G - The group channel\n§5ForeignLanguage - FL - The foreign language channel\n§7Modded - M - The modded server's channel§3RolePlay - RP - The Roleplay channel";
		break;
	case "MUTE":
		echo "Muting doesn't work yet.\n";
		break;
	case "KICK":
		echo "Kicking doesn't work yet.\n";
		break;
	default:
		echo "§cUnknown command! §a/ch help§c for help.\n";
}
}
?>