<?php
// Fetch variables
$args = $_POST[args];
$X = $args[1];
$Y = $args[2];
$Z = $args[3];
$name = $_POST[player];
$W = $_POST[playerWorld];

// Connect to MySQL database
include('includes/mysql.php');

// Fetch current amount of money
$iconomytable = mysql_query("SELECT * FROM iConomy WHERE username='$name' AND status=0")
or die(mysql_error());
$iconomyrow = mysql_fetch_array( $iconomytable );
$iconomyvalue = $iconomyrow['balance'];

// Relate coordinates to block numbers
if($X=="774" && $Y=="59" && $Z=="717" && $W=="Araeosia") { $block = 1; }
if($X=="740" && $Y=="59" && $Z=="688" && $W=="Araeosia") { $block = 2; }
if($X=="758" && $Y=="75" && $Z=="623" && $W=="Araeosia") { $block = 3; }
if($X=="-345" && $Y=="63" && $Z=="-81" && $W=="Araeosia_tutorial2") { $block = 4; }
if($X=="-343" && $Y=="63" && $Z=="-81" && $W=="Araeosia_tutorial2") { $block = "A"; }
if($X=="727" && $Y=="67" && $Z=="705" && $W=="Araeosia") { $block = 5; }
if($X=="728" && $Y=="67" && $Z=="705" && $W=="Araeosia") { $block = 6; }
if($X=="729" && $Y=="67" && $Z=="705" && $W=="Araeosia") { $block = 7; }
if($X=="783" && $Y=="72" && $Z=="601" && $W=="Araeosia") { $block = 8; }
if($X=="521" && $Y=="69" && $Z=="633" && $W=="Araeosia") { $block = 9; }
if($X=="714" && $Y=="79" && $Z=="310" && $W=="Araeosia") { $block = 10; }
if($X=="829" && $Y=="68" && $Z=="65" && $W=="Araeosia") { $block = 11; }
if($X=="746" && $Y=="91" && $Z=="-234" && $W=="Araeosia") { $block = 12; }
if($X=="485" && $Y=="62" && $Z=="-750" && $W=="Araeosia") { $block = 13; }
if($X=="657" && $Y=="63" && $Z=="-783" && $W=="Araeosia") { $block = 14; }
if($X=="552" && $Y=="77" && $Z=="-877" && $W=="Araeosia") { $block = 15; }
if($X=="518" && $Y=="69" && $Z=="-812" && $W=="Araeosia") { $block = 16; }
if($X=="246" && $Y=="61" && $Z=="-932" && $W=="Araeosia") { $block = 17; }
if($X=="236" && $Y=="84" && $Z=="-926" && $W=="Araeosia") { $block = 18; }
if($X=="267" && $Y=="74" && $Z=="-877" && $W=="Araeosia") { $block = 19; }
if($X=="178" && $Y=="65" && $Z=="-657" && $W=="Araeosia") { $block = 20; }
if($X=="291" && $Y=="73" && $Z=="-441" && $W=="Araeosia") { $block = 21; }
if($X=="51" && $Y=="80" && $Z=="-112" && $W=="Araeosia") { $block = 22; }
if($X=="-182" && $Y=="82" && $Z=="-223" && $W=="Araeosia") { $block = 23; }
if($X=="-247" && $Y=="79" && $Z=="-167" && $W=="Araeosia") { $block = 24; }
if($X=="-340" && $Y=="45" && $Z=="-208" && $W=="Araeosia") { $block = 25; }
if($X=="-546" && $Y=="66" && $Z=="-70" && $W=="Araeosia") { $block = 26; }
if($X=="-434" && $Y=="73" && $Z=="62" && $W=="Araeosia") { $block = 27; }
if($X=="-181" && $Y=="68" && $Z=="49" && $W=="Araeosia") { $block = 28; }
if($X=="-148" && $Y=="66" && $Z=="296" && $W=="Araeosia") { $block = 29; }
if($X=="-103" && $Y=="81" && $Z=="446" && $W=="Araeosia") { $block = 30; }
if($X=="21" && $Y=="75" && $Z=="490" && $W=="Araeosia") { $block = 31; }
if($X=="-794" && $Y=="71" && $Z=="607" && $W=="Araeosia") { $block = 32; }
if($X=="-584" && $Y=="66" && $Z=="399" && $W=="Araeosia") { $block = 33; }
if($X=="-686" && $Y=="73" && $Z=="139" && $W=="Araeosia") { $block = 34; }
if($X=="-416" && $Y=="106" && $Z=="-76" && $W=="Araeosia") { $block = 35; }
if($X=="-605" && $Y=="71" && $Z=="-28" && $W=="Araeosia") { $block = 36; }
if($X=="-661" && $Y=="81" && $Z=="-84" && $W=="Araeosia") { $block = 37; }
if($X=="-646" && $Y=="76" && $Z=="-186" && $W=="Araeosia") { $block = 38; }
if($X=="-628" && $Y=="68" && $Z=="-334" && $W=="Araeosia") { $block = 39; }
if($X=="-640" && $Y=="104" && $Z=="-563" && $W=="Araeosia") { $block = 40; }
if($X=="-579" && $Y=="111" && $Z=="-597" && $W=="Araeosia") { $block = 41; }
if($X=="-581" && $Y=="111" && $Z=="-597" && $W=="Araeosia") { $block = 42; }
if($X=="-581" && $Y=="111" && $Z=="-599" && $W=="Araeosia") { $block = 43; }
if($X=="-579" && $Y=="111" && $Z=="-599" && $W=="Araeosia") { $block = 44; }
if($X=="469" && $Y=="66" && $Z=="-48" && $W=="Araeosia") { $block = 45; }
if($X=="552" && $Y=="68" && $Z=="-182" && $W=="Araeosia") { $block = 46; }
if($X=="692" && $Y=="83" && $Z=="118" && $W=="Araeosia") { $block = 47; }
if($X=="873" && $Y=="76" && $Z=="380" && $W=="Araeosia") { $block = 48; }
if($X=="958" && $Y=="73" && $Z=="634" && $W=="Araeosia") { $block = 49; }
if($X=="-388" && $Y=="66" && $Z=="-105" && $W=="Araeosia_instance") { $block = 50; }
if($X=="-386" && $Y=="66" && $Z=="-119" && $W=="Araeosia_instance") { $block = 51; }
if($X=="-399" && $Y=="66" && $Z=="-132" && $W=="Araeosia_instance") { $block = 52; }
if($X=="706" && $Y=="65" && $Z=="583" && $W=="Araeosia") { $block = "B"; }
if($X=="-315" && $Y=="81" && $Z=="29" && $W=="Araeosia") { $block = 53; }
if($X=="-328" && $Y=="65" && $Z=="52" && $W=="Araeosia") { $block = 54; }
if($X=="-305" && $Y=="85" && $Z=="174" && $W=="Araeosia") { $block = 55; }
if($X=="-294" && $Y=="64" && $Z=="278" && $W=="Araeosia") { $block = 56; }
if($X=="-323" && $Y=="75" && $Z=="300" && $W=="Araeosia") { $block = 57; }
if($X=="-98" && $Y=="82" && $Z=="556" && $W=="Araeosia") { $block = 58; }
if($X=="-134" && $Y=="77" && $Z=="634" && $W=="Araeosia") { $block = 59; }
if($X=="-126" && $Y=="106" && $Z=="965" && $W=="Araeosia") { $block = 60; }
if($X=="-260" && $Y=="66" && $Z=="941" && $W=="Araeosia") { $block = 61; }
if($X=="-337" && $Y=="70" && $Z=="825" && $W=="Araeosia") { $block = 62; }
if($X=="-152" && $Y=="69" && $Z=="538" && $W=="Araeosia") { $block = 63; }
if($X=="-558" && $Y=="59" && $Z=="379" && $W=="Araeosia") { $block = 64; }
if($X=="-667" && $Y=="102" && $Z=="-143" && $W=="Araeosia") { $block = 65; }
if($X=="-495" && $Y=="68" && $Z=="-193" && $W=="Araeosia") { $block = 66; }
if($X=="-457" && $Y=="95" && $Z=="-298" && $W=="Araeosia") { $block = 67; }
if($X=="-285" && $Y=="31" && $Z=="-338" && $W=="Araeosia") { $block = 68; }
if($X=="-310" && $Y=="35" && $Z=="-264" && $W=="Araeosia") { $block = 69; }
if($X=="-273" && $Y=="82" && $Z=="-132" && $W=="Araeosia") { $block = 70; }
if($X=="-758" && $Y=="72" && $Z=="-463" && $W=="Araeosia") { $block = 71; }
if($X=="-347" && $Y=="73" && $Z=="8" && $W=="Araeosia_tutorial2") { $block = 72; }
if($X=="-228" && $Y=="75" && $Z=="-185" && $W=="Araeosia") { $block = 73; }
if($X=="-226" && $Y=="89" && $Z=="-132" && $W=="Araeosia") { $block = 74; }
if($X=="-233" && $Y=="88" && $Z=="-204" && $W=="Araeosia") { $block = 75; }
if($X=="177" && $Y=="65" && $Z=="350" && $W=="Araeosia") { $block = 76; }
if($X=="396" && $Y=="80" && $Z=="408" && $W=="Araeosia") { $block = 77; }
if($X=="-290" && $Y=="103" && $Z=="-301" && $W=="Araeosia") { $block = 78; }
if($X=="-71" && $Y=="93" && $Z=="-148" && $W=="Araeosia") { $block = 79; }
if($X=="49" && $Y=="82" && $Z=="-136" && $W=="Araeosia") { $block = 80; }
if($X=="49" && $Y=="82" && $Z=="-150" && $W=="Araeosia") { $block = 81; }
if($X=="206" && $Y=="81" && $Z=="-98" && $W=="Araeosia") { $block = 82; }
if($X=="480" && $Y=="65" && $Z=="-6" && $W=="Araeosia") { $block = 83; }
if($X=="559" && $Y=="64" && $Z=="-27" && $W=="Araeosia") { $block = 84; }
if($X=="485" && $Y=="69" && $Z=="-48" && $W=="Araeosia") { $block = 85; }
if($X=="338" && $Y=="81" && $Z=="-124" && $W=="Araeosia") { $block = 86; }
if($X=="354" && $Y=="87" && $Z=="548" && $W=="Araeosia") { $block = 87; }
if($X=="170" && $Y=="106" && $Z=="22" && $W=="Araeosia") { $block = 88; }
if($X=="736" && $Y=="81" && $Z=="290" && $W=="Araeosia") { $block = 89; }
if($X=="-203" && $Y=="98" && $Z=="-187" && $W=="Araeosia") { $block = 90; }
if($X=="721" && $Y=="65" && $Z=="635" && $W=="Araeosia") { $block = 91; }
if($X=="813" && $Y=="74" && $Z=="665" && $W=="Araeosia") { $block = 92; }
if($X=="71" && $Y=="84" && $Z=="-204" && $W=="Araeosia") { $block = 93; }
if($X=="53" && $Y=="84" && $Z=="-166" && $W=="Araeosia") { $block = 94; }
if($X=="854" && $Y=="79" && $Z=="-767" && $W=="Araeosia") { $block = 95; }
if($X=="855" && $Y=="78" && $Z=="-767" && $W=="Araeosia") { $block = 96; }
if($X=="-687" && $Y=="72" && $Z=="204" && $W=="Araeosia") { $block = 97; }
if($X=="-334" && $Y=="61" && $Z=="-74" && $W=="Araeosia") { $block = 98; }
if($X=="744" && $Y=="59" && $Z=="717" && $W=="Araeosia") { $block = 99; }
if($X=="545" && $Y=="66" && $Z=="102" && $W=="Araeosia") { $block = 100; }
if($X=="-334" && $Y=="61" && $Z=="-74" && $W=="Araeosia_tutorial2") { $block = 101; }
if($X=="766" && $Y=="74" && $Z=="614" && $W=="Araeosia") { $block = 102; }
if($X=="-539" && $Y=="62" && $Z=="-205" && $W=="Araeosia") { $block = 103; }
if($X=="-315" && $Y=="71" && $Z=="-180" && $W=="Araeosia") { $block = 104; }
if($X=="-765" && $Y=="64" && $Z=="413" && $W=="Araeosia") { $block = 105; }
if($X=="-957" && $Y=="64" && $Z=="981" && $W=="Araeosia") { $block = "B"; }
if($X=="53" && $Y=="53" && $Z=="-157" && $W=="Araeosia") { $block = 106; }
if($X=="51" && $Y=="53" && $Z=="-161" && $W=="Araeosia") { $block = 107; }
if($X=="52" && $Y=="53" && $Z=="-157" && $W=="Araeosia") { $block = "C"; }
if($X=="-315" && $Y=="76" && $Z=="56" && $W=="Araeosia") { $block = 108; }
if($X=="-273" && $Y=="74" && $Z=="40" && $W=="Araeosia") { $block = 109; }
if($X=="-242" && $Y=="16" && $Z=="314" && $W=="Araeosia") { $block = 110; }
if($X=="510" && $Y=="69" && $Z=="-130" && $W=="Araeosia") { $block = "D"; }

// Generate a random amount of money
$amount = rand(5,20);
if(rand(1,200)==10){
	$lucky = "true";
	$amount = rand(25,100);
	$amount = $amount*10;
}

// Check for multipliers
if($block==68 || $block==69){
    $multiplier = rand(1,3) . "." . rand(0,9) . rand(0,9);
    $amount=ceil($amount*$multiplier);
}

// Calculate the new total
$newtotal = $amount + $iconomyvalue;

// See if the block has already been used
$blocktable = mysql_query("SELECT * FROM MoneyBlocks WHERE user='$name' AND block='$block'")
or die(mysql_error());
$blockrow = mysql_fetch_array( $blocktable );
$blockdone = $blockrow['block'];

// Set the status
if(!isset($block)){
	exit;
}
if($blockdone == $block) {
	$status = "alreadydone";
} else {
	$status = "using";
}

// Get current quests
#$questquery = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission LIKE'quest.current.%.%.%'") or die(mysql_error());

// Check if this block is a special mission block
if($block == "A") {
	$special = "true";
	$quest = "The Tutorial";
	$specialmsg2 = "§e**You pick up some parts of the ship**";
	$specialmsg1 = "§e[A] §bGordon_Cassidy§f: You find anything? Come back here and we'll chat.";
        $specialmsg3 = "/Command/ExecuteConsoleCommand:give " . $name . " flint:1270;";
}
if($block == "B" && $perm == "quest.current.coalcrisis.1.3") {
	$special = "true";
	$quest = "Coal Crisis";
	$specialmsg1 = "§1**You pick up §216 coal§1.**";
	$specialmsg2 = "Oh, you found it! Bring it back here.";
}
if($block == "B" && $perm != "quest.current.coalcrisis.1.3") {
	$special = "true";
	echo "You do not currently have the quest for this loot block.";
        exit;
}
if($block == "C" && $perm == "quest.current.caverncatastrophe.4.1"){
        $special = "true";
        $quest = "Cavern Catastrophe";
        $specialmsg1 = "§1**You pick up §2Jeb Finch's Sword§1.**";
        $specialmsg2 = "§[A] §bJeb_Finch§f: Oh, you found it!? Thank you so much! Bring it back here.";
}
if($block == "C" && $perm != "quest.current.caverncatastrophe.4.1") {
	$special = "true";
	echo "You do not currently have the quest for this loot block.";
    exit;
}
if($block == "D" && $perm == "quest.current.caverncatastrophe.5.0"){
        $special = "true";
        $quest = "Cavern Catastrophe";
        $specialmsg1 = "§1**You pick up §2Mechanic Mink's Toolbox§1.**";
        $specialmsg2 = "§[A] §bMechanic_Mink§f: Great, bring it back to me so I can make those gears for you.";
}
if($block == "D" && $perm != "quest.current.caverncatastrophe.5.0") {
	$special = "true";
	echo "You do not currently have the quest for this loot block.";
    exit;
}
// Echo to player
if($status == "alreadydone") {
	echo "§eYou've already gotten the loot from this block!";
	exit;
}
if($special == "true") {
	mysql_query("INSERT INTO MoneyBlocks (id, user, block) VALUES ('NULL', '$name', '$block')") or die(mysql_error());
	echo $specialmsg1;
	if(isset($specialmsg2)) {
		echo "\n" . $specialmsg2;
	}
	if(isset($specialmsg3)) {
		echo "\n" . $specialmsg3;
	}
	if(isset($specialmsg4)) {
		echo "\n" . $specialmsg4;
	}
}
if(!isset($status)) {
	echo "§4An error occurred. Please try again.";
	echo "If this error persists, please contact the administration.";
}
if($status == "using" && $lucky != "true" && $special != "true") {
	mysql_query("INSERT INTO MoneyBlocks (id, user, block) VALUES ('NULL', '$name', '$block')") or die(mysql_error());
	echo "/Command/ExecuteConsoleCommand:money give " . $name . " " . $amount . ";\n";
	echo "§6You picked up §2$" . $amount . "§6, giving you a total of §2$" . $newtotal . "§6 dollars!";
}
if($status == "using" && $lucky == "true" && $special != "true") {
	mysql_query("INSERT INTO MoneyBlocks (id, user, block) VALUES ('NULL', '$name', '$block')") or die(mysql_error());
	echo "/Command/ExecuteConsoleCommand:money give " . $name . " " . $amount . ";\n";
	echo "§6You found a jackpot block, which contained §2$" . $amount . "! ";
	echo "§6This gives you a total of §2$" . $newtotal . "§6 dollars!";
}
?>
