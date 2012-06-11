<?php
while(1){
	$query = mysql_query("SELECT * FROM ChatToSend");
	while($row = mysql_fetch_array($query)){
		// Execute the actual code here
		//ChatToSend id (auto_inc, int) | sender (text) | channel (text) | message (text, html encoded) | prefix (text)
		$channelsin = array("A"=>array(), "S"=>array(), "T"=>array());
		$querychannels = mysql_query("SELECT * FROM ChannelsIn");
		while($rowchannels = mysql_fetch_array($querychannels)){ array_push($channelsin[$rowchannels['channel']], $rowchannels['sender']); }
		foreach($servers as $server){
			$jsonapi = new JSONAPI($ips[$server], $ports['jsonapi'][$server]);
			$players = $jsonapi->call('getPlayerNames', array());
			$players = $players['success'];
			foreach($players as $player){ if(in_array($channelsin[$row['channel']], $player)){ $jsonapi->call('message', array($player, html_entity_decode($row['message']))); }}
		}
	}
}
?>