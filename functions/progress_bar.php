<?php
function progressBar($done, $total, $step_time, $start_time)
{
    $perc = floor(($done / $total) * 10);
    $perc2 = floor(($done / $total) * 100);
    $left = 10 - $perc;
    $spent=microtime(true)*10000-$start_time;
    $sec2=$spent/10000;
    $mil2=(int)$spent%10000;
    $min2=intval($sec2/60);
    $sec2=(int)$sec2%60;
    $mil=(($total-$done)*$step_time);
    $sec=$mil/10000;
    $mil=(int)$mil%10000;
    $min=intval($sec/60);
    $sec=(int)$sec%60;
    $write = sprintf("\033[0G\033[2K[%'={$perc}s>%-{$left}s] - $perc2%% - $done/$total; Time spend: ".$min2." min ".$sec2." sec ".$mil2." mil". "; Time left: ".$min." min ".$sec." sec ".$mil." mil", "", "");
    fwrite(STDERR, $write);
}
?>
