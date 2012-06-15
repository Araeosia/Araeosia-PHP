<?php
// This file handles picking where to respawn the player. It's uglyh4x around line 80, but there isn't much I can do about that because of the way Multiverse-Core handles respawns. It's executed the moment you hit "Respawn"
// Fetch variables
$name = $_POST['player'];
$args = $_POST['args'];

// Fetch death location
include('includes/mysql.php');
include('includes/functions.php');
$playerX = $args[1];
$playerY = $args[2];
$playerZ = $args[3];
$world = $args[4];
// Fetch the respawn location
$respawnloc = new MCFunctions();
$RespawnArray = $respawnloc->respawncoords($name, $playerX, $playerZ, $world);

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
echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:" . $RespawnArray['world'] . ":" . $RespawnArray['X'] . "," . $RespawnArray['Y'] . "," . $RespawnArray['Z'] . ";\n";
echo "§cYou died and were respawned at §e" . $RespawnArray['name'] . "§c.\n";
echo "§eYou lost §2$" . $lostf . " §ewhen you died, leaving you with §2$" . $leftoverf . " §eleft.";
?>​