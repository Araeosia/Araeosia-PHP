<?php
include('includes/mysql.php');
$name = $_POST['player'];
$query = mysql_fetch_array(mysql_query("SELECT * FROM KickMe WHERE name='$name'"));
if(!$query){
    mysql_query("INSERT INTO KickMe (id, name) VALUES ('NULL', '$name')");
    echo "Evaded.";
}
?>
