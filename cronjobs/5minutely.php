<?php
// This file is executed by cron every 5 minutes. It handles things such as message timed message sending.
include('includes/passwords.php');
include('includes/servers.php');
include('includes/mysql.php');
include('includes/functions.php');
$msgs = array( "§bDon't forget to check out the forums at §bhttp://forums.araeosia.com/ §b!", "§bDid you know that you can disable these messages with §c/optout§b?");
foreach($servers as $server){
	//Connect to the server
	$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("error: could not create socket\n");
	//Auth
	socket_connect($sock, $ips[$server], $ports['websend'][$server]) or die("error: could not connect to host\n");
	socket_write($sock, $command = md5($passwords['websend'])."<Password>", strlen($command) + 1);
	$Query = new MinecraftQuery();
	$players = array();
	try{
		$Query->Connect( $ips[$server], $ports['mc'][$server], 1 );
		$players = $Query->GetPlayers();
	}catch(MinecraftQueryException $e){
		// Only reason this query would fail is if the server is down. Report it
		$players = array();
	}
	// Msgs is formatted like this: id (autoinc) | name (text) | msgnum (int)
	$query = mysql_query("SELECT * FROM Msgs") or die(mysql_error());
	while($row = mysql_fetch_array($query)){
		$msgsent[$row['name']] = $row['msgnum'];
	}
	foreach($players as $player){
		if(isset($msgsent[$player])){
			$newnum = $msgsent[$player]+1;
			if(!array_key_exists($newnum, $msgs)){
				$newnum = 0;
			}
			mysql_query("UPDATE Msgs SET msgnum='$newnum' WHERE name='$player'");
			socket_write($sock, $command = "/Command/ExecuteConsoleCommand:echo ".$player." ".$msgs[$msgsent[$player]].";", strlen($command) + 1);
			socket_write($sock, $command = "/Command/ExecuteConsoleCommand:say Hello, ".$player.";", strlen($command) + 1);
		}else{
			// New player :o
			mysql_query("INSERT INTO Msgs (id, name, msgnum) VALUES ('NULL', '$player', '0')");
			socket_write($sock, $command = "/Command/ExecuteConsoleCommand:echo ".$player." ".$msgs[0].";", strlen($command) + 1);
		}
	}
}

?>