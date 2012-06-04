<?php
$name = $_POST[name];
$args = $_POST[args];
$booknew = $books[$args[1]];

// Include SQL
include('includes/mysql.php');
include('includes/staff.php');
include('includes/classes.php');
include('includes/books.php');

// Build an array of the completed books
$query = mysql_query("SELECT * FROM BooksComplete WHERE name='$name'");
$completed = array();
while($row = mysql_fetch_array($query)){
	array_push($completed, $row[book]);
}
$query = mysql_query("SELECT * FROM BooksReading WHERE name='$name'");
if($query!=false){
	$row = mysql_fetch_array($query);
	$stat = "currentread"
	$bookold = $books[$row[book]];
}
$time = time();
// Echo outputs
switch($stat){
	case "currentread":
		echo "§cYou are cannot read the §bBook of ".$booknew[name]." §cuntil you finish reading the §bBook of ".$bookold[name].".\n";
		break;
	case "newread":
		mysql_query("INSERT INTO BooksReading (id, name, book, time) VALUES ('NULL', '$name', '$bookold[num]', '$time')");
		break;
}
?>