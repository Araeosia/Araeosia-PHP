<?php
// Fetch variables
$playerX = $_POST[playerX];
$playerY = $_POST[playerY];
$playerZ = $_POST[playerZ];
$world = $_POST[playerWorld];
$name = $_POST[player];

include("includes/functions.php");
include('includes/mysql.php');
// City locations
$Cities = array('Araeos City', 'Everstone City', 'Crystalton', 'Darmouth', 'Talltree Point', 'Strongport', 'Coalmoor', 'Westcliff Plains Village', 'Cle Elum', 'The Bridge');
$CitiesCoords = array(
	'Araeos City' => array( 'X' => -212.5, 'Y' => 73, 'Z' => -183.5, 'name' => 'Araeos City', 'world' => 'Araeosia' ),
	'Everstone City' => array( 'X' => 486.5, 'Y' => 68, 'Z' => -125.5, 'name' => 'Everstone City', 'world' => 'Araeosia' ),
	'Crystalton' => array( 'X' => -962.5, 'Y' => 73, 'Z' => 989.5, 'name' => 'Crystalton', 'world' => 'Araeosia' ),
	'Darmouth' => array( 'X' => -234.5, 'Y' => 70, 'Z' => 213.5, 'name' => 'Darmouth', 'world' => 'Araeosia' ),
	'Talltree Point' => array( 'X' => -260.5, 'Y' => 76, 'Z' => 677.5, 'name' => 'Talltree Point', 'world' => 'Araeosia' ),
	'Strongport' => array( 'X' => 729.5, 'Y' => 68, 'Z' => 700.5, 'name' => 'Strongport', 'world' => 'Araeosia' ),
	'Coalmoor' => array( 'X' => 242.5, 'Y' => 74, 'Z' => -899.5, 'name' => 'Coalmoor', 'world' => 'Araeosia' ),
	'Westcliff Plains Village' => array( 'X' => -636.5, 'Y' => 74, 'Z' => -167.5, 'name' => 'Westcliff Plains Village', 'world' => 'Araeosia' ),
	'Fivepiece Island' => array( 'X' => 454, 'Y' => 73, 'Z' => -723, 'name' => 'Fivepiece Island', 'world' => 'Araeosia' ),
	'Cle Elum' => array( 'X' => 262, 'Y' => 74, 'Z' => 211, 'name' => 'Cle Elum', 'world' => 'Araeosia' ),
	'The Bridge' => array( 'X' => 770, 'Y' => 78, 'Z' => -21, 'name' => 'The Bridge', 'world' => 'Araeosia' ));

// Include functions

// Check for worlds
switch($world){
	case "Araeosia_tutorial2":
		die("§cYou are currently in §bThe Tutorial§c.\n");
		break;
	case "Araeosia_instance":
		$row = mysql_fetch_array(mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'"));
		$currentquest = $row[permission];
		switch($currentquest){
			case "quest.current.dungeon.5.1":
				die("§cYou are currently in §bThe Dungeon§c.\n");
				break;
			case "quest.current.archeologist.4.1":
				die("§cYou are currently in §bThe Ruins§c.\n");
				break;
			default:
				die("§c...I'm....not sure where you are.....");
				break;
		}
		break;
	case "Araeosia":
		// Most of the work happens here. Calculates and pushes the distance to each city...
		$minsname = array();
		$mins = array();
		foreach($CitiesCoords as $CityPT){
			$dist = sqrt(pow(($playerX-$CityPT[X]), 2)+pow(($playerZ-$CityPT[Z]), 2));
			$minsname[$dist] = $CityPT[name];
			array_push($mins, floor($dist));
		}
		// ...then figures out which is the smallest and sets the respawn array to it's coordinates.
		$min = min($mins);
		$minname = $minsname[$min];
		echo "§cThe closest city to your current location is §b".$minname."§c.\n";
		break;
	default:
		die("§c...I'm....not sure where you are.....");
		break;
}
?>
