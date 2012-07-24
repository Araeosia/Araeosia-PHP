<?php
include('includes/mysql.php');
include('includes/staff.php');
include('includes/functions.php');
serverCheck($server, array('Freebuild'));
if($_GET['s']!="Freebuild"){ exit; }
if(isInGroup($_POST['player'], "Veteran")){ echo '/Command/ExecuteConsoleCommand:mvtp '.$_POST['player'].' VetFlatlands;§aYou were teleported to VetFlatlands.'; }else{ echo "§cYou don't have permission to go to VetFlatlands!"; }
?>