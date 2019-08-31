<?php
require '../classes/clan.php';
$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);

$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from clans_list_newest();\n";
$result = pg_query($query);
$clans_new = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new ClanClassMerge($line["timemark"], $line["id"], $line["title"], $line["points"], $line["gone"]);
	array_push($clans_new, $tmp);
}

$query = "select * from clans;\n";
$result = pg_query($query);
$clans_new_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new ClanClassMerge($line["timemark"], $line["id"], $line["title"], $line["points"], $line["gone"]);
	array_push($clans_new_all, $tmp);
}
// print_r($clans_new_all);

$query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from clans_list_oldest();\n";
$result = pg_query($query);
$clans_old = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new ClanClassMerge($line["timemark"], $line["id"], $line["title"], $line["points"], $line["gone"]);
	array_push($clans_old, $tmp);
}
$query = "select * from clans;\n";
$result = pg_query($query);
$clans_old_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new ClanClassMerge($line["timemark"], $line["id"], $line["title"], $line["points"], $line["gone"]);
	array_push($clans_old_all, $tmp);
}

$query = "host={$config['host']} dbname={$config['dbname3']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
foreach ($clans_new_all as $clan) {
	if ($clan->gone == "") {
		$query = "insert into clans (timemark,id,title,points) values ('{$clan->timemark}',$clan->id,'{$clan->title}',$clan->points);\n";

	} else {
		$query = "insert into clans (timemark,id,title,points,gone) values ('{$clan->timemark}',$clan->id,'{$clan->title}',$clan->points,'{$clan->gone}');\n";
	}
	echo $query . PHP_EOL;
	$result = pg_query($query);
}
foreach ($clans_old_all as $clan) {
	if ($clan->gone == "") {
		$query = "insert into clans (timemark,id,title,points) values ('{$clan->timemark}',$clan->id,'{$clan->title}',$clan->points);\n";

	} else {
		$query = "insert into clans (timemark,id,title,points,gone) values ('{$clan->timemark}',$clan->id,'{$clan->title}',$clan->points,'{$clan->gone}');\n";
	}
	echo $query . PHP_EOL;
	$result = pg_query($query);
}
$query = "host={$config['host']} dbname={$config['dbname3']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
foreach ($clans_new as $new) {
	foreach ($clans_old as $old) {
		if ($new->id == $old->id) {
			if (($new->title == $old->title) && ($new->points == $old->points) && ($new->gone == $old->gone)) {
				$query = "delete from clans where id={$old->id} and timemark='{$old->timemark}';\n";
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
