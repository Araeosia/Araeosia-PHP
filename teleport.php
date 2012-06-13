<?php
// This file handles teleports on the 2 ships and 4 airships in Araeosia.
// Fetch variables
$name = $_POST['player'];
$args = $_POST['args'];
$blockX = $args[1];
$blockY = $args[2];
$blockZ = $args[3];
$blockWorld = $args[4];
if($args[5]=='L'){ $click = "Left"; }else{ $click = "Right"; }
// Connect to database
include('includes/mysql.php');
// Check for mission completion
$permcheck = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission='quest.current.ships.1.0'") or die(mysql_error());
$permrow = mysql_fetch_array($permcheck);
// Check which block is being used
if($blockX=="-339" && $blockY=="71" && $blockZ=="55" && $blockWorld=="Araeosia"){
    $location="Moku Harbor";
    $locid=0;
    $destlocation="Strongport";
    $destlocid=1;
    $money=250;
    $type = "sail";
}elseif($blockX=="676" && $blockY=="71" && $blockZ=="649" && $blockWorld=="Araeosia"){
    $location="Strongport";
    $locid="1";
    $destlocation="Moku Harbor";
    $destlocid="0";
    $money=250;
    $type = "sail";
}elseif($blockX=="605" && $blockY=="86" && $blockZ=="865" && $blockWorld=="Araeosia"){
    $location="Strongport";
    $locid=2;
    $destlocation="Darmouth";
    $destlocid=3;
    $money=630;
    $type = "fly";
}elseif($blockX=="-299" && $blockY=="71" && $blockZ=="240" && $blockWorld=="Araeosia"){
    $location="Darmouth";
    $locid=3;
    $destlocation="Araeos City";
    $destlocid=4;
    $money=350;
    $type = "fly";
}elseif($blockX=="-233" && $blockY=="105" && $blockZ=="-197" && $blockWorld=="Araeosia"){
    $location="Araeos City";
    $locid=4;
    $destlocation="Everstone City";
    $destlocid=5;
    $money=470;
    $type = "fly";
}elseif($blockX=="557" && $blockY=="76" && $blockZ=="-59" && $blockWorld=="Araeosia"){
    $location="Everstone City";
    $locid=5;
    $destlocation="Strongport";
    $destlocid=2;
    $money=720;
    $type = "fly";
} else { exit; }
$check = mysql_query("SELECT * FROM permissions WHERE name='$name' AND permission='quest.current.ships.1.0'");
$checkrow = mysql_fetch_array($check);
if($permrow['name']!=$name){ die('§cYou must complete the mission §2Ship Travel §c before riding.'); }
// Destination locations
$dests = array(
    0 => array( "X" => -341.5, "Y" => 71.5, "Z" => 55.5, "world" => "Araeosia"),
    1 => array( "X" => 673.5, "Y" => 71.5, "Z" => 649.5, "world" => "Araeosia"),
    2 => array( "X" => 600.5, "Y" => 85.5, "Z" => 863.5, "world" => "Araeosia"),
    3 => array( "X" => -302.5, "Y" => 70.5, "Z" => 238.5, "world" => "Araeosia"),
    4 => array( "X" => -231.5, "Y" => 104.5, "Z" => -201.5, "world" => "Araeosia"),
    5 => array( "X" => 551.5, "Y" => 75.5, "Z" => -59.5, "world" => "Araeosia")
);
// Check to see where the player is registered
$isin = array();
$isinquery = mysql_query("SELECT * FROM ShipsCheckin WHERE name='$name'");
while($isinrow = mysql_fetch_array($isinquery)){
    array_push($isin, $isinrow['loc']);
}
// Lets see if the player is registered here already
if(!in_array($locid, $isin)){
    echo '§cYou are now registered at §b' . $location . '§c. Left click for info.';
    mysql_query("INSERT INTO ShipsCheckin (id, name, loc) VALUES('NULL', '$name', '$locid')") or die(mysql_error());
    if($checkrow['name']==$name){ die('§e[A] §bSailor_Bryan§f: Good work, now come back here.'); }
    exit;
}
// Lets see if the player is registered at the destination
if(!in_array($destlocid, $isin)){
    echo '§cYou have to register at §b' . $destlocation . '§c before riding this ship.';
    exit;
}
if($click == "Left"){
    echo '§cThis ship block will take you from §b' . $location . ' §cto§b ' . $destlocation . "§c.\n";
    echo '§cIt will cost you §2$' . $money . ' §cto ' . $type . ' there. Right click to ' . $type . '.';
}
if($click == "Right"){
    $moneyquery = mysql_query("SELECT * FROM iConomy WHERE username='$name'") or die(mysql_error());
    $moneyrow = mysql_fetch_array($moneyquery);
    $currentmoney = $moneyrow['balance'];
    $newmoney = $currentmoney-$money;
    if($newmoney<0){
        echo "§cYou only had §2$" . $currentmoney . "§c, but needed §2$" . $money . "§c to ride this ship.\n";
        exit;
    }
    mysql_query("UPDATE iConomy SET balance='$newmoney' WHERE username='$name'") or die(mysql_error());
    echo "§cYou paid §2$" . $money . " §cto ride the ship to §b" . $destlocation . "§c!\n";
    echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " e:" . $dests[$destlocid]['world'] . ":" . $dests[$destlocid]['X'] . "," . $dests[$destlocid]['Y'] . "," . $dests[$destlocid]['Z'] . ";\n";
}
?>