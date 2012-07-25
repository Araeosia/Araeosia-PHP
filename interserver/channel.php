<?php
// Developer notes:
// This file is executed whenever anyone on any server uses the /ch command with any or no arguments.
// Things that need doing in this file: Set up /ch kick, set up /ch SRC.

// Includes
include_once('includes/mysql.php');
include_once('includes/functions.php');
include_once('includes/passwords.php');
include_once('includes/channels.php');
include_once('includes/staff.php');

// Dynamic variables
$name = $_POST['player'];
$server = $_GET['s'];
$args = $_POST['args'];
$channel=channel($args[2]);
$arg1 = strtoupper($args[1]);
#if($name!="AgentKid"){ die('This is broken. Sorry.'); }
// Generic queries
// Type 1 means you're speaking in that room, Type 2 means that you're just in that room and listening.
$chatHandle = new ChannelHandle($name);

// Most of the actual code
if(channel($arg1)!=false){
// So they want to focus on that channel specifically.
	$chatHandle->joinChannel($arg1);
}else{
	switch($arg1){
		case "HELP":
			echo "§a/ch help §f- §bDisplays this help message.\n§a/ch join [channel] §f- §bJoins the specified channel.\n§a/ch leave [channel] §f- §bLeaves the specified channel.\n§a/ch who §f- §bDisplays online members in your channel.\n§a/ch list §f- §bLists all available channels.\n";
			break;
		case "DUMP":
			$chatHandle->varDump();
			break;
		case "ENTER":
		case "JOIN":
			$chatHandle->joinChannel($channel);
			break;
		case "QUIT":
		case "EXIT":
		case "LEAVE":
			$chatHandle->leaveChannel($channel);
			break;
		case "WHO":
			echo "------------------- Channel Members -------------------\n\n";
			echo getChannelColor($chatHandle->currentChannel)."----------- ".getColoredChannel($chatHandle->currentChannel)." -----------\n";
			echo "§aMembers: ".implode('§f, ', $chatHandle->getChannelMembers($chatHandle->currentChannel))."\n";
			foreach($chatHandle->channelsIn as $channel){
				echo getChannelColor($channel)."----------- ".getColoredChannel($channel)." -----------\n";
				echo "§aMembers: ".implode('§f, ', $chatHandle->getChannelMembers($channel))."\n";
			}
			break;
		case "LIST":
			echo "-------- Channels --------\n§eAraeosia - A - The main channel\n§aStaff - S - The staff channel\n§bTrade - T - The trade channel\n§9Help - H - The help channel\n§cLocal - L - The Local channel\n§6Group - G - The group channel\n§5ForeignLanguage - FL - The foreign language channel\n§7Modded - M - The modded server's channel\n§3RolePlay - RP - The Roleplay channel";
		break;
		case "MUTE":
			if(!isStaff($name)){ die('§cYou do not have permission to mute players!'); }
			$ch = $args[3];
			if(!isChannel($ch)){ die('§cCould not find a channel by that name!'); }
			$mutee = player($args[2]);
			if($mutee==false){ die('§cCould not find a player by that name!'); }
			$query = mysql_query("SELECT * FROM Mutes WHERE name='$mutee' AND channel='$ch'");
			if($query!=false){
				// Player is already muted, unmute them.
				mysql_query("DELETE FROM Mutes WHERE name='$mutee' AND channel='$ch'");
				echo "§aUnmuted ".$mutee." in channel §".$channelColors[$ch].$channelFullNames[$ch]."§a!";
			}else{
				// Player isn't muted, mute them.
				mysql_query("INSERT INTO Mutes (id, name, channel) VALUES ('NULL', '$mutee', '$ch')");
				echo "§aMuted ".$mutee." in channel §".$channelColors[$ch].$channelFullNames[$ch]."§a!";
			}
			break;
		case "GMUTE":
			if(!isStaff($name)){ die('§cYou do not have permission to mute players!'); }
			$mutee = player($args[2]);
			if($mutee==false){ die('§cCould not find a player by that name!'); }
			$query = mysql_query("SELECT * FROM GMutes WHERE name='$mutee'");
			if($query!=false){
				// Player is already muted, unmute them.
				mysql_query("DELETE FROM GMutes WHERE name='$mutee'");
				echo "§aUnmuted ".$mutee." globally!";
			}else{
				// Player isn't muted, mute them.
				mysql_query("INSERT INTO GMutes (id, name) VALUES ('NULL', '$mutee')");
				echo "§aMuted ".$mutee." globally!";
			}
			break;
		case "KICK":
			echo "Kicking doesn't work yet.\n";
			break;
		default:
			echo "§cUnknown command! §a/ch help§c for help.\n";
	}
}
?>