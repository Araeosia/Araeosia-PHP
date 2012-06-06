<?php
// This file handles the /unstuck command, locating the nearest Health Shack, then teleporting the player to that Health Shack.
// Fetch variables
$name = $_POST[player];
$args = $_POST[args];

// Fetch death location
include('includes/mysql.php');
$playerX = $_POST[playerX];
$playerY = $_POST[playerY];
$playerZ = $_POST[playerZ];
$world = $_POST[playerWorld];

// Connect to MySQL server
include('includes/mysql.php');
$query = mysql_query("SELECT * FROM Unstucks WHERE name='$name'");
$row = mysql_fetch_array($query);
if($row[name] == $name){
    $alreadywaiting = 1;
    $timeleft = time-$row[timestamp];
}

// Check for completion. This is handled by CommandHelper automatically. If you understand the code, you can actually bypass the 20 second wait ;)
if($args[1] != "fin"){
    echo "§cYou will be spawned at the nearest Health Shack in 20 seconds\n";
    echo "/Command/ExecuteBukkitCommand:ws unstuck fin;";
    mysql_query("INSERT INTO Unstucks (id, name, timestamp) VALUES ('NULL', '$name', '$currenttime')");
    exit;
} elseif($alreadywaiting == "1" && $args[1] != "fin"){
    echo "§cYou will be spawned at the nearest Health Shack in " . $timeleft . " seconds\n";
    exit;
} else{
    sleep(20);
    mysql_query("DELETE FROM Unstucks WHERE name='$name'");
}

// Fetch current quest permission
$currentrow = mysql_fetch_array( mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'") );
$currentquestperm = $currentrow['permission'];
// Respawn locations
$RespawnPTs = array('Araeos City', 'Everstone City', 'Crystalton', 'Darmouth', 'Talltree Point', 'Strongport', 'Coalmoor', 'Westcliff Plains Village', 'Cle Elum', 'The Bridge');
$RespawnCoords = array(
	'Araeos City' => array( 'X' => -212.5, 'Y' => 73, 'Z' => -183.5, 'name' => 'Araeos City' ),
	'Everstone City' => array( 'X' => 486.5, 'Y' => 68, 'Z' => -125.5, 'name' => 'Everstone City' ),
	'Crystalton' => array( 'X' => -962.5, 'Y' => 73, 'Z' => 989.5, 'name' => 'Crystalton' ),
	'Darmouth' => array( 'X' => -234.5, 'Y' => 70, 'Z' => 213.5, 'name' => 'Darmouth' ),
	'Talltree Point' => array( 'X' => -260.5, 'Y' => 76, 'Z' => 677.5, 'name' => 'Talltree Point' ),
	'Strongport' => array( 'X' => 729.5, 'Y' => 68, 'Z' => 700.5, 'name' => 'Strongport' ),
	'Coalmoor' => array( 'X' => 242.5, 'Y' => 74, 'Z' => -899.5, 'name' => 'Coalmoor' ),
	'Westcliff Plains Village' => array( 'X' => -636.5, 'Y' => 74, 'Z' => -167.5, 'name' => 'Westcliff Plains Village' ),
	'Fivepiece Island' => array( 'X' => 454, 'Y' => 73, 'Z' => -723, 'name' => 'Fivepiece Island' ),
	'Cle Elum' => array( 'X' => 262, 'Y' => 74, 'Z' => 211, 'name' => 'Cle Elum' ),
	'The Bridge' => array( 'X' => 770, 'Y' => 78, 'Z' => -21, 'name' => 'The Bridge'), );
// Figure out which world
switch($world){
	case "Araeosia_tutorial2":
		$RespawnArray = array( 'X' => -300.5, 'Y' => 69, 'Z' => -52.5, 'name' => 'The Tutorial');
		break;
	case "Araeosia_instance":
		switch($currentquest){
			case "quest.current.dungeon.5.1":
				$RespawnArray = array( 'X' => -0.5, 'Y' => 64, 'Z' => 42.5, 'name' => 'The Dungeon' );
				break;
			case "quest.current.archeologist.4.1":
				$RespawnArray = array( 'X' => -314.5, 'Y' => 64, 'Z' => -59.5, 'name' => 'The Ruins' );
				break;
			default:
				die("§4Error! §cCannot find where to teleport you!\n§cDefaulting to spawning at Araeos City. \n§aPlease tell a staff member about this error.\n/Command/ExecuteConsoleCommand:mvtp " . $name . " e:Araeosia:-212.5,73,-183.5;");
				break;
		}
		break;
	case "Araeosia":
		$mins = array();
		// Most of the work happens here. Calculates and pushes the distance to each respawn point...
		foreach($RespawnCoords as $RespawnPT){
			$dist = sqrt(pow(($playerX-$RespawnPT[X]), 2)+pow(($playerZ-$RespawnPT[Z]), 2));
			$push = array($RespawnPT[name] => $dist);
			array_push($mins, $push);
		}
		// ...then figures out which is the smallest and sets the respawn array to it's coordinates.
		$min = min($mins);
		$minflipped = array_flip($mins);
		$minname = $minflipped[$min];
		$RespawnArray = $RespawnCoords[$minname];
		break;
	default:
		die("§4Error! §cCannot find where to teleport you!\n§cDefaulting to spawning at Araeos City. \n§aPlease tell a staff member about this error.\n/Command/ExecuteConsoleCommand:mvtp ".$name." e:Araeosia:-212.5,73,-183.5;");
		break;
}
// Echo results to player and execute commands
echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:" . $world . ":" . $RespawnX . "," . $RespawnY . "," . $RespawnZ . ";\n";
echo "§cYou were spawned at §e" . $spawnlocation . "§c.\n";

?>​