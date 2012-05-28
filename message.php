<?php
// Define variables
$servers = array('RPG', 'Free');
$ips = array( 'RPG' => '192.168.5.106', 'Free' => '192.168.5.104');
$ports = array( 'RPG' => 25566, 'Free' => 25565);
// Connect to MySQL
include('includes/mysql.php');
// Check to see if the player has opted-out
$query = mysql_query("SELECT * FROM optouts");
$optouts = array();
while($row = mysql_fetch_array($query)){
	array_push($optouts, $row[name]);
}
// Query each server
$players = array();
include('classes.php');
foreach($servers as $server){
	try{
		$Query = new MinecraftQuery();
		$Query->Connect($ips[$server], $ports[$server], 1);
		$players[$server] = $Query->GetPlayers();
	} catch (MinecraftQueryException $e){
		$players[$server] = array();
	}
	foreach($players[$server] as $player){
		if(!in_array($player, $optouts)){
			$cmd = "msg ".$player." ".$msgs[rand(0,(count($msgs)-1))];
			// Send the message, but I have to invent a way to do that. Still working on that :P
		}
	}
}
?>