<?php
// This file handles picking where to respawn the player. It's uglyh4x around line 80, but there isn't much I can do about that because of the way Multiverse-Core handles respawns. It's executed the moment you hit "Respawn"
// Fetch variables
$name = $_POST[player];
$args = $_POST[args];

// Fetch death location
include('includes/mysql.php');
$playerX = $args[1];
$playerY = $args[2];
$playerZ = $args[3];
$world = $args[4];

// Fetch current quest permission
$currentrow = mysql_fetch_array( mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'") );
$currentquestperm = $currentrow['permission'];
// Respawn locations
$RespawnPTs = array('Araeos City', 'Everstone City', 'Crystalton', 'Darmouth', 'Talltree Point', 'Strongport', 'Coalmoor', 'Westcliff Plains Village', 'Cle Elum', 'The Bridge');
$RespawnCoords = array(
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
// Figure out which world
switch($world){
	case "Araeosia_tutorial2":
		$RespawnArray = array( 'X' => -300.5, 'Y' => 69, 'Z' => -52.5, 'name' => 'The Tutorial', 'world' => 'Araeosia_tutorial2' );
		break;
	case "Araeosia_instance":
		switch($currentquest){
			case "quest.current.dungeon.5.1":
				$RespawnArray = array( 'X' => -0.5, 'Y' => 64, 'Z' => 42.5, 'name' => 'The Dungeon', 'world' => 'Araeosia_instance' );
				break;
			case "quest.current.archeologist.4.1":
				$RespawnArray = array( 'X' => -314.5, 'Y' => 64, 'Z' => -59.5, 'name' => 'The Ruins', 'world' => 'Araeosia_instance' );
				break;
			default:
				die("§4Error! §cCannot find where to respawn you!\n§cDefaulting to respawn at Araeos City. \n§aPlease tell a staff member about this error.\n/Command/ExecuteConsoleCommand:mvtp " . $name . " e:Araeosia:-212.5,73,-183.5;");
				break;
		}
		break;
	case "Araeosia":
		// Most of the work happens here. Calculates and pushes the distance to each respawn point...
		$minsname = array();
		$mins = array();
		foreach($RespawnCoords as $RespawnPT){
			$dist = sqrt(pow(($playerX-$RespawnPT[X]), 2)+pow(($playerZ-$RespawnPT[Z]), 2));
			$minsname[$dist] = $RespawnPT[name];
			array_push($mins, floor($dist));
		}
		// ...then figures out which is the smallest and sets the respawn array to it's coordinates.
		$min = min($mins);
		$minname = $minsname[$min];
		$RespawnArray = $RespawnCoords[$minname];
		break;
	default:
		die("§4Error! §cCannot find where to respawn you!\n§cDefaulting to respawn at Araeos City. \n§aPlease tell a staff member about this error.\n/Command/ExecuteConsoleCommand:mvtp ".$name." e:Araeosia:-212.5,73,-183.5;");
		break;
}
// Fetch current iConomy balance
$lname = strtolower($name);
$iconomytable = mysql_query("SELECT * FROM iConomy WHERE username='$lname' AND status=0") or die(mysql_error());
$iconomyrow = mysql_fetch_array( $iconomytable );
$iconomyvalue = $iconomyrow['balance'];
$lostrand = rand(7,13)/100;
$lost = ceil($iconomyvalue*$lostrand);
$leftover = $iconomyvalue-$lost;
mysql_query("UPDATE iConomy SET balance='$leftover' WHERE name='$name'");
$lostf = number_format($lost);
$leftoverf = number_format($leftover);
// Echo results to player and execute commands
sleep(1);
echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:" . $RespawnArray[world] . ":" . $RespawnArray[X] . "," . $RespawnArray[Y] . "," . $RespawnArray[Z] . ";\n";
echo "§cYou died and were respawned at §e" . $RespawnArray[name] . "§c.\n";
echo "§eYou lost §2$" . $lostf . " §ewhen you died, leaving you with §2$" . $leftoverf . " §eleft.";
?>​