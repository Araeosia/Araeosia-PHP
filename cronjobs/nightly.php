<?php
// Basic framework for the nightly cronjob.

// SQL Backups
$loc = "/home/agentkid/backups";
$time = time();
$cmd = "mysqldump -u ".$passwords['mysql']['user']." -PASS ".$passwords['mysql']['pass']." Araeosia > ".$loc.$time.".sql";
exec($cmd);

// Archive the chat log
$loc = "/home/agentkid/logs/chat.log";
$date = date("m.d.y");
$newloc = "/home/agentkid/logs/archive/chat.".$date.".log";
rename($loc, $newloc);
touch($loc);
?>