<?php
// This file handles opting out from the timed messages that occur every 5 minutes. The structure of the optouts table is: id (autoinc, int, primarykey) | name (text)
include('includes/mysql.php');
$name = $_POST[player];
$query = mysql_query("SELECT * FROM optouts WHERE name='$name'");
$row = mysql_fetch_array($row);
if($row[name]!=$name){
	mysql_query("INSERT INTO optouts (id, name) VALUES('NULL', '$name')");
	echo "§bYou have opted out of recieving timed messages.";
} else { echo "§cYou've already opted out of recieving timed messages!"; }
?>