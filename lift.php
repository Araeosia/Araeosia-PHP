<?php
// This file handles the custom lift at Coalmoor Caverns on the Araeosia RPG server.
$name = $_POST[player];
$args = $_POST[args];
$playerWorld = $_POST[playerWorld];
$X = $args[1];
$Y = $args[2];
$Z = $args[3];
include('includes/mysql.php');
if($X!=256){ exit; }
if($Y==62){ $spot = "the surface"; } else { $spot = "the caves"; }
if($Z==-891 || $Z ==-893){
	$query = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission='quest.completed.caverncatastrophe.5.3'");
	if($query==false){ die('§4The lift is still broken!'); }
	if($spot=="the caves"){ $newcoords = "e:Araeosia:254.5,61,-891.5:0:180"; }elseif($spot=="the surface"){ $newcoords = "e:Araeosia:254.5,75,-888.5"; }
	echo "§cYou rode the lift to §b".$spot."§c.\n";
	echo "/Command/ExecuteConsoleCommand:mvtp ".$name." ".$newcoords.";\n";
}

?>