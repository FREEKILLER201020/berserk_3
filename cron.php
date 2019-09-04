<?php
while (true) {
	exec("cd /Applications/MAMP/htdocs/berserk_3; php test.php -new_scan -s -move -d");
	echo $i . PHP_EOL;
	$i++;
	sleep(60);
}
?>