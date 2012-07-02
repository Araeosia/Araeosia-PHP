<?php
include('includes/mysql.php');
include('includes/staff.php');
include('includes/functions.php');
if($_GET['s']!="Freebuild"){ exit; }
if(isInGroup("Veteran", $_POST['player'])){ echo '/Command/ExecuteConsoleCommand:mvtp '.$_POST['player'].' VetFlatlands;\n§aYou were teleported to VetFlatlands.'}else{ echo "§cYou don't have permission to go to VetFlatlands!"; }
?>