<?php
$fileToRead = "/home/agentkid/logs/chat.log";
$lines = count(file($fileToRead));
set_time_limit(0);
while(1){
	if(count(file($fileToRead))>$lines){
		$fileHandle = fopen($fileToRead, 'a+');
		$fileArray = file($fileToRead);
		echo $fileArray[$lines];
		$lines = count(file($fileToRead));
	}
}