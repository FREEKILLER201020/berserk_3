<?php
require '../classes/city.php';
$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);

$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from cities_newest();\n";
$result = pg_query($query);
$cities_new = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new CityMerge($line["timemark"], $line["id"], $line["name"], $line["clan"]);
	array_push($cities_new, $tmp);
}

$query = "select * from cities;\n";
$result = pg_query($query);
$cities_new_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new CityMerge($line["timemark"], $line["id"], $line["name"], $line["clan"]);
	array_push($cities_new_all, $tmp);
}
// print_r($cities_new);
// exit();

$query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from cities_oldest();\n";
$result = pg_query($query);
$cities_old = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new CityMerge($line["timemark"], $line["id"], $line["name"], $line["clan"]);
	array_push($cities_old, $tmp);
}
// print_r($cities_old);
// exit();
$query = "select * from cities;\n";
$result = pg_query($query);
$cities_old_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new CityMerge($line["timemark"], $line["id"], $line["name"], $line["clan"]);
	array_push($cities_old_all, $tmp);
}

$query = "host={$config['host']} dbname={$config['dbname3']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
foreach ($cities_new_all as $citie) {

	$query = "insert into cities (timemark,id,name,clan) values ('{$citie->timemark}',$citie->id,'{$citie->name}',$citie->clan);\n";

	echo $query . PHP_EOL;
	$result = pg_query($query);
}
foreach ($cities_old_all as $citie) {
	$query = "insert into cities (timemark,id,name,clan) values ('{$citie->timemark}',$citie->id,'{$citie->name}',$citie->clan);\n";

	echo $query . PHP_EOL;
	$result = pg_query($query);
}
$query = "host={$config['host']} dbname={$config['dbname3']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
foreach ($cities_new as $new) {
	foreach ($cities_old as $old) {
		if ($new->id == $old->id) {
			if (($new->name == $old->name) && ($new->clan == $old->clan)) {
				$query = "delete from cities where id={$old->id} and timemark='{$old->timemark}';\n";
				echo $query . PHP_EOL;
				$result = pg_query($query);
			}
		}
	}
}

pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
?>
