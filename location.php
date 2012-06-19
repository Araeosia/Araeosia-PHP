<?php
// This file handles the /whereami and /getpos commands on the Araeosia RPG server.
// Fetch variables
$playerX = $_POST['playerX'];
$playerY = $_POST['playerY'];
$playerZ = $_POST['playerZ'];
$world = $_POST['playerWorld'];
$name = $_POST['player'];

include("includes/functions.php");
include('includes/mysql.php');

// Check for worlds
switch($world){
	case "Araeosia_tutorial2":
		die("§cYou are currently in §bThe Tutorial§c.\n");
		break;
	case "Araeosia_instance":
		$row = mysql_fetch_array(mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'"));
		$currentquest = $row['permission'];
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
		$locator = new MCFunctions();
		$locator->get
		break;
	default:
		die("§c...I'm....not sure where you are.....");
		break;
}
?>
