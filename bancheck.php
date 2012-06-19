<?php
$name = $_POST['player'];
$server = $_GET['s'];
$args = $_POST['args'];
$lookUp = $args[1];

include('includes/staff.php');
include('includes/mysql.php');
include('includes/functions.php');

// Fishbans! http://fishbans.com/
$webLocation = "http://www.fishbans.com/api/bans/".$lookUp."/";
$fileHandle = fopen($webLocation, 'r');
$webOutput = fread($fileHandle, 1000000);
$jsonDecoded = json_decode($webOutput, true);
// Nice and simple.
if(!$jsonDecoded['success']){ die('§c'.$jsonDecoded['error'].'!'); }
$services = array_keys($jsonDecoded['bans']['service']);
foreach($services as $service){
	if($jsonDecoded['bans']['service'][$service]['bans']>0){
		echo "§a-------- ".$service." --------\n";
		$bankeys = array_keys($jsonDecoded['bans']['service'][$service]['ban_info']);
		foreach($bankeys as $bankey){
			echo "§b".trim($bankey)." §f- §4".trim($jsonDecoded['bans']['service'][$service]['ban_info'][$bankey])."\n";
		}
	}
}
?>