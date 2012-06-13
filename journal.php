<?php
// This file handles the custom questing journal on the Araeosia RPG server.
// Fetch variables
$name = $_POST['player'];
$args = $_POST['args'];

// Make a MySQL Connection
include('includes/mysql.php');
// Check if the user has previously opened the journal
$journaltable = mysql_query("SELECT * FROM JournalOpened WHERE name='$name'")
or die(mysql_error());
$journalrow = mysql_fetch_array( $journaltable );
if($journalrow['name'] != $name){
  $status = "cover";
  unset($args[1]);
}
if($journalrow['name'] == $name && $journalrow['type'] == 1){
  $status = "1sttoc";
  unset($arg[1]);
}
if($journalrow['type'] == 2 && !isset($args[1]) || $args[1] == "info"){
  $status = "toc";
}
if(isset($args[1])){
  if($args[1] == "available"){
    $status = "available";
  }
  if($args[1] == "completed"){
    $status = "completed";
  }
  if($args[1] == "current"){
    $status = "current";
  }
}
  
// Get quests array
$completedtable = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.completed.%.%.%'") or die(mysql_error()); 

// Create the array
$completedarray = array();

// Fill the array
while ($completedrow = mysql_fetch_array( $completedtable )) {
  array_push($completedarray, $completedrow['permission']);
}

// Get current quest
$currenttable = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'") or die(mysql_error()); 
$currentrow = mysql_fetch_array($currenttable);
$currentperm = $currentrow['permission'];

// Create output array for Available quests
$namearraya = array();
$giverarraya = array();
$diffarraya = array();

// Create output array for Completed quests
$namearrayc = array();
$giverarrayc = array();
$diffarrayc = array();

// Check for available quests
if(in_array("quest.completed.caverncatastrophe.1.2",$completedarray)) {
  array_push($namearraya,"Cavern Catastrophe, Part 2");
  array_push($giverarraya,"Dimitri_Macintosh");
  array_push($diffarraya,"normal");
}

// Check for completed quests
if(in_array("quest.completed.caverncatastrophe.1.2",$completedarray)) {
  array_push($namearrayc,"Cavern Catastrophe, Part 1");
  array_push($giverarrayc, "Dimitri_Macintosh");
  array_push($diffarrayc,"normal");
}

// Check to see if the number of quests will fit on the screen
$linesremaininga = count($availablearray);
$linesremainingc = count($completedarray);


// First time that the user opened the journal
if($status=="cover"){
  echo "§a**You wipe the dust off the cover. It reads:\n";
  echo "§6|--------------------------------------------------|\n";
  echo "§6                                                                  \n";
  echo "§6§9                     Enchanted Quest Journal                   §6\n";
  echo "§6§9  §6\n";
  echo "§6§9   §6\n";
  echo "§6§c                    There is adventure within!                    §6\n";
  echo "§6                                                                  \n";
  echo "§6§4                           Click again!                           §6\n";
  echo "§6                                                                  \n";
  echo "§6|--------------------------------------------------|\n";
  mysql_query("INSERT INTO JournalOpened (id, name, type) VALUES ('NULL', '$name', '1')");
}
if($status=="1sttoc"){
  echo "§6|--------------------------------------------------|\n";
  echo "§6 §2Hello! Welcome to your Journal! If you're seeing §6\n";
  echo "§6 §2this, it's because you haven't removed this page §6\n";
  echo "§6 §2yet, meaning this book is brand new and unused.  \n";
  echo "§6 §2This journal is here to keep a record of all of  §6\n";
  echo "§6 §2your quests and missions, in addition to guiding §6\n";
  echo "§6 §2you to find new ones. This book has been         \n";
  echo "§6 §2enchanted by a wizard, who has made it access    §6\n";
  echo "§6 §2ible from anywhere. Commands to use the it:      §6\n";
  echo "§6                                                  §6\n";
  echo "§6 §a/journal available §f- §bList available quests       §6\n";
  echo "§6 §a/journal completed §f- §bList completed quests       §6\n";
  echo "§6 §a/journal current §f- §bShow info about current quest §6\n";
  echo "§6 §a/journal info §f- §bDisplay the Table of Contents    §6\n";
  echo "§6|--------------------------------------------------|";
  mysql_query("DELETE FROM JournalOpened WHERE name='$name' AND type='1'");
  mysql_query("INSERT INTO JournalOpened (id, name, type) VALUES('NULL', '$name', '2')");
}
// "/journal" or "/journal info" commands
if($status=="toc"){
  echo "§6|---------------------------------------------------|\n";
  echo "§6                            §4Table of Contents§f:                           §6\n";
  echo "§6  §2The journal is here to help you with quests. To use it, use §6\n";
  echo "§6  §2the commands listed here for different functions:           §6\n";
  echo "§6                                                   §6\n";
  echo "§6  §a/journal available §f- §bList available quests       §6\n";
  echo "§6  §a/journal completed §f- §bList completed quests       §6\n";
  echo "§6  §a/journal current §f- §bShow info about current quest §6\n";
  echo "§6  §a/journal info §f- §bDisplay the Table of Contents    §6\n";
  echo "§6|--------------------------------------------------§6|";
}
if($status=="available"){
    echo "§cDIFFICULTY   -   §bQUEST NAME   -   QUEST GIVER";
    while($linesremaininga != 0) {
      $number = $number+1;
      echo "§4" . $number . ": §c" . strtoupper($diffarraya[0]) . " - §b" . $namearraya[0] . "§f - §b" . $giverarraya[0];
      $linesremaininga = $linesremaininga-1; 
    }
}
if($status=="completed"){
    echo "§4#: §cDIFFICULTY   -   §bQUEST NAME   -   QUEST GIVER\n";
    echo "§6---------------------------------------------------§6-\n";
    while($linesremainingc != 0) {
	  $number = $number+1;
      echo "§4" . $number . ": §c" . strtoupper($diffarrayc[0]) . " - §b" . $namearrayc[0] . "§f - §b" . $giverarrayc[0] . "\n";
      $linesremainingc = $linesremainingc-1;
    } 
}
  ?>​

