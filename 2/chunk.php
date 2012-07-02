<?php
set_time_limit(0);
$name = "AgentKid";
$range = range(-500, 500, 1);
array_splice($range, 251, 499);
$range2 = range(-500, 500, 1);
array_splice($range2, 251, 499);
$chunksToMove = array();
$count = 0;
foreach($range as $value){
	foreach($range2 as $value2){
		$chunksToMove[$count] = array( 'X' => $value, 'Z' => $value2);
		$count = $count+1;
	}
}
error_reporting(E_ALL);
/*
 * We have $chunksToMove, which contains count($range)*count($range2) entries. Each in the form array('X' => int, 'Z' => int). Lets put them to work.
 */
$count = 0;
var_dump(count($chunksToMove));
foreach($chunksToMove as $chunk){
	$count = $count+1;
	$insideCoordinateX = $chunk['X']*16;
	$insideCoordinateZ = $chunk['Z']*16;
	$distanceFromOrigin = sqrt(pow($insideCoordinateX, 2)+pow($insideCoordinateZ, 2));
	$reduced = abs(round($distanceFromOrigin/1000));
	$shift = $reduced*3;
#	if($count>8){ die('Killed.'); }
/*
 * We now know how much we're going to shift the chunk down by. Lets teleport the player to the sample spot.
 */
 	echo "/Command/ExecuteConsoleCommand:mvtp AgentKid e:Araeosia2A:".$insideCoordinateX.",128,".$insideCoordinateZ.";\n";
 	echo "/Command/ExecuteBukkitCommand://chunk;\n";
 	echo "/Command/ExecuteBukkitCommand://move ".$shift." down;\n";
 	echo "/Command/ExecuteBukkitCommand://contract 255 down;\n";
 	echo "/Command/ExecuteBukkitCommand://set 7;\n";
 	echo "§aMoved chunk ".$chunk['X'].", ".$chunk['Z']." down by ".$shift." blocks.\n";
 	sleep(1);
}


?>