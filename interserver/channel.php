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
if(isset($args[2])){ $channel=channel($args[2]); }
$arg1 = strtoupper($args[1]);
#if($name!="AgentKid"){ die('This is broken. Sorry.'); }
// Generic queries
// Type 1 means you're speaking in that room, Type 2 means that you're just in that room and listening.
$chatHandle = new ChannelHandle($name);
$logHandle = new Logger('chat');

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
		case "FORMAT":
		case "STYLE":
                        $chatHandle->setStyle($args[2]);
			break;
                case "LISTSTYLES":
                case "STYLELIST":
                case "STYLELISTS":
                case "LISTSTYLE":
                        clearScreen();
                        echo "-------------------- Chat Styles --------------------\n\n";
                        echo "Style 1:    |  §e[A] §f[§9Main§f] §bPlayer§f: Hello.\n\n";
                        echo "Style 2:    |  §e[A] §bPlayer§f: Hello.\n\n";
                        echo "Style 3:    |  §8(§bPlayer §8to §eAraeosia§8)§f: Hello.\n\n";
                        echo "Style 4:    |  §d14:52:08 §e[A] §f[§9Main§f] §bPlayer§f: Hello.\n\n";
                        echo "Style 5:    |  §d14:52:08 §e[A] §bPlayer§f: Hello.\n\n";
                        echo "Style 6:    |  §d14:52:08 §8(§bPlayer §8to §eAraeosia§8)§f: Hello.\n";
                        echo "§bChoose your style with §a/ch style #§b.";
                        break;
		case "QUIT":
		case "EXIT":
		case "LEAVE":
			$chatHandle->leaveChannel($channel);
			break;
                case "PUT":
                    // Because people don't listen when you say "JOIN ROLEPLAY CHANNEL GOD DAMNIT!".
                        if(!isStaff($name)){ die('No.'); }
                        $target = player($args[2]);
                        $channel = channel($args[3]);
                        if(!$target){ die('Invalid player!'); }
                        if(!$channel){ die('Invalid channel!'); }
                        $channelHandle2 = new ChannelHandle($target);
                        $channelHandle2->joinChannel($channel);
                        $logHandle->addLog("$name put $target into $channel.");
                        break;
                case "NUKE":
                        if(!isStaff($name)){ die('No.'); }
                        $logHandle->addLog("$name detonated a global chat nuke.");
                        foreach(getAllPlayers() as $mutee){
                                mysql_query("INSERT INTO GMutes (id, name) VALUES ('NULL', '$mutee')");
                                tellPlayer($mutee, "§cA global chat nuke detonated by order of ".getFullName($name)."\n");
				echo "§aMuted ".getFullName($mutee)." §aglobally!";
                        }
		case "WHO":
			if(channel($args[2])!=false){
				$channel = channel($args[2]);
				echo getChannelColor($channel)."----------- ".getColoredChannel($channel)." -----------\n";
				$channelContents = $chatHandle->getChannelMembers($channel);
				if(count($channelContents)>0){
					echo "§aMembers: ".implode('§f, ', rankPlayers($channelContents))."\n";
				}else{
					echo "§cNo members!";
				}
			}else{
				echo "------------------- Channel Members -------------------\n\n";
				echo getChannelColor($chatHandle->currentChannel)."----------- ".getColoredChannel($chatHandle->currentChannel)." -----------\n";
				echo "§aMembers: ".implode('§f, ', rankPlayers($chatHandle->getChannelMembers($chatHandle->currentChannel)))."\n";
				foreach($chatHandle->channelsIn as $channel){
					echo getChannelColor($channel)."----------- ".getColoredChannel($channel)." -----------\n";
					echo "§aMembers: ".implode('§f, ', rankPlayers($chatHandle->getChannelMembers($channel)))."\n";
				}
			}
			break;
		case "LIST":
			echo "-------- Channels --------\n§eAraeosia - A - The main channel\n§aStaff - S - The staff channel\n§bTrade - T - The trade channel\n§9Help - H - The help channel\n§cLocal - L - The Local channel\n§6Group - G - The group channel\n§5ForeignLanguage - FL - The foreign language channel\n§7Modded - M - The modded server's channel\n§3RolePlay - RP - The Roleplay channel";
		break;
		case "MUTE":
			if(!isStaff($name)){ die('§cYou do not have permission to mute players!'); }
			$ch = $args[2];
			if(!isChannel($ch)){ die('§cCould not find a channel by that name!'); }
			$mutee = player($args[3]);
			if(isStaff($mutee) && $name!='AgentKid'){ die('§cYou cannot mute '.getFullName($mutee).'§c as they are staff!'); }
			if($mutee==false){ die('§cCould not find a player by that name!'); }
			$query = mysql_fetch_array(mysql_query("SELECT * FROM Mutes WHERE name='$mutee' AND channel='$ch'") or die(mysql_error()));
			if($query!=false){
				// Player is already muted, unmute them.
                                $logHandle->addLog("$name unmuted $mutee in $channel.");
				mysql_query("DELETE FROM Mutes WHERE name='$mutee' AND channel='$ch'");
				tellPlayer($mutee, "§cYou were unmuted by ".getFullName($name)." §c in the ".getColoredChannel($ch)." §cchannel!\n");
				echo "§aUnmuted ".getFullName($mutee)." §ain channel §".$channelColors[$ch].$channelFullNames[$ch]."§a!";
			}else{
				// Player isn't muted, mute them.
                                $logHandle->addLog("$name muted $mutee in $channel.");
				mysql_query("INSERT INTO Mutes (id, name, channel) VALUES ('NULL', '$mutee', '$ch')");
				tellPlayer($mutee, "§cYou were muted by ".getFullName($name)." §c in the ".getColoredChannel($ch)." §cchannel!\n");
				echo "§aMuted ".getFullName($mutee)." §ain channel §".$channelColors[$ch].$channelFullNames[$ch]."§a!";
			}
			break;
		case "GMUTE":
			if(!isStaff($name)){ die('§cYou do not have permission to mute players!'); }
			$mutee = player($args[2]);
			if(isStaff($mutee) && $name!='AgentKid'){ die('§cYou cannot mute '.getFullName($mutee).'§c as they are staff!'); }
			if($mutee==false){ die('§cCould not find a player by that name!'); }
			$query = mysql_fetch_array(mysql_query("SELECT * FROM GMutes WHERE name='$mutee'"));
			if($query!=false){
				// Player is already muted, unmute them.
                                $logHandle->addLog("$name globally muted $mutee.");
				mysql_query("DELETE FROM GMutes WHERE name='$mutee'");
				echo "§aUnmuted ".getFullName($mutee)." §aglobally!";
			}else{
				// Player isn't muted, mute them.
                                $logHandle->addLog("$name globally unmuted $mutee.");
				mysql_query("INSERT INTO GMutes (id, name) VALUES ('NULL', '$mutee')");
				echo "§aMuted ".getFullName($mutee)." §aglobally!";
			}
			break;
		case "KICK":
			// Syntax should be /ch kick <channel> <name> [reason]
			if(!isStaff($name)){ die('§cYou do not have permission to kick players!'); }
			$kickee = player($args[3]);
			$ch = channel($args[2]);
			if($ch==false){ die('§cInvalid channel!'); }
			if($kickee==false){ die('§cInvalid player!'); }
			if(isStaff($kickee) && $name!='AgentKid'){ die('§cYou cannot kick another staff member from a channel!'); }
			if(count($args)>4){
				$args = array_shift_multiple($args, 4);
				$reason = implode(' ', $args);
			}
			// Okay, checks are complete and variables are set. Now lets actually kick the player.
                        $logHandle->addLog("$name kicked $kickee from $ch.");
			$theirChatHandle = new ChannelHandle($kickee);
			if(count($theirChatHandle->getChannelsIn())==0){ die('§cYou cannot kick '.getFullName($kickee).' §cfrom '.getColoredChannel($ch).' §cas it\'s the only channel they\'re in!'); }
			mysql_query("DELETE FROM ChannelsIn WHERE name='$kickee' AND channel='$ch'");
			$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$kickee' AND type='1'");
			$row = mysql_fetch_array($row);
			if($row==false){
				$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$kickee' AND type='2'");
				$row = mysql_fetch_array($query);
				$newChannel = $row['channel'];
				mysql_query("UPDATE ChannelsIn SET type='1' WHERE name='$kickee' AND channel='$newChannel'");
			}
			if(isset($reason)){
				tellPlayer($kickee, "§cYou were kicked from the ".getColoredChannel($ch)." §cchannel by ".getFullName($name)." §cwith the reason §b\"".$reason."§c!");
			}else{
				tellPlayer($kickee, "§cYou were kicked from the ".getColoredChannel($ch)." §cchannel by ".getFullName($name)."§c!");
			}
                        echo "You kicked ".getFullName($kickee)." from ".getColoredChannel($ch);
			break;
		default:
			echo "§cUnknown command! §a/ch help§c for help.\n";
	}
}
?>