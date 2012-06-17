<?php
// Dynamic variables
$name = $_POST['player'];
$server = $_GET['s'];
if($name!='AgentKid' && $name!='console'){die('§4This is still a work-in-progress.'); }
$args = $_POST['args'];
$channel = strtoupper($args[2]);
$arg1 = strtoupper($args[1]);

// Includes
include('includes/mysql.php');
include('includes/functions.php');
include('includes/passwords.php');

// Static variables
$channels = array('A', 'S', 'T', 'H', 'L', 'G', 'FL', 'M');
$channelFullNames = array('A' => 'Araeosia', 'S' => 'Staff', 'T' => 'Trade', 'H' => 'Help', 'L' => 'Local', 'G' => 'Group', 'FL' => 'Foreign Language', 'M' => 'Modded');
$channelColors = array('A' => 'e', 'S' => 'a', 'T' => 'b', 'H' => '9', 'L' => 'c', 'G' => '6', 'FL' => '5', 'M' => '7');

// Generic queries
$query = mysql_fetch_array(mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='1'") or die(mysql_error()));
$currentChannel = $query['channel'];
$channelsIn = array();
$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='2'");
while($row=mysql_fetch_array($query)){ array_push($channelsIn, $row['channel']); }

// Most of the actual code
if(in_array($arg1, $channels)){
// So they want to focus on that channel specifically.
	if($currentChannel==$arg1){ die('§cYou are already in §'.$channelColors[$arg1].$channelFullNames[$arg1].' §c!'); }
//	if(!in_array($channelsIn))
	die();
}

switch($args[1]){
	case "HELP":
		echo "Nag AgentKid to write the help message!\n";
		break;
	case "ENTER":
	case "JOIN":
		if(!in_array($channel, $channels)){ die('§cInvalid channel or usage! Channel list: §a/ch list§c.'); }
# This always returns one row, so no point in going further with this.
		if($currentChannel==$channel){ die('§cYou are already in §'.$channelColors[$toJoin].$channelFullNames[$channel].'§c!'); }
		if($channel=="S" && !in_array(strtolower($name), $staff)){ die('§cYou cannot join §'.$channelColors[$channel].$channelFullNames[$channel].'§c!'); }
		mysql_query("INSERT INTO ChannelsIn (id, name, channel, type) VALUES ('NULL', '$name', '$channel', '1')");
		echo "§aYou have joined the §".$channelColors[$channel].$channelFullNames[$channel]." §achannel!\n";
		break;
	case "QUIT":
	case "EXIT":
	case "LEAVE":
		if(!in_array($channel, $channels)){ die('§cInvalid channel! Usage: §a/ch leave [channel]'); }
		if($channel!=$currentChannel)
		break;
	case "LIST":
		break;
	default:
		echo "§cUnknown command! §a/ch help§c for help.\n";
}
?>