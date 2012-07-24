<?php
// This file handles setting your website password from ingame. It's currently not used in Araeosia.
$args = $_POST['args'];
$name = $_POST['player'];
// Connect to the MySQL server
include('includes/mysql.php');
include('includes/functions.php');
serverCheck($server, true);

// Set status
if($args[1] == "changing" && $args[2] == "confirmed"){
    $changing = "true";
    $confirmed = "true";
} elseif ($args[1] == "changing" && $args[2] == "unconfirmed"){
    $changing = "true";
    $confirmed = "false";
} elseif($args[1] == "set" && $args[2] == "confirmed"){
    $changing = "false";
    $confirmed = "true";
} elseif ($args[1] == "set" && $args[2] == "unconfirmed"){
    $changing = "false";
    $confirmed = "false";
} else {
    echo "Failure! Line <22. Exiting...\n";
    echo "Please tell a staff member about this error:\n";
    echo "Error: " . $changing . " : " . $confirmed . " : " . $args[1] . " : " . $args[2];
    exit;
}

// No point in hashing at all if the player already has a password
$querycheck = mysql_query("SELECT * FROM Passwords WHERE name='$name'");
$currentpass = mysql_fetch_array($querycheck);
// If the password has been set and the player isn't changing it, exit.
if(isset($currentpass) && $changing == "false"){
    echo "You've already set your password! If you wish to change it,\nuse /changepass [password].";
    exit;
// If the password hasn't been set and the player is trying to change it, exit.
} elseif(!isset($currentpass) && $changing == "true"){
    echo "You haven't set your password yet! If you wish to set it,\nuse /setpass [password].";
    exit;
}
?>
