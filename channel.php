<?php
// Dynamic variables
$name = $_POST['player'];
$server = $_GET['s'];
if($name!='AgentKid'){die('§4This is still a work-in-progress.'); }

// Includes
include('includes/mysql.php');
include('includes/functions.php');
include('includes/passwords.php');
$args = $_POST['args'];

// Static variables
$channels = array('A', 'S', 'T', 'H', 'L', 'G', 'FL', 'M');
$channelFullNames = array('A' => 'Araeosia', 'S' => 'Staff', 'T' => 'Trade', 'H' => 'Help', 'L' => 'Local', 'G' => 'Group', 'FL' => 'Foreign Language', 'M' => 'Modded');
$channelColors = array('A' => 'e', 'S' => 'a', 'T' => 'b', 'H' => '9', 'L' => 'c', 'G' => '6', 'FL' => '5', 'M' => '7');

switch($args[1]){
	case "help":
		echo "Nag AgentKid to write the help message!\n";
		break;
	case "enter":
	case "join":
		$toJoin = strtoupper($args[2]);
		if(!in_array($toJoin, $channels)){ die('§cInvalid channel or usage! Channel list: §a/ch list§c.'); }
# This always returns one row, so no point in going further with this.
		$query = mysql_fetch_array(mysql_query("SELECT * FROM ChannelsIn WHERE name='$name'"));
		$currentChannel = $query['channel'];
		if($currentChannel==$toJoin){ die('§cYou are already in '.$channelColors[$toJoin].$channelFullNames[$toJoin].'§c!'); }
		if($toJoin=="S" && !in_array(strtolower($name), $staff)){ die('§cYou cannot join '.$channelColors[$toJoin].$channelFullNames[$toJoin].'§c!'); }
		mysql_query("INSERT INTO ChannelsIn (id, name, channel) VALUES ('NULL', '$name', '$toJoin')");
		echo "§aYou have joined the ".$channelColors[$toJoin].$channelFullNames[$toJoin]." §achannel!\n";
		break;
	case "quit":
	case "exit":
	case "leave":
		break;
	case "list":
		break;
	default:
		echo "§cUnknown command! §a/ch help§c for help.\n";
}
?>