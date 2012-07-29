<?php
include('includes/functions.php');
serverCheck($server, array('Modded'));
$folderHandle = opendir('/home/agentkid/NewestIndustry/Industry/data');
while($file = readdir($folderHandle)){
#	var_dump($file);
	if(strpos($file, 'bag_')!==false){
		$playerWithBagNumber = substr($file, 4, strlen($file)-8);
		var_dump($playerWithBagNumber);
	}
}
?>