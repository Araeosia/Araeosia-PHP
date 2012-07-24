<?php
$name = $_POST['player'];
$server = $_GET['s'];
$args = $_POST['args'];
$lookUp = $args[1];

include('includes/staff.php');
include('includes/mysql.php');
include('includes/functions.php');
serverCheck($server, true);
if(!in_array(strtolower($name), $staff)){ die('§cYou do not have permission to perform this command!'); }
// Fishbans! http://fishbans.com/
$webLocation = "http://www.fishbans.com/api/bans/".$lookUp."/";
$fileHandle = fopen($webLocation, 'r');
$webOutput = fread($fileHandle, 1000000);
$jsonDecoded = json_decode($webOutput, true);
// Nice and simple.
echo "§aLooking up §b".$lookUp."§a...\n";
if(!$jsonDecoded['success']){ die('§c'.$jsonDecoded['error'].'!'); }
$services = array_keys($jsonDecoded['bans']['service']);
foreach($services as $service){
	if($jsonDecoded['bans']['service'][$service]['bans']>0){
		echo "§a-------- ".$service." --------\n";
		$bankeys = array_keys($jsonDecoded['bans']['service'][$service]['ban_info']);
		foreach($bankeys as $bankey){
			echo "§b".trim($bankey)." §f- §4".trim($jsonDecoded['bans']['service'][$service]['ban_info'][$bankey])."\n";
		}
	}else{
		echo "§b".$lookUp."§a has no bans on record from §b".$service."!\n";
	}
}
?>