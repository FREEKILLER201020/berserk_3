<?php
require 'class.php';
require '../berserk/functions.php';
ini_set('memory_limit', '16384M');

$file = file_get_contents(realpath(dirname(__FILE__)) . "/.config.json");
$config = json_decode($file, true);
print_r($config);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from clans order by id,timemark asc;\n";
$result = pg_query($query);
$clans_server = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new Clan_class_test($line["id"], $line["title"], $line["points"], 0);
	array_push($clans_server, $tmp);
}

$query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from clans order by id, timemark asc;\n";
// $result = $connection->query($query);
$result = pg_query($query);
$clans_server2 = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new Clan_class_test($line["id"], $line["title"], $line["points"], 0);
	array_push($clans_server2, $tmp);

}
if (count($clans_server) != count($clans_server2)) {
	echo "Error" . PHP_EOL;
	exit();
}
for ($i = 0; $i < count($clans_server); $i++) {
	if ($clans_server[$i]->id != $clans_server2[$i]->id) {
		echo "Error" . PHP_EOL;
		print_r($clans_server[$i]);
		print_r($clans_server2[$i]);
		exit();
	}
	if ($clans_server[$i]->title != $clans_server2[$i]->title) {
		echo "Error" . PHP_EOL;
		print_r($clans_server[$i]);
		print_r($clans_server2[$i]);
		exit();
	}
	if ($clans_server[$i]->points != $clans_server2[$i]->points) {
		echo "Error" . PHP_EOL;
		print_r($clans_server[$i]);
		print_r($clans_server2[$i]);
		exit();
	}
}
unset($clans_server);
unset($clans_server2);
// $query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from players order by id,timemark asc;\n";
// $result = pg_query($query);
// $players_server = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

// 	$tmp = new Player_class($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan"], "");
// 	array_push($players_server, $tmp);
// }
// $query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from players order by id,timemark asc;\n";
// $result = pg_query($query);
// $players_server2 = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

// 	$tmp = new Player_class($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan"], "");
// 	array_push($players_server2, $tmp);
// }
// if (count($players_server) != count($players_server2)) {
// 	echo "Error" . PHP_EOL;
// 	exit();
// }
// for ($i = 0; $i < count($players_server); $i++) {
// 	if ($players_server[$i]->id != $players_server2[$i]->id) {
// 		echo "Error" . PHP_EOL;
// 		print_r($players_server[$i]);
// 		print_r($players_server2[$i]);
// 		exit();
// 	}
// 	if ($players_server[$i]->nick != $players_server2[$i]->nick) {
// 		echo "Error" . PHP_EOL;
// 		print_r($players_server[$i]);
// 		print_r($players_server2[$i]);
// 		exit();
// 	}
// 	if ($players_server[$i]->frags != $players_server2[$i]->frags) {
// 		echo "Error" . PHP_EOL;
// 		print_r($players_server[$i]);
// 		print_r($players_server2[$i]);
// 		exit();
// 	}
// 	if ($players_server[$i]->deaths != $players_server2[$i]->deaths) {
// 		echo "Error" . PHP_EOL;
// 		print_r($players_server[$i]);
// 		print_r($players_server2[$i]);
// 		exit();
// 	}
// 	if ($players_server[$i]->level != $players_server2[$i]->level) {
// 		echo "Error" . PHP_EOL;
// 		print_r($players_server[$i]);
// 		print_r($players_server2[$i]);
// 		exit();
// 	}
// 	if ($players_server[$i]->clan_id != $players_server2[$i]->clan_id) {
// 		echo "Error" . PHP_EOL;
// 		print_r($players_server[$i]);
// 		print_r($players_server2[$i]);
// 		exit();
// 	}
// }
// unset($players_server);
// unset($players_server2);
// $query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from cities order by id,timemark asc;\n";
// $result = pg_query($query);
// $city_server = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

// 	$tmp = new City($line["id"], $line["name"], $line["clan"]);
// 	array_push($city_server, $tmp);
// }
// $query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from cities order by id,timemark asc;\n";
// $result = pg_query($query);
// $city_server2 = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

// 	$tmp = new City($line["id"], $line["name"], $line["clan"]);
// 	array_push($city_server2, $tmp);
// }
// if (count($city_server) != count($city_server2)) {
// 	echo "Error" . PHP_EOL;
// 	exit();
// }
// for ($i = 0; $i < count($city_server); $i++) {
// 	if ($city_server[$i]->id != $city_server2[$i]->id) {
// 		echo "Error" . PHP_EOL;
// 		print_r($city_server[$i]);
// 		print_r($city_server2[$i]);
// 		exit();
// 	}
// 	if ($city_server[$i]->title != $city_server2[$i]->title) {
// 		echo "Error" . PHP_EOL;
// 		print_r($city_server[$i]);
// 		print_r($city_server2[$i]);
// 		exit();
// 	}
// 	if ($city_server[$i]->points != $city_server2[$i]->points) {
// 		echo "Error" . PHP_EOL;
// 		print_r($city_server[$i]);
// 		print_r($city_server2[$i]);
// 		exit();
// 	}
// }
// unset($city_server);
// unset($city_server2);
// $query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from attacks order by declared,attacker,defender asc;\n";
// $result = pg_query($query);
// $attacks_server = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

// 	$tmp = new Fight_class($line["attacker"], $line["defender"], $line["from"], $line["to"], $line["declared"], $line["resolved"], $line["winer"], $line["ended"]);
// 	array_push($attacks_server, $tmp);
// }
// $query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from attacks order by declared,attacker,defender asc;\n";
// $result = pg_query($query);
// $attacks_server2 = array();
// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

// 	$tmp = new Fight_class($line["attacker"], $line["defender"], $line["from"], $line["to"], $line["declared"], $line["resolved"], $line["winer"], $line["ended"]);
// 	array_push($attacks_server2, $tmp);
// }
// if (count($attacks_server) != count($attacks_server2)) {
// 	echo "Error attacks count " . count($attacks_server) . " != " . count($attacks_server2) . PHP_EOL;
// 	// exit();
// }
// if (count($attacks_server) >= count($attacks_server2)) {
// 	$max = count($attacks_server);
// } else {
// 	$max = count($attacks_server2);
// }
// for ($i = 0; $i < $max; $i++) {
// 	if ($attacks_server[$i]->attacker_id != $attacks_server2[$i]->attacker_id) {
// 		echo "Error1" . PHP_EOL;
// 		print_r($attacks_server[$i]);
// 		print_r($attacks_server2[$i]);
// 		// exit();
// 	}
// 	if ($attacks_server[$i]->defender_id != $attacks_server2[$i]->defender_id) {
// 		echo "Error2" . PHP_EOL;
// 		print_r($attacks_server[$i]);
// 		print_r($attacks_server2[$i]);
// 		// exit();
// 	}
// 	if ($attacks_server[$i]->from != $attacks_server2[$i]->from) {
// 		echo "Error3" . PHP_EOL;
// 		print_r($attacks_server[$i]);
// 		print_r($attacks_server2[$i]);
// 		// exit();
// 	}
// 	if ($attacks_server[$i]->to != $attacks_server2[$i]->to) {
// 		echo "Error4" . PHP_EOL;
// 		print_r($attacks_server[$i]);
// 		print_r($attacks_server2[$i]);
// 		// exit();
// 	}
// 	if ($attacks_server[$i]->declared != $attacks_server2[$i]->declared) {
// 		echo "Error5" . PHP_EOL;
// 		print_r($attacks_server[$i]);
// 		print_r($attacks_server2[$i]);
// 		// exit();
// 	}
// 	if ($attacks_server[$i]->resolved != $attacks_server2[$i]->resolved) {
// 		echo "Error6" . PHP_EOL;
// 		print_r($attacks_server[$i]);
// 		print_r($attacks_server2[$i]);
// 		// exit();
// 	}
// 	if ($attacks_server[$i]->ended != $attacks_server2[$i]->ended) {
// 		echo "Error7" . PHP_EOL;
// 		print_r($attacks_server[$i]);
// 		print_r($attacks_server2[$i]);
// 		// exit();
// 	}
// }
// unset($attacks_server);
// unset($attacks_server2);
echo "Ok" . PHP_EOL;
pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
?>