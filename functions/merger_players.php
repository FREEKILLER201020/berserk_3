<?php
require '../classes/player.php';
$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);

$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from players_newest();\n";
$result = pg_query($query);
$players_new = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new PlayerClassMerge($line["timemark"], $line["id"], $line["nick"], $line["frags"], $line["deaths"], $line["level"], $line["clan"], $line["folder"]);
	array_push($players_new, $tmp);
}

$query = "select * from players;\n";
$result = pg_query($query);
$players_new_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new PlayerClassMerge($line["timemark"], $line["id"], $line["nick"], $line["frags"], $line["deaths"], $line["level"], $line["clan"], $line["folder"]);
	array_push($players_new_all, $tmp);
}
// print_r($players_new);
// exit();

$query = "host={$config['host']} dbname={$config['dbname2']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from players_oldest();\n";
$result = pg_query($query);
$players_old = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new PlayerClassMerge($line["timemark"], $line["id"], $line["nick"], $line["frags"], $line["deaths"], $line["level"], $line["clan"], $line["folder"]);
	array_push($players_old, $tmp);
}
// print_r($players_old);
// exit();
$query = "select * from players;\n";
$result = pg_query($query);
$players_old_all = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new PlayerClassMerge($line["timemark"], $line["id"], $line["nick"], $line["frags"], $line["deaths"], $line["level"], $line["clan"], $line["folder"]);
	array_push($players_old_all, $tmp);
}

$query = "host={$config['host']} dbname={$config['dbname3']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
foreach ($players_new_all as $player) {

	$query = "insert into players (timemark,id,nick,frags,deaths,level,clan,folder) values ('{$player->timemark}',$player->id,'{$player->nick}',$player->frags,$player->deaths,$player->level,$player->clan_id,'$player->folder');\n";

	echo $query . PHP_EOL;
	$result = pg_query($query);
}
foreach ($players_old_all as $player) {
	$query = "insert into players (timemark,id,nick,frags,deaths,level,clan,folder) values ('{$player->timemark}',$player->id,'{$player->nick}',$player->frags,$player->deaths,$player->level,$player->clan_id,'$player->folder');\n";

	echo $query . PHP_EOL;
	$result = pg_query($query);
}
$query = "host={$config['host']} dbname={$config['dbname3']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
foreach ($players_new as $new) {
	foreach ($players_old as $old) {
		if ($new->id == $old->id) {
			if (($new->nick == $old->nick) && ($new->frags == $old->frags) && ($new->deaths == $old->deaths) && ($new->level == $old->level) && ($new->clan_id == $old->clan_id)) {
				$query = "delete from players where id={$old->id} and timemark='{$old->timemark}';\n";
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
