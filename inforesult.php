<?php
// RPG
include('includes/mysql.php');
include('includes/passwords.php');
$data['RPG']['countLogins'] = 0;
$query = mysql_query("SELECT * FROM stats WHERE stat='login'") or die(mysql_error());
while($row = mysql_fetch_array($query)){
	$data['RPG']['countLogins'] = $row['value']+$data['RPG']['countLogins'];
}
$data['RPG']['todaysLogins'] = 0;
$query = mysql_query("SELECT * FROM stats WHERE stat='lastlogin'") or die(mysql_error());
$data['RPG']['todaysLoginsPeople'] = array();
while($row = mysql_fetch_array($query)){
	if($row['value']>time()-(24*3600)){
		$data['RPG']['todaysLogins'] = $data['RPG']['todaysLogins']+1;
		array_push($data['RPG']['todaysLoginsPeople'], $row['player']);
	}
}
$query = mysql_query("SELECT * FROM stats WHERE stat='lastlogin'");
$data['RPG']['countTotalPeople'] = 0;
while($row = mysql_fetch_array($query)){
	$data['RPG']['countTotalPeople'] = $data['RPG']['countTotalPeople']+1;
}

// Modded
mysql_select_db('Araeosia-Modded');
$data['Modded']['countLogins'] = 0;
$query = mysql_query("SELECT * FROM stats WHERE stat='login'") or die(mysql_error());
while($row = mysql_fetch_array($query)){
	$data['Modded']['countLogins'] = $row['value']+$data['Modded']['countLogins'];
}
$data['Modded']['todaysLogins'] = 0;
$query = mysql_query("SELECT * FROM stats WHERE stat='lastlogin'") or die(mysql_error());
$data['Modded']['todaysLoginsPeople'] = array();
while($row = mysql_fetch_array($query)){
	if($row['value']>time()-(24*3600)){
		$data['Modded']['todaysLogins'] = $data['Modded']['todaysLogins']+1;
		array_push($data['Modded']['todaysLoginsPeople'], $row['player']);
	}
}
$query = mysql_query("SELECT * FROM stats WHERE stat='lastlogin'");
$data['Modded']['countTotalPeople'] = 0;
while($row = mysql_fetch_array($query)){
	$data['Modded']['countTotalPeople'] = $data['Modded']['countTotalPeople']+1;
}

// Freebuild
mysql_connect('192.168.5.104', 'minecraft', $passwords['mysql']['minecraft']) or die(mysql_error());
mysql_select_db('minecraft');
$data['Freebuild']['countLogins'] = 0;
$query = mysql_query("SELECT * FROM stats WHERE stat='login'") or die(mysql_error());
while($row = mysql_fetch_array($query)){
	$data['Freebuild']['countLogins'] = $row['value']+$data['Freebuild']['countLogins'];
}
$data['Freebuild']['todaysLogins'] = 0;
$query = mysql_query("SELECT * FROM stats WHERE stat='lastlogin'") or die(mysql_error());
$data['Freebuild']['todaysLoginsPeople'] = array();
while($row = mysql_fetch_array($query)){
	if($row['value']>time()-(24*3600)){
		$data['Freebuild']['todaysLogins'] = $data['Freebuild']['todaysLogins']+1;
		array_push($data['Freebuild']['todaysLoginsPeople'], $row['player']);
	}
}
$query = mysql_query("SELECT * FROM stats WHERE stat='lastlogin'");
$data['Freebuild']['countTotalPeople'] = 0;
while($row = mysql_fetch_array($query)){
	$data['Freebuild']['countTotalPeople'] = $data['Freebuild']['countTotalPeople']+1;
}

// Other


// Total
$data['Total']['countLogins'] = $data['RPG']['countLogins']+$data['Modded']['countLogins']+$data['Freebuild']['countLogins'];
$data['Total']['todaysLogins'] = $data['RPG']['todaysLogins']+$data['Modded']['todaysLogins']+$data['Freebuild']['todaysLogins'];
$data['Total']['countTotalPeople'] = $data['RPG']['countTotalPeople']+$data['Modded']['countTotalPeople']+$data['Freebuild']['countTotalPeople'];
$data['Total']['todaysLoginsPeople'] = array_merge($data['RPG']['todaysLoginsPeople'], $data['Modded']['todaysLoginsPeople'], $data['Freebuild']['todaysLoginsPeople']);


var_dump($data);

?>