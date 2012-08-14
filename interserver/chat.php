<?php
// Variables
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$msg = $_POST['args'];

// Includes
include('includes/servers.php');
include('includes/staff.php');
include('includes/functions.php');
include('includes/passwords.php');
include('includes/mysql.php');
include('includes/channels.php');

// Remove the first value, leaving us with an array of all words in the chat message.
array_shift($args);

// Then turn it into a string.
$msg = trim(implode(' ', $args));
$msg = str_replace('    ', '', $msg);

// Create a new channel handle to get the current channel.
$channelHandle = new ChannelHandle($name);
$channel = $channelHandle->currentChannel;

// Create the log variable for later use
$log = getChannelColor($channel)."[".$channel."]"." §f[§9".getWorldName($world)."§f] ".getFullName($name)."§f: ".$msg;

// Die if the player is muted.
if($channelHandle->isMute($channel) || $channelHandle->isMute()){ die('§cYou are currently muted!'); }

// Output the message to the player first.
tellPlayer($name, formatOutput($channel, $name, $msg, $world, $channelHandle->style));

// Send the message to everyone in the channel.
sendMessageToChannel($channel, $name, $msg, $world, array($name));

// Save a log of this chat message.
$logHandle = new Logger('chat');
$logHandle->addLog($log);
?>