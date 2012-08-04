<?php
include('includes/mysql.php');
include('includes/functions.php');
include('includes/staff.php');
serverCheck($server, true);

$onlinePlayers = getOnlineStaff();
echo "§b---------------- Online Staff ----------------\n";
$onlinePlayers = rankPlayers($onlinePlayers);
foreach($onlinePlayers as $player){
    $playersFinal = getFullName($player);
}
echo implode('§e, ', $playersFinal);
?>