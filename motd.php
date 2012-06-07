<?php
$name = $_POST[player];
$world = $_POST[playerWorld];
// Includes
include('includes/mysql.php');
$query = mysql_query("SELECT * FROM permissions_inheritance WHERE child='$name'");
$groups = array();
while($row = mysql_fetch_array($query)){ array_push($groups, $row[parent]); }
$iconomyvalue = mysql_fetch_array( mysql_query("SELECT * FROM iConomy WHERE username='$name'"));
$iconomyvalue = $iconomyvalue[value];
switch($world){
	case "Araeosia_instance":
	case "Araeosia":
		$worldnamef = "Araeosia";
		break;
	case "Araeosia_tutorial2":
		$worldnamef = "The Tutorial";
		break;
}
if(in_array("Tutorial", $groups)){ echo "§2Welcome to Araeosia, §b".$name."\n/Command/ExecuteBukkitCommand:ch join Tutorial;\n/Command/ExecuteBukkitCommand:ch leave Araeosia;\n§eYou can skip this tutorial using §9/tutorialskip§f.\n§cUntil you finish the tutorial, your chat is muted.\n§eTo begin the tutorial, right click on §6Gordon_Cassidy.\n§7There will be more useful information here after the tutorial.";}
if(in_array("1", $groups)){ echo "§3You currently have §6$" . number_format($iconomyvalue) . " §3dollars.\n"; }
?>