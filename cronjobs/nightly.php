<?php
// Basic framework for the nightly cronjob.

// SQL Backups
$loc = "/home/agentkid/backups";
$time = time();
$cmd = "mysqldump -u website -PASS password Araeosia > ".$loc.$time.".sql";
exec($cmd);
?>