<?php
// Fetch variables
$name = $_POST['player'];
$playerWorld = $_POST['playerWorld'];
$args = $_POST['args'];
$cmd = strtolower($args[1]);
// Make a MySQL connection
include('includes/mysql.php');
include('includes/staff.php');
// Handle commands
switch($cmd){
	case "block":
	case "ignore":
	// Check to see if command syntax is correct.
		if(strpos($args[2], "[")!=false || strpos($args[2], "]")!=false){ die('§cYou\'re not supposed to include the [] brackets, just the name.'); }
		$blockee = strtolower(htmlspecialchars($args[2]));
		if(!isset($blockee)){ die('§cImproper usage! Correct usage is: §a/privacy ignore [player]'); }
	// Check to see if user has been on server before
		$query = mysql_query("SELECT * FROM TrueGroups WHERE name='$blockee'");
		$query = mysql_fetch_array($query);
		if($query==false){ die('§cThe player §b'.$blockee.'§c has never been on this server before!'); }
	// Check to see if user is staff
		if(in_array($blockee, $staff)){ die('§cBecause §b'.$blockee.'§c is staff on Araeosia, you cannot block him/her.'); }
	// Check to see if user is already blocked
		$query = mysql_query("SELECT * FROM Blocks WHERE blockee='$blockee' AND name='$name'");
		$query = mysql_fetch_array($query);
		if($query!=false){ die('§b'.$blockee.' §4is already on your blocked players list!'); }
	// Ignore code
		mysql_query("INSERT INTO Blocks VALUES ('NULL', '$name', '$blockee')") or die(mysql_error());
		echo "§b".$blockee."§a has been added to your blocked players list!";
		break;
	case "unblock":
	case "unignore":
	// Check to make sure user is already on blocked list
		if(strpos($args[2], "[")!=false || strpos($args[2], "]")!=false){ die('§cYou\'re not supposed to include the [] brackets, just the name.'); }
		$blockee = strtolower(htmlspecialchars($args[2]));
		$query = mysql_query("SELECT * FROM Blocks WHERE name='$name' AND blockee='$blockee'");
		$query = mysql_fetch_array($query);
		if($query==false){ die('§b'.$blockee.' §4isn\'t on your blocked players list!'); }
		mysql_query("DELETE FROM Blocks WHERE name='$name' AND blockee='$blockee'");
		echo "§b".$blockee."§a has been removed from your blocked players list.";
		break;
	case "list":
		$blocked = array();
		$query = mysql_query("SELECT * FROM Blocks WHERE name='$name'");
		if(!$query){ die('§cYou have not blocked any players!'); }
		while($row = mysql_fetch_array($query)){
			array_push($blocked, $row['blockee']);
		}
		echo "§a------- Blocked Players -------\n";
		foreach($blocked as $blockedplayer){
			$num = $num+1;
			echo "§2".$num.". §f- §b".$blockedplayer."\n";
		}
		break;
	case "whoblocked":
		if(!in_array(strtolower($name), $staff)){ die('§cOnly staff can use this command!'); }
		if(strpos($args[2], "[")!=false || strpos($args[2], "]")!=false){ die('§cYou\'re not supposed to include the [] brackets, just the name.'); }
		$blockee = strtolower(htmlspecialchars($args[2]));
		$blockers = array();
		$query = mysql_query("SELECT * FROM Blocks WHERE blockee='$blockee'");
		while($row = mysql_fetch_array($query)){
			array_push($blockers, $row['name']);
		}
		echo "§a------- Players who blocked §b".$blockee."§a -------\n";
		foreach($blockers as $blocker){
			$num = $num+1;
			echo "§2".$num.". §f- §b".$blocker."\n";
		}
	case "help":
	default:
	// Echo help message
		echo "§a/privacy ignore [player] §f- Add a player to your ignore list.\n§a/privacy unignore [player] §f- Remove a player from your ignore list.\n§a/privacy block [player] §f- Alias for /privacy ignore [player].\n§a/privacy unblock [player] §f- Alias for /privacy unignore [player].\n§a/privacy list §f- Lists blocked players.\n§a/privacy help §f- Displays this help message.";
		break;
}
?>