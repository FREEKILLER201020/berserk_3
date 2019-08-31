<?php
$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
$db = "";
$db2 = "";
$db3 = "";
for ($i = 0; $i < count($argv); $i++) {
	if ($argv[$i] == "-db") {
		$db = $argv[$i + 1];
	}
	if ($argv[$i] == "-db2") {
		$db2 = $argv[$i + 1];
	}
	if ($argv[$i] == "-db3") {
		$db3 = $argv[$i + 1];
	}
}
// $bcp = $config['dbname'];
$config['dbname'] = $db;
$config['dbname2'] = $db2;
$config['dbname3'] = $db3;
print_r($config);

file_put_contents(realpath(dirname(__FILE__)) . "/../.config.json", json_encode($config, JSON_UNESCAPED_UNICODE));
?>