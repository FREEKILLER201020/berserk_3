<?php
$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());

$query = file_get_contents("../sql/scout.sql");
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
?>
