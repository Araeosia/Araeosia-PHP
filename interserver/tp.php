<?php
include('includes/functions.php');
include('includes/mysql.php');
include('includes/servers.php');
include('includes/passwords.php');
$name = $_POST['player'];
if(!isStaff($name)){ die('No.'); }
$target = $args[1];
if(!$target){ die('Could not find a player by that name!'); }
$serverSending = $_GET['s'];
serverCheck($serverSending, true);
$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
if(!isOnlineOnServer($serverSending, $target)){
	// Player is not currently online.
	$query = mysql_fetch_array(mysql_query("SELECT * FROM PlayerLocs WHERE name='$target'"));
	if(!$query){ die('Invalid player!'); }
	$newCoords = $query;
}else{
	
}
?>