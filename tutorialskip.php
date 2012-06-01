<?php
// Get variables from URL
$name = $_POST['player'];
$args = $_POST['args'];
$confirmed = $args[1];
// Get group from database, since Websend might be used
include('includes/mysql.php');
$grouptable = mysql_query("SELECT * FROM permissions_inheritance WHERE child='$name' AND parent='Tutorial'")
or die(mysql_error());  
$grouprow = mysql_fetch_array( $grouptable );
$group = $grouprow['parent'];
// Set status
if( $confirmed == "true" && $group == "Tutorial") {
	$status = "confirmed";
} elseif( $confirmed != "true" && $group == "Tutorial") {
	$status = "unconfirmed";
} elseif( $group != "Tutorial") {
	$status = "alreadydone";
} else {
    echo "Failed to skip tutorial! Error line 22. Status: " . $status;
    exit;
}
// Output messages to player
if( $status == "unconfirmed") {
	echo "§bAre you sure you wish to skip the tutorial?\n§3The tutorial contains important information about the\n§3plotline of Araeosia and interation with NPCs.\n§cIf you skip the tutorial, you cannot return to it.\n§4If you wish to skip the tutorial, use §d/skipconfirm\n§2If you wish to continue the tutorial, don't do anything.";
} elseif( $status == "alreadydone") {
	echo "§4You've already completed/skipped the tutorial!\n";
} elseif( $status == "confirmed") {
	echo "§2Terminating Tutorial session...";
	echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:Araeosia:766.5,68,632.5:0:0;\n";
	echo "/Command/ExecutePlayerCommand:vanish " . $name . ";\n";
        echo "/Command/ExecuteConsoleCommand:pex user " . $name . " group set 1;\n";
        echo "/Command/ExecuteConsoleCommand:pex user " . $name . " add add quest.completed.tutorial.1.11";
        mysql_query("DELETE FROM permissions WHERE name='$name' AND permission LIKE('quest.current.tutorial.%.%')");
}
?>