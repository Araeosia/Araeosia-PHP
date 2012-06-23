<?php
include('includes/mysql.php');
include('includes/staff.php');
$name = $_POST['player'];
if(!in_array($name, $staffranks['admin'])){ die('No.'); }
$args = $_POST['args'];
$toChange = htmlspecialchars($args[1]);
$newGroup = htmlspecialchars($args[2]);
mysql_query("DELETE FROM TrueGroups WHERE name='$toChange'");
mysql_query("INSERT INTO TrueGroups (id, name, group) VALUES ('NULL', '$toChange', '$newGroup')");
?>