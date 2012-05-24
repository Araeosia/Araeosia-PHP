<?php
// Connect to MySQL server
include("includes/mysql.php");
// Fetch vars
$args = $_POST[args];
$name = $_POST[player];
// Build an array of players currently in the same group as the triggering player
$ingroup = array();
$querymast = mysql_query("SELECT * FROM GroupPlayers WHERE name='$name'");
$query = mysql_fetch_array($querymast);
$group = $query[group];
$query2 = mysql_query("SELECT * FROM GroupPlayers WHERE group='$group'");
while($row = mysql_fetch_array($query2)){
    array_push($ingroup, $row[name]);
}
if($args[1]=="help"){
    // Echo the help message
    echo "";
}elseif($args[1]=="current" || $args[1]=="info"){
    // Fetch information about the player's group
    if($querymast==false){ die('§4You are not in a group!'); }
    $query = mysql_fetch_array($query);
    $query = mysql_query("SELECT * FROM Groups WHERE group='$group'");
    echo "§c-----" . $group . "-----\n";
    echo "§lOwned by: §b" . $query[owner] . "§f.\n";
    $date = date('jS \of F Y \at h:i:s A', $query[created]);
    echo "§lCreated on the §a" . $date . "§f.\n";
    if($query[anyadd]==true){ echo "§a§lAnyone§a can invite other players to the group.\n";
    } else { echo "§cOnly §l" . $query[owner] . " §ccan add new players to the group.\n"; }
}elseif($args[1]=="list" || $args[1]=="who"){
    if($querymast==false){ die('§4You are not in a group!'); }
    echo "§c-----" . $group . "-----\n";
    foreach($ingroup as $player){
        $num = $num+1;
        echo "§a" . $num . ": §b" . $player . "\n";
    }
}elseif($args[1]=="tp" || $args[1]=="teleport"){
    if($querymast==false){ die('§4You are not in a group!'); }
    if(!in_array($args[2], $ingroup)){ die('§4' . $args[2] . ' is not in your group!'); }
    echo "/Command/ExecuteConsoleCommand:mvtp " . $name . " p:" . $args[2] . "\n;";
}elseif($args[1]=="leave"){
    if($querymast==false){ die('§4You are not in a group!'); }
    mysql_query("DELETE FROM GroupsPlayers WHERE name='$name'") or die(mysql_error());
    echo "§cYou left §b" . $querymast . "§c.\n";
}elseif($args[1]=="create"){
    if($querymast!=false){ die('§4You are already in a group! Please leave with §b/group leave\n'); }
    if(!isset($args[2])){ die('§4Invalid usage! Correct usage is: §a/group create [Name]\n'); }
    $groupname = htmlspecialchars($args[2]);
    $testquery = mysql_query("SELECT * FROM Groups WHERE group='$groupname'");
    if($testquery == false){ die('§4There is already a group with this name!'); }
    $time = time();
    mysql_query("INSERT INTO Groups (id, group, owner, created, anyadd) VALUES ('NULL', '$groupname', '$name', '$time', false)");
    echo "§bYou created the group §a" . $groupname . "§b.";
}elseif($args[1]=="invite"){
    if($querymast==false){ die('§4You are not in a group!'); }
    $queryinv = mysql_query("SELECT * FROM Groups WHERE group='$querymast[group]'");
    $rowinv = mysql_fetch_array($queryinv);
    if($rowinv[alladd]==false){ die('§4Your group doesn\'t allow members to invite players.\n§bTalk to ' . $rowinv[owner] . ' to invite other people.'); }
}elseif($args[1]=="modify"){
    if($querymast==false){ die('§4You are not in a group!'); }
    $querymod = mysql_query("SELECT * FROM Groups WHERE owner='$name'");
    if($querymod==false){ die('§4You are not the owner of this group!'); }
    if(!isset($args[2])){ die('§4Invalid usage! Correct usage is: §a/group modify [Type]\n§4Valid types are: name, alladd'); }
    if($args[2]=="name"){
        if(!isset($args[3])){ die('§4Invalid usage! correct usage is: §a/group modify name [Name]'); }
        if($querymod[group]==htmlspecialchars($args[3])){ die('§4That is already the name of the group!'); }
        $newname = htmlspecialchars($args[3]);
        mysql_query("UPDATE Groups SET group='$newname' WHERE group='$querymod[group]'");
    }elseif($args[2]=="alladd"){
        if(!isset($args[3])){ die('§4Invalid usage! correct usage is: §a/group modify alladd [true/false]'); }
        if($args[3]==false){ }elseif($args[3]==true){ } else { die('§4Invalid usage! correct usage is: §a/group modify alladd [true/false]'); }
        if($groupmod[alladd]==false){ $end = "'t"; } elseif($groupmod[alladd]==true){ $end = ""; }
        if($querymod[alladd]==$args[3]){ die('§4Everyone already can' . $end . " add other people to the group!"); }
        mysql_query("UPDATE Groups SET alladd='$args[3]' WHERE group='$querymod[group]'");
    }
}
?>
