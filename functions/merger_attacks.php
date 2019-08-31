<?php
require '../classes/fight.php';
$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);

$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from cities_newest();\n";
// $result = pg_query($query);
// $cities_new = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
// 	$tmp = new FightClassMerge($line["timemark"], $line["from"], $line["to"], $line["attacker"], $line["defender"], $line["declared"], $line["resolved"], $line["ended"], $line["winer"]);
// 	array_push($cities_new, $tmp);
// }

$query = "select * from attacks;\n";
$result = pg_query($query);
$attacks_new_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new FightClassMerge($line["attacker"], $line["defender"], $line["from"], $line["to"], $line["declared"], $line["resolved"], $line["ended"], $line["winer"], $line["folder"]);
	array_push($attacks_new_all, $tmp);
}
// print_r($cities_new);
// exit();

$query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from cities_oldest();\n";
// $result = pg_query($query);
// $cities_old = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
// 	$tmp = new FightClassMerge($line["timemark"], $line["from"], $line["to"], $line["attacker"], $line["defender"], $line["declared"], $line["resolved"], $line["ended"], $line["winer"]);
// 	array_push($cities_old, $tmp);
// }
// print_r($cities_old);
// exit();
$query = "select * from attacks;\n";
$result = pg_query($query);
$attacks_old_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new FightClassMerge($line["attacker"], $line["defender"], $line["from"], $line["to"], $line["declared"], $line["resolved"], $line["ended"], $line["winer"], $line["folder"]);
	array_push($attacks_old_all, $tmp);
}
// print_r($attacks_old_all);
// exit();

$query = "host={$config['host']} dbname={$config['dbname3']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
foreach ($attacks_new_all as $new) {
	foreach ($attacks_old_all as $old) {
		// if ($new->id == $old->id) {
		if (($new->from == $old->from) && ($new->to == $old->to) && ($new->declared == $old->declared) && ($new->resolved == $old->resolved) && ($new->attacker_id == $old->attacker_id) && ($new->defender_id == $old->defender_id)) {
			if ($old->winer == "") {
				$query = "insert into attacks (\"from\",\"to\",attacker,defender,declared,resolved,folder) values ('{$old->from}','{$old->to}',{$old->attacker_id},{$old->defender_id},'{$old->declared}','{$old->resolved}','{$new->folder}');\n";
			} else {
				$query = "insert into attacks (\"from\",\"to\",attacker,defender,declared,resolved,ended,winer,folder) values ('{$old->from}','{$old->to}',{$old->attacker_id},{$old->defender_id},'{$old->declared}','{$old->resolved}','{$old->ended}',{$old->winer},'{$new->folder}');\n";
			}
			$new->was = 1;
			$old->was = 1;
			echo $query . PHP_EOL;
			$result = pg_query($query);
		}
		// }
	}
}

foreach ($attacks_new_all as $new) {
	if ($new->was != 1) {
		// foreach ($attacks_old_all as $old) {
		// if ($new->id == $old->id) {
		// if (($new->from == $old->from) && ($new->to == $old->to) && ($new->declared == $old->declared) && ($new->resolved == $old->resolved) && ($new->attacker_id == $old->attacker_id) && ($new->defender_id == $old->defender_id)) {
		if ($new->winer == "") {
			$query = "insert into attacks (\"from\",\"to\",attacker,defender,declared,resolved,folder) values ('{$new->from}','{$new->to}',{$new->attacker_id},{$new->defender_id},'{$new->declared}','{$new->resolved}','{$new->folder}');\n";
		} else {
			$query = "insert into attacks (\"from\",\"to\",attacker,defender,declared,resolved,ended,winer,folder) values ('{$new->from}','{$new->to}',{$new->attacker_id},{$new->defender_id},'{$new->declared}','{$new->resolved}','{$new->ended}',{$new->winer},'{$new->folder}');\n";
		}
		$new->was = 1;
		echo $query . PHP_EOL;
		$result = pg_query($query);
		// }
		// }
	}
}
foreach ($attacks_old_all as $old) {
	if ($old->was != 1) {
		// foreach ($attacks_old_all as $old) {
		// if ($old->id == $old->id) {
		// if (($old->from == $old->from) && ($old->to == $old->to) && ($old->declared == $old->declared) && ($old->resolved == $old->resolved) && ($old->attacker_id == $old->attacker_id) && ($old->defender_id == $old->defender_id)) {
		if ($old->winer == "") {
			$query = "insert into attacks (\"from\",\"to\",attacker,defender,declared,resolved,folder) values ('{$old->from}','{$old->to}',{$old->attacker_id},{$old->defender_id},'{$old->declared}','{$old->resolved}','{$old->folder}');\n";
		} else {
			$query = "insert into attacks (\"from\",\"to\",attacker,defender,declared,resolved,ended,winer,folder) values ('{$old->from}','{$old->to}',{$old->attacker_id},{$old->defender_id},'{$old->declared}','{$old->resolved}','{$old->ended}',{$old->winer},'{$old->folder}');\n";
		}
		$old->was = 1;
		echo $query . PHP_EOL;
		$result = pg_query($query);
		// }
		// }
		// }
	}
}

pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
?>
