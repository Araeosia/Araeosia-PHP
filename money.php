<?php
include('includes/functions.php');
// This file handles the money blocks and quest blocks on the Araeosia RPG server.
// Fetch variables
$args = $_POST['args'];
$X = intval($args[1]);
$Y = intval($args[2]);
$Z = intval($args[3]);
$name = $_POST['player'];
$W = $_POST['playerWorld'];

// Connect to MySQL database
include('includes/mysql.php');
serverCheck($server, array('RPG'));

// Fetch current amount of money
$iconomyrow = mysql_fetch_array( mysql_query("SELECT * FROM iConomy WHERE username='$name' AND status=0") );
$iconomyvalue = $iconomyrow['balance'];
// Include the moneyblock locations
include('includes/blocks.php');
$searchquery = $X." ".$Y." ".$Z." ".$W;
$block = $blocks[$searchquery];

// Generate a random amount of money
$amount = rand(5,20);
if(rand(1,200)==10){
	$lucky = "true";
	$amount = rand(25,100)*10;
}

// Check for multipliers
if(in_array($block, $hardtoget)){
    $multiplier = rand(1,3) . "." . rand(0,9) . rand(0,9);
    $amount=ceil($amount*$multiplier);
}

// Calculate the new total
$newtotal = $amount + $iconomyvalue;

// See if the block has already been used
$blockrow = mysql_fetch_array( mysql_query("SELECT * FROM MoneyBlocks WHERE user='$name' AND block='$block'") );
$blockdone = $blockrow['block'];

// Set the status
if(!isset($block)){ exit; }elseif($blockdone == $block) { die("§eYou've already gotten the loot from this block!"); } else { $give = true; }

// Get current quests
$questquery = mysql_fetch_array(mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'"));
$perm = $questquery['permission'];

// Check if this block is a special mission block
if($block == "A") {
	$special = true;
	$quest = "The Tutorial";
	$specialmsg = "§e**You pick up some parts of the ship**\n§e[A] §bGordon_Cassidy§f: You find anything? Come back here and we'll chat.\n/Command/ExecuteConsoleCommand:give ".$name." flint:1270;";
}
if($block == "B" && $perm == "quest.current.coalcrisis.1.3") {
	$special = true;
	$quest = "Coal Crisis";
	$specialmsg = "§1**You pick up §216 coal§1.**\nOh, you found it! Bring it back here.";
}
if($block == "C" && $perm == "quest.current.caverncatastrophe.4.1"){
	$special = true;
	$quest = "Cavern Catastrophe";
	$specialmsg = "§1**You pick up §2Jeb Finch's Sword§1.**\n§[A] §bJeb_Finch§f: Oh, you found it!? Thank you so much! Bring it back here.";
}
if($block == "D" && $perm == "quest.current.caverncatastrophe.5.0"){
	$special = true;
	$quest = "Cavern Catastrophe";
	$specialmsg = "§1**You pick up §2Mechanic Mink's Toolbox§1.**\n§[A] §bMechanic_Mink§f: Great, bring it back to me so I can make those gears for you.";
}
if(!is_int($block)&&!$special){ die("You do not currently have the quest for this loot block."); }
// Echo to player
mysql_query("INSERT INTO MoneyBlocks (id, user, block) VALUES ('NULL', '$name', '$block')");
if($special) { die($specialmsg1); }
if($give&&!$lucky) { echo "/Command/ExecuteConsoleCommand:money give " . $name . " " . $amount . ";\n§6You picked up §2$" . $amount . "§6, giving you a total of §2$" . $newtotal . "§6 dollars!"; }
if($give&&$lucky) { echo "/Command/ExecuteConsoleCommand:money give " . $name . " " . $amount . ";\n§6You found a jackpot block, which contained §2$" . $amount . "§6! This gives you a total of §2$" . $newtotal . "§6 dollars!"; }
?>