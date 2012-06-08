<?php
// This file contains the IP addresses and ports for each of Araeosia's servers, viewed from inside Araeosia's local network. They are not accessible from outside the network, so don't even bother ;) The $ports array also has subarrays for Websend and mcqueries. This file is used for things such as messaging players from PHP and querying each server for players.
$servers = array('RPG', 'Freebuild', 'Modded', 'Sandbox');
$ips = array( 'RPG' => '192.168.5.106', 'Freebuild' => '192.168.5.104', 'Modded' => '192.168.5.106', 'Sandbox' => '192.168.5.106');
$ports = array(
	'mc' => array('RPG' => 25566, 'Freebuild' => 25565, 'Modded' => 25567, 'Sandbox' => 25569),
	'websend' => array('RPG' => 4445, 'Freebuild' => 4445, 'Modded' => 4446, 'Sandbox' => 4447),
	'jsonapi' => array('RPG' => 20059, 'Freebuild' => 20059, 'Modded' => 20061, 'Sandbox' => 20063),
	'mysql' => array('RPG' => 3306, 'Freebuild' => 3306, 'Modded' => NULL, 'Sandbox' => 3306)
);
?>