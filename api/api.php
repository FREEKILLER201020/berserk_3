<?php
// require "class.php";
require "../functions/string.php";
require "../classes/player.php";
require "../classes/clan.php";
require "../classes/era.php";
require "../classes/fight.php";
require "../classes/city.php";

// print_r($_POST);

$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
// print_r($config);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

OnCall($_POST);

function OnCall($array) {
	if ($array['type'] == 'era_dates') {
		$return = EraDates($array);
	}
	if ($array['type'] == 'clans') {
		$return = Clans($array);
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
	echo json_encode($return);
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
	if ($array['clan'] < 0) {
		$query = "select distinct on (id) timemark,id,nick, frags, deaths,level,clan from players where timemark<='" . $array['datee'] . "' order by id,timemark DESC ";
	} else {
		$query = "select distinct on (id) timemark,id,nick, frags, deaths,level,clan from players where clan=" . $array['clan'] . " and timemark<='" . $array['datee'] . "' order by id,timemark DESC ";
	}
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

		if ($array['clan'] == -1) {
			// if (($nickname==$row["nick"])&&($nickname!=null)){
			$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
			array_push($players, $tmp);
			// }
		} else {
			if ($array['clan'] == $clan_id) {
				// if (($nickname==$row["nick"])&&($nickname!=null)){
				$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $clan_id, $clan_title);
				array_push($players, $tmp);
				// }
			}
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

?>