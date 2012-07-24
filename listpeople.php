<?php
include('includes/mysql.php');
include('includes/functions.php');
serverCheck($server, true);

$query = mysql_query("SELECT * FROM TrueGroups");
while($row = mysql_fetch_array($query)){
	echo $row['name']."\n";
}