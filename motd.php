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
$query = mysql_fetch_array(mysql_query("SELECT * FROM permissions WHERE permission LIKE quest.current.%.%.%"))
$quest = $query[permission];
$questexploded=explode($quest, '.');
switch($questexploded[2]){
	case "tutorial":
		$questname = "The Tutorial";
		break;
	case "nomoregooddays":
		$questname = "No More Good Days, Part ".$questexploded[3];
		break;
	case "ships":
		$questname = "Ship Travel, Part ".$questexploded[3];
		break;
	case "caverncatastrophe":
		$questname = "Cavern Catastrophe, Part ".$questexploded[3];
		break;
	case "coalcrisis":
		$questname = "Coal Crisis, Part ".$questexploded[3];
		break;
	case "archaeologist":
		$questname = "The Archaeologist, Part ".$questexploded[3];
		break;
	case "familytrouble":
		$questname = "Family Troubles, Part ".$questexploded[3];
		break;
	case "fruity":
		$questname = "A Fruity Conundrum";
		break;
	default:
		$questname = "UNKNOWN QUEST NAME";
		break;
}
if(in_array("Tutorial", $groups)){ echo "§2Welcome to Araeosia, §b".$name."\n/Command/ExecuteBukkitCommand:ch join Tutorial;\n/Command/ExecuteBukkitCommand:ch leave Araeosia;\n§eYou can skip this tutorial using §9/tutorialskip§f.\n§cUntil you finish the tutorial, your chat is muted.\n§eTo begin the tutorial, right click on §6Gordon_Cassidy.\n§7There will be more useful information here after the tutorial.";}
if(in_array("1", $groups)){ echo "§3You currently have §6$" . number_format($iconomyvalue) . " §3dollars.\n"; }
if(isset($quest)){ echo "§2Your current quest is §7".$questname.".\n"; }
?>