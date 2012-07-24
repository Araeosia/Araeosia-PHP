<?php
// This file handles opting out from the timed messages that occur every 5 minutes. The structure of the optouts table is: id (autoinc, int, primarykey) | name (text)
// Note to AgentKid: I need to redo this file to handle multiple opt-outs. Lets have it something like /optout [timed/joins/leaves/channel]
include('includes/mysql.php');
include('includes/functions.php');
serverCheck($server, true);
$name = $_POST['player'];
$query = mysql_query("SELECT * FROM optouts WHERE name='$name'");
$row = mysql_fetch_array($query);
if($row['name']!=$name){
	mysql_query("INSERT INTO optouts (id, name) VALUES('NULL', '$name')");
	echo "§bYou have opted out of recieving timed messages until the next time you log out.";
} else { echo "§cYou've already opted out of recieving timed messages!"; }
?>