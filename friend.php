<?php
include('includes/mysql.php');
$name = $_POST[player];
$args = $_POST[args];
$currentquery = mysql_query("SELECT * FROM Friends WHERE name='$name' AND type='1'");
$current = array();
while($currentrow=$currentquery){
    array_push($current, $currentrow[friend]);
}
$askingquery = mysql_query("SELECT * FROM Friends WHERE name='$name' AND type='0'");
$asking = array();
while($askingrow=$askingquery){
    array_push($asking, $askingrow[friend]);
}
$permsquery = mysql_query("SELECT * FROM permissions WHERE name='$args[2]'");
$permsrow = mysql_fetch_array($permsquery);
if($args[1]=="add"){
    if($permsrow[name]!=$args[2]){
        echo "§b " . $args[2] . " §4has never been on the server before!";
        exit;
    }
    if(in_array($args[2], $current)){
        echo "§b" . $args[2] . "§4 is already on your friends list!";
        exit;
    }
    if(in_array($args[2], $asking)){
        echo "§b" . $args[2] . "§4 has not accepted your friend request yet!";
        exit;
    }
    $blockquery = mysql_query("SELECT * FROM Blocks WHERE name='$args[2]' AND blockee='$name'");
    $blockrow = mysql_fetch_array($blockquery);
    if($blockrow[blockee]==$name){
        echo '§b ' . $args[2] . " §4has blocked you from contact!";
        exit;
    }
    mysql_query("INSERT INTO Friends (id, name, friend, type) VALUES('NULL', '$name', '$args[2]', '0')");
    echo "§aYou have added §b" . $args[2] . "§a as a friend!";
} elseif($args[1]=="remove"){
    if(!in_array($args[2], $current)){
        echo "§b" . $args[2] . "§4 is not on your friends list!";
        exit;
    }
    mysql_query("DELETE FROM Friends WHERE name='$name' AND friend='$args[2]'");
    echo '§aYou have removed §b' . $args[2] . '§a from your friends list!';
} elseif($args[1]=="list"){
    echo '§4--------Friends--------\n§b';
    echo $current[0];
    array_shift($current);
    echo implode(' §a- Confirmed\n§b', $current);
    echo '\n';
    echo $asking[0];
    array_shift($asking);
    echo implode(' §a- Pending\n§b', $asking);
} elseif($args[1]=="tp"){
    
} else {
    echo "§cUnrecognized command. §b/friend help§c for usage.";
}
?>
