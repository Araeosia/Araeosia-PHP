<?php
// System Variables
date_default_timezone_set('EST');

// Fetch variables from URL
$name = $_POST[player];
$world = $_POST[playerWorld];

// Make a MySQL Connection
include('includes/mysql.php');

// Retrieve current quest
$currenttable = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'")
or die(mysql_error());  
$currentrow = mysql_fetch_array( $currenttable );
$currentquestperm = $currentrow['permission'];

// Retrieve lastlogout timestamp
$logouttable = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission='last-logout-time'")
or die(mysql_error());
$logoutrow = mysql_fetch_array( $logouttable );
$logouttimestamp = $logoutrow['value'];

// Retrieve iConomy balance
$iconomytable = mysql_query("SELECT * FROM iConomy WHERE username='$name' AND status=0")
or die(mysql_error());
$iconomyrow = mysql_fetch_array( $iconomytable );
$iconomyvalue = $iconomyrow['balance'];

// Check if current group is Tutorial
$groupTtable = mysql_query("SELECT * FROM permissions_inheritance WHERE child='$name' AND parent='Tutorial'")
or die(mysql_error()); 
$groupTrow = mysql_fetch_array( $groupTtable );
$groupT = $groupTrow['parent'];
if($groupT=="Tutorial"){ $group="Tutorial"; }
$grouptable = mysql_query("SELECT * FROM permissions_inheritance WHERE child='$name'") or die(mysql_error());
$grouprow = mysql_fetch_array($grouptable);
$group2 = $grouprow['parent'];
if(!isset($group2)){ $group="Tutorial"; }

// If user is broke, set $iconomyvalue to 0 rather than null
if (!isset($iconomyvalue)) {
  $iconomyvalue = "0.00";
}
// Convert timestamp to human readable
$logouttime = date("F jS h:i:s A", $logouttimestamp);

// Quest name determining
$questA = explode(".",$currentquestperm);
$questParent = $questA[2];
$questChild = $questA[3];
if ( $questParent == "nomoregooddays") {
        $questname = "No More Good Days, Part " . $questChild;
        $questparent = "No More Good Days";
        $questchild = "Part " . $questChild;
        $questhelp = "Jonathan needs your help!";
        $questgiver = "Jonathan_Toness";
        $questlocation = "Strongport";
} elseif ( $questParent == "tutorial" && $questChild = 1) {
        $questname = "The Tutorial";
        $questparent = "The Tutorial";
        $questchild = "";
        $questhelp = "Check with Gordon Cassidy to complete this mission.";
        $questgiver = "Gordon Cassidy";
        $questlocation = "Tutorial Island";
}
// Is this player a new player, as evidence by the timestamp?
if ( $group == "Tutorial" ) { $newplayer = true; } else { $newplayer = false; }

// Are there completed books waiting?
$bookstable = mysql_query("SELECT * FROM bookswaiting WHERE name='$name'");
$booksrow = mysql_fetch_array( $bookstable );
$booknumber = $booksrow['booknumber'];
include("includes/books.php");

// Turn world name into worldnamef
if($world=="Araeosia" || $world=="Araeosia_instance"){
	$worldnamef = "Araeosia";
} elseif($world=="Araeosia_tutorial2"){
	$worldnamef = "The Araeosia Tutorial";
}

// Print message to player
echo '§2Welcome to ' . $worldnamef . ', §b' . $name . "\n";
if ($newplayer) {
	echo "You were last seen on " . $logouttime . "\n";
	echo "/Command/ExecuteBukkitCommand:ch join Tutorial;";
	echo "/Command/ExecuteBukkitCommand:ch leave Araeosia;";
	echo "§eYou can skip this tutorial using §9/tutorialskip§f.\n§cUntil you finish the tutorial, your chat is muted.\n§eTo begin the tutorial, right click on §6Gordon_Cassidy.\n§7There will be more useful information here after the tutorial.";
}
if (isset($questname) && $newplayer != "true") {
  echo "§6Your current quest is §3" . $questname . "\n";
}
if (!isset($questname) && $newplayer != "true") {
  echo "§6You currently don't have a quest.\n";
}
if ($newplayer != "true") {
  echo "§3You currently have §d" . number_format($iconomyvalue) . " §3dollars.\n";
}
if (isset($bookwaitingnumber)) {
  echo "§5You have finished reading the Book of " . $bookname . ".";
}
?>
