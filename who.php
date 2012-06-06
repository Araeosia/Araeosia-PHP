<?php
// Fetch variables
$name=$_POST[player];
include('includes/functions.php');
include('includes/servers.php');
include('includes/staff.php');
foreach($servers as $server){
	$Query = new MinecraftQuery();
	try{
		$Query->Connect( $ips[$server], $ports[mc][$server], 1 );
		$players = $Query->GetPlayers();
	}catch(MinecraftQueryException $e){
		// Only reason this query would fail is if the server is down. Report it
		$players = array();
	}
	// Now we have a populated (or empty) array of players, lets handle it.
		$playersfinal = array();
		foreach($players as $player){
			if(in_array($player,$staffranks[admin])){$player="§4".$player;}elseif(in_array($player,$staffranks[moderator])){$player = "§a".$player;}else{$player = "§b".$player; }
			array_push($playersfinal, $player);
		}
		echo "§c------- §b".$server." §c-------\n";
	if(count($playersfinal)!=0 && $playersfinal!=false){
		echo "§aOnline§f: ".implode('§f, ', $playersfinal)."\n";
	}else{
		echo "No online players!\n";
	}
}
?>
