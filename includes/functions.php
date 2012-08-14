<?php
// This file contains general functions that are used in more than one place, such as Bcrypt and the minecraft query. I've placed them here to both keep them out of the way and also to shorten the length of the PHP files.
// User related functions
include('includes/mysql.php');
function getPrimaryGroup($player){
	/* Gets the primary group of a player
	 *
	 * Recieves: $player
	 * Throws: Primary group of a player
	 */
	include('includes/mysql.php');
	$query = mysql_query("SELECT * FROM TrueGroups WHERE name='$player'") or die(mysql_error());
	$groups = array();
	while($row = mysql_fetch_array($query)){
		array_push($groups, $row['group']);
#		var_dump($row);
	}
	$primaryGroup = "Default";
	if(in_array('Veteran', $groups)){ $primaryGroup = "Veteran"; }
	if(in_array('Supporter', $groups)){ $primaryGroup = "Supporter"; }
	if(in_array('Moderator', $groups)){ $primaryGroup = "Moderator"; }
	if(in_array('Admin', $groups)){ $primaryGroup = "Admin"; }
	if(in_array('Head-Admin', $groups)){ $primaryGroup = "Head-Admin"; }
	return $primaryGroup;
}
function getFullName($player){
	/* Gets the full name of a player, including color codes.
	 *
	 * Recieves: $player
	 * Throws: Full name of a player
	 */
	switch(getPrimaryGroup($player)){
		case "Veteran":
			$prefix = "§2";
			break;
		case "Moderator":
			$prefix = "§a";
			break;
		case "Supporter":
			$prefix = "§1";
			break;
		case "Admin":
			$prefix = "§4";
			break;
		case "Head-Admin":
			$prefix = "§4";
			break;
		default:
			$prefix = "§b";
			break;
	}
	$playerFinal = getAlias($player);
	$playername = $prefix.$playerFinal;
	return $playername;
}
function getAlias($player){
	/* Gets the alias of a player
	 *
	 * Recieves: $player
	 * Throws: Alias of a player
	 */
    switch($player){
	case "CanadianCellist":
            $playerFinal = "The Canadian";
            break;
	case "AgentKid":
            $playerFinal = "The Agent";
            break;
        case "mrthemaster10":
            $playerFinal = "The Master";
            break;
	case "turkeymilk":
            $playerFinal = "The Turkey";
            break;
        case "anoki123":
            $playerFinal = "Anoki";
            break;
	default:
            $playerFinal = $player;
            break;
    }
    return $playerFinal;
}
function isInGroup($player, $group){
	/* Checks whether a player is in the specified group
	 *
	 * Recieves: $player, $group
	 * Throws: True if the player is in the specified group, otherwise false.
	 */
	include('includes/mysql.php');
	$query = mysql_query("SELECT * FROM TrueGroups WHERE name='$player'") or die(mysql_error());
	$query = mysql_fetch_array($query);
	$playerGroups = array();
	array_push($playerGroups, $query['group']);
	if($query['group']=="Veteran"){
		array_push($playerGroups, "Default");
	}
	if($query['group']=="Moderator"){
		array_push($playerGroups, "Veteran");
		array_push($playerGroups, "Default");
	}
	if($query['group']=="Admin"){
		array_push($playerGroups, "Moderator");
		array_push($playerGroups, "Veteran");
		array_push($playerGroups, "Default");
	}
	if($query['group']=="Head-Admin"){
		array_push($playerGroups, "Admin");
		array_push($playerGroups, "Moderator");
		array_push($playerGroups, "Veteran");
		array_push($playerGroups, "Default");
	}
	if(in_array($group, $playerGroups)){ return true; }else{ return false; }
}
function isStaff($player){
	/* Checks to see whether a player is staff or not
	 *
	 * Recieves: $player
	 * Throws: True if the player is staff, otherwise false
	 */
	return isInGroup($player, "Moderator");
}
function isOnlinePlayer($player){
	/* Checks to see if the specified player is online or not
	 *
	 * Recieves: $player
	 * Throws: True of the specified player is online on any server, otherwise false.
	 */
	if(in_array($player, getAllPlayers())){ return true; }else{ return false; }
}
function getAllPlayers(){
	/* Gets all online players
	 *
	 * Recieves: null
	 * Throws: Array of all online players, or empty array if none.
	 */
	include('includes/servers.php');
	$finalPlayers = array();
	foreach($servers as $server){
		$players = getOnlinePlayers($server);
		foreach($players as $player){ array_push($finalPlayers, $player); }
	}
	return $finalPlayers;
}
function isRealPlayer($player){
	/* Checks to see if the specified player is a real player and has visited an Araeosia server before.
	 *
	 * Recieves: $player
	 * Throws: True if the specified player is a real player, otherwise false.
	 */
	include('includes/mysql.php');
	$query = mysql_query("SELECT * FROM TrueGroups WHERE name='$player'");
	if(mysql_fetch_array($query)!=false){ return true; }else{ return false; }
}
function player($player){
	/* Returns a full player's name based off a beginning fragment
	 *
	 * Recieves: $player
	 * Throws: Full player's name, or false if the player isn't online.
	 */
	$onlinePlayers = getAllPlayers();
	foreach($onlinePlayers as $playerToCheck){
	// Matches to the beginning of the name only, just like Bukkit.
		if(strpos(strtolower($playerToCheck), strtolower($player))===0 || strpos(strtolower($playerToCheck), strtolower(getAlias($player)))===0){
			return $playerToCheck;
			break;
		}
	}
	return false;
}
function offlinePlayer(){
	/* Returns a full player's name based off a beginning fragment, including offline players.
	 *
	 * Recieves: $player
	 * Throws: Full player's name, or false if the player doesn't exist.
	 */
	$players = getAllOfflinePlayers();
	$done = false;
	foreach($players as $playerToCheck){
	// Matches to the beginning of the name only, just like Bukkit.
		echo "Checking ".$playerToCheck."\n";
		if(strpos(strtolower($playerToCheck), strtolower($player))===0 || strpos(strtolower($playerToCheck), strtolower(getAlias($player)))===0){
			return $playerToCheck;
			$done = true;
			break;
		}
	}
	if(!$done){ return false; }
}
function rankPlayers($players){
	/* Returns an array with the given players sorted from highest to lowest rank.
	 *
	 * Recieves: $players
	 * Throws: Sorted array of player names
	 */
	$rankedList = array();
	foreach($players as $player){if(getPrimaryGroup($player)=="Head-Admin"){ array_push($rankedList, getFullName($player)); } }
	foreach($players as $player){if(getPrimaryGroup($player)=="Admin"){ array_push($rankedList, getFullName($player)); } }
	foreach($players as $player){if(getPrimaryGroup($player)=="Moderator"){ array_push($rankedList, getFullName($player)); } }
	foreach($players as $player){if(getPrimaryGroup($player)=="Veteran"){ array_push($rankedList, getFullName($player)); } }
	foreach($players as $player){if(getPrimaryGroup($player)=="Default"){ array_push($rankedList, getFullName($player)); } }
	return $rankedList;
}
function getOnlineStaff(){
	/* Returns an array with all online staff members names.
	 *
	 * Recieves: Null
	 * Throws: Array of online staff members or false if none.
	 */
	$onlineStaff = array();
	foreach(getAllPlayers() as $player){
		if(isStaff($player)){ array_push($onlineStaff, $player); }
	}
	if(count($onlineStaff)>0){ return $onlineStaff; }else{ return false; }
}
function isOnlineOnServer($server, $player){
	/* Checks to see if the specified player is online on a server or not
	 *
	 * Recieves: $server, $player
	 * Throws: True of the specified player is online on specified server, otherwise false.
	 */
	 if(in_array($player, getOnlinePlayers($server))){ return true; }else{ return false; }
}
// Server related functions
function getServersByPlayer($player){
	/* Gets the servers that a player is connected to.
	 *
	 * Recieves: $player
	 * Throws: Array of servers that the player is connected to, or an empty array if none.
	 */
	include('includes/servers.php');
	$player = player($player);
	if($player==false){ die('Invalid player!'); }
	$serversPlayerIsIn = array();
	foreach($servers as $server){
		$players = getOnlinePlayers($server);
		if(in_array($player, $players)){ array_push($serversPlayerIsIn, $server); }
	}
	return $serversPlayerIsIn;
}
function getAllOfflinePlayers(){
	/* Gets a list of all players that have ever connected to the server.
	 *
	 * Recieves: null
	 * Throws: Array of players that have visited any Araeosia servers, or empty array if none.
	 */
	include('includes/mysql.php');
	$query = mysql_query("SELECT * FROM TrueGroups");
	$finalPlayers = array();
	while($row = mysql_fetch_array($query)){
		array_push($finalPlayers, $row['name']);
	}
	return $finalPlayers;
}
function getOnlinePlayers($server){
	/* Gets all online players on a server.
	 *
	 * Recieves: $server
	 * Throws: Array of players that are connected to a server.
	 */
	include('includes/servers.php');
	$Query = new MinecraftQuery();
	$players = array();
	try{
		$Query->Connect( $ips[$server], $ports['mc'][$server], 1 );
		$players = $Query->GetPlayers();
	}catch(MinecraftQueryException $e){
                $e = $e;
		$players = array();
	}
	return $players;
}
function serverCheck($server, $allowedServers){
	/* Checks to see if the command is allowed to be used on that server.
	 *
	 * Recieves: $server, array of allowed servers
	 * Throws: True if allowed, or exits the script.
	 */
	if(is_array($allowedServers) && in_array($server, $allowedServers) || $allowedServers === true){ return true; }else{ exit; }
}
// Teleport related functions
function isRegisteredAtLocation($locid, $name){
	// Placeholder function.
    if(!is_int($locid)){ die('Bad location ID!'); }
    $name = htmlspecialchars($name);
    $query = mysql_query("SELECT * FROM ShipsCheckin WHERE name='$name' AND loc='$locid'");
    if(mysql_fetch_array($query)==false){ return false; }else{ return true; }
}
// Book related functions
function readBook($player, $bookid){
	if(hasReadBook($player, $bookid)){
		return false;
	}else{
		
	}
}
function hasReadBook($player, $bookid){
	if(!is_int($bookid)){ die('Invalid book ID!'); }
	include('includes/mysql.php');
	$query = mysql_query("SELECT * FROM BooksComplete WHERE name='$player' AND id='$bookid'");
	if(!mysql_fetch_array($query)){ return false; }else{ return true; }
}
// Mail related functions
function readMessage($msgid, $player=false){
	/* Reads a mail message from the database to the specified player.
	 *
	 * Recieves: $msgid, [$player]
	 * Throws: null
	 */
	include("includes/mysql.php");
	$query = mysql_query("SELECT * FROM Mail WHERE msgid='$msgid'");
	$row = mysql_fetch_array($query);
	$finaloutput = "§b------- §aMail Message ".$msgid." from ".getFullName(offlinePlayer($row['name']))." §b-------\n§eMessage sent on ".date("l jS \of F Y h:i:s A", $row['time'])."\n".htmlspecialchars_decode($row['message']);
	if($player==false){ echo $finaloutput; }else{ tellPlayer($player, $finaloutput); }
}
function writeMessage($player, $recipient, $message){
	/* Writes a mail message to the database
	 *
	 * Recieves: $player, $recipient, $message
	 * Throws: null
	 */
	if(offlinePlayer($player)===false){ die('Not a valid player'); }
	include('includes/mysql.php');
	$query = mysql_query("SELECT * FROM Mail");
	$count = 0;
	while($row = mysql_fetch_array($query)){$count = $count+1;}
	$msg = htmlspecialchars($message);
	if(is_null($message)){ die('No message provided!'); }
	$msgid = $count+1;
	$time = time();
	$name = player($player);
	$recipient = offlinePlayer($recipient);
	mysql_query("INSERT INTO Mail (id, msgid, time, name, recipient, message, status) VALUES ('NULL', '$msgid', '$time', '$name', '$recipient', '$msg', '1')");
}
function listMessages($player, $page=0){
	/* Echos a list of mail messages
	 *
	 * Recieves: $player, [$page]
	 * Throws: null
	 */
	include('includes/mysql.php');
	$player = offlinePlayer($player);
	if($player==false){ die('Invalid player!'); }
	$query = mysql_query("SELECT * FROM Mail WHERE recipient='$player'");
	$messages = array();
	while($row = mysql_fetch_array($query)){
		$messages[count($messages)] = array(
			'msgid' => $row['msgid'],
			'time' => $row['time'],
			'name' => $row['name'],
			'recipient' => $row['recipient'],
			'message' => $row['message'],
			'status' => $row['status']
		);
	}
	if(count($messages)==0){ die(''); }
	$numberOfPages = ceil(count($messages)/8);
	if($page!=0){
		// They want a listing other than the first page
		if(!$page<=$numberOfPages){ die('§cThat page doesn\'t exist!'); }
	}else{
		
	}
}
// Chat related functions
function sendMessageToChannel($channel, $sender, $message, $world, $excluded=array(), $me=false){
	/* Sends a message to all members of a channel while excluding any user names in the $excluded array.
	 *
	 * Recieves: $channel, $message, $sender, [$excluded]
	 * Throws: null
	 */
	if(!isset($channel) || !is_string($channel)){ die('$channel is not a string!'); }
	if(!isset($message) || !is_string($message)){ die('$message is not a string!'); }
	if(!isset($excluded) || !is_array($excluded)){ die('$excluded is not an array!'); }
	include('includes/servers.php');
	include('includes/mysql.php');
	include('includes/passwords.php');
	$inChannel = array();
	$query = mysql_query("SELECT * FROM ChannelsIn WHERE channel='$channel'");
	while($row = mysql_fetch_array($query)){ array_push($inChannel, $row['name']); }
	$ignoredby = array();
	$query2 = mysql_query("SELECT * FROM Blocks WHERE blockee='$sender'");
	while($row2 = mysql_fetch_array($query2)){ array_push($ignoredby, $row2['name']); }
	$query3 = mysql_query("SELECT * FROM ChannelStyles");
	while($row3 = mysql_fetch_array($query3)){ $channelStyles[$row3['name']] = $row3['style']; }
	foreach($serversChat as $server){
		$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
		$players = getOnlinePlayers($server);
		if(count($players)!=0){
			foreach($players as $player){
				if(in_array($player, $inChannel) && !in_array($player, $excluded) && !in_array($player, $ignoredby)){
                                      $realMessage = formatOutput($channel, $sender, $message, $world, $channelStyles[$player], $me);
                                      $JSONAPI->call('sendMessage', array($player, $realMessage));
				}
			}
		}
	}
}
function channel($channel){
	/* Returns a database-compatible channel identifier based off a name, abbreviation, etc.
	 *
	 * Recieves: $channel
	 * Throws: Database-compatible channel identifier, or false if not a channel.
	 */
	switch(strtoupper($channel)){
		case "S":
		case "STAFF":
			$output = "S";
			break;
		case "A":
		case "ARAEOSIA":
			$output = "A";
			break;
		case "T":
		case "TRADE":
			$output = "T";
			break;
		case "V":
		case "VANILLA":
			$output = "V";
			break;
		case "H":
		case "HELP":
			$output = "H";
			break;
		case "L":
		case "LOCAL":
			$output = "L";
			break;
		case "G":
		case "GROUP":
			$output = "G";
			break;
		case "FL":
		case "FOREIGNLANGUAGE":
			$output = "FL";
			break;
		case "M":
		case "MODDED":
			$output = "M";
			break;
		case "RP":
		case "ROLEPLAY":
			$output = "RP";
			break;
		default:
			$output = false;
			break;
	}
	return $output;
}
function getColoredChannel($channel){
	/* Returns a colored channel name
	 *
	 * Recieves: $channel
	 * Throws: String containing the color code and full name of the specified channel.
	 */
	$channel = channel($channel);
	if($channel==false){ die('Invalid channel!'); }
	$channelFullNames = array('A' => 'Araeosia', 'S' => 'Staff', 'T' => 'Trade', 'H' => 'Help', 'L' => 'Local', 'G' => 'Group', 'FL' => 'Foreign Language', 'M' => 'Modded', 'RP' => 'Roleplay', 'V' => 'Vanilla');
	$channelColors = array('A' => 'e', 'S' => 'a', 'T' => 'b', 'H' => '9', 'L' => 'c', 'G' => '6', 'FL' => '5', 'M' => '7', 'RP' => '3', 'V' => 'f');
	return "§".$channelColors[$channel].$channelFullNames[$channel];
}
function getChannelColor($channel){
	/* Gets the color code of a specified channel, with the §.
	 *
	 * Recieves: $channel
	 * Throws: Channel color code
	 */
	$channel = channel($channel);
	if($channel==false){ die('Invalid channel!'); }
	$channelColors = array('A' => 'e', 'S' => 'a', 'T' => 'b', 'H' => '9', 'L' => 'c', 'G' => '6', 'FL' => '5', 'M' => '7', 'RP' => '3', 'V' => 'f');
	return "§".$channelColors[$channel];
}
function getWorldName($world){
	/* Gets the properly formatted world name of a specified world.
	 *
	 * Recieves: $world
	 * Throws: Properly formatted and replaced world name.
	 */
	switch($world){
		case "Main_nether":
			$worldname = "Nether";
			break;
		case "Main_the_end":
			$worldname = "The End";
			break;
		case "Tekkit_nether":
			$worldname = "Tekkit Nether";
			break;
		case "Tekkit_the_end":
			$worldname = "Tekkit The End";
			break;
		case "world":
			$worldname = "Vanilla";
			break;
		case "world_nether":
			$worldname = "Vanilla Nether";
			break;
		case "world_the_end":
			$worldname = "Vanilla The End";
			break;
		case "Eco_nether":
			$worldname = "Eco Nether";
			break;
		case "Echo_the_end":
			$worldname = "Eco The End";
			break;
		default:
			$worldname = $world;
			break;
	}
	return $worldname;
}
function isChannel($channel){
	/* Checks to see if a specified string is a real channel
	 *
	 * Recieves: $channel
	 * Throws: True if real channel, otherwise false.
	 */
	if(channel($channel)==false){ return false; }else{ return true; }
}
function tellPlayer($player, $message){
	/* Sends a message to the specified player on all servers.
	 *
	 * Recieves: $player, $message
	 * Throws: null
	 */
	include('includes/passwords.php');
	include('includes/servers.php');
	$player = player($player);
	if($player==false){ die('Invalid player!'); }
	$servers = getServersByPlayer($player);
	foreach($servers as $server){
		$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
		$JSONAPI->call('sendMessage', array($player, $message));
	}
}
function formatOutput($channel, $name, $msg, $world, $style=1, $me=false){
	$channel = channel($channel);
	if($channel==false){ die('Invalid channel!'); }
        if(!$me){
            switch($style){
                    case "1":
                            $finalOutput = getChannelColor($channel)."[".$channel."] §f[§9".getWorldName($world)."§f] ".getFullName($name)."§f: ".$msg;
                            break;
                    case "2":
                            $finalOutput = getChannelColor($channel)."[".$channel."] ".getFullName($name)."§f: ".$msg;
                            break;
                    case "3":
                            $finalOutput = "§8(".getFullName($name)." §8to ".getColoredChannel($channel)."§8)§f: ".$msg;
                            break;
                    case "4":
                            $finalOutput = "§d".date("H:i:s").getChannelColor($channel)." [".$channel."] §f[§9".getWorldName($world)."§f] ".getFullName($name)."§f: ".$msg;
                            break;
                    case "5":
                            $finalOutput = "§d".date("H:i:s").getChannelColor($channel)." [".$channel."] ".getFullName($name)."§f: ".$msg;
                            break;
                    case "6":
                            $finalOutput = "§d".date("H:i:s")." §8(".getFullName($name)." §8to ".getColoredChannel($channel)."§8)§f: ".$msg;
                            break;
            }
	}else{
            switch($style){
                    case "1":
                            $finalOutput = getChannelColor($channel)."[".$channel."] §f* §f[§9".getWorldName($world)."§f] ".getFullName($name)."§f ".$msg;
                            break;
                    case "2":
                            $finalOutput = getChannelColor($channel)."[".$channel."] §f* ".getFullName($name)."§f ".$msg;
                            break;
                    case "3":
                            $finalOutput = getChannelColor($channel)."[".$channel."] §f* ".getFullName($name)."§f ".$msg;
                            break;
                    case "4":
                            $finalOutput = "§d".date("H:i:s").getChannelColor($channel)." [".$channel."] §f* §f[§9".getWorldName($world)."§f] ".getFullName($name)."§f ".$msg;
                            break;
                    case "5":
                            $finalOutput = "§d".date("H:i:s").getChannelColor($channel)." [".$channel."] §f* ".getFullName($name)."§f ".$msg;
                            break;
                    case "6":
                            $finalOutput = "§d".date("H:i:s").getChannelColor($channel)." [".$channel."] §f* ".getFullName($name)."§f ".$msg;
                            break;
            }
        }
	return $finalOutput;
}
function clearScreen(){
	echo "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
}
function sysMessage($message){
	include('includes/servers.php');
	include('includes/passwords.php');
	foreach($servers as $server){
		$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
		$JSONAPI->call('broadcast', array($message));
	}
}
// System related functions
function paginateOutput($output, $page=1, $pagelength=7){
        $start = ($page-1)*$pagelength;
	$data = explode("\n", $output);
	
}
function getArray($mysqlResult){
	if(mysql_num_rows($mysqlResult)==0){ return array(); }
	$output = array();
	while($row = mysql_fetch_array($mysqlResult)){
		foreach(array_keys($row) as $key){
			$output[count($output)][$key] = $row[$key];
		}
	}
	return $output;
}
function array_shift_multiple($array, $times=1){
	$count = 0;
	while($count<$times){
		$count = $count+1;
		array_shift($array);
	}
	return $array;
}
function centerOutput($string){
	$totalLength = strlen($string);
}
function stripColors($string){
	if(!is_string($string)){ die('String needs to be a string!'); }
	$outputString = str_replace(array('&0', '&1', '&2', '&3', '&4', '&5', '&6', '&7', '&8', '&9', '&a', '&b', '&c', '&d', '&e', '&f', '§0', '§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§a', '§b', '§c', '§d', '§e', '§f'), '', $string);
	return $outputString;
}
class Bcrypt {
	private $rounds;
	public function __construct($rounds = 12) {
		if(CRYPT_BLOWFISH != 1) { throw new Exception("bcrypt not supported in this installation. See http://php.net/crypt"); }
		$this->rounds = $rounds;
	}
	public function hash($input) {
		$hash = crypt($input, $this->getSalt());
		if(strlen($hash) > 13) {return $hash; }
		return false;
    }
	public function verify($input, $existingHash) {
		$hash = crypt($input, $existingHash);
		return $hash === $existingHash;
	}
	private function getSalt() {
    $salt = sprintf('$2a$%02d$', $this->rounds);

    $bytes = $this->getRandomBytes(16);

    $salt .= $this->encodeBytes($bytes);

    return $salt;
  }
	private $randomState;
	private function getRandomBytes($count) {
    $bytes = '';

    if(function_exists('openssl_random_pseudo_bytes') &&
        (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL slow on Win
      $bytes = openssl_random_pseudo_bytes($count);
    }

    if($bytes === '' && is_readable('/dev/urandom') &&
       ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
      $bytes = fread($hRand, $count);
      fclose($hRand);
    }

    if(strlen($bytes) < $count) {
      $bytes = '';

      if($this->randomState === null) {
        $this->randomState = microtime();
        if(function_exists('getmypid')) {
          $this->randomState .= getmypid();
        }
      }

      for($i = 0; $i < $count; $i += 16) {
        $this->randomState = md5(microtime() . $this->randomState);

        if (PHP_VERSION >= '5') {
          $bytes .= md5($this->randomState, true);
        } else {
          $bytes .= pack('H*', md5($this->randomState));
        }
      }

      $bytes = substr($bytes, 0, $count);
    }

    return $bytes;
  }
	private function encodeBytes($input) {
    // The following is code from the PHP Password Hashing Framework
    $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $output = '';
    $i = 0;
    do {
      $c1 = ord($input[$i++]);
      $output .= $itoa64[$c1 >> 2];
      $c1 = ($c1 & 0x03) << 4;
      if ($i >= 16) {
        $output .= $itoa64[$c1];
        break;
      }

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 4;
      $output .= $itoa64[$c1];
      $c1 = ($c2 & 0x0f) << 2;

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 6;
      $output .= $itoa64[$c1];
      $output .= $itoa64[$c2 & 0x3f];
    } while (1);

    return $output;
  }
}
class minecraft {

    public $account;

    private function request($website, array $parameters) {
        $request = curl_init();
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        if ($parameters != null) {
            curl_setopt($request, CURLOPT_URL, $website.'?'.http_build_query($parameters, null, '&'));
        } else {
            curl_setopt($request, CURLOPT_URL, $website);
        }
        return curl_exec($request);
        curl_close($request);
    }

    public function signin($username, $password, $version) {
        $parameters = array('user' => $username, 'password' => $password, 'version' => $version);
        $request = $this->request('https://login.minecraft.net/', $parameters);
        $response = explode(':', $request);
        if ($request != 'Old version' && $request != 'Bad login') {
            $this->account = array(
                'current_version' => $response[0],
                'correct_username' => $response[2],
                'session_token' => $response[3],
                'premium_account' => $this->is_premium($username),
                'player_skin' => $this->get_skin($username),
                'request_timestamp' => date("dmYhms", mktime(date(h), date(m), date(s), date(m), date(d), date(y)))
            );
            return true;
        } else {
            return false;
        }
    }

    public function is_premium($username) {
        $parameters = array('user' => $username);
        return $this->request('https://www.minecraft.net/haspaid.jsp', $parameters);
    }

    public function get_skin($username) {
        if ($this->is_premium($username)) {
            $headers = get_headers('http://s3.amazonaws.com/MinecraftSkins/'.$username.'.png');
            if ($headers[7] == 'Content-Type: image/png' || $headers[7] == 'Content-Type: application/octet-stream') {
                return 'https://s3.amazonaws.com/MinecraftSkins/'.$username.'.png';
            } else {
                return 'https://s3.amazonaws.com/MinecraftSkins/char.png';
            }
        } else {
            return false;
        }
    }

    public function keep_alive($username, $session) {
        $parameters = array('name' => $username, 'session' => $session);
        $request = $this->request('https://login.minecraft.net/session', $parameters);
        return null;
    }

    public function join_server($username, $session, $server) {
        $parameters = array('user' => $username, 'sessionId' => $session, 'serverId' => $server);
        $request = $this->request('http://session.minecraft.net/game/joinserver.jsp', $parameters);
        if ($request != 'Bad login') {
            return true;
        } else {
            return false;
        }
    }

    public function check_server($username, $server) {
        $parameters = array('user' => $username, 'serverId' => $server);
        $request = $this->request('http://session.minecraft.net/game/checkserver.jsp', $parameters);
        if ($request == 'YES') {
            return true;
        } else {
            return false;
        }
    }

    public function render_skin($username, $render_type, $size) {
        if (in_array($render_type, array('head', 'body'))) {
            if ($render_type == 'head') {
                header('Content-Type: image/png');
                $canvas = imagecreatetruecolor($size, $size);
                $image = imagecreatefrompng($this->get_skin($username));
                imagecopyresampled($canvas, $image, 0, 0, 8, 8, $size, $size, 8, 8);
                return imagepng($canvas);
            } else if($render_type == 'body') {
                header('Content-Type: image/png');
                $scale = $size / 16;
                $canvas = imagecreatetruecolor(16*$scale, 32*$scale);
                $image = imagecreatefrompng($this->get_skin($username));
                imagealphablending($canvas, false);
                imagesavealpha($canvas,true);
                $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                imagefilledrectangle($canvas, 0, 0, 16*$scale, 32*$scale, $transparent);
                imagecopyresized  ($canvas, $image, 4*$scale,  0*$scale,  8,   8,   8*$scale,  8*$scale,  8,  8);
                imagecopyresized  ($canvas, $image, 4*$scale,  8*$scale,  20,  20,  8*$scale,  12*$scale, 8,  12);
                imagecopyresized  ($canvas, $image, 0*$scale,  8*$scale,  44,  20,  4*$scale,  12*$scale, 4,  12);
                imagecopyresampled($canvas, $image, 12*$scale, 8*$scale,  47,  20,  4*$scale,  12*$scale, -4,  12);
                imagecopyresized  ($canvas, $image, 4*$scale,  20*$scale, 4,   20,  4*$scale,  12*$scale, 4,  12);
                imagecopyresampled($canvas, $image, 8*$scale,  20*$scale, 7,   20,  4*$scale,  12*$scale, -4,  12);
                return imagepng($canvas);
            }
        } else {
            return false;
        }
    }

}
class MinecraftQueryException extends Exception{
	// Exception thrown by MinecraftQuery class
}
class MinecraftQuery{
	/*
	 * Class written by xPaw
	 *
	 * Website: http://xpaw.ru
	 * GitHub: https://github.com/xPaw/PHP-Minecraft-Query
	 */
	
	const STATISTIC = 0x00;
	const HANDSHAKE = 0x09;
	
	private $Socket;
	private $Players;
	private $Info;
	
	public function Connect( $Ip, $Port = 25565, $Timeout = 3 )
	{
		if( $this->Socket = FSockOpen( 'udp://' . $Ip, (int)$Port ) )
		{
			Socket_Set_TimeOut( $this->Socket, $Timeout );
			
			$Challenge = $this->GetChallenge( );
			
			if( $Challenge === false )
			{
				FClose( $this->Socket );
				throw new MinecraftQueryException( "Failed to receive challenge." );
			}
			
			if( !$this->GetStatus( $Challenge ) )
			{
				FClose( $this->Socket );
				throw new MinecraftQueryException( "Failed to receive status." );
			}
			
			FClose( $this->Socket );
		}
		else
		{
			throw new MinecraftQueryException( "Can't open connection." );
		}
	}
	
	public function GetInfo( )
	{
		return isset( $this->Info ) ? $this->Info : false;
	}
	
	public function GetPlayers( )
	{
		return isset( $this->Players ) ? $this->Players : false;
	}
	
	private function GetChallenge( )
	{
		$Data = $this->WriteData( self :: HANDSHAKE );
		
		return $Data ? Pack( 'N', $Data ) : false;
	}
	
	private function GetStatus( $Challenge )
	{
		$Data = $this->WriteData( self :: STATISTIC, $Challenge . Pack( 'c*', 0x00, 0x00, 0x00, 0x00 ) );
		
		if( !$Data )
		{
			return false;
		}
		
		$Last = "";
		$Info = Array( );
		
		$Data    = SubStr( $Data, 11 ); // splitnum + 2 int
		$Data    = Explode( "\x00\x00\x01player_\x00\x00", $Data );
		$Players = SubStr( $Data[ 1 ], 0, -2 );
		$Data    = Explode( "\x00", $Data[ 0 ] );
		
		// Array with known keys in order to validate the result
		// It can happen that server sends custom strings containing bad things (who can know!)
		$Keys = Array(
			'hostname'   => 'HostName',
			'gametype'   => 'GameType',
			'version'    => 'Version',
			'plugins'    => 'Plugins',
			'map'        => 'Map',
			'numplayers' => 'Players',
			'maxplayers' => 'MaxPlayers',
			'hostport'   => 'HostPort',
			'hostip'     => 'HostIp'
		);
		
		foreach( $Data as $Key => $Value )
		{
			if( ~$Key & 1 )
			{
				if( !Array_Key_Exists( $Value, $Keys ) )
				{
					$Last = false;
					continue;
				}
				
				$Last = $Keys[ $Value ];
				$Info[ $Last ] = "";
			}
			else if( $Last != false )
			{
				$Info[ $Last ] = $Value;
			}
		}
		
		// Ints
		$Info[ 'Players' ]    = IntVal( $Info[ 'Players' ] );
		$Info[ 'MaxPlayers' ] = IntVal( $Info[ 'MaxPlayers' ] );
		$Info[ 'HostPort' ]   = IntVal( $Info[ 'HostPort' ] );
		
		// Parse "plugins", if any
		if( $Info[ 'Plugins' ] )
		{
			$Data = Explode( ": ", $Info[ 'Plugins' ], 2 );
			
			$Info[ 'RawPlugins' ] = $Info[ 'Plugins' ];
			$Info[ 'Software' ]   = $Data[ 0 ];
			
			if( Count( $Data ) == 2 )
			{
				$Info[ 'Plugins' ] = Explode( "; ", $Data[ 1 ] );
			}
		}
		else
		{
			$Info[ 'Software' ] = 'Vanilla';
		}
		
		$this->Info = $Info;
		
		if( $Players )
		{
			$this->Players = Explode( "\x00", $Players );
		}
		
		return true;
	}
	
	private function WriteData( $Command, $Append = "" )
	{
		$Command = Pack( 'c*', 0xFE, 0xFD, $Command, 0x01, 0x02, 0x03, 0x04 ) . $Append;
		$Length  = StrLen( $Command );
		
		if( $Length !== FWrite( $this->Socket, $Command, $Length ) )
		{
			return false;
		}
		
		$Data = FRead( $this->Socket, 1440 );
		
		if( StrLen( $Data ) < 5 || $Data[ 0 ] != $Command[ 2 ] )
		{
			return false;
		}
		
		return SubStr( $Data, 5 );
	}
}
class FishBans {
	public $nick;
	private $data;
	public function countBans($nick){
		$data = $this->getArray($nick);
		$numberOfBans = count($data['bans']);
		return $numberOfBans;
	}
	public function isCached($nick){
		$data = $this->getArray($nick);
		if($data['success']){ return true; }else{ return false; }
	}
	public function getBans($serviceToCheck='all'){
		$output = array();
		foreach($data['service'] as $service){
			if($service==$serviceToCheck || $service=='all'){
				$output[count($output)] = 0;
			}
		}
	}
	private function getArray($nick){
		// Fetches the JSON from FishBans, then converts it to an array and outputs it for use in the other functions.
		$webLocation = "http://www.fishbans.com/api/bans/".$nick."/";
		$fileHandle = fopen($webLocation, 'r');
		$webOutput = fread($fileHandle, 1000000);
		$data = json_decode($webOutput, true);
		return $data;
	}
}
class ChannelHandle {
	/*
	 * Class written by AgentKid
	 *
	 * This class is the channel interface class, providing an interface for all of the chat channel systems.
	 * 
	 */
	public $nick;
	public $currentChannel;
	public $channelsIn;
	public $style;
	public function __construct($nick){
		include('includes/mysql.php');
		$query = mysql_fetch_array(mysql_query("SELECT * FROM ChannelsIn WHERE name='$nick' AND type='1'"));
		$this->nick = $nick;
		if($query==false){
			mysql_query("INSERT INTO ChannelsIn (id, name, channel, type) VALUES ('NULL', '$name', 'A', '1')");
			$query['channel'] = 'A';
		}
		$this->currentChannel = $query['channel'];
		$query2 = mysql_query("SELECT * FROM ChannelsIn WHERE name='$nick' AND type='2'") or die(mysql_error());
		$channelsIn = array();
		while($row2 = mysql_fetch_array($query2)){
			array_push($channelsIn, $row2['channel']);
		}
		$query3 = mysql_fetch_array(mysql_query("SELECT * FROM ChannelStyles WHERE name='$nick'"));
		if($query3==false){
			mysql_query("INSERT INTO ChannelStyles (id, name, style) VALUES ('NULL', '$nick', '1')") or die(mysql_error());
			$style = 1;
		}else{
			$style = $query3['style'];
		}
		$this->style = $style;
		$this->channelsIn = $channelsIn;
	}
	public function isMute($channel=false){
		include('includes/mysql.php');
                $nick = $this->nick;
		if($channel!=false){
			$channel = channel($channel);
			if($channel==false){ die('Invalid channel!'); }
			$query = mysql_query("SELECT * FROM Mutes WHERE name='$nick' AND channel='$channel'");
			$row = mysql_fetch_array($query);
			if($row!=false || $this->isMute()){ return true; }else{ return false; }
		}else{
			$query = mysql_query("SELECT * FROM GMutes WHERE name='$nick'");
			$row = mysql_fetch_array($query);
			if($row!=false){ return true; }else{ return false; }
		}
	}
	public function isInChannel($channel){
		$channel = channel($channel);
		if(in_array($channel, $this->channelsIn) || $channel==$this->currentChannel){ return true; }else{ return false; }
	}
	public function getChannelMembers($channel){
		include('includes/mysql.php');
		$channel = channel($channel);
		if($channel==false){ die('Invalid Channel!'); }
		$query = mysql_query("SELECT * FROM ChannelsIn WHERE channel='$channel'");
		$onlinePlayers = getAllPlayers();
		$inThisRoom = array();
		while($row = mysql_fetch_array($query)){
			// If the player specified in this row is online, push their full name into the $inThisRoom array.
			if(in_array($row['name'], $onlinePlayers)){ array_push($inThisRoom, $row['name']); }
		}
		return $inThisRoom;
	}
	public function getChannelsIn(){
		return $this->channelsIn;
	}
	public function varDump(){
		if(!isStaff($this->nick)){ die('This is a debug function and you are not staff.'); }
		var_dump($this->nick);
		var_dump($this->currentChannel);
		var_dump($this->channelsIn);
                var_dump($this->isMute());
                var_dump(getAllPlayers());
	}
        public function setStyle($style){
            include('includes/mysql.php');
            if($style==$this->style){ die('§cYou are already using style '.$this->style.'!'); }
            if(!in_array($style, range(1, 6))){ die('§cInvalid style!'); }
            mysql_query("UPDATE ChannelStyles SET style='$style' WHERE name='$this->nick'");
            echo "§aYou set your style to $style!";
        }
	public function joinChannel($channel){
		$name = $this->nick;
		$channel = channel($channel);
		if($channel==false){ die('Invalid channel!'); }
		if($this->currentChannel==$channel){ die('You are already focused on this channel!'); }
		include('includes/mysql.php');
		if(!$this->isInChannel($channel)){
			mysql_query("INSERT INTO ChannelsIn (id, name, channel, type) VALUES ('NULL', '$name', '$channel', '2')");
			array_push($this->channelsIn, $channel);
			echo "§aYou joined the ".getColoredChannel($channel)." §achannel!\n";
		}
		mysql_query("UPDATE ChannelsIn SET type='2' WHERE name='$name'");
		mysql_query("UPDATE ChannelsIn SET type='1' WHERE name='$name' AND channel='$channel'");
		$this->currentChannel=$channel;
		echo "§aYou set focus on the ".getColoredChannel($channel)." §achannel!\n";
	}
	public function leaveChannel($channel=null){
		$name = $this->nick;
		if($channel!=null){
			$channel = channel($channel);
			if($channel==false){ die("§cInvalid channel! Usage: §a/ch leave [channel]\n"); }
		}else{
			$channel = $this->currentChannel;
		}
		// So we have the channel name, lets work with it.
		if(!$this->isInChannel($channel)){ die("§cYou are not in the ".getColoredChannel($channel)." §cchannel!\n"); }
		if(count($this->currentChannels==0)){ die("§cYou cannot leave the only channel you're in! Join another first."); }
		mysql_query("DELETE FROM ChannelsIn WHERE name='$name' AND channel='$channel'");
		echo "§aYou left the ".getColoredChannel($channel)." §achannel.";
		$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='1'");
		$row = mysql_fetch_array($row);
		if($row==false){
			$query = mysql_query("SELECT * FROM ChannelsIn WHERE name='$name' AND type='2'");
			$row = mysql_fetch_array($query);
			$newChannel = $row['channel'];
			mysql_query("UPDATE ChannelsIn SET type='1' WHERE name='$name' AND channel='$newChannel'");
			echo "§aYou set focus on the ".getColoredChannel($newChannel)." §achannel.\n";
		}
	}
}
class JSONAPI {
	public $host;
	public $port;
	public $salt;
	public $username;
	public $password;
	private $urlFormats = array(
		"call" => "http://%s:%s/api/call?method=%s&args=%s&key=%s",
		"callMultiple" => "http://%s:%s/api/call-multiple?method=%s&args=%s&key=%s"
	);
	
	/**
	 * Creates a new JSONAPI instance.
	 */
	public function __construct ($host, $port, $uname, $pword, $salt) {
		$this->host = $host;
		$this->port = $port;
		$this->username = $uname;
		$this->password = $pword;
		$this->salt = $salt;
		
		if(!extension_loaded("cURL")) {
			throw new Exception("JSONAPI requires cURL extension in order to work.");
		}
	}
	
	/**
	 * Generates the proper SHA256 based key from the given method suitable for use as the key GET parameter in a JSONAPI API call.
	 * 
	 * @param string $method The name of the JSONAPI API method to generate the key for.
	 * @return string The SHA256 key suitable for use as the key GET parameter in a JSONAPI API call.
	 */
	public function createKey($method) {
		if(is_array($method)) {
			$method = json_encode($method);
		}
		return hash('sha256', $this->username . $method . $this->password . $this->salt);
	}
	
	/**
	 * Generates the proper URL for a standard API call the given method and arguments.
	 * 
	 * @param string $method The name of the JSONAPI API method to generate the URL for.
	 * @param array $args An array of arguments that are to be passed in the URL.
	 * @return string A proper standard JSONAPI API call URL. Example: "http://localhost:20059/api/call?method=methodName&args=jsonEncodedArgsArray&key=validKey".
	 */
	public function makeURL($method, array $args) {
		return sprintf($this->urlFormats["call"], $this->host, $this->port, rawurlencode($method), rawurlencode(json_encode($args)), $this->createKey($method));
	}
	
	/**
	 * Generates the proper URL for a multiple API call the given method and arguments.
	 * 
	 * @param array $methods An array of strings, where each string is the name of the JSONAPI API method to generate the URL for.
	 * @param array $args An array of arrays, where each array contains the arguments that are to be passed in the URL.
	 * @return string A proper multiple JSONAPI API call URL. Example: "http://localhost:20059/api/call-multiple?method=[methodName,methodName2]&args=jsonEncodedArrayOfArgsArrays&key=validKey".
	 */
	public function makeURLMultiple(array $methods, array $args) {
		return sprintf($this->urlFormats["callMultiple"], $this->host, $this->port, rawurlencode(json_encode($methods)), rawurlencode(json_encode($args)), $this->createKey($methods));
	}
	
	/**
	 * Calls the single given JSONAPI API method with the given args.
	 * 
	 * @param string $method The name of the JSONAPI API method to call.
	 * @param array $args An array of arguments that are to be passed.
	 * @return array An associative array representing the JSON that was returned.
	 */
	public function call($method, array $args = array()) {
		if(is_array($method)) {
			return $this->callMultiple($method, $args);
		}
		
		$url = $this->makeURL($method, $args);

		return json_decode($this->curl($url), true);
	}
	
	private function curl($url) {
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_PORT, $this->port);
		curl_setopt($c, CURLOPT_HEADER, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_TIMEOUT, 10);		
		$result = curl_exec($c);
		curl_close($c);
		return $result;
	}
	
	/**
	 * Calls the given JSONAPI API methods with the given args.
	 * 
	 * @param array $methods An array strings, where each string is the name of a JSONAPI API method to call.
	 * @param array $args An array of arrays of arguments that are to be passed.
	 * @throws Exception When the length of the $methods array and the $args array are different, an exception is thrown.
	 * @return array An array of associative arrays representing the JSON that was returned.
	 */
	public function callMultiple(array $methods, array $args = array()) {
		if(count($methods) !== count($args)) {
			throw new Exception("The length of the arrays \$methods and \$args are different! You need an array of arguments for each method!");
		}
		
		$url = $this->makeURLMultiple($methods, $args);

		return json_decode($this->curl($url), true);
	}
}
class MCFunctions {
	public $player;
	public $playerData;
	public function __construct($player){
		$this->player = $player;
	}
	private function compass($degrees){
		$rounded = round($degrees/45);
		switch($rounded){
			case 0:
				$direction = "East";
				break;
			case 1:
				$direction = "Northeast";
				break;
			case 2:
				$direction = "North";
				break;
			case 3:
				$direction = "Northwest";
				break;
			case 4:
				$direction = "West";
				break;
			case 5:
				$direction = "Southwest";
				break;
			case 6:
				$direction = "South";
				break;
			case 7:
				$direction = "Southeast";
				break;
			case 8:
				$direction = "East";
				break;
			default:
				$direction = "Unknown Direction";
				break;
		}
		return $direction;
	}
	private function getAngle($X1, $Z1, $X2, $Z2){
		
	}
	public function getDist($X1, $Z1, $X2, $Z2, $precision=2){
		$distprecise = sqrt(pow(($X2-$X1), 2)+pow(($Z2-$Z1), 2));
		$dist = round($distprecise, $precision);
		return $dist;
	}
	public function respawnCoords($P, $X, $Z, $W='Araeosia'){
		$RespawnCoords = array(
			'Araeos City' => array( 'X' => -212.5, 'Y' => 73, 'Z' => -183.5, 'name' => 'Araeos City', 'world' => 'Araeosia' ),
			'Everstone City' => array( 'X' => 486.5, 'Y' => 68, 'Z' => -125.5, 'name' => 'Everstone City', 'world' => 'Araeosia' ),
			'Crystalton' => array( 'X' => -962.5, 'Y' => 73, 'Z' => 989.5, 'name' => 'Crystalton', 'world' => 'Araeosia' ),
			'Darmouth' => array( 'X' => -234.5, 'Y' => 70, 'Z' => 213.5, 'name' => 'Darmouth', 'world' => 'Araeosia' ),
			'Talltree Point' => array( 'X' => -260.5, 'Y' => 76, 'Z' => 677.5, 'name' => 'Talltree Point', 'world' => 'Araeosia' ),
			'Strongport' => array( 'X' => 729.5, 'Y' => 68, 'Z' => 700.5, 'name' => 'Strongport', 'world' => 'Araeosia' ),
			'Coalmoor' => array( 'X' => 242.5, 'Y' => 74, 'Z' => -899.5, 'name' => 'Coalmoor', 'world' => 'Araeosia' ),
			'Westcliff Plains Village' => array( 'X' => -636.5, 'Y' => 74, 'Z' => -167.5, 'name' => 'Westcliff Plains Village', 'world' => 'Araeosia' ),
			'Fivepiece Island' => array( 'X' => 454, 'Y' => 73, 'Z' => -723, 'name' => 'Fivepiece Island', 'world' => 'Araeosia' ),
			'Cle Elum' => array( 'X' => 262, 'Y' => 74, 'Z' => 211, 'name' => 'Cle Elum', 'world' => 'Araeosia' ),
			'The Bridge' => array( 'X' => 770, 'Y' => 78, 'Z' => -21, 'name' => 'The Bridge', 'world' => 'Araeosia' ));
		switch($W){
			case "Araeosia":
				$mins = array();
				foreach($RespawnCoords as $RespawnCoord){
					$min = $this->getdist($X, $Z, $RespawnCoord['X'], $RespawnCoord['Z'], 0);
					array_push($mins, $min);
					$minnames[$min]=$RespawnCoord['name'];
				}
				$RespawnArray = $RespawnCoords[$minnames[min($mins)]];
				$RespawnArray['dist'] = min($mins);
				break;
			case "Araeosia_tutorial2":
				$RespawnArray = array('X'=>-300.5,'Y'=>69,'Z'=>-52.5,'name'=>'The Tutorial','world'=>'Araeosia_tutorial2');
				break;
			case "Araeosia_instance":
				$quest = $this->getquest($P);
				$RespawnArray = $quest['RespawnArray'];
				break;
		}
		return $RespawnArray;
	}
	public function getLoc($P, $X, $Z, $W='Araeosia'){
		switch($W){
			case "Araeosia_tutorial2":
				die("§cYou are currently in §bThe Tutorial§c.\n");
				break;
			case "Araeosia_instance":
				$quest = $this->getquest($P);
				$quest = $quest['RespawnArray'];
				die("§cYou are currently in §b".$quest['name']."§c.\n");
				break;
			case "Araeosia":
				$city = $this->respawncoords($P, $X, $Z, $W);
				$city = $city['RespawnArray'];
				$direction = compass(getangle($X, $Z, $city['X'], $city['Z'], 0));
				die("§cThe closest city is §b".$city['name']."§c.\n§aIt is roughly ".$city['dist']." meters ".$direction." of you.\n");
				break;
		}
	}
	public function tpPlayer($X, $Y, $Z, $W){
		
	}
	public function msgPlayer($msg){
		
	}
	public function getQuest(){
		include('includes/mysql.php');
		$quest = mysql_query("SELECT * FROM permission WHERE permission LIKE quest.current.%.%.%");
		$quest = $quest['permission'];
		$questdata = array(
		"quest.current.dungeon.5.1" => array(
			"RespawnArray" => array( 'X' => -0.5, 'Y' => 64, 'Z' => 42.5, 'name' => 'The Dungeon', 'world' => 'Araeosia_instance' ),
			"Giver" => "Adventurer Finn?",
			"Name" => "The Dungeon, Part 5",
			"Part" => 5,
		),
		"quest.current.archeologist.4.1" => array(
			"RespawnArray" => array( 'X' => -314.5, 'Y' => 64, 'Z' => -59.5, 'name' => 'The Ruins', 'world' => 'Araeosia_instance' ),
			"Giver" => "The Archeologist?",
			"Name" => "The Archeologist, Part 4",
			"Part" => 4
		)
		);
		if($quest!=false){
			return $questdata[$quest];
		}else{
			return null;
		}
	}
	public function getActiveRespawnLocs($world){
		include('includes/mysql.php');
	}
}
class NBT {
/**
 * Class for reading in NBT-format files.
 * 
 * @author  Justin Martin <frozenfire@thefrozenfire.com>
 * @version 1.0
 *
 * Dependencies:
 *  PHP 4.3+ (5.3+ recommended)
 *  GMP Extension
 */
	public $root = array();
	
	public $verbose = false;
	
	const TAG_END = 0;
	const TAG_BYTE = 1;
	const TAG_SHORT = 2;
	const TAG_INT = 3;
	const TAG_LONG = 4;
	const TAG_FLOAT = 5;
	const TAG_DOUBLE = 6;
	const TAG_BYTE_ARRAY = 7;
	const TAG_STRING = 8;
	const TAG_LIST = 9;
	const TAG_COMPOUND = 10;
	
	public function loadFile($filename, $wrapper = "compress.zlib://") {
		if(is_string($wrapper) && is_file($filename)) {
			if($this->verbose) trigger_error("Loading file \"{$filename}\" with stream wrapper \"{$wrapper}\".", E_USER_NOTICE);
			$fp = fopen("{$wrapper}{$filename}", "rb");
		} elseif(is_null($wrapper) && is_resource($filename)) {
			if($this->verbose) trigger_error("Loading file from existing resource.", E_USER_NOTICE);
			$fp = $filename;
		} else {
			trigger_error("First parameter must be a filename or a resource.", E_USER_WARNING);
			return false;
		}
		if($this->verbose) trigger_error("Traversing first tag in file.", E_USER_NOTICE);
		$this->traverseTag($fp, $this->root);
		if($this->verbose) trigger_error("Encountered end tag for first tag; finished.", E_USER_NOTICE);
		return end($this->root);
	}
	
	public function writeFile($filename, $wrapper = "compress.zlib://") {
		if(is_string($wrapper)) {
			if($this->verbose) trigger_error("Writing file \"{$filename}\" with stream wrapper \"{$wrapper}\".", E_USER_NOTICE);
			$fp = fopen("{$wrapper}{$filename}", "wb");
		} elseif(is_null($wrapper) && is_resource($fp)) {
			if($this->verbose) trigger_error("Writing file to existing resource.", E_USER_NOTICE);
			$fp = $filename;
		} else {
			trigger_error("First parameter must be a filename or a resource.", E_USER_WARNING);
			return false;
		}
		if($this->verbose) trigger_error("Writing ".count($this->root)." root tag(s) to file/resource.", E_USER_NOTICE);
		foreach($this->root as $rootNum => $rootTag) if(!$this->writeTag($fp, $rootTag)) trigger_error("Failed to write root tag #{$rootNum} to file/resource.", E_USER_WARNING);
		return true;
	}
	
	public function purge() {
		if($this->verbose) trigger_error("Purging all loaded data", E_USER_ERROR);
		$this->root = array();
	}
	
	public function traverseTag($fp, &$tree) {
		if(feof($fp)) {
			if($this->verbose) trigger_error("Reached end of file/resource.", E_USER_NOTICE);
			return false;
		}
		$tagType = $this->readType($fp, self::TAG_BYTE); // Read type byte.
		if($tagType == self::TAG_END) {
			return false;
		} else {
			if($this->verbose) $position = ftell($fp);
			$tagName = $this->readType($fp, self::TAG_STRING);
			if($this->verbose) trigger_error("Reading tag \"{$tagName}\" at offset {$position}.", E_USER_NOTICE);
			$tagData = $this->readType($fp, $tagType);
			$tree[] = array("type"=>$tagType, "name"=>$tagName, "value"=>$tagData);
			return true;
		}
	}
	
	public function writeTag($fp, $tag) {
		if($this->verbose) {
			$position = ftell($fp);
			trigger_error("Writing tag \"{$tag["name"]}\" of type {$tag["type"]} at offset {$position}.", E_USER_NOTICE);
		}
		return $this->writeType($fp, self::TAG_BYTE, $tag["type"]) && $this->writeType($fp, self::TAG_STRING, $tag["name"]) && $this->writeType($fp, $tag["type"], $tag["value"]);
	}
	
	public function readType($fp, $tagType) {
		switch($tagType) {
			case self::TAG_BYTE: // Signed byte (8 bit)
				list(,$unpacked) = unpack("c", fread($fp, 1));
				return $unpacked;
			case self::TAG_SHORT: // Signed short (16 bit, big endian)
				list(,$unpacked) = unpack("n", fread($fp, 2));
				if($unpacked >= pow(2, 15)) $unpacked -= pow(2, 16); // Convert unsigned short to signed short.
				return $unpacked;
			case self::TAG_INT: // Signed integer (32 bit, big endian)
				list(,$unpacked) = unpack("N", fread($fp, 4));
				if($unpacked >= pow(2, 31)) $unpacked -= pow(2, 32); // Convert unsigned int to signed int
				return $unpacked;
			case self::TAG_LONG: // Signed long (64 bit, big endian)
				extension_loaded("gmp") or trigger_error (
					"This file contains a 64-bit number and execution cannot continue. ".
					"Please install the GMP extension for 64-bit number handling.", E_USER_ERROR
				);
				list(,$firstHalf) = unpack("N", fread($fp, 4));
				list(,$secondHalf) = unpack("N", fread($fp, 4));
				$value = gmp_add($secondHalf, gmp_mul($firstHalf, "4294967296"));
				if(gmp_cmp($value, gmp_pow(2, 63)) >= 0) $value = gmp_sub($value, gmp_pow(2, 64));
				return gmp_strval($value);
			case self::TAG_FLOAT: // Floating point value (32 bit, big endian, IEEE 754-2008)
				list(,$value) = (pack('d', 1) == "\77\360\0\0\0\0\0\0")?unpack('f', fread($fp, 4)):unpack('f', strrev(fread($fp, 4)));
				return $value;
			case self::TAG_DOUBLE: // Double value (64 bit, big endian, IEEE 754-2008)
				list(,$value) = (pack('d', 1) == "\77\360\0\0\0\0\0\0")?unpack('d', fread($fp, 8)):unpack('d', strrev(fread($fp, 8)));
				return $value;
			case self::TAG_BYTE_ARRAY: // Byte array
				$arrayLength = $this->readType($fp, self::TAG_INT);
				$array = array();
				for($i = 0; $i < $arrayLength; $i++) $array[] = $this->readType($fp, self::TAG_BYTE);
				return $array;
			case self::TAG_STRING: // String
				if(!$stringLength = $this->readType($fp, self::TAG_SHORT)) return "";
				$string = utf8_decode(fread($fp, $stringLength)); // Read in number of bytes specified by string length, and decode from utf8.
				return $string;
			case self::TAG_LIST: // List
				$tagID = $this->readType($fp, self::TAG_BYTE);
				$listLength = $this->readType($fp, self::TAG_INT);
				if($this->verbose) trigger_error("Reading in list of {$listLength} tags of type {$tagID}.", E_USER_NOTICE);
				$list = array("type"=>$tagID, "value"=>array());
				for($i = 0; $i < $listLength; $i++) {
					if(feof($fp)) break;
					$list["value"][] = $this->readType($fp, $tagID);
				}
				return $list;
			case self::TAG_COMPOUND: // Compound
				$tree = array();
				while($this->traverseTag($fp, $tree));
				return $tree;
		}
	}
	
	public function writeType($fp, $tagType, $value) {
		switch($tagType) {
			case self::TAG_BYTE: // Signed byte (8 bit)
				return is_int(fwrite($fp, pack("c", $value)));
			case self::TAG_SHORT: // Signed short (16 bit, big endian)
				if($value < 0) $value += pow(2, 16); // Convert signed short to unsigned short
				return is_int(fwrite($fp, pack("n", $value)));
			case self::TAG_INT: // Signed integer (32 bit, big endian)
				if($value < 0) $value += pow(2, 32); // Convert signed int to unsigned int
				return is_int(fwrite($fp, pack("N", $value)));
			case self::TAG_LONG: // Signed long (64 bit, big endian)
				extension_loaded("gmp") or trigger_error (
					"This file contains a 64-bit number and execution cannot continue. ".
					"Please install the GMP extension for 64-bit number handling.", E_USER_ERROR
				);
				$secondHalf = gmp_mod($value, 2147483647);
				$firstHalf = gmp_sub($value, $secondHalf);
				return is_int(fwrite($fp, pack("N", gmp_intval($firstHalf)))) && is_int(fwrite($fp, pack("N", gmp_intval($secondHalf))));
			case self::TAG_FLOAT: // Floating point value (32 bit, big endian, IEEE 754-2008)
				return is_int(fwrite($fp, (pack('d', 1) == "\77\360\0\0\0\0\0\0")?pack('f', $value):strrev(pack('f', $value))));
			case self::TAG_DOUBLE: // Double value (64 bit, big endian, IEEE 754-2008)
				return is_int(fwrite($fp, (pack('d', 1) == "\77\360\0\0\0\0\0\0")?pack('d', $value):strrev(pack('d', $value))));
			case self::TAG_BYTE_ARRAY: // Byte array
				return $this->writeType($fp, self::TAG_INT, count($value)) && is_int(fwrite($fp, call_user_func_array("pack", array_merge(array("c".count($value)), $value))));
			case self::TAG_STRING: // String
				$value = utf8_encode($value);
				return $this->writeType($fp, self::TAG_SHORT, strlen($value)) && is_int(fwrite($fp, $value));
			case self::TAG_LIST: // List
				if($this->verbose) trigger_error("Writing list of ".count($value["value"])." tags of type {$value["type"]}.", E_USER_NOTICE);
				if(!($this->writeType($fp, self::TAG_BYTE, $value["type"]) && $this->writeType($fp, self::TAG_INT, count($value["value"])))) return false;
				foreach($value["value"] as $listItem) if(!$this->writeType($fp, $value["type"], $listItem)) return false;
				return true;
			case self::TAG_COMPOUND: // Compound
				foreach($value as $listItem) if(!$this->writeTag($fp, $listItem)) return false;
				if(!is_int(fwrite($fp, "\0"))) return false;
				return true;
		}
	}
}
class Logger{
    private $logFileHandle;
    function __construct($logtype){
        $logFileHandle = fopen('/home/agentkid/logs/'.$logtype.'.log', 'a');
        $this->logFileHandle = $logFileHandle;
    }
    function __destruct(){
        fclose($this->logFileHandle);
    }
    public function addLog($logData){
        $input = $this->getTimestamp()." ".$this->cleanColors($logData)."\n";
        fwrite($this->logFileHandle, $input);
    }
    private function cleanColors($data){
        return str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $data);
    }
    private function getTimestamp(){
        return "[".date('m-d-y H:i:s', time())."]";
    }
}
?>