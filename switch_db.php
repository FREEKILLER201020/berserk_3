<?php
$file = file_get_contents(realpath(dirname(__FILE__)) . "/.config.json");
$config = json_decode($file, true);
$bcp = $config['dbname'];
$config['dbname'] = $config['dbname2'];
$config['dbname2'] = $bcp;
print_r($config);
file_put_contents(realpath(dirname(__FILE__)) . "/.config.json", json_encode($config, JSON_UNESCAPED_UNICODE));
?>