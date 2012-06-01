<?php
set_time_limit(0);
$denom = 1;
$neg = +1;
while(1){
    $current = $current+((4/$denom)*$neg);
    $neg = $neg*-1;
    $denom = $denom+2;
    echo "Pi is close to " . $current . "\n";
    flush();
}
?>