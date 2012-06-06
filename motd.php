<?php
// This file handles the MotD for the Araeosia RPG server. It needs major recoding to work better and cleaner.
// System Variables
date_default_timezone_set('EST');

// Fetch variables from URL
$name = $_POST[player];
$world = $_POST[playerWorld];

// Make a MySQL Connection
include('includes/mysql.php');

// Retrieve current quest
$currentrow = mysql_fetch_array( mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'") );
$currentquestperm = $currentrow['permission'];

// Retrieve lastlogout timestamp
$logoutrow = mysql_fetch_array( mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission='last-logout-time'") );
$logouttimestamp = $logoutrow['value'];
// Convert timestamp to human readable
$logouttime = date("F jS h:i:s A", $logouttimestamp);

// Retrieve iConomy balance
$iconomyrow = mysql_fetch_array( mysql_query("SELECT * FROM iConomy WHERE username='$name' AND status=0") );
$iconomyvalue = $iconomyrow['balance'];

// Check if current group is Tutorial
$groups = array();
$groupTquery = mysql_query("SELECT * FROM permissions_inheritance WHERE child='$name'");
while($row = mysql_fetch_array($groupTquery)){ array_push($groups, $row[parent]); }

// If user is broke, set $iconomyvalue to 0 rather than null
if (!isset($iconomyvalue)) { $iconomyvalue = "0.00"; }

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
switch($world){
	case "Araeosia_instance":
	case "Araeosia":
		$worldnamef = "Araeosia";
		break;
	case "Araeosia_tutorial2":
		$worldnamef = "The Tutorial";
		break;
}

// Print message to player
echo '§2Welcome to ' . $worldnamef . ', §b' . $name . "\n";
if ($newplayer) {
	echo "You were last seen on " . $logouttime . "\n";
	echo "/Command/ExecuteBukkitCommand:ch join Tutorial;";
	echo "/Command/ExecuteBukkitCommand:ch leave Araeosia;";
	echo "§eYou can skip this tutorial using §9/tutorialskip§f.\n§cUntil you finish the tutorial, your chat is muted.\n§eTo begin the tutorial, right click on §6Gordon_Cassidy.\n§7There will be more useful information here after the tutorial.";
}
if (isset($questname)) { echo "§6Your current quest is §3" . $questname . "\n"; }
if (!isset($questname) && $newplayer != "true") { echo "§6You currently don't have a quest.\n"; }
if ($newplayer != "true") { echo "§3You currently have §6$" . number_format($iconomyvalue) . " §3dollars.\n"; }
if (isset($bookwaitingnumber)) { echo "§5You have finished reading the Book of " . $bookname . "."; }
?>
