<?php
include('includes/mysql.php');
include('includes/books.php');
// Book completion check
$query = mysql_query("SELECT * FROM BooksReading");
while($row = mysql_fetch_array($query)){
	$bookarray = $books[$row[book]];
	if(time()>=($bookarray[time]+$row[time])){
		$name = $row[name];
		mysql_query("DELETE FROM BooksReading WHERE name='$name");
		mysql_query("INSERT INTO BooksComplete (id, name, book) VALUES ('NULL', '$name', '$bookarray[num]')");
		$query2 = mysql_query("SELECT * FROM BooksComplete WHERE name='$name'");
		$completed = array();
		while($row2 = mysql_fetch_array($query2)){
			array_push($completed, $row2[book]);
		}
		$msgtosend = "§aYou finished reading the §bBook of ".$bookarray[name]."§a.";
		// I should connect to the server and send a message to the player, but that requires the new messaging backbone which I don't have yet.
		// Push the message to the MSGBot's list
//		mysql_query("INSERT INTO MSGTOSEND (id, recipient, message) VALUES ('NULL', '$name', '$msgtosend')");
		$count = count($completed);
		switch($count){
			case 8:
				mysql_query("INSERT INTO permissions (id, name, type, permission) VALUE ('NULL', '$name', '1', 'quest.available.bookbased.1')");
				break;
			case 16;
				mysql_query("INSERT INTO permissions (id, name, type, permission) VALUE ('NULL', '$name', '1', 'quest.available.bookbased.2')");
				break;
			case 24;
				mysql_query("INSERT INTO permissions (id, name, type, permission) VALUE ('NULL', '$name', '1', 'quest.available.bookbased.3')");
				break;
			case 32;
				mysql_query("INSERT INTO permissions (id, name, type, permission) VALUE ('NULL', '$name', '1', 'quest.available.bookbased.4')");
				break;
		}
	}
}

?>