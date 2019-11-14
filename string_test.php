<?php
// $string1 = "б";
// $string2 = "а";
// echo strnatcmp($string1, $string2);
$d = date('Y-m-d H:i:s');
echo $d . PHP_EOL;
$d1 = explode(" ", $d);
$d1_1 = explode("-", $d1[0]);
$year = $d1_1[0];
$month = $d1_1[1];
$day = $d1_1[2];

$d1_2 = explode(":", $d1[1]);
$hour = $d1_2[0];
$min = $d1_2[1];
$sec = $d1_2[2];

echo date('Y-m-d H:i:s', mktime($hour, $min, $sec, $month, $day, $year));

?>