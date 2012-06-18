<?php
// Dynamic variables
$name = $_POST['player'];
$server = $_GET['s'];
if($name!='AgentKid' && $name!='console'){die('§4This is still a work-in-progress.'); }
$args = $_POST['args'];
if(isset($args[2])){ $channel=strtoupper($args[2]); }
$arg1 = strtoupper($args[1]);

// Includes
include('includes/mysql.php');
include('includes/functions.php');
include('includes/passwords.php');

// Because I can't think of a cleaner way to do this at 6AM after an all-nighter.
if($channel=="ARAEOSIA"){$channel="A";}elseif($channel=="STAFF"){$channel="S";}elseif($channel=="TRADE"){$channel="T";}elseif($channel=="HELP"){$channel="H";}elseif($channel=="LOCAL"){$channel="L";}elseif($channel=="GROUP"){$channel="G";}elseif($channel=="FOREIGNLANGUAGE"){$channel="FL";}elseif($channel=="MODDED"){$channel="M";}

// Static variables
$channels = array('A', 'S', 'T', 'H', 'L', 'G', 'FL', 'M');
$channelFullNames = array('A' => 'Araeosia', 'S' => 'Staff', 'T' => 'Trade', 'H' => 'Help', 'L' => 'Local', 'G' => 'Group', 'FL' => 'Foreign Language', 'M' => 'Modded');
$channelColors = array('A' => 'e', 'S' => 'a', 'T' => 'b', 'H' => '9', 'L' => 'c', 'G' => '6', 'FL' => '5', 'M' => '7');

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
		if(!in_array($channel, $channels)){ die("§cInvalid channel! Usage: §a/ch leave [channel]\n"); }
		if($channel!=$currentChannel && !in_array($channel, $channelsIn)){ die("§cYou are not in the §".$channelColors[$channel].$channelFullNames[$channel]." §cchannel!\n"); }
		if(count($channelsIn)==0){ die("§cYou cannot leave the only channel you're in! Join another first."); }
		break;
	case "WHO":
		$inChannel=array();
		echo "§".$channelColors[$currentChannel]."------- ".$channelFullNames[$currentChannel]." -------\n";
		$inThisChannel=array();
		$query = mysql_query("SELECT * FROM ChannelsIn WHERE channel='$currentChannel'") or die(mysql_error());
		while($row = mysql_fetch_array($query)){ array_push($inThisChannel, $row['name']); }
		echo "§b".implode('§f, §b', $inThisChannel)."\n";
		foreach($channelsIn as $ch){
			$inThisChannel=array();
			$query = mysql_query("SELECT * FROM ChannelsIn WHERE channel='$ch'");
			while($row = mysql_fetch_array($query)){ array_push($inThisChannel, $row['name']); }
			echo "§".$channelColors[$ch]."------- ".$channelFullNames[$ch]." -------\n";
			echo "§b".implode('§f, §b', $inThisChannel)."\n";
		}
		break;
	case "LIST":
		echo "Here's a channel list.";
		break;
	default:
		echo "§cUnknown command! §a/ch help§c for help.\n";
}
}
?>