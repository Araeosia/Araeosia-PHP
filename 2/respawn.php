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
		"Z" => array()
	),
	"Araeosia2B" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	),
	"Araeosia2C" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	),
	"Araeosia2D" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	),
	"Araeosia2E" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	),
	"Araeosia2F" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	),
	"Araeosia2G" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	),
	"Araeosia2H" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	),
	"Araeosia2I" => array(
		"X" => array(),
		"Y" => array(),
		"Z" => array()
	)
// Figure out what world
if(!isset($type)){
	switch($playerWorld){
		case "Araeosia2A":
			break;
		case "Araeosia2B":
			break;
		case "Araeosia2C":
			break;
		case "Araeosia2D":
			break;
		case "Araeosia2E":
			break;
		case "Araeosia2F":
			break;
		case "Araeosia2G":
			break;
		case "Araeosia2H":
			break;
		case "Araeosia2I":
			break;
		default:
			echo "Cannot find what world to teleport you to! Aborting and teleporting you to the spawn point.\nPlease inform an administrator of this error: WORLD_DEFAULT";
			echo "/Command/ExecuteBukkitCommand:mvspawn ".$name.";\n";
			break;
	}
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
if(isset($location)){
	echo "/Command/ExecuteBukkitCommand:mvtp e:".$location[world].":".$location[X].",".$location[Y].",".$location[Z].";\n";
	echo "§cYou died and lost §2$".$lost."§c, leaving you with §2$".$left."§c.\n";
	echo "§aYou were respawned at §b".$location[name].".\n";
}else {
	echo "Aborting and teleporting you to the spawn point.\nPlease inform an administrator of this error: LOCATION_ARRAY_NULL";
}
?>