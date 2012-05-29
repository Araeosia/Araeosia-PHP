<?php
$name = $_POST[player];
$playerX = $_POST[playerX];
$playerY = $_POST[playerY];
$playerZ = $_POST[playerZ];
$playerWorld = $_POST[playerWorld];
include('includes/mysql.php');
if(strpos($playerWorld, "dungeon")!=false){ $type = "dungeon"; }
if(strpos($playerWorld, "tutorial")!=false){ $type = "tutorial"; }
// Fetch the current respawn points
$possible = array(
	"Araeosia2A" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array(),
		"world" => array(),
		"name" => array()
	),
	"Araeosia2B" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
		"world" => array(),
		"name" => array()
	),
	"Araeosia2C" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
		"world" => array(),
		"name" => array()
	),
	"Araeosia2D" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
		"world" => array(),
		"name" => array()
	),
	"Araeosia2E" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
		"world" => array(),
		"name" => array()
	),
	"Araeosia2F" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
		"world" => array(),
		"name" => array()
	),
	"Araeosia2G" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array() 
		"world" => array(),
		"name" => array()
	),
	"Araeosia2H" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
		"world" => array(),
		"name" => array()
	),
	"Araeosia2I" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
		"world" => array(),
		"name" => array()
	)
$keys = array_keys($possible[$playerWorld]);
$active = mysql_query("SELECT * FROM Respawns WHERE world='$playerWorld'");
$active = mysql_fetch_array($active);
$active = $active[active];
$active = explode(',', $active);
// Figure out what world
if(!isset($type)){
	// Regular respawns
	$array = $possible[$playerWorld];
	foreach($keys as $key){
		if(in_array($key, $active)){ $dist[$key] = sqrt( (pow($playerX-$array[$key]["X"]), 2)+(pow($playerZ-$array[$key]["Z"]), 2)); }
	}
	$min = min($dist);
	$flipped = array_flip($min);
	$loc = $flipped[$min];
	$location = array(
		"X" => $array[X][$loc],
		"Y" => $array[Y][$loc],
		"Z" => $array[Z][$loc],
		"world" => $array[world][$loc],
		"name" => $array[name][$loc]
	);
}elseif($type=="dungeon"){
	$questquery = mysql_query("SELECT * FROM Quests WHERE name='$name'");
	$questrow = mysql_fetch_array($questquery);
	$quest = $questrow[quest];
	switch($quest){
// Example quest
		case "Questnamehere":
			$location = array(
				"X" => 234,
				"Y" => 64,
				"Z" => 432,
				"world" => "Araeosia2C_dungeon",
				"name" => "The Dungeon"
		default:
			echo "Cannot find your quest! Aborting and teleporting you to the primary spawn point.\nPlease inform an administrator of this error: QUEST_DEFAULT_DUNGEON";
			echo "/Command/ExecuteBukkitCommand:mvspawn ".$name.";\n";
			break;
	}
}elseif($type=="tutorial"){
	switch($playerWorld){
		case "Araeosia2A_tutorial":
			$location = array(
				"X" => 555,
				"Y" => 64,
				"Z" => 666,
				"world" => "Araeosia2A_tutorial",
				"name" => "The Tutorial"
			);
			break;
		default:
			echo "Cannot find what world to teleport you to! Aborting and teleporting you to the spawn point.\nPlease inform an administrator of this error: WORLD_DEFAULT_TUTORIAL";
			echo "/Command/ExecuteBukkitCommand:mvspawn ".$name.";\n";
			break;
	}
}
$money = mysql_query("SELECT * FROM iConomy WHERE username='$name'");
$money = mysql_fetch_array($money);
$money = $money[balance];
$rand = rand(7, 13)*.01;
$lost = ceil($rand*$money);
$left = $money-$lost;
mysql_query("UPDATE iConomy SET balance='$left' WHERE username='$name'");
if(isset($location)){
	echo "/Command/ExecuteBukkitCommand:mvtp e:".$location[world].":".$location[X].",".$location[Y].",".$location[Z].";\n";
	echo "§cYou died and lost §2$".$lost."§c, leaving you with §2$".$left."§c.\n";
	echo "§aYou were respawned at §b".$location[name].".\n";
}else {
	echo "Aborting and teleporting you to the spawn point.\nPlease inform an administrator of this error: LOCATION_ARRAY_NULL";
}
?>