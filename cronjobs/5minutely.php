<?php
// This file is executed by cron every 5 minutes. It handles things such as message timed message sending.
include('/home/agentkid/web/includes/passwords.php');
include('/home/agentkid/web/includes/servers.php');
include('/home/agentkid/web/includes/mysql.php');
include('/home/agentkid/web/includes/functions.php');
$timestamp = "[".date('m-d-y H:i:s', time())."] ";
$log = $timestamp."Attempting to cycle through ".implode($servers, ', ').".\n";
$msgs = array( "§4[S] §aCheck out the forums at §bforums.araeosia.com§a!", "§4[S] §bServer maintenance happens nightly around 1AM-2AM EST", "§4[S] §bThe world map for Araeosia RPG is available at map.araeosia.com", "§4[S] §bYou can disable these messages with §c/optout§b!", "§4[S] §bAraeosia is accepting staff applications on the forums!", "§4[S] §bAraeosia RPG is currently in beta testing! Help us find bugs!", "§4[S] §bAraeosia has adopted a No-Mercy policy. §e/nomercy §bfor info.", "§4[S] §bThe rules are available at §e/rules§b.", "§4[S] §bAraeosia will soon have a Survival Games server! :O");
$query = mysql_query("SELECT * FROM optouts");
$optouts = array();
while($row = mysql_fetch_array($query)){ array_push($optouts, $row['name']); }
foreach($servers as $server){
	$json = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$Query = new MinecraftQuery();
	$players = array();
	try{
		$Query->Connect( $ips[$server], $ports['mc'][$server], 1 );
		$players = $Query->GetPlayers();
	}catch(MinecraftQueryException $e){ $players = array(); }
	if($players==false){ $players = array();}
	// Msgs is formatted like this: id (autoinc, primarykey) | name (text) | msgnum (int)
	$query = mysql_query("SELECT * FROM Msgs") or die(mysql_error());
	while($row = mysql_fetch_array($query)){ $msgsent[$row['name']] = $row['msgnum']; }
	foreach($players as $player){
		if(!in_array($player, $optouts)){
			if(isset($msgsent[$player])){
				$newnum = $msgsent[$player]+1;
				if(!array_key_exists($newnum, $msgs)){ $newnum = 0; }
				mysql_query("UPDATE Msgs SET msgnum='$newnum' WHERE name='$player'");
				$json->call('sendMessage', array($player, $msgs[$newnum]));
				$log = $log.$timestamp."Echoed message ".$newnum. " to ".$player." on ".$server.".\n";
			}else{
				// New player :o
				mysql_query("INSERT INTO Msgs (id, name, msgnum) VALUES ('NULL', '$player', '0')");
				$json->call('sendMessage', array($player, $msgs[0]));
				$log = $log.$timestamp."New player: ".$player.". Echoing first message on ".$server.".\n";
			}
		}else{ $log = $log.$timestamp."Skipped player ".$player." on ".$server." due to opt-out.\n"; }
	}
}
$logfile = fopen('/home/agentkid/logs/messages.log', 'a');
fwrite($logfile, $log);
fclose($logfile);
?>