<?php
include('includes/passwords.php');
include('includes/servers.php');
include('includes/mysql.php');
include('includes/functions.php');
// This file is executed every 10 minutes by cron. It handles things such as relocating the Health Balloons.
#foreach($servers2 as $server){
$server = "Freebuild";
	$json = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$players = $json->call('getPlayerNames');
	$players = $players['success'];
	var_dump($players);
	foreach($players as $player){
		$playerobject = $json->call('getPlayer', array($player));
		var_dump($playerobject);
	}
	
?>