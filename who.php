<?php
// This file handles the /who command used on all Araeosia servers.
// Fetch variables
$name=$_POST['player'];
$output = null;
include('includes/functions.php');
include('includes/servers.php');
include('includes/staff.php');
foreach($servers as $server){
	$Query = new MinecraftQuery();
	try{
		$Query->Connect( $ips[$server], $ports['mc'][$server], 1 );
		$players = $Query->GetPlayers();
	}catch(MinecraftQueryException $e){
		// Only reason this query would fail is if the server is down. Report it
		$players = array();
	}
	// Now we have a populated (or empty) array of players, lets handle it.
		$playersfinal = array();
		foreach($players as $player){
			if(in_array($player,$staffranks['admin'])){$player="§4".$player;}elseif(in_array($player,$staffranks['moderator'])){$player = "§a".$player;}else{$player = "§b".$player; }
			array_push($playersfinal, $player);
		}
		$output = $output."§c------- §bAraeosia ".$server." §c-------\n";
	if(count($playersfinal)!=0 && $playersfinal!=false){
		$output = $output."§aOnline§f: ".implode('§f, ', $playersfinal)."\n";
	}else{
		$output = $output."No online players!\n";
	}
}
if($name=='console'){$output = str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $output);}
echo $output;
?>
