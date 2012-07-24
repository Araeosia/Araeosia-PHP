<?php
// This file handles the /unstuck command, locating the nearest Health Shack, then teleporting the player to that Health Shack.
// Fetch variables
$name = $_POST['player'];
$args = $_POST['args'];
include('includes/mysql.php');
include('includes/functions.php');
$playerX = $_POST['playerX'];
$playerY = $_POST['playerY'];
$playerZ = $_POST['playerZ'];
$world = $_POST['playerWorld'];
serverCheck($server, array('RPG'));


// Connect to MySQL server
include('includes/mysql.php');
$query = mysql_query("SELECT * FROM Unstucks WHERE name='$name'");
$row = mysql_fetch_array($query);
if($row['name'] == $name){
    $alreadywaiting = 1;
    $timeleft = time()-$row['timestamp'];
}

// Check for completion. This is handled by CommandHelper automatically. If you understand the code, you can actually bypass the 20 second wait ;)
if($args[1] != "fin"){
    echo "§cYou will be spawned at the nearest Health Shack in 10 seconds\n";
    echo "/Command/ExecuteBukkitCommand:ws unstuck fin;";
    mysql_query("INSERT INTO Unstucks (id, name, timestamp) VALUES ('NULL', '$name', '$currenttime')");
    exit;
} elseif($alreadywaiting == 1 && $args[1] != "fin"){
    echo "§cYou will be spawned at the nearest Health Shack in " . $timeleft . " seconds\n";
    exit;
} else{
    sleep(10);
    mysql_query("DELETE FROM Unstucks WHERE name='$name'");
}

// Fetch the location
$respawnloc = new MCFunctions();
$RespawnArray = $respawnloc->respawncoords($name, $playerX, $playerZ, $world);

// Echo results to player and execute commands
echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:" . $RespawnArray['world'] . ":" . $RespawnArray['X'] . "," . $RespawnArray['Y'] . "," . $RespawnArray['Z'] . ";\n";
echo "§cYou were spawned at §e" . $RespawnArray['name'] . "§c";
if(isset($RespawnArray['dist'])){ echo ", ".$RespawnArray['dist']." meters away"; }
echo ".\n";
?>​