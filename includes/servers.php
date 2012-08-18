<?php
// This file contains the IP addresses and ports for each of Araeosia's servers, viewed from inside Araeosia's local network. They are not accessible from outside the network, so don't even bother ;) The $ports array also has subarrays for Websend and mcqueries. This file is used for things such as messaging players from PHP and querying each server for players.
#$servers = array('Freebuild', 'Modded', 'Sandbox', 'Vanilla', 'Eco', '2', 'RPG');
$servers = array('ServerA', 'ServerB', 'ServerC', 'ServerD', 'ServerE', 'ServerF', 'ServerG', 'ServerH', 'ServerI', 'ServerJ', 'ServerK');
$serverNames = array('ServerA' => 'Main', 'ServerB' => 'Survival', 'ServerC' => 'Creative', 'ServerD' => 'RPG', 'ServerE' => 'Modded', 'ServerF' => '2', 'ServerG' => 'Vanilla', 'ServerH' => 'Eco', 'ServerI' => 'Event', 'ServerJ' => 'Gen', 'ServerK' => 'Sandbox');
$serversChat = array('ServerA', 'ServerE', 'ServerH', 'ServerK');

$tf2servers = array(
    'servers' => array('A', 'B', 'C', 'D'),
    'ips' => array('A' => '192.168.5.106', 'B' => '192.168.5.106', 'C' => '192.168.5.106', 'D' => '192.168.5.106'),
    'ports' => array('A' => 27015, 'B' => 27045, 'C' => 27060, 'D' => 27075),
);
$ips = array( 'ServerA' => '192.168.5.104', 'ServerB' => '192.168.5.104', 'ServerC' => '192.168.5.104', 'ServerD' => '192.168.5.106', 'ServerE' => '192.168.5.106', 'ServerF' => '192.168.5.106', 'ServerG' => '192.168.5.102', 'ServerH' => '192.168.5.102', 'ServerI' => NULL, 'ServerJ' => '192.168.5.106', 'ServerK' => '192.168.5.106');
$ports = array(
    'mc' => array('ServerA' => 25565, 'ServerB' => 25566, 'ServerC' => 25567, 'ServerD' => 25568, 'ServerE' => 25569, 'ServerF' => 25570, 'ServerG' => 25571, 'ServerH' => 25572, 'ServerI' => 25573, 'ServerJ' => 25574, 'ServerK' => 25575),
    'websend' => array('ServerA' => 4445, 'ServerB' => 4446, 'ServerC' => 4447, 'ServerD' => 4448, 'ServerE' => 4449, 'ServerF' => 4450, 'ServerG' => 4451, 'ServerH' => 4452, 'ServerI' => 4453, 'ServerJ' => 4454, 'ServerK' => 4455),
    'jsonapi' => array('ServerA' => 20059, 'ServerB' => 20062, 'ServerC' => 20065, 'ServerD' => 20068, 'ServerE' => 20071, 'ServerF' => 20074, 'ServerG' => 20077, 'ServerH' => 20080, 'ServerI' => 20083, 'ServerJ' => 20086, 'ServerK' => 20089),
);
?>