<?php
require "api.php";
require "../functions/matrix.php";

// echo realpath(dirname(__FILE__)) . "/../.config.json";
// print_r(pathinfo(get_included_files()[0]));

// $file = file_get_contents(realpath(dirname(__FILE__)) . "/.config.json");
// $config = json_decode($file, true);
// // print_r($config);
// $query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// // $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $dbconn = pg_connect($query) or die('Не удалось соединиться: ' . pg_last_error());
$array = array();
$array['type'] = "history";
$array['id'] = "52";
$array['clan'] = "171";

// print_r($dbconn);
$array = array();
$array['type'] = "history";
$array['id'] = "52";
$array['clan'] = "171";

// print_r($dbconn);
// print_r(OnCall($array, null));
$answer = OnCall($array, null);
$json = json_decode($answer, true);
PrintTable($json);

// $answer = "<pre>" . PHP_EOL;
// $js = $json[0];
// $keys = array();
// $length = array();
// foreach ($js as $key => $value) {
// 	array_push($keys, $key);
// }
// foreach ($keys as $key => $value) {
// 	$keys[$key] = str_replace("Начало_боя", "Начало", $value);
// }
// foreach ($keys as $key) {
// 	if (($key == "Атакует") || ($key == "Защищается") || ($key == "Начало") || ($key == "Победитель")) {
// 		$answer .= " $key |";
// 	}
// }
?>