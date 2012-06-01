<?php
// Fetch variables
$name=$_POST[player];
include('includes/functions.php');
include('includes/servers.php');
foreach($servers as $server){
	$Query = new MinecraftQuery();
	try{
		$Query->Connect( $ips[$server], $ports[$server], 1 );
		$players = $Query->GetPlayers();
	}catch(MinecraftQueryException $e){
		// Only reason this query would fail is if the server is down. Report it
		$players = array();
	}
	// Now we have a populated (or empty) array of players, lets handle it.
		echo "§c------- §b".$server." §c-------\n";
	if(count($players)!=0 && $players!=false){
		echo implode(', ', $players)."\n";
	}else{
		echo "No online players!";
	}
}
?>
