<?php
include('includes/passwords.php');
include('includes/servers.php');
$msgs = array( "§bDon't forget to check out the forums at §bhttp://forums.araeosia.com/ §b!", "§bDid you know that you can disable these messages with §c/optout§b?");
foreach($servers as $server){
	//Connect to the server
	$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("error: could not create socket\n");
	//Auth
	socket_connect($sock, $ips[$server], $ports[$server][websend]) or die("error: could not connect to host\n");
	socket_write($sock, $command = md5($passwords[websend])."<Password>", strlen($command) + 1);
	$Query = new MinecraftQuery();
	try{
		$Query->Connect( $ips[$server], $ports[mc][$server], 1 );
		$players = $Query->GetPlayers();
	}catch(MinecraftQueryException $e){
		// Only reason this query would fail is if the server is down. Report it
		$players = array();
	}
	$query = mysql_query("SELECT * FROM Msgs");
	while($row = $query){
		$msgsent[$row[player]] = $row[msgnum]
	}
	foreach($players as $player){
		$newnum = $msgsent[$player]+1;
		mysql_query("UPDATE Msgs SET msgnum='$newnum' WHERE player='$player'");
		socket_write($sock, $command = "/Command/ExecuteConsoleCommand:echo ".$playername." ".$msgs[$msgsent[$player]].";", strlen($command) + 1);
	}
}

?>