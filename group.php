<?php
include('includes/mysql.php');
include('includes/staff.php');
include('includes/functions.php');
serverCheck($server, true);
$name = $_POST['player'];
if(!isStaff($name)){ die('No.'); }
$args = $_POST['args'];
$toChange = htmlspecialchars($args[1]);
$newGroup = htmlspecialchars($args[2]);
mysql_query("DELETE FROM TrueGroups WHERE name='$toChange'");
mysql_query("INSERT INTO TrueGroups (id, name, group) VALUES ('NULL', '$toChange', '$newGroup')") or die(mysql_error());
?>