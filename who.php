<?php
// Fetch variables
$name=$_POST[player];
include('includes/functions.php');
$Query = new MinecraftQuery();
try{
	
}catch(MinecraftQueryException $e){
	
}
foreach($online as $server){
	echo "§c------- §b".$server." §c-------";
	echo implode(', ', $online[$server]);
}
?>
