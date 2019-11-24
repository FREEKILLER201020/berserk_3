<?php
// require "class.php";
error_reporting(1);
// print_r(pathinfo(get_included_files()[0]));
$a = pathinfo(get_included_files()[0])[dirname];
$a = explode("/", $a);
// print_r($a);
// echo $a[count($a) - 1];
if ($a[count($a) - 1] == "berserk_3") {
	require pathinfo(get_included_files()[0])[dirname] . "/functions/string.php";
	require pathinfo(get_included_files()[0])[dirname] . "/classes/player.php";
	require pathinfo(get_included_files()[0])[dirname] . "/classes/clan.php";
	require pathinfo(get_included_files()[0])[dirname] . "/classes/era.php";
	require pathinfo(get_included_files()[0])[dirname] . "/classes/fight.php";
	require pathinfo(get_included_files()[0])[dirname] . "/classes/city.php";
	require pathinfo(get_included_files()[0])[dirname] . "/classes/deck.php";
	require pathinfo(get_included_files()[0])[dirname] . "/classes/cards.php";
	$file = file_get_contents(realpath(pathinfo(get_included_files()[0])[dirname] . "/.config.json"));
} else {
// exit();

	require pathinfo(get_included_files()[0])[dirname] . "/../functions/string.php";
	require pathinfo(get_included_files()[0])[dirname] . "/../classes/player.php";
	require pathinfo(get_included_files()[0])[dirname] . "/../classes/clan.php";
	require pathinfo(get_included_files()[0])[dirname] . "/../classes/era.php";
	require pathinfo(get_included_files()[0])[dirname] . "/../classes/fight.php";
	require pathinfo(get_included_files()[0])[dirname] . "/../classes/city.php";
	require pathinfo(get_included_files()[0])[dirname] . "/../classes/deck.php";
	require pathinfo(get_included_files()[0])[dirname] . "/../classes/cards.php";
	$file = file_get_contents(realpath(pathinfo(get_included_files()[0])[dirname] . "/../.config.json"));
}

// print_r($_POST);
session_start();
// $file = file_get_contents(realpath(pathinfo(get_included_files()[0])[dirname] . "/.config.json"));

$config = json_decode($file, true);
// print_r($config);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$dbconn = pg_connect($query) or die('API Не удалось соединиться: ' . pg_last_error());
// $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

OnCall($_REQUEST, $config);

function OnCall($array, $config) {
	$image_sige = $config["image_size"];
	if ($array['type'] == 'era_dates') {
		$return = EraDates($array);
	}
	if ($array['type'] == 'clans') {
		$return = Clans($array);
	}
	if ($array['type'] == 'clan') {
		$return = Clan($array);
	}
	if ($array['type'] == 'eras') {
		$return = Eras($array);
	}
	if ($array['type'] == 'index') {
		$return = Index($array);
	}
	if ($array['type'] == 'index_era') {
		$return = indexEra($array);
	}
	if ($array['type'] == 'era_res_clans') {
		$return = EraResClans($array);
	}
	if ($array['type'] == 'players') {
		$return = Players($array);
	}
	if ($array['type'] == 'player') {
		$return = Player($array);
	}
	if ($array['type'] == 'cards') {
		$return = Cards($array);
	}
	if ($array['type'] == 'cards_full') {
		$return = CardsFull($array);
	}
	if ($array['type'] == 'decks_all') {
		$return = DecksALL($array);
	}
	if ($array['type'] == 'decks_full') {
		$return = DecksFULL($array);
	}
	if ($array['type'] == 'load_cards') {
		$return = LoadCards($array);
	}
	if ($array['type'] == 'deck') {
		$return = Deck($array);
	}
	if ($array['type'] == 'save_deck') {
		$return = SaveDeck($array);
	}
	if ($array['type'] == 'set_session') {
		$return = SessionSet($array);
	}
	if ($array['type'] == 'players_updates') {
		$return = PlayersUpdates($array);
	}
	if ($array['type'] == 'clans_updates') {
		$return = ClansUpdates($array);
	}
	if ($array['type'] == 'history') {
		$return = History($array);
	}
	if ($array['type'] == 'timetable') {
		$return = Timetable($array);
	}
	if ($array['type'] == 'upload') {
		$uploadFileDir = $config['screenshot_dir'];
		$message = '';
		$return = UploadFile($_POST, $_FILES, $uploadFileDir, $message);
		header("Location: " . $array['back']);
	}
	pg_close($dbconn);
	echo json_encode($return, JSON_UNESCAPED_UNICODE);
	return json_encode($return, JSON_UNESCAPED_UNICODE);
}

function EraResClans($array) {
	$pre_era_prep = 5;
	$query = "select * from eras where id=$array[id];\n";

	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$timetable = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$tmp = array();
		$tmp[started_origin] = $line[started] . " 00:00:00";
		$tmp[started] = date('Y-m-d H:i:s', strtotime($tmp[started_origin]) - $pre_era_prep * 24 * 60 * 60);
		$tmp[ended] = $line[ended] . " 23:59:59";
		$tmp[ended] = date('Y-m-d H:i:s', strtotime($tmp[ended]) + 24 * 60 * 60);

	}
	// print_r($tmp);
	$results = array();

	// $query = "select distinct on (id) timemark,id,title, points, gone from clans where timemark>='" . $tmp[started] . "' order by id, timemark asc";
	// // echo $query;
	// $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

	// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	// 	// print_r($line);
	// 	if ($line[gone] = "NULL") {
	// 		$results[$line[id]][start] = $line[points];
	// 	}
	// }
	// print_r($results);
	$query = "select distinct on (id) timemark,id,title, points, gone from clans where timemark<='" . $tmp[ended] . "' and timemark>='" . $tmp[started] . "' order by id, timemark desc";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		// print_r($line);
		if ($line[gone] = "NULL") {
			$results[$line[id]][start] = 0;
			$results[$line[id]][end] = $line[points];
			$results[$line[id]][title] = $line[title];
		}
	}
	// print_r($results);
	foreach ($results as $r => $val) {
		if (!isset($results[$r][start])) {
			$results[$r][start] = 0;
		}
		if (!isset($results[$r][end])) {
			$results[$r][end] = 0;
		}
		if (!isset($results[$r][start_c])) {
			$results[$r][start_c] = 0;
		}
		if (!isset($results[$r][end_c])) {
			$results[$r][end_c] = 0;
		}
	}
	$total = array();
	$st = array();
	$array_prep = array();
	$query = "SELECT distinct on (id) timemark, id, name, clan  from cities where clan <> -2 and timemark<='" . $tmp[started_origin] . "' and timemark>='" . $tmp[started] . "' order by id, timemark desc;";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	// echo $query . PHP_EOL;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$array_prep[$line[timemark]]++;
	}
	$max = 0;
	$max_time = "";
	foreach ($array_prep as $time => $value) {
		if ($value >= $max) {
			$max = $value;
			$max_time = $time;
		}
	}

	$query = "SELECT distinct on (id) timemark, id, name, clan  from cities where clan <> -2 and timemark='" . $max_time . "'  order by id, timemark desc;";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	// echo $query . PHP_EOL;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		// print_r($line);
		if (!isset($results[$line[clan]])) {
			$query2 = "select distinct on (id) timemark,id,title, points, gone from clans where id<=" . $line[clan] . " and timemark<='" . $tmp[started] . "' order by id, timemark desc";
			$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
			// echo $query;
			while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
				$results[$line2[id]][start] = 0;
				$results[$line2[id]][title] = $line2[title];
			}
		}
		if (isset($results[$line[clan]])) {
			$results[$line[clan]][start_c]++;
			$total[start]++;
			$st[$line[id]][strat] = 1;
		}
	}
	$err = 0;
	foreach ($results as $res) {
		foreach ($results as $res2) {
			// print_r($res);
			// print_r($res2);
			if (($res[start_c] != $res2[start_c]) && ($res[start_c] != 0) && ($res2[start_c] != 0)) {
				$err = 1;
			}
		}
	}
	// echo $err . PHP_EOL;
	if ($err == 2) {
		$total = array();

		foreach ($results as $res_id => $val) {
			// echo "here" . PHP_EOL;
			$results[$res_id][start_c] = 0;
			// print_r($res);
		}
		// print_r($results);
		$query = "SELECT distinct on (id) timemark, id, name, clan  from cities where clan <> -2 and timemark<='" . $tmp[started] . "' order by id, timemark asc;";
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		// echo $query;
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			// print_r($line);
			if (!isset($results[$line[clan]])) {
				$query2 = "select distinct on (id) timemark,id,title, points, gone from clans where id<=" . $line[clan] . " and timemark<='" . $tmp[started] . "' order by id, timemark desc";
				$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
				// echo $query;
				while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
					$results[$line2[id]][start] = 0;
					$results[$line2[id]][title] = $line2[title];
				}
			}
			if (isset($results[$line[clan]])) {
				$results[$line[clan]][start_c]++;
				$total[start]++;
				$st[$line[id]][strat] = 1;
			}
		}
	}

	$query = "SELECT distinct on (id) timemark, id, name, clan  from cities where clan <> -2 and timemark<='" . $tmp[ended] . "' and timemark>='" . $tmp[started] . "' order by id, timemark desc;";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	echo $query . PHP_EOL;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		if (isset($results[$line[clan]])) {
			// print_r($line);
			$results[$line[clan]][end_c]++;
			$total[end]++;
			$st[$line[id]][end] = 1;

		}
	}

	// print_r($results);
	print_r($total);
	$return = array();
	foreach ($results as $id => $r) {
		if (!isset($r[start])) {
			$r[start] = 0;
		}
		if (!isset($r[end])) {
			$r[end] = 0;
		}
		if (!isset($r[start_c])) {
			$r[start_c] = 0;
		}
		if (!isset($r[end_c])) {
			$r[end_c] = 0;
		}
		array_push($return, new ClanEraRes($r[title], $id, $r[start], $r[end], $r[start_c], $r[end_c]));
	}
	return $return;

}
function History($array) {
	$query = "select * from eras where id=$array[id];\n";

	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$timetable = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$tmp = array();
		$tmp[started] = $line[started] . " 00:00:00";
		$tmp[ended] = $line[ended] . " 23:59:59";
	}
	if ($array[clan] == -1) {
		$query = "select * from attacks where ended is not null and declared>='$tmp[started]' and declared <='$tmp[ended]'  order by resolved desc;\n";
	} else {
		$query = "select * from attacks where ended is not null and (attacker=$array[clan] or defender=$array[clan]) and declared>='$tmp[started]' and declared <='$tmp[ended]' order by resolved desc;\n";
	}
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$i = 1;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$query2 = "SELECT distinct on (id) timemark,id,title, points, created, gone from clans where timemark<='$line[resolved]' order by id,timemark desc;\n";
		$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
		while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
			if ($line[attacker] == $line2[id]) {
				$line[attacker] = $line2[title];
			}
			if ($line[defender] == $line2[id]) {
				$line[defender] = $line2[title];
			}
			if ($line[winer] == $line2[id]) {
				$line[winer] = $line2[title];
			}
		}

		$query2 = "SELECT distinct on (id) timemark, id, name, clan from cities where timemark<='$line[resolved]' order by id,timemark desc;\n";
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
		$attacker = $line[attacker];
		$defender = $line[defender];
		$from = $line[from];
		$to = $line[to];

		$tmp = new FightClassWeb($i, $attacker, $defender, $from, $to, $line[resolved], $line[ended], $line[winer]);
		array_push($timetable, $tmp);
		$i++;
	}
	return $timetable;
}
function Timetable($array) {
	$query = "select * from eras where id=$array[id];\n";

	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$timetable = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$tmp = array();
		$tmp[started] = $line[started] . " 00:00:00";
		$tmp[ended] = $line[ended] . " 23:59:59";
	}
	if ($array[clan] == -1) {
		$query = "select * from attacks where ended is null and declared>='$tmp[started]' and declared <='$tmp[ended]'  order by resolved asc;\n";
	} else {
		$query = "select * from attacks where ended is null and (attacker=$array[clan] or defender=$array[clan]) and declared>='$tmp[started]' and declared <='$tmp[ended]' order by resolved asc;\n";
	}
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$i = 1;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$query2 = "SELECT distinct on (id) timemark,id,title, points, created, gone from clans where timemark<='$line[resolved]' order by id,timemark desc;\n";
		$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
		while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
			if ($line[attacker] == $line2[id]) {
				$line[attacker] = $line2[title];
			}
			if ($line[defender] == $line2[id]) {
				$line[defender] = $line2[title];
			}
			if ($line[winer] == $line2[id]) {
				$line[winer] = $line2[title];
			}
		}

		$query2 = "SELECT distinct on (id) timemark, id, name, clan from cities where timemark<='$line[resolved]' order by id,timemark desc;\n";
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
		$attacker = $line[attacker];
		$defender = $line[defender];
		$from = $line[from];
		$to = $line[to];

		$tmp = new FightClassWeb($i, $attacker, $defender, $from, $to, $line[resolved], $line[ended], $line[winer]);
		array_push($timetable, $tmp);
		$i++;
	}
	return $timetable;
}
function PlayersUpdates($array) {

	$query = "select * from players_updates order by timemark desc;\n";

	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$log = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$tmp = array();
		$tmp[time] = $line[timemark];
		if (($line[old_clan] == "") && ($line[new_clan] == "")) {
			$tmp[log] = "Игрок <b>$line[nick] ($line[id])</b> сменил ник на <b>$line[new_nick]</b>";
		} else if ($line[old_nick] == "") {
			if ($line[old_clan] == "Нет клана") {
				$tmp[log] = "Игрок <b>$line[nick] ($line[id])</b> присоединился к клану <b>$line[new_clan]</b>";
			}
			if ($line[new_clan] == "Нет клана") {
				$tmp[log] = "Игрок <b>$line[nick] ($line[id])</b> покинул клан <b>$line[old_clan]</b>";
			}
			if (($line[new_clan] != "Нет клана") && ($line[old_clan] != "Нет клана")) {
				$tmp[log] = "Игрок <b>$line[nick] ($line[id])</b> сменил клан с <b>$line[old_clan]</b> на <b>$line[new_clan]</b>";
			}
			// $tmp="$line[nick] changed nick to $line[new_nick]";
		}
		// print_r($line);
		// $tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan_id"], null);
		array_push($log, $tmp);
	}
	return $log;
}
function ClansUpdates($array) {

	$query = "select * from clans_updates order by timemark desc;\n";

	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$log = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$tmp = array();
		$tmp[time] = $line[timemark];
		if (($line[title] != "") && ($line[new_title] != "")) {
			$tmp[log] = "Клан <b>$line[title] ($line[id])</b> сменил название на <b>$line[new_title]</b>";
		} else if ($line[gone] != "") {
			$tmp[time] = $line[gone];
			$tmp[log] = "Клан <b>$line[title] ($line[id])</b> был расформерован";
		} else if (($line[gone] == "") && ($line[new_title] == "")) {
			$tmp[time] = $line[created];
			$tmp[log] = "Клан <b>$line[title] ($line[id])</b> был создан";
		}
		// print_r($line);
		// $tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan_id"], null);
		array_push($log, $tmp);
	}
	return $log;
}
function SessionSet($array) {
	$res["ok"] = 0;
	$_SESSION[$array['key']] = $array['val'];
	$res["ok"] = 1;
	return $res;
}
function Players($array) {
	// $connection = Connect($config);
	if ($array['clan_id'] < 0) {
		$query = "select * from players_all();\n";
	} else {
		$query = "select * from players(" . $array['clan_id'] . ");\n";
	}
	// $result = $connection->query($query);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$players = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan_id"], null);
		array_push($players, $tmp);
	}
	return $players;
}
function Player($array) {
	// $connection = Connect($config);

	$query = "select distinct on (id) timemark,id,nick,frags,deaths,level,clan,folder from players where id=" . $array["player_id"] . " order by id, timemark desc;\n";

	// $result = $connection->query($query);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$players = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan"], null);
		array_push($players, $tmp);
	}
	return $players;
}
function Clan($array) {
	// $connection = Connect($config);

	$query = "select distinct on (id) timemark,id,title, points, gone from clans where id=" . $array["clan_id"] . " order by id, timemark desc;\n";

	// $result = $connection->query($query);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$clan = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		$tmp = new ClanClass($line["id"], $line["title"], $line["points"]);
		array_push($clan, $tmp);
	}
	return $clan;
}
function Cards($array) {
	// $connection = Connect($config);
	$query = "select * from cards;\n";
	// $result = $connection->query($query);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$cards = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		array_push($cards, new Card($line['id'], $line['name'], $line['proto']));

	}
	return $cards;
}
function CardsFull($array) {
	// $connection = Connect($config);
	$query = "select * from cards;\n";
	// $result = $connection->query($query);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$cards = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		$tmp = new Card($line['id'], $line['name'], $line['proto']);
		$tmp->type = "<input class='color_text sp_input' data-lpignore='true' id='card" . $line['id'] . "' name='card" . $line['id'] . "' value='" . $line['type'] . "'>";

		$line["type"];
		$tmp->health = $line["health"];
		$tmp->kick = $line["kick"];
		$tmp->steps = $line["steps"];
		$tmp->race = $line["race"];
		$tmp->rarity = $line["rarity"];
		$tmp->fly = $line["fly"];
		$tmp->desc = $line["desc"];
		$tmp->crystal = $line["crystal"];
		$tmp->crystalCount = $line["crystalCount"];
		$tmp->abilities = $line["abilities"];
		$tmp->f = $line["f"];
		$tmp->rows = $line["rows"];
		$tmp->case = $line["case"];
		$tmp->horde = $line["horde"];
		$tmp->rangeAttack = $line["rangeAttack"];
		$tmp->classes = $line["classes"];
		$tmp->series = $line["series"];
		$tmp->typeEquipment = $line["typeEquipment"];
		$tmp->hate_calss = $line["hate_calss"];
		$tmp->hate_race = $line["hate_race"];
		$tmp->only = $line["only"];
		$tmp->unlim = $line["unlim"];
		$tmp->number = $line["number"];
		$tmp->author = $line["author"];
		$tmp->main = $line["main"];

		array_push($cards, $tmp);

	}
	return $cards;
}
function SaveDeck($array) {
	// print_r($array);
	$res["ok"] = 0;
	$json = json_decode($array["cards"], true);
	$ids = array();
	foreach ($json as $card) {
		array_push($ids, $card["id"]);
	}
	$query = "Update deck set cards='{" . implode(",", $ids) . "}', edited=current_timestamp  where id=" . $array["deck_id"] . ";\n";
	// echo $query;
	// $result = $connection->query($query);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$res["ok"] = 1;
	return $res;
}
function DecksFULL($array) {
	// print_r($array);
	// if ($array['id'] == -1) {
	$decks = array();
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	$query = "\nSELECT * FROM deck ORDER BY (CASE WHEN edited IS NULL THEN 1 ELSE 0 END) DESC,
         edited DESC;\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// $tmp = array();
		// $split = explode("-", $line["timemark"]);
		// $split2 = explode(" ", $split[2]);
		// $year = (int) $split[0];
		// $month = (int) $split[1];
		// $day = (int) $split2[0];
		// $ret = "$split[0]-$split[1]-$split2[0]";
		// // $ret2="$year-$month-$day";
		// // array_push($tmp, $ret);
		// // array_push($tmp, $ret2);
		// array_push($dates, $ret);
		array_push($decks, new Deck($line['id'], $line['player_id'], $line['cards'], $line['screenshot_id'], $line['description'], $line['timemark'], $line['edited'], $line['type']));
		$decks[count($decks) - 1]->GetScreenshot();
		$decks[count($decks) - 1]->EditButton();
		// }
		$cards = array();
		// $query = "use berserk;\n";
		// $result = $connection->query($query);
		// $connection = Connect($config);
		$query2 = "\nSELECT cards FROM deck where id=" . $line['id'] . ";\n";
		// echo $query;
		$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
		while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
			$line2["cards"] = str_replace("{", " ", $line2["cards"]);
			$line2["cards"] = str_replace("}", " ", $line2["cards"]);
			$ids = explode(",", $line2["cards"]);

		}
		// print_r($ids);
		if ($ids[0] > 0) {
			$links = array();
			foreach ($ids as $id) {
				array_push($cards, GetCard($id));
			}
			// print_r($cards);
			foreach ($cards as $card) {
				array_push($links, $card->CreateImage("cards_parser/cards/small/"));
			}
			// print_r($links);
			$decks[count($decks) - 1]->cards = $links;
		}
	}
	// print_r($dates);
	// $tmp = array();

	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	// $query = "call {$config["base_database"]}.era_data(\"$idd\");\n";
	// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	// 	// print_r($row);$id, $start, $end, $lbz, $points
	// 	$tmp = new EraClass($line["id"], $line["started"], $line["ended"], $line["lbz"], $line["pointw"]);
	// 	array_push($eras, $tmp);
	// }
	return $decks;
	// }
}
function DecksALL($array) {
	// print_r($array);
	// if ($array['id'] == -1) {
	$decks = array();
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	$query = "\nSELECT * FROM deck where player_id=" . $array['id'] . " ORDER BY (CASE WHEN edited IS NULL THEN 1 ELSE 0 END) DESC,
         edited DESC;\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// $tmp = array();
		// $split = explode("-", $line["timemark"]);
		// $split2 = explode(" ", $split[2]);
		// $year = (int) $split[0];
		// $month = (int) $split[1];
		// $day = (int) $split2[0];
		// $ret = "$split[0]-$split[1]-$split2[0]";
		// // $ret2="$year-$month-$day";
		// // array_push($tmp, $ret);
		// // array_push($tmp, $ret2);
		// array_push($dates, $ret);
		array_push($decks, new Deck($line['id'], $line['player_id'], $line['cards'], $line['screenshot_id'], $line['description'], $line['timemark'], $line['edited'], $line['type']));
		$decks[count($decks) - 1]->GetScreenshot();
		$decks[count($decks) - 1]->EditButton();
		// }
		$cards = array();
		// $query = "use berserk;\n";
		// $result = $connection->query($query);
		// $connection = Connect($config);
		$query2 = "\nSELECT cards FROM deck where id=" . $line['id'] . ";\n";
		// echo $query;
		$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
		while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
			$line2["cards"] = str_replace("{", " ", $line2["cards"]);
			$line2["cards"] = str_replace("}", " ", $line2["cards"]);
			$ids = explode(",", $line2["cards"]);

		}
		// print_r($ids);
		if ($ids[0] > 0) {
			$links = array();
			foreach ($ids as $id) {
				array_push($cards, GetCard($id));
			}
			// print_r($cards);
			foreach ($cards as $card) {
				array_push($links, $card->CreateImage("cards_parser/cards/small/"));
			}
			// print_r($links);
			$decks[count($decks) - 1]->cards = $links;
		}
	}
	// print_r($dates);
	// $tmp = array();

	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	// $query = "call {$config["base_database"]}.era_data(\"$idd\");\n";
	// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	// 	// print_r($row);$id, $start, $end, $lbz, $points
	// 	$tmp = new EraClass($line["id"], $line["started"], $line["ended"], $line["lbz"], $line["pointw"]);
	// 	array_push($eras, $tmp);
	// }
	return $decks;
	// }
}
function GetCard($id) {
	$query = "\nSELECT * FROM cards where id=" . $id . ";\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		// array_push($decks, new Deck($line['id'], $line['player_id'], $line['cards'], $line['screenshot_id'], $line['description'], $line['timemark'], $line['edited']));
		$card = new Card($line['id'], $line['name'], $line['proto']);
		// }
	}
	return $card;

}
function LoadCards($array) {
	// print_r($array);
	// if ($array['id'] == -1) {
	$cards = array();
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	$query = "\nSELECT cards FROM deck where id=" . $array['deck_id'] . ";\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$line["cards"] = str_replace("{", " ", $line["cards"]);
		$line["cards"] = str_replace("}", " ", $line["cards"]);
		$ids = explode(",", $line["cards"]);

	}
	foreach ($ids as $id) {
		array_push($cards, GetCard($id));
	}

	return $cards;
	// }
}
function Deck($array) {
	// print_r($array);
	// if ($array['id'] == -1) {
	$decks = array();
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	$query = "\nSELECT * FROM deck where id=" . $array['deck_id'] . ";\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// $tmp = array();
		// $split = explode("-", $line["timemark"]);
		// $split2 = explode(" ", $split[2]);
		// $year = (int) $split[0];
		// $month = (int) $split[1];
		// $day = (int) $split2[0];
		// $ret = "$split[0]-$split[1]-$split2[0]";
		// // $ret2="$year-$month-$day";
		// // array_push($tmp, $ret);
		// // array_push($tmp, $ret2);
		// array_push($dates, $ret);
		array_push($decks, new Deck($line['id'], $line['player_id'], $line['cards'], $line['screenshot_id'], $line['description'], $line['timemark'], $line['edited'], $line['type']));
		$decks[count($decks) - 1]->GetScreenshot();
		// }
	}
	// print_r($dates);
	// $tmp = array();

	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	// $query = "call {$config["base_database"]}.era_data(\"$idd\");\n";
	// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	// 	// print_r($row);$id, $start, $end, $lbz, $points
	// 	$tmp = new EraClass($line["id"], $line["started"], $line["ended"], $line["lbz"], $line["pointw"]);
	// 	array_push($eras, $tmp);
	// }
	return $decks;
	// }
}
function Eras($array) {
	// print_r($array);
	// if ($array['id'] == -1) {
	$eras = array();
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	$query = "\nSELECT * FROM eras ORDER BY id desc;\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// $tmp = array();
		// $split = explode("-", $line["timemark"]);
		// $split2 = explode(" ", $split[2]);
		// $year = (int) $split[0];
		// $month = (int) $split[1];
		// $day = (int) $split2[0];
		// $ret = "$split[0]-$split[1]-$split2[0]";
		// // $ret2="$year-$month-$day";
		// // array_push($tmp, $ret);
		// // array_push($tmp, $ret2);
		// array_push($dates, $ret);
		array_push($eras, new EraClass($line['id'], $line['started'], $line['ended'], $line['lbz'], $line['pointw']));

		// }
	}
	// print_r($dates);
	// $tmp = array();

	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	// $query = "call {$config["base_database"]}.era_data(\"$idd\");\n";
	// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	// 	// print_r($row);$id, $start, $end, $lbz, $points
	// 	$tmp = new EraClass($line["id"], $line["started"], $line["ended"], $line["lbz"], $line["pointw"]);
	// 	array_push($eras, $tmp);
	// }
	return $eras;
	// }
}
function EraDates($array) {
	// print_r($array);
	if ($array['id'] == -1) {
		$dates = array();
		// $query = "use berserk;\n";
		// $result = $connection->query($query);
		// $connection = Connect($config);
		$query = "\nSELECT DISTINCT timemark FROM players ORDER BY timemark DESC;\n";
		// echo $query;
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

			$tmp = array();
			$split = explode("-", $line["timemark"]);
			$split2 = explode(" ", $split[2]);
			$year = (int) $split[0];
			$month = (int) $split[1];
			$day = (int) $split2[0];
			$ret = "$split[0]-$split[1]-$split2[0]";
			// $ret2="$year-$month-$day";
			// array_push($tmp, $ret);
			// array_push($tmp, $ret2);
			array_push($dates, $ret);
			// }
		}
		// print_r($dates);
		$tmp = array();
		array_push($tmp, new EraClass(-1, $dates[count($dates) - 1], $dates[0], "", ""));

		// $query = "use berserk;\n";
		// $result = $connection->query($query);
		// $connection = Connect($config);
		// $query = "call {$config["base_database"]}.era_data(\"$idd\");\n";
		// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// 	// print_r($row);$id, $start, $end, $lbz, $points
		// 	$tmp = new EraClass($line["id"], $line["started"], $line["ended"], $line["lbz"], $line["pointw"]);
		// 	array_push($eras, $tmp);
	} else {
		$eras = array();
		// $query = "use berserk;\n";
		// $result = $connection->query($query);
		// $connection = Connect($config);
		$query = "\nSELECT * FROM eras where id=" . $array['id'] . " ORDER BY id desc;\n";
		// echo $query;
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

			// $tmp = array();
			// $split = explode("-", $line["timemark"]);
			// $split2 = explode(" ", $split[2]);
			// $year = (int) $split[0];
			// $month = (int) $split[1];
			// $day = (int) $split2[0];
			// $ret = "$split[0]-$split[1]-$split2[0]";
			// // $ret2="$year-$month-$day";
			// // array_push($tmp, $ret);
			// // array_push($tmp, $ret2);
			// array_push($dates, $ret);
			array_push($eras, new EraClass($line['id'], $line['started'], $line['ended'], $line['lbz'], $line['pointw']));

			// }
		}
		$tmp = $eras;
	}
	return $tmp;
	// }
}

function Clans($array) {

	// echo $today;
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	$today = $array["datee"];
	// echo $today;
	$d = explode("/", $today);
	// print_r($d);
	$today = $d[2] . "-" . $d[0] . "-" . $d[1];

	$query = "select distinct on (id) timemark,id,title, points, gone from clans where timemark<='" . $today . "' and gone is null order by id,timemark DESC ";
	// $query = "call {$config["base_database"]}.clans_list(\"$today\");\n";
	// $result = $connection->query($query);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$clans = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		$tmp = new ClanClass($line["id"], $line["title"], $line["points"]);
		array_push($clans, $tmp);
	}
	return $clans;
}

function Index($array) {

	// echo $today;
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	// $query = "call {$config["base_database"]}.clans_list(\"$today\");\n";
	// $result = $connection->query($query);
	$clans = Clans($array);
	// echo $query;
	// if ($result->num_rows > 0) {
	// 	while ($row = $result->fetch_assoc()) {
	// 		// print_r($row);
	// 		$tmp = new ClanClass($row["id"], $row["title"], $row["points"]);
	// 		array_push($clans, $tmp);
	// 	}
	// }
	// // print_r($clans);
	// $connection = Connect($config);
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// print_r($clans);
	// exit();
	$query = "select distinct on (id) timemark,id,nick, frags, deaths,level,clan from players where timemark<='" . $array['datee'] . "' order by id,timemark DESC ";
	// } else {
	// 	$query = "select distinct on (id) timemark,id,nick, frags, deaths,level,clan from players where clan=" . $array['clan'] . " and timemark<='" . $array['datee'] . "' order by id,timemark DESC ";
	// }
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$players = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		// if ($row["timemark"] == $today){
		$clan_title = "Нет клана";
		$clan_id = -2;
		foreach ($clans as $clan) {
			if ($line["clan"] == $clan->id) {
				$clan_title = $clan->title;
				$clan_id = $line["clan"];
			}
		}
		// }

		// if ($array['clan'] == -1) {
		// if (($nickname==$row["nick"])&&($nickname!=null)){
		$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
		// }
		// } else {
		// 	if ($array['clan'] == $clan_id) {
		// 		// if (($nickname==$row["nick"])&&($nickname!=null)){
		// 		$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
		// 		// array_push($players, $tmp);
		// 		// }
		// 	}
		// }
		if ($line["clan"] == -1) {
			$line["clan"] = -2;
		}
		if ((($array['clan'] != -1) && ($line["clan"] == $array['clan'])) || ($array['clan'] == -1)) {
			array_push($players, $tmp);
		}

	}
	// }
	// print_r($players);
	if ($array['order'] == "nick") {
		$nicks = array();
		$ids = array();
		for ($i = 0; $i < count($players); $i++) {
			array_push($nicks, $players[$i]->nick);
			array_push($ids, $i);
		}
		for ($i = 0; $i < count($nicks); $i++) {
			for ($j = 0; $j < count($nicks); $j++) {
				if (strnatcmp(mb_strtolower($nicks[$i]), mb_strtolower($nicks[$j])) == -1) {
					$tmp = $nicks[$i];
					$nicks[$i] = $nicks[$j];
					$nicks[$j] = $tmp;
					$tmp = $ids[$i];
					$ids[$i] = $ids[$j];
					$ids[$j] = $tmp;
				}
			}
		}
		$new = array();
		for ($i = 0; $i < count($ids); $i++) {
			array_push($new, $players[$ids[$i]]);
		}
		$players = $new;
	}
	if ($array['order'] == "frags") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->frags > $players[$j]->frags) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "deaths") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->deaths > $players[$j]->deaths) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "level") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->level > $players[$j]->level) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "clan_id") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->clan_id > $players[$j]->clan_id) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order_way'] == "asc") {
		$players = array_reverse($players);
	}
	$return = array();
	$num = 1;
	foreach ($players as $player) {
		if ($array['debug'] == "true") {
			array_push($return, new PlayerClassIndexD($num, $player->nick, $player->frags, $player->deaths, $player->level, $player->clan_title, $player->timemark));
		} else {
			array_push($return, new PlayerClassIndex($num, $player->nick, $player->frags, $player->deaths, $player->level, $player->clan_title));
		}
		$num++;

	}
	// print_r($players);
	return $return;
}

function indexEra($array) {
	$today = $array["datee"];
	// echo $today;
	$d = explode("/", $today);
	// print_r($d);
	$today = $d[2] . "-" . $d[0] . "-" . $d[1] . " 23:59:59";

	// echo $today;
	// $query = "use berserk;\n";
	// $result = $connection->query($query);
	// $connection = Connect($config);
	// $query = "call {$config["base_database"]}.clans_list(\"$today\");\n";
	// $result = $connection->query($query);
	$era = EraDates($array);
	$lbz = json_decode($era[0]->lbz, true);
	// print_r($lbz);
	$started = $era[0]->started . " 23:59:59";
	$ended = $era[0]->ended . " 23:59:59";
	if ($array['big'] != 1) {
		if (strtotime($today) > strtotime($ended)) {
			$timestamp = strtotime($ended);
			$ended = date("Y-m-d H:i:s", $timestamp + 2 * 24 * 60 * 60);
		} else {
			$ended = $today;
		}
	}
	$timestamp = strtotime($started);
	$started2 = date("Y-m-d H:i:s", $timestamp - 24 * 60 * 60);
	// print_r($era);
	// exit();
	$clans = Clans($array);
	if ($array['clan'] < 0) {
		$query = "select timemark,id,nick, frags, deaths,level,clan from players where timemark<='" . $ended . "' and timemark>= '" . $started . "' order by id,timemark DESC ";
	} else {
		$query = "select timemark,id,nick, frags, deaths,level,clan from players where clan=" . $array['clan'] . " and timemark<='" . $ended . "' and timemark>= '" . $started . "' order by id,timemark DESC ";
	}
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$players = array();
	// exit();
	$id_p = -1;
	$rows = array();

	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		// if ($row["timemark"] == $today){
		$clan_title = "Нет клана";
		$clan_id = -2;
		foreach ($clans as $clan) {
			if ($line["clan"] == $clan->id) {
				$clan_title = $clan->title;
				$clan_id = $line["clan"];
			}
		}
		// }

		if ($array['clan'] == -1) {
			// if (($nickname==$row["nick"])&&($nickname!=null)){
			$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
			// array_push($players, $tmp);
			// }
		} else {
			if ($array['clan'] == $clan_id) {
				// if (($nickname==$row["nick"])&&($nickname!=null)){
				$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
				// array_push($players, $tmp);
				// }
			}
		}
		if (($id_p == -1) || ($line["id"] == $id_p)) {
			array_push($rows, $tmp);
		} else {
			// echo $id_p . PHP_EOL;
			// echo "->";
			// print_r($rows);
			if ($array['clan'] < 0) {
				$query = "select timemark,id,nick, frags, deaths,level,clan from players where id=" . $id_p . " and timemark< '" . $started2 . "' order by id,timemark DESC limit 1";
			} else {
				$query = "select timemark,id,nick, frags, deaths,level,clan from players where clan=" . $array['clan'] . " and id=" . $id_p . " and timemark< '" . $started2 . "' order by id,timemark DESC limit 1";
			}
			// echo $query . PHP_EOL;
			$result2 = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
			// $players = array();
			// exit();
			// $id_p = -1;
			// $rows = array();

			while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
				// echo $line2["id"] . PHP_EOL;
				// echo ";";

				// print_r($row);
				// if ($row["timemark"] == $today){
				$clan_title = "Нет клана";
				$clan_id = -2;
				foreach ($clans as $clan) {
					if ($line2["clan"] == $clan->id) {
						$clan_title = $clan->title;
						$clan_id = $line2["clan"];
					}
				}
				// }

				if ($array['clan'] == -1) {
					// if (($nickname==$row["nick"])&&($nickname!=null)){
					$tmp2 = new PlayerClass($line2["timemark"], $line2["id"], Restring($line2["nick"]), $line2["frags"], $line2["deaths"], $line2["level"], $clan_id, $clan_title);
					// array_push($players, $tmp);
					// }
				} else {
					if ($array['clan'] == $clan_id) {
						// if (($nickname==$row["nick"])&&($nickname!=null)){
						$tmp2 = new PlayerClass($line2["timemark"], $line2["id"], Restring($line2["nick"]), $line2["frags"], $line2["deaths"], $line2["level"], $clan_id, $clan_title);
						// array_push($players, $tmp);
						// }
					}
				}
				// if (($id_p == -1) || ($line["id"] == $id_p)) {
				array_push($rows, $tmp2);
				// } else {
				// 	array_push($players, new Big_player($rows));
				// 	$players[count($players) - 1]->Cut();
				// 	$rows = array();
				// 	array_push($rows, $tmp);
				// }
				$id_p = $line2["id"];
			}
			// print_r($rows);

			array_push($players, new BigPlayer($rows));
			$players[count($players) - 1]->Cut();
			$rows = array();
			array_push($rows, $tmp);
		}
		$id_p = $line["id"];
	}
	array_push($players, new BigPlayer($rows));
	$players[count($players) - 1]->Cut();
	$rows = array();
	// }
	// }
	// print_r($players);
	$new_players = array();
	foreach ($players as $player) {
		foreach ($player->cuts as $cut) {
			// print_r($cut);
			if (count($cut->rows) > 0) {
				$a = $cut->max_frags - $cut->min_frags;
				$b = $cut->max_deaths - $cut->min_deaths;
				$c = floor(2 * $a + 0.5 * $b);
				$u = $a + $b;
				$o = 5 * $a + $b;
				$lbzz = "";
				foreach ($lbz as $lb_k => $lb_v) {
					if (intval($lb_k) <= $u) {
						// echo intval($lb_k) . " " . $u . PHP_EOL;
						$lbzz = $lb_v;
					}
				}
				// if (($lazy != "true")) {
				// 	array_push($new_players, new Player_class_era($cut->nick, $cut->max_frags, $cut->max_deaths, $cut->level, $cut->clan_id, $cut->clan_title, $a, $b, $u, $o, $lbzz));
				// } else {
				if ($o > 0) {
					array_push($new_players, new PlayerClassEra($cut->time, $cut->nick, $cut->max_frags, $cut->max_deaths, $cut->level, $cut->clan_id, $cut->clan_title, $a, $b, $u, $o, $lbzz));
				}
				// }
				// $lbzz=str_replace("+","<br>",$lbzz);
			}
		}
		// if (count($player->cuts)>1){
		// echo "more".PHP_EOL;
		// print_r($player);
		// }
	}

	$query = "select distinct on (id) timemark,id,nick, frags, deaths,level,clan from players where timemark<='" . $started2 . "' order by id,timemark DESC ";
	// } else {
	// 	$query = "select distinct on (id) timemark,id,nick, frags, deaths,level,clan from players where clan=" . $array['clan'] . " and timemark<='" . $array['datee'] . "' order by id,timemark DESC ";
	// }
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$players_extra = array();
	// echo $query;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		// if ($row["timemark"] == $today){
		$clan_title = "Нет клана";
		$clan_id = -2;
		foreach ($clans as $clan) {
			if ($line["clan"] == $clan->id) {
				$clan_title = $clan->title;
				$clan_id = $line["clan"];
			}
		}
		// }

		// if ($array['clan'] == -1) {
		// if (($nickname==$row["nick"])&&($nickname!=null)){
		$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
		// }
		// } else {
		// 	if ($array['clan'] == $clan_id) {
		// 		// if (($nickname==$row["nick"])&&($nickname!=null)){
		// 		$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
		// 		// array_push($players, $tmp);
		// 		// }
		// 	}
		// }
		if ($line["clan"] == -1) {
			$line["clan"] = -2;
		}
		if ((($array['clan'] != -1) && ($line["clan"] == $array['clan'])) || ($array['clan'] == -1)) {
			array_push($players_extra, $tmp);
		}

	}

	// print_r($players_extra);
	// print_r($new_players);

	$players = $new_players;

	foreach ($players_extra as $player_extra) {
		$was = 0;
		foreach ($players as $player) {
			if ($player_extra->nick == $player->nick) {
				$was = 1;
			}
		}
		if ($was == 0) {
			array_push($new_players, new PlayerClassEra(0, $player_extra->nick, $player_extra->frags, $player_extra->deaths, $player_extra->level, $player_extra->clan_id, $player_extra->clan_title, 0, 0, 0, 0, ""));

		}
	}
	$players = $new_players;

	if ($array['order'] == "nick") {
		$nicks = array();
		$ids = array();
		for ($i = 0; $i < count($players); $i++) {
			array_push($nicks, $players[$i]->nick);
			array_push($ids, $i);
		}
		for ($i = 0; $i < count($nicks); $i++) {
			for ($j = 0; $j < count($nicks); $j++) {
				if (strnatcmp(mb_strtolower($nicks[$i]), mb_strtolower($nicks[$j])) == -1) {
					$tmp = $nicks[$i];
					$nicks[$i] = $nicks[$j];
					$nicks[$j] = $tmp;
					$tmp = $ids[$i];
					$ids[$i] = $ids[$j];
					$ids[$j] = $tmp;
				}
			}
		}
		$new = array();
		for ($i = 0; $i < count($ids); $i++) {
			array_push($new, $players[$ids[$i]]);
		}
		$players = $new;
	}
	if ($array['order'] == "frags") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->frags > $players[$j]->frags) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "deaths") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->deaths > $players[$j]->deaths) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "level") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->level > $players[$j]->level) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "clan_id") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->clan_id > $players[$j]->clan_id) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "fragse") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->frags_era > $players[$j]->frags_era) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "deathse") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->deaths_era > $players[$j]->deaths_era) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	// if ($order=="sodars"){
	//   for ($i=0;$i<count($players);$i++){
	//     for ($j=0;$j<count($players);$j++){
	//       if ($players[$i]->games>$players[$j]->games){
	//         $tmp=$players[$i];
	//         $players[$i]=$players[$j];
	//         $players[$j]=$tmp;
	//       }
	//     }
	//   }
	// }
	if ($array['order'] == "actions") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->games > $players[$j]->games) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order'] == "points") {
		for ($i = 0; $i < count($players); $i++) {
			for ($j = 0; $j < count($players); $j++) {
				if ($players[$i]->points > $players[$j]->points) {
					$tmp = $players[$i];
					$players[$i] = $players[$j];
					$players[$j] = $tmp;
				}
			}
		}
	}
	if ($array['order_way'] == "asc") {
		$players = array_reverse($players);
	}
	// print_r($players);
	$return = array();
	$num = 1;
	foreach ($players as $player) {
		$tmp = "";
		foreach ($player->timemark as $time) {
			$tmp .= $time . "<br>";
		}
		// if (($player->points==inf) || ($player->points==NaN)|| ($player->frags_era==inf) || ($player->frags_era==NaN) || ($player->deaths_era==inf) || ($player->deaths_era==NaN)){
		//   print_r($player);
		// }
		if ($array['debug'] == "true") {
			array_push($return, new PlayerClassEraReturnD($num, $player->nick, $player->frags, $player->deaths, $player->level, $player->clan_title, $player->frags_era, $player->deaths_era, $player->games, $player->points, $player->lbz, $tmp));
		} else {
			array_push($return, new PlayerClassEraReturn($num, $player->nick, $player->frags, $player->deaths, $player->level, $player->clan_title, $player->frags_era, $player->deaths_era, $player->games, $player->points, $player->lbz));
			// }
		}
		$num++;
	}
	// print_r($players);
	return $return;
}
function UploadFile($array, $file, $uploadFileDir, $message) {
	// echo $uploadFileDir;
	// print_r($array);
	// print_r($file);
	if (isset($array['type']) && $array['type'] == 'upload') {
		if (isset($file['file']) && $file['file']['error'] === UPLOAD_ERR_OK) {
			// get details of the uploaded file
			$fileTmpPath = $file['file']['tmp_name'];
			$fileName = $file['file']['name'];
			$fileSize = $file['file']['size'];
			$fileType = $file['file']['type'];
			$fileNameCmps = explode(".", $fileName);
			$fileExtension = strtolower(end($fileNameCmps));

			// sanitize file-name
			// $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
			while (true) {
				$newFileName = uniqid('Berserk-', true) . '.' . $fileExtension;
				if (!file_exists(sys_get_temp_dir() . $uploadFileDir . $newFileName)) {
					break;
				}

			}

			// check if file has one of the following extensions
			$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');

			if (in_array($fileExtension, $allowedfileExtensions)) {
				// directory in which the uploaded file will be moved
				$dest_path = $uploadFileDir . $newFileName;
				// echo $dest_path;

				if (move_uploaded_file($fileTmpPath, "../" . $dest_path)) {
					$message = 'File is successfully uploaded.';
				} else {
					$message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
				}
			} else {
				$message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
			}
		} else {
			$message = 'There is some error in the file upload. Please check the following error. ';
			$message .= 'Error:' . $file['file']['error'];
		}
	}
	$query = "\nINSERT INTO screenshots (name,file,timemark) values ('$newFileName','$dest_path',current_timestamp) RETURNING id;\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$id = $line['id'];
	}
	$query = "\nINSERT INTO deck (player_id,description,screenshot_id,timemark) values (" . $array['player_id'] . ",'" . $array['descr'] . "',$id,current_timestamp) RETURNING id;\n";
	// echo $query;
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$res['message'] = $message;
	return $res;
}
?>