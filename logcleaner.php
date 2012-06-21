<?php
$filetoread = "/home/agentkid/server.log";
$filetooutput = "/home/agentkid/".time().".log";
$filehandle = fopen($filetoread, 'r');
$fileread = fread($filehandle, filesize($filetoread));
$content = str_replace(chr(10), chr(13), $fileread);
$content = explode(chr(13), $content);
$outputhandle = fopen($filetooutput, 'w');
foreach($content as $line){
	if(strpos($line, "[INFO]")!=false){
		if(strpos($line, "JSONAPI")==false){
			if(strpos($line, "WorldGuard")==false){
	#			if(strpos($line, "[A]")!=false || strpos($line, "[g]")!=false){
					if(strpos($line, "06-16")!=false){
					echo $line."\n";
					$cleanoutput = str_replace(array('^[[32m', '^[[37m', '^[[34m', '^[[36m', '^[[31m'), '', $line)."\n";
					fwrite($outputhandle, $cleanoutput);
					flush();
					}
		#		}
			}
		}
	}
}
?>