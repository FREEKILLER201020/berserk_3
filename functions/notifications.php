<?php
require "../classes/fight.php";
require "../classes/notofication.php";

date_default_timezone_set('Europe/London');

$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$query = "select * from attacks where ended is null order by resolved desc;\n";

$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$fights = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$query2 = "SELECT distinct on (id) timemark,id,title, points, created, gone from clans where timemark<='$line[resolved]';\n";
	$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
	while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
		if ($line[attacker] == $line2[id]) {
			$attacker = $line2[title];
		}
		if ($line[defender] == $line2[id]) {
			$defender = $line2[title];
		}
		if ($line[winer] == $line2[id]) {
			$line[winer] = $line2[title];
		}
	}

	$query2 = "SELECT distinct on (id) timemark, id, name, clan from cities where timemark<='$line[resolved]';\n";
	$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
	while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
		if ($line[from] == $line2[id]) {
			$line[from] = $line2[name];
		}
		if ($line[to] == $line2[id]) {
			$line[to] = $line2[name];
		}
	}
	if ($line[from] == -1) {
		$line[from] = "Варвары";
	}
	if ($line[to] == -1) {
		$line[to] = "Варвары";
	}
	// $attacker = $line[attacker];
	// $defender = $line[defender];
	$from = $line[from];
	$to = $line[to];

	$tmp = new FightClassNot($attacker, $defender, $from, $to, $line[declared], $line[resolved], $line[winer], $line[ended], $line[attacker], $line[defender]);
	array_push($fights, $tmp);
}
print_r($fights);

$query = "select * from notifications;\n";

$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$i = 1;
$not = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	$tmp = new Notification($line[user_id], $line[user_key], $line[clan_id], $line[type]);
	array_push($not, $tmp);
}

print_r($not);

foreach ($not as $user) {
	foreach ($fights as $fight) {
		if (($user->user_id == $fight->attacker_id) || ($user->user_id == $fight->defender_id)) {
			$d = date('Y-m-d H:i:s');
			$timestamp1 = strtotime($d);
			$timestamp2 = strtotime($fight->resolved);
			$d = $timestamp2 - $timestamp1;
			echo $d . PHP_EOL;
		}
	}
}
pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
?>