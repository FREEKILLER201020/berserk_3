<?php
require "api.php";

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
echo implode(",", OnCall($array, null));
?>