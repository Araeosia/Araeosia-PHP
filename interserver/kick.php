<?php
include('includes/staff.php');
include('includes/servers.php');
include('includes/functions.php');
include('includes/passwords.php');
$name = $_POST['player'];
$world = $_POST['playerWorld'];
$server = $_GET['s'];
$args = $_POST['args'];
$logHandle = new Logger('chat');
//Argument 0 is kick, Argument 1 is name, Arguments 2-∞ are the kick message
if(!isStaff($name)){ die('§cYou don\'t have permission to use that command!'); }
if(isStaff($args[1]) && $name!="AgentKid"){ die('§cYou cannot kick another staff member!'); }
if(!isset($args[2])){
	$msg = "Kicked from server by ".$name."!";
	$kickee = $args[1];
}else{
	$msg = $args;
	array_shift($msg);
	$kickee = array_shift($msg);
	$msg = implode(' ', $msg);
}
$kicked=0;
foreach($servers as $server){
	$JSONAPI = new JSONAPI($ips[$server], $ports['jsonapi'][$server], $passwords['jsonapi']['user'], $passwords['jsonapi']['password'], $passwords['jsonapi']['salt']);
	$players = $JSONAPI->call('getPlayerNames', array());
	$players = $players['success'];
	if(in_array($kickee, $players)){
		$JSONAPI->call('kickPlayer', array($kickee, $msg));
		echo "Kicked ".$kickee." from Araeosia ".$server." with the message \"".$msg."\".\n";
		$kicked=$kicked+1;
                $logHandle->addLog("$name kicked $kickee from $server.");
	}
}
if($kicked==0){ echo "§cCould not find player ".$kickee."!"; }
?>