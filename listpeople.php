<?php
include('includes/mysql.php');

$query = mysql_query("SELECT * FROM TrueGroups");
while($row = mysql_fetch_array($query)){
	echo $row['name']."\n";
}