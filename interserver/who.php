<?php
// This file handles the /who command used on all Araeosia servers.
// Fetch variables
$name=$_POST['player'];
$output = null;
include('includes/functions.php');
include('includes/servers.php');
include('includes/staff.php');
foreach($servers as $server){
	$players = getOnlinePlayers($server);
	$playersfinal = array();
	// Now we have a populated (or empty) array of players, lets handle it.
	foreach($players as $player){ array_push($playersfinal, getFullName($player)); }
	$output = $output."§c------- §bAraeosia ".$server." §c-------\n";
	if(count($playersfinal)!=0){
		$output = $output."§aOnline§f: ".implode('§f, ', $playersfinal)."\n";
	}else{
		$output = $output."No online players!\n";
	}
}
if($name=='console'){$output = str_replace(array('§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§0', '§a', '§b', '§c', '§d', '§e', '§f'), '', $output);}
echo $output;
?>
