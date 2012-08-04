<?php
// This file contains the IP addresses and ports for each of Araeosia's servers, viewed from inside Araeosia's local network. They are not accessible from outside the network, so don't even bother ;) The $ports array also has subarrays for Websend and mcqueries. This file is used for things such as messaging players from PHP and querying each server for players.
$servers = array('Freebuild', 'Modded', 'Sandbox', 'Vanilla', 'Eco', '2', 'RPG');
#$servers = array('ServerA', 'ServerB', 'ServerC', 'ServerD', 'ServerE', 'ServerF', 'ServerG', 'ServerH', 'ServerI');
$serverNames = array('ServerA' => 'Main', 'ServerB' => 'Survival', 'ServerC' => 'Creative', 'ServerD' => 'RPG', 'ServerE' => 'Modded', 'ServerF' => '2', 'ServerG' => 'Vanilla', 'ServerH' => 'Eco', 'ServerI' => 'Event');

#$servers = array('ServerA', 'ServerB', 'ServerC', 'ServerD', 'ServerE', 'ServerF', 'ServerG', 'ServerH', 'ServerI');
#$serverNames = array('ServerA' => 'Main', 'ServerB' => 'Survival', 'ServerC' => 'Creative', 'ServerD' => 'RPG', 'ServerE' => 'Modded', 'ServerF' => '2', 'ServerG' => 'Vanilla', 'ServerH' => 'Eco', 'ServerI' => 'Event');

#$ips = array( 'ServerA' => '192.168.5.104', 'ServerB' => '192.168.5.104', 'ServerC' => '192.168.5.104', 'ServerD' => '192.168.5.106', 'ServerE' => '192.168.5.106', 'ServerF' => '192.168.5.106', 'ServerG' => '192.168.5.102', 'ServerH' => '192.168.5.102', 'ServerI' => NULL);

$chatservers = array('Freebuild', 'Modded', 'Sandbox', 'Vanilla', 'Eco');
$ips = array( 'RPG' => '192.168.5.106', 'Freebuild' => '192.168.5.104', 'Modded' => '192.168.5.106', 'Sandbox' => '192.168.5.106', '2' => '192.168.5.106', 'Tekkit' => '192.168.5.102', 'Vanilla' => '192.168.5.102', 'Eco' => '192.168.5.102');
$ports = array(
	'mc' => array('RPG' => 25566, 'Freebuild' => 25565, 'Modded' => 25567, 'Sandbox' => 25569, '2' => 25570, 'Tekkit' => 25566, 'Vanilla' => 25569, 'Eco' => 25570),
	'websend' => array('RPG' => 4445, 'Freebuild' => 4445, 'Modded' => 4446, 'Sandbox' => 4447, '2' => '4448', 'Tekkit' => 4446, 'Vanilla' => 4446, 'Eco' => 4447),
	'jsonapi' => array('RPG' => 20059, 'Freebuild' => 20059, 'Modded' => 20062, 'Sandbox' => 20065, '2' => 20068, 'Modded' => 20062, 'Vanilla' => 20062, 'Eco' => 20065),
	'mysql' => array('RPG' => 3306, 'Freebuild' => 3306, 'Modded' => NULL, 'Sandbox' => 3306, '2' => 3306, 'Tekkit' => NULL, 'Vanilla' => NULL, 'Eco' => 3306)
);
?>