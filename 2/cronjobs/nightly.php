<?php
include('includes/mysql.php');
// Lottery
$lotteries = array('halfhalf');
$lotteryname = array('halfhalf' => 'Half\'n\'Half');
foreach($lotteries as $type){
	$query = mysql_query("SELECT * FROM Lotteries WHERE type='$type'");
	$entries = array();
	while($row = mysql_fetch_array($query)){
		array_push($entries, $row[name]);
	}
	$counts[$type] = count($entries);
	$thiscount = $counts[$type];
	$winners[$type] = $entries[rand(0,$thiscount)];
}
$msg = "§aThe daily lottery winners have been selected!\n";
foreach($lotteries as $type){
	$won = $counts[$type]/2;
	$thiswinner = $winners[$type];
	$money = mysql_query("SELECT * FROM iConomy WHERE name='$thiswinner'");
	$moneyrow = mysql_fetch_array($money);
	$moneynew = $moneyrow[value]+$won
	mysql_query("UPDATE iConomy SET value='$moneynew' WHERE name='$thiswinner'");
	$won = 
	$msg=$msg."§b".$thiswinner."§1 has won the §c".$lotteryname[$type]." §1lottery, winnning §2$".number_format($won)."§1!\n";
}
// Send a message to everyone who's in the trade channel about who won
#$IRC = new IRC();
#IRC->sendmessage('#Trade', '$msg');

?>