<?php
// This file handles the lottery system for the Araeosia RPG. It's not currently used.
// Fetch variables
$name = $_POST['player'];
$currenttype = "50/50 split";
$typedescription = "This means that if you win, you'll recieve 50% of the profit.\n";

// Connect to MySQL database
include('includes/mysql.php');

// Fetch status
if($hasread){}


// Echo output to player
if($status == "info1"){
    echo "This is the lottery system. Here you can purchase lottery tickets.\n";
    echo "The current lottery type is: " . $currenttype . ".\n";
    echo $typedescription;
    
}
?>
