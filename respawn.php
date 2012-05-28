<?php
// Fetch variables
$name = $_POST[player];
$args = $_POST[args];

// Fetch death location
mysql_connect("localhost", "website", "fXsRx0GEGw9M") or die(mysql_error());
mysql_select_db("Araeosia") or die(mysql_error());
$playerX = $args[1];
$playerY = $args[2];
$playerZ = $args[3];
$world = $args[4];

// Fetch current quest permission
$currenttable = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'")
or die(mysql_error());  
$currentrow = mysql_fetch_array( $currenttable );
$currentquestperm = $currentrow['permission'];
// Respawn locations
$RespawnAX = array('-212.5', '486.5', '-962.5', '-234.5', '-260.5', '729.5', '242.5', '-636.5', '454', '262', '770');
$RespawnAY = array('73', '68', '73', '70', '76', '68', '74', '74', '73', '74', '78');
$RespawnAZ = array('-183.5', '-125.5', '989.5', '213.5', '677.5', '700.5', '-899.5', '-167.5', '-723', '211', '-21');
$RespawnTX = "-300.5";
$RespawnTY = "69";
$RespawnTZ = "-52.5";
// Check to see what world the player is in
if($world == "Araeosia_instance"){
	if($currentquestperm == "quest.current.dungeon.5.1"){
		$RespawnX = "-0.5";
		$RespawnY = "64";
		$RespawnZ = "42.5";
		$spawnlocation = "The Dungeon";
	} elseif ($currentquestperm == "quest.current.archeologist.4.1"){
		$RespawnX = "-314.5";
		$RespawnY = "64";
		$RespawnZ = "-59.5";
		$spawnlocation = "The ruins";
	} else {
		echo "§4Error! §cCannot find where to respawn you!\n";
		echo "§cDefaulting to respawn at Araeos City. \n§aPlease tell a staff member about this error.\n";
		echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:Araeosia:-212.5,73,-183.5;";
		exit;
	}
}
if($world == "Araeosia_tutorial2"){
  $RespawnX = $RespawnTX;
  $RespawnY = $RespawnTY;
  $RespawnZ = $RespawnTZ;
  $spawnlocation = "The Tutorial";
}
if($world == "Araeosia"){
// Calculate distance to each respawn location
  $RespawnDist[0] = sqrt(pow(($playerX-$RespawnAX['0']), 2))+sqrt(pow(($playerZ-$RespawnAZ['0']), 2));
  $RespawnDist[1] = sqrt(pow(($playerX-$RespawnAX['1']), 2))+sqrt(pow(($playerZ-$RespawnAZ['1']), 2));
  $RespawnDist[2] = sqrt(pow(($playerX-$RespawnAX['2']), 2))+sqrt(pow(($playerZ-$RespawnAZ['2']), 2));
  $RespawnDist[3] = sqrt(pow(($playerX-$RespawnAX['3']), 2))+sqrt(pow(($playerZ-$RespawnAZ['3']), 2));
  $RespawnDist[4] = sqrt(pow(($playerX-$RespawnAX['4']), 2))+sqrt(pow(($playerZ-$RespawnAZ['4']), 2));
  $RespawnDist[5] = sqrt(pow(($playerX-$RespawnAX['5']), 2))+sqrt(pow(($playerZ-$RespawnAZ['5']), 2));
  $RespawnDist[6] = sqrt(pow(($playerX-$RespawnAX['6']), 2))+sqrt(pow(($playerZ-$RespawnAZ['6']), 2));
  $RespawnDist[7] = sqrt(pow(($playerX-$RespawnAX['7']), 2))+sqrt(pow(($playerZ-$RespawnAZ['7']), 2));
  $RespawnDist[8] = sqrt(pow(($playerX-$RespawnAX['8']), 2))+sqrt(pow(($playerZ-$RespawnAZ['8']), 2));
// Calculate the minimum of the distances
  $min = min($RespawnDist);
  if($min == $RespawnDist[0]){
    $spawnlocation = "Araeos City";
    $RespawnX = $RespawnAX[0];
    $RespawnY = $RespawnAY[0];
    $RespawnZ = $RespawnAZ[0];
  }
  if($min == $RespawnDist[1]){
    $spawnlocation = "Everstone City";
    $RespawnX = $RespawnAX[1];
    $RespawnY = $RespawnAY[1];
    $RespawnZ = $RespawnAZ[1];
  }
  if($min == $RespawnDist[2]){
    $spawnlocation = "Crystalton";
    $RespawnX = $RespawnAX[2];
    $RespawnY = $RespawnAY[2];
    $RespawnZ = $RespawnAZ[2];
  }
  if($min == $RespawnDist[3]){
    $spawnlocation = "Darmouth";
    $RespawnX = $RespawnAX[3];
    $RespawnY = $RespawnAY[3];
    $RespawnZ = $RespawnAZ[3];
  }
  if($min == $RespawnDist[4]){
    $spawnlocation = "Talltree Point";
    $RespawnX = $RespawnAX[4];
    $RespawnY = $RespawnAY[4];
    $RespawnZ = $RespawnAZ[4];
  }
  if($min == $RespawnDist[5]){
    $spawnlocation = "Strongport";
    $RespawnX = $RespawnAX[5];
    $RespawnY = $RespawnAY[5];
    $RespawnZ = $RespawnAZ[5];
  }
  if($min == $RespawnDist[6]){
    $spawnlocation = "Coalmoor";
    $RespawnX = $RespawnAX[6];
    $RespawnY = $RespawnAY[6];
    $RespawnZ = $RespawnAZ[6];
  }
  if($min == $RespawnDist[7]){
    $spawnlocation = "Westcliff Plains Village";
    $RespawnX = $RespawnAX[7];
    $RespawnY = $RespawnAY[7];
    $RespawnZ = $RespawnAZ[7];
  }
  if($min == $RespawnDist[8]){
    $spawnlocation = "Fivepiece Island";
    $RespawnX = $RespawnAX[8];
    $RespawnY = $RespawnAY[8];
    $RespawnZ = $RespawnAZ[8];
  }
  if($min == $RespawnDist[9]){
    $spawnlocation = "NewTown";
    $RespawnX = $RespawnAX[9];
    $RespawnY = $RespawnAY[9];
    $RespawnZ = $RespawnAZ[9];
  }
  if($min == $RespawnDist[10]){
    $spawnlocation = "The Bridge";
    $RespawnX = $RespawnAX[10];
    $RespawnY = $RespawnAY[10];
    $RespawnZ = $RespawnAZ[10];
  }
}
// Fetch current iConomy balance
$lname = strtolower($name);
$iconomytable = mysql_query("SELECT * FROM iConomy WHERE username='$lname' AND status=0")
or die(mysql_error());
$iconomyrow = mysql_fetch_array( $iconomytable );
$iconomyvalue = $iconomyrow['balance'];
$lostrand = rand(7,13);
if($lostrand<10){$lostrand = "0" . $lostrand;}
$lostdecimal = "0." . $lostrand;
$lost = ceil($iconomyvalue*$lostdecimal);
$leftover = $iconomyvalue-$lost;
mysql_query("DELETE FROM iConomy WHERE username='$lname' AND status='0'") or die(mysql_error());
mysql_query("INSERT INTO iConomy (id, username, balance, status) VALUES('NULL', '$lname', '$leftover', '0')") or die(mysql_error());
$lostf = number_format($lost);
$leftoverf = number_format($leftover);
// Echo results to player and execute commands
sleep(1);
echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:" . $world . ":" . $RespawnX . "," . $RespawnY . "," . $RespawnZ . ";\n";
echo "§cYou died and were respawned at §e" . $spawnlocation . "§c.\n";
echo "§eYou lost §2$" . $lostf . " §ewhen you died, leaving you with §2$" . $leftoverf . " §eleft.";
?>​
