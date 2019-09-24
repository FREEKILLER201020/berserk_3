<?php
error_reporting(1);
date_default_timezone_set('Europe/Moscow');
//
// require "class.php";
// require("functions.php");
require "functions/progress_bar.php";
require "functions/string.php";
require "functions/get_data.php";
include 'classes/pushover.php';
require "classes/player.php";
require "classes/clan.php";
require "classes/era.php";
require "classes/fight.php";
require "classes/city.php";
require "classes/timer.php";
ini_set('memory_limit', '16384M');

$file = file_get_contents(realpath(dirname(__FILE__)) . "/.config.json");
$config = json_decode($file, true);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());

// $TimeApp = new Timer();

// $colors = new Colors();
$all_attacks_array = array();
$all_attacks_array_i = 0;

// $file  = file_get_contents(realpath(dirname(__FILE__))."/../config.json");
// $config = json_decode($file, true);
$scanned_folders = array();
$scanned_folders["done"] = 0;
$save = 0;
$load = 0;
$cities_load = 0;
$start_p = -1;
$end_p = -1;
$restart = -1;
$restart_count = 0;
$debug = 0;
$no_update = 0;
$continue = 0;
$count = -1;
$notification = 0;
$period = 0;
$resave = 0;
$move = 0;
$new_scan = 0;
for ($i = 0; $i < count($argv); $i++) {
	if ($argv[$i] == "-s") {
		$save = 1;
	}
	if ($argv[$i] == "-res") {
		$resave = 1;
	}
	if ($argv[$i] == "-d") {
		$debug = 1;
	}
	if ($argv[$i] == "-l") {
		$load = 1;
	}
	if ($argv[$i] == "-city") {
		$cities_load = 1;
	}
	if ($argv[$i] == "-start") {
		$start_p = $argv[$i + 1];
	}
	if ($argv[$i] == "-end") {
		$end_p = $argv[$i + 1];
	}
	if ($argv[$i] == "-r") {
		$restart = $argv[$i + 1];
	}
	if ($argv[$i] == "-no_update") {
		$no_update = 1;
	}
	if ($argv[$i] == "-contin") {
		$continue = 1;
	}
	if ($argv[$i] == "-count") {
		$count = $argv[$i + 1];
	}
	if ($argv[$i] == "-notification") {
		$notification = 1;
	}
	if ($argv[$i] == "-new_scan") {
		$new_scan = 1;
	}
	if ($argv[$i] == "-update") {
		$period = $argv[$i + 1];
	}
	if ($argv[$i] == "-move") {
		$move = 1;
	}
}
$folder_root = "../THE_DATA/DATA";

// GET NEW DATA FROM THE GAME
if ($new_scan == 1) {
	$year = date('Y');
	$month = date('F');
	$day = date('d');
	$time = date('l-jS-\of-F-Y-h:i:s-A');
	$clans = GetClans($config);
	// $date = new DateTime();
	print_r($clans);
	$folder = $folder_root . "/new";
	$path = $folder . "/$year";
	exec("mkdir $path");
	$path .= "/$month";
	exec("mkdir $path");
	$path .= "/$day";
	exec("mkdir $path");
	// $path=realpath(dirname(__FILE__))."/DATA/$time";
	// $path_perf.="/Applications/MAMP/htdocs/THE_DATA/DATA/$year/$month/$day/$time";
	// $path_perf .= realpath(dirname(__FILE__)) . "/../THE_DATA/$data_folder_name/$year/$month/$day/$time";
	$path .= "/$time";
	exec("mkdir $path");
	echo $path . PHP_EOL;
	$query = " wget -O \"$path/cities_$time.json\" http://berserktcg.ru/api/export/cities.json";
	echo $query . PHP_EOL;
	exec($query);
	$query = " wget -O \"$path/clans_$time.json\" http://berserktcg.ru/api/export/clans.json";
	exec($query);
	$query = " wget -O \"$path/fights_$time.json\" http://berserktcg.ru/api/export/attacks.json";
	exec($query);
	foreach ($clans as $clan) {
		$query = " wget -O \"$path/clan[{$clan['id']}]_$time.json\" http://berserktcg.ru/api/export/clan/" . $clan['id'] . ".json";
		exec($query);
	}
	// exit();
	// // echo $time;
	// $file = array();
	// $folders = array();
	// $folder = "../THE_DATA/$data_folder_name";
	// $date = "";
	// $date .= intval(explode('-', $time)[1]) . " ";
	// $date .= explode('-', $time)[3] . " ";
	// $date .= explode('-', $time)[4] . " ";
	// $date .= explode('-', $time)[5] . " ";
	// $date .= explode('-', $time)[6] . " ";
	// $file["folder"] = "$folder/$year/$month/$day/$time";
	// $file["time"] = strtotime($date);
	// $file["file_dir"] = "$time";
	// array_push($folders, $file);

	// print_r($folders);
}

// GET NEW DATA
if ($load != 1) {
	$folder = $folder_root . "/new";
	$ls = shell_exec("ls $folder");
	$folders = array();
	$years = explode("\n", $ls);
	unset($years[count($years) - 1]);
	foreach ($years as $year) {
		if ($move == 1) {
			$query = "mkdir " . str_replace("new", "scaned", "$folder/$year");
			// echo $query;
			exec($query);
		}
		$ls = shell_exec("ls $folder/$year");
		$months = explode("\n", $ls);
		unset($months[count($months) - 1]);
		foreach ($months as $month) {
			if ($move == 1) {
				$query = "mkdir " . str_replace("new", "scaned", "$folder/$year/$month");
				// echo $query;
				exec($query);
			}
			$ls = shell_exec("ls $folder/$year/$month");
			$days = explode("\n", $ls);
			unset($days[count($days) - 1]);
			foreach ($days as $day) {
				if ($move == 1) {
					$query = "mkdir " . str_replace("new", "scaned", "$folder/$year/$month/$day");
					// echo $query;
					exec($query);
				}
				$file = array();
				$ls = shell_exec("ls $folder/$year/$month/$day");
				$scans = explode("\n", $ls);
				unset($scans[count($scans) - 1]);
				foreach ($scans as $scan) {
					$date = "";
					$date .= intval(explode('-', $scan)[1]) . " ";
					$date .= explode('-', $scan)[3] . " ";
					$date .= explode('-', $scan)[4] . " ";
					$date .= explode('-', $scan)[5] . " ";
					$date .= explode('-', $scan)[6] . " ";
					$file["folder"] = "$folder/$year/$month/$day/$scan";
					$file["time"] = strtotime($date);
					$file["file_dir"] = "$scan";
					array_push($folders, $file);
				}
			}
		}
	}
	// $new = array();
	// for ($i = 0; $i < 15000; $i++) {
	// 	array_push($new, $folders[$i]);
	// }
	// $folders = $new;
	print_r($folders[count($folders) - 1]);
	// SORT NEW DATA
	$t0 = microtime(true) * 10000;
	for ($i = 0; $i < count($folders); $i++) {
		$t = (microtime(true) * 10000 - $t0) / ($i + 1);
		progressBar($i, count($folders), $t, $t0, "");
		for ($j = $i; $j < count($folders); $j++) {
			if ($folders[$i]['time'] > $folders[$j]['time']) {
				$tmp = $folders[$i];
				$folders[$i] = $folders[$j];
				$folders[$j] = $tmp;
			}
		}
	}

	// quicksort($folders,0,0);

}
// print_r($folders);
// arraycheck($folders);
// SAVE NEW DATA
if ($save == 1) {
	$file_load = "big_order_data.json";
	$file_big_order = file_get_contents($file_load);
	$file_big_order_json = json_decode($file_big_order, true);
	if (!isset($file_big_order_json)) {
		$file_big_order_json = array();
	}
	// print_r($file_big_order_json);
	if ($resave == 1) {
		$new_big = $folders;
	} else {
		$new_big = array_merge($file_big_order_json, $folders);
	}
	$new_big = array_unique($new_big, SORT_REGULAR);
	// print_r($new_big);
	// shell_exec("rm big_order_data.json");
	// $folders = json_decode($file_load, true);
	file_put_contents($file_load, json_encode($new_big, JSON_UNESCAPED_UNICODE));
	// echo PHP_EOL . "Folders: " . count($new_big) . PHP_EOL;
	sleep(1);
}
// exit();
// LOAD SAVED DATA
if ($load == 1) {
	$file_load = file_get_contents(realpath(dirname(__FILE__)) . "/big_order_data.json");
	$folders = json_decode($file_load, true);
	// file_put_contents($file_load, json_encode($data,JSON_UNESCAPED_UNICODE));
}

// ADD CITIES DATA

if ($cities_load == 1) {
	$file = file_get_contents(realpath(dirname(__FILE__)) . "/../config.json");
	$config = json_decode($file, true);
	$connection = new mysqli($config["hostname2"] . $config["port2"], $config["username2"], $config["password2"]);

	if ($connection->connect_errno) {
		exit();
	}

	$query = "SELECT DISTINCT timemark from {$config["base_database2"]}.Cities_fast order by timemark desc";
	$result = $connection->query($query);
	mysqli_close($connection);

	$times = array();
	if ($result->num_rows > 0) {
		while ($row2 = $result->fetch_assoc()) {
			$tmp = array();
			array_push($tmp, $row2['timemark']);
			array_push($tmp, strtotime($row2['timemark']));
			array_push($times, $tmp);
		}
	}
	$t0 = microtime(true) * 10000;
	$iji = 0;
	foreach ($times as $time) {
		$t = (microtime(true) * 10000 - $t0) / $iji;
		progressBar($iji, count($times), $t, $t0, "");
		$iji++;
		for ($i = 0; $i < count($folders) - 1; $i++) {
			if (($folders[$i]['time'] <= $time[1]) && ($time[1] <= $folders[$i + 1]['time'])) {
				$file_link = $folders[$i]['folder'] . "/cities_" . $folders[$i]['file_dir'] . ".json";
				// exec("rm ".$file_link);
				if (filesize($file_link) == 0) {
					$connection = new mysqli($config["hostname2"] . $config["port2"], $config["username2"], $config["password2"]);
					$query = "SELECT DISTINCT * from {$config["base_database2"]}.Cities_fast where timemark=\"$time[0]\"";
					if ($debug == 1) {
						echo $query . PHP_EOL;
					}
					$data = array();
					$result = $connection->query($query);
					if ($result->num_rows > 0) {
						while ($row2 = $result->fetch_assoc()) {
							array_push($data, $row2);
						}
					}
					mysqli_close($connection);
					file_put_contents($file_link, json_encode($data, JSON_UNESCAPED_UNICODE));
				}
				break;
			}
		}
	}
}
// $config['base_database']="berserk";
// $query = "SET search_path = berserk;\n";
// $result = $connection->query($query);
// $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

// DATA ANALISE START
$t0 = microtime(true) * 10000;
$i = 0;
if (($start_p == -1) && ($end_p == -1)) {
	$start_p = 0;
	$end_p = count($folders);
}

if ($continue == 1) {
	$file_done = file_get_contents(realpath(dirname(__FILE__)) . "/scanned_folders.json");
	$scanned_done = json_decode($file_done, true);
	$start_p = $scanned_done["done"] - 1;
	$scanned_folders["done"] = $start_p;
}
if ($count > -1) {
	$end_p = $start_p + $count;
}
if ($debug == 1) {
	// echo PHP_EOL . "Scan from $start_p to $end_p" . PHP_EOL;
}
$eras_log = array();
$eras_stage = 0;
for ($i = $start_p; $i < $end_p; $i++) {
	if (!isset($folders[$i])) {
		exit();
	}
	$extra = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
	$scanned_folders["done"]++;
	if ($restart > 0) {
		if ($restart_count > 0) {
			$restart_count--;
		} else {
			$restart_count = $restart;
			$res = file_get_contents("/Applications/MAMP/bin/sqlrestart.sh");
			$ls = shell_exec($res);
			// echo PHP_EOL . $ls . PHP_EOL;
			// exit();
		}
	}
	$log = array();
	$timee = $folders[$i]['time'];
	$s_start = microtime(true) * 10000;
	$t = (microtime(true) * 10000 - $t0) / $i;
	progressBar($i, $end_p, $t, $t0, $extra);
	if ($period != 0) {
		if (bcmod($i, $period) == 0) {
			$push = new Pushover();

			$push->setToken('a5g19h6if4cdvvfrdw8n5najpm68rb');
			$push->setUser('uuaj196grt8gjg6femsnjgc8tte1k8');

			$push->setTitle('Data scaner');
			$push->setMessage('Scan in progress: ' . $i . ' from ' . $end_p . ' ' . time());
			// $push->setUrl('http://chris.schalenborgh.be/blog/');
			// $push->setUrlTitle('cool php blog');
			$push->setDevice('pixel2xl');
			$push->setPriority(0);
			$push->setRetry(60); //Used with Priority = 2; Pushover will resend the notification every 60 seconds until the user accepts.
			$push->setExpire(3600); //Used with Priority = 2; Pushover will resend the notification every 60 seconds for 3600 seconds. After that point, it stops sending notifications.
			$push->setTimestamp(time());
			$push->send();
		}
	}
	$noerr = 1;
	// FOLDER IS BAD IF CLANS FILE IS EMPTY
	$file = realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/clans_{$folders[$i]['file_dir']}.json";
	if (filesize($file) == 0) {
		$noerr = 0;
		$log['clans'] = "error";
	} else {
		$log['clans'] = "ok";
	}
	$file = file_get_contents(realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/clans_{$folders[$i]['file_dir']}.json");
	$json = json_decode($file, true);
	if ($json == NULL) {
		$noerr = 0;
		$log['clans'] = "error";
	}
	// if (!isset($json[players])) {
	// 	$noerr = 0;
	// 	$log['clans'] = "error";
	// }
	// if (!isset($json[cities])) {
	// 	$noerr = 0;
	// 	$log['clans'] = "error";
	// }
	// echo $folders[$i]['folder'] . PHP_EOL;
	// print_r($json);
	$clans = array();
	if ($noerr == 1) {
		$cities = array();
		foreach ($json as $row) {
			// FOLDER IS BAD IF CLAN[$I] FILE IS EMPTY
			$file = realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/clan[{$row['id']}]_{$folders[$i]['file_dir']}.json";
			if (filesize($file) == 0) {
				$noerr = 0;
				$log["clan[{$row['id']}]"] = "error";
			} else {
				$file = file_get_contents(realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/clan[{$row['id']}]_{$folders[$i]['file_dir']}.json");
				$json_players = json_decode($file, true);
				if ($json_players[players] == NULL) {
					$noerr = 0;
					$log["clan[{$row['id']}]"] = "error";
					// echo $folders[$i]['file_dir'] . PHP_EOL;
					// print_r($log);
					// print_r($json_players);
				}
				foreach ($json_players['cities'] as $city) {
					array_push($cities, $city);
				}
				$log["clan[{$row['id']}]"] = "ok";
				if (!isset($json_players[players])) {
					$noerr = 0;
					$log["clan[{$row['id']}]"] = "error";
					// echo $folders[$i]['file_dir'] . PHP_EOL;
					// print_r($log);
					// print_r($json_players);
				}
				if (!isset($json_players[cities])) {
					$noerr = 0;
					$log["clan[{$row['id']}]"] = "error";
					// echo $folders[$i]['file_dir'] . PHP_EOL;
					// print_r($log);
					// print_r($json_players);
				}
			}
		}
		$dups = 0;
		for ($k = 0; $k < count($cities); $k++) {
			for ($j = $k; $j < count($cities); $j++) {
				if ($cities[$k] > $cities[$j]) {
					$tmp = $cities[$k];
					$cities[$k] = $cities[$j];
					$cities[$j] = $tmp;
				}
			}
		}
		for ($k = 0; $k < count($cities) - 1; $k++) {
			if ($cities[$k] == $cities[$k + 1]) {
				$dups++;
			}
		}
		// FOLDER IS BAD IF CITY IS IN 2 CLANS
		if ($dups > 0) {
			$noerr = 0;
			$log["clans_cities"] = "error";
		} else {
			$log["clans_cities"] = "ok";
		}
	}
	// FOLDER IS BAD IF FIGHTS FILE IS EMPTY
	$file = realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/fights_{$folders[$i]['file_dir']}.json";
	if (filesize($file) == 0) {
		$noerr = 0;
		$log["fights"] = "error";
	} else {
		$log["fights"] = "ok";
	}
	// FOLDER IS BAD IF CITIES FILE IS EMPTY
	$file = realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/cities_{$folders[$i]['file_dir']}.json";
	if (filesize($file) == 0) {
		$noerr = 0;
		$log["cities"] = "error";
	} else {
		$log["cities"] = "ok";
	}
	if ($noerr == 0) {
		$bad_folders++;
	}
	// IF FOLDER IS GOOD, SCAN IT!
	$query_log = "";
	if ($noerr == 1) {
		if ($no_update != 1) {
			// $TimeApp->Save();
			$cities2 = array();
			// CLANS DATA START
			// $connection=Connect($config);
			$query = "select * from clans_list_all();\n";
			// $result = $connection->query($query);
			$result = pg_query($query);
			$clans_server = array();
			$clans_befor_update = array();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				$tmp = new ClanClassTest($line["id"], $line["title"], $line["points"], 0, $line["gone"], $line["created"]);
				array_push($clans_server, $tmp);
				array_push($clans_befor_update, $tmp);
			}
			// print_r($clans_server);
			// sleep(1);
			// mysqli_close($connection);
			// $connection=Connect($config);
			$query = "select * from players_all();\n";
			$result = pg_query($query);
			$players_server = array();
			$players_befor_update = array();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

				$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan"], "");
				array_push($players_server, $tmp);
				array_push($players_befor_update, $tmp);
			}
			// echo PHP_EOL . "Load_players_all" . PHP_EOL;
			// $TimeApp->Revil();
			// mysqli_close($connection);
			$cities_in_clans_files = array();
			foreach ($json as $row) {
				$was = 0;
				$was_server = 0;
				$file = file_get_contents(realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/clan[{$row['id']}]_{$folders[$i]['file_dir']}.json");
				$clans_tmp = json_decode($file, true);
				foreach ($clans_server as $clan) {
					if ($clan->id == $row['id']) {
						$was = 1;
						$clan->was = 1;
						if (($clan->title != $row['title']) || ($clan->points != $row['points'])) {

							// $connection=Connect($config);
							$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
							// echo $query . PHP_EOL;
							$query = "INSERT INTO clans (timemark,id,title,points,created) values ('{$d}',{$row['id']},'{$row['title']}',{$row['points']},'{$clans_tmp['created']}');\n";
							$query_log .= $query;
							if ($debug == 1) {
								$log["log"] .= "{" . $query . "}";
								echo $query . PHP_EOL;
							}
							// $result = $connection->query($query);
							$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
							// mysqli_close($connection);
						}
					}
				}
				if ($was == 0) {
					// $connection=Connect($config);
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$query = "INSERT INTO clans (timemark,id,title,points,created) values ('{$d}',{$row['id']},'{$row['title']}',{$row['points']},'{$clans_tmp['created']}');\n";
					// echo $query . PHP_EOL;
					$query_log .= $query;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . PHP_EOL;
					}
					// $result = $connection->query($query);
					$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
					// mysqli_close($connection);
				}
				// CLANS DATA END
				// PLAYERS DATA START
				$file = file_get_contents(realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/clan[{$row['id']}]_{$folders[$i]['file_dir']}.json");
				$json_players = json_decode($file, true);

				// print_r($json_players);
				// echo $folders[$i]['file_dir'] . PHP_EOL;
				foreach ($json_players['cities'] as $clan_file_cities) {
					array_push($cities_in_clans_files, $clan_file_cities);
				}
				foreach ($json_players['players'] as $player) {
					$was2 = 0;
					foreach ($players_server as $player_server) {
						if ($player_server->id == $player['id']) {
							if ($player_server->clan_id != -1) {
								$player_server->was = 1;
								$was2 = 1;
								if (($player_server->nick != $player['nick']) || ($player_server->deaths != $player['deaths']) || ($player_server->frags != $player['frags']) || ($player_server->level != $player['level']) || ($player_server->clan_id != $row['id'])) {
									// $connection=Connect($config);
									$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
									$query = "INSERT INTO players (timemark,id,nick,frags,deaths,level,clan,folder) values ('{$d}',{$player['id']},'{$player['nick']}',{$player['frags']},{$player['deaths']},{$player['level']},{$row['id']},'{$folders[$i]['folder']}');\n";
									$query_log .= $query;
									// echo $query . PHP_EOL;
									if ($debug == 1) {
										$log["log"] .= "{" . $query . "}";
										echo $query . PHP_EOL;
									}
									// $result = $connection->query($query);
									$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
									// mysqli_close($connection);
								}
							}
						}
					}
					if ($was2 == 0) {
						// $connection=Connect($config);
						$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
						$query = "INSERT INTO players (timemark,id,nick,frags,deaths,level,clan,folder) values ('{$d}',{$player['id']},'{$player['nick']}',{$player['frags']},{$player['deaths']},{$player['level']},{$row['id']},'{$folders[$i]['folder']}');\n";
						$query_log .= $query;
						// echo $query . PHP_EOL;
						if ($debug == 1) {
							$log["log"] .= "{" . $query . "}";
							echo $query . PHP_EOL;
						}
						// $result = $connection->query($query);
						$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
						// mysqli_close($connection);
					}
				}
				// CITIES_CLAN DATA START
				foreach ($json_players['cities'] as $city) {
					array_push($cities2, $city);
				}
				// CITIES_CLAN DATA END
			}
			// echo PHP_EOL . "First big loop" . PHP_EOL;
			// $TimeApp->Revil();
			foreach ($players_server as $player_server) {
				$file = file_get_contents(realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/clan[{$player_server->clan_id}]_{$folders[$i]['file_dir']}.json");
				$json_players = json_decode($file, true);
				if (($player_server->was == 0) && ($player_server->clan_id != -1) && (count($json_players['players']) > 0)) {
					// print_r($player_server);
					// print_r($json_players['players']);
					// $connection=Connect($config);
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$query = "INSERT INTO players (timemark,id,nick,frags,deaths,level,clan,folder) values ('{$d}',{$player_server->id},'{$player_server->nick}',{$player_server->frags},{$player_server->deaths},{$player_server->level},-1,'{$folders[$i]['folder']}');\n";
					$query_log .= $query;
					// echo $query . PHP_EOL;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . PHP_EOL;
					}
					$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
					// $result = $connection->query($query);
					// mysqli_close($connection);
				}
			}
			// echo PHP_EOL . "foreach (players_server" . PHP_EOL;
			// $TimeApp->Revil();

			// PLAYERS DATA END
			// UPDATE GONE CLAN
			foreach ($clans_server as $clan) {
				// print_r($clan);
				if (($clan->was == 0) && ($clan->gone == "")) {

					// print_r($clan);
					// $connection=Connect($config);
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$query = "INSERT INTO clans (timemark,id,title,points,created,gone) values ('{$d}',{$clan->id},'{$clan->title}',{$clan->points},'{$clan->created}','{$d}');\n";
					$query_log .= $query;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . "!!!" . PHP_EOL;
					}
					$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
					// print_r($update_clans);

				}
			}
			// echo PHP_EOL . "foreach (clans_server" . PHP_EOL;
			// $TimeApp->Revil();
			// CITIES DATA START
			$file_city = realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/cities_{$folders[$i]['file_dir']}.json";
			$file_city = file_get_contents($file_city);
			$city_json = json_decode($file_city, true);
			$total = count($city_json);
			for ($ipo = 0; $ipo < $total; $ipo++) {
				if ($city_json[$ipo]["clan"] == null) {
					unset($city_json[$ipo]);
				}
			}
			$total = count($city_json);
			for ($ipo = 0; $ipo < $total; $ipo++) {
				if (!in_array($city_json[$ipo]["id"], $cities_in_clans_files)) {
					unset($city_json[$ipo]);
				}
			}
			// $connection = Connect($config);
			$query = "select * from cities_all();\n";
			// $result = $connection->query($query);
			$result = pg_query($query);
			$cities_server = array();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

				$tmp = new City($line["id"], $line["name"], $line["clan"]);
				array_push($cities_server, $tmp);

			}
			// mysqli_close($connection);
			// $TimeApp->Save();
			foreach ($city_json as $row) {
				foreach ($cities2 as $city_clan) {
					$was_city = 0;
					if ($city_clan == $row['id']) {
						foreach ($cities_server as $city) {
							if ($city->id == $row['id']) {
								$was_city = 1;
								$city->was = 1;
								if (($city->name != $row['name']) || ($city->clan != $row['clan'])) {
									// $connection=Connect($config);
									$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
									$query = "INSERT INTO cities (timemark,id,name,clan) values ('{$d}',{$row['id']},'{$row['name']}',{$row['clan']});\n";
									$query_log .= $query;
									if ($debug == 1) {
										$log["log"] .= "{" . $query . "}";
										echo $query . PHP_EOL;
									}
									$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
									// $result = $connection->query($query);
									// mysqli_close($connection);
								}
							}
						}
						if ($was_city == 0) {
							// $connection=Connect($config);
							$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
							$query = "INSERT INTO cities (timemark,id,name,clan) values ('{$d}',{$row['id']},'{$row['name']}',{$row['clan']});\n";
							$query_log .= $query;
							if ($debug == 1) {
								$log["log"] .= "{" . $query . "}";
								echo $query . PHP_EOL;
							}
							$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
							// $result = $connection->query($query);
							// mysqli_close($connection);
						}
					}
				}
			}
			foreach ($cities_server as $city) {
				if (($city->was != 1) && ($city->clan != -2)) {
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$query = "INSERT INTO cities (timemark,id,name,clan) values ('{$d}',$city->id,'$city->name',-2);\n";
					$query_log .= $query;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . PHP_EOL;
					}
					$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
					echo "This city is gone! check it! " . $city->id . " " . $folders[$i]['file_dir'] . PHP_EOL;
				}
			}
			// echo PHP_EOL . "foreach (city_json" . PHP_EOL;
			// $TimeApp->Revil();
			// CITIES DATA END
			// ATTACKS DATA START
			$file_attacks = realpath(dirname(__FILE__)) . "/{$folders[$i]['folder']}/fights_{$folders[$i]['file_dir']}.json";
			$file_attacks = file_get_contents($file_attacks);
			$attacks_json = json_decode($file_attacks, true);
			// $connection=Connect($config);
			// $query = "select * from attacks;\n";
			$query = "select * from attacks where ended is null;\n";
			$result = pg_query($query);
			// $result = $connection->query($query);
			$attacks_server = array();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

				$tmp = new FightClass($line["attacker"], $line["defender"], $line["from"], $line["to"], $line["declared"], $line["resolved"], $line["winer"], $line["ended"]);
				array_push($attacks_server, $tmp);
			}
			// mysqli_close($connection);
			// $TimeApp->Save();
			$query = "select * from clans_list_all();\n";
			// $result = $connection->query($query);
			$result = pg_query($query);
			$clans_server = array();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				$tmp = new ClanClassTest($line["id"], $line["title"], $line["points"], 0, $line["gone"], $line["created"]);
				array_push($clans_server, $tmp);

			}

			$query = "select * from cities_all();\n";
			// $result = $connection->query($query);
			$result = pg_query($query);
			$cities_server = array();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

				$tmp = new City($line["id"], $line["name"], $line["clan"]);
				array_push($cities_server, $tmp);

			}
			$tmp = new City(-1, "Варвары", 0);
			array_push($cities_server, $tmp);
			foreach ($attacks_json as $attacks) {
				// print_r($clans_server);
				$attacks['declared'] = ReDate1($attacks['declared']);
				$attacks['resolved'] = ReDate1($attacks['resolved']);
				foreach ($cities_server as $city) {
					if ($city->name == $attacks['from']) {
						$attacks['from'] = $city->id;
					}
					if ($city->name == $attacks['to']) {
						$attacks['to'] = $city->id;
					}
				}
				foreach ($clans_server as $clan) {
					if ($clan->title == $attacks['defender']) {
						$attacks['defender'] = $clan->id;
					}
					if ($clan->title == $attacks['attacker']) {
						$attacks['attacker'] = $clan->id;
					}
				}
				// $attacks['defender'] = GetClanId($config, $attacks['defender']);
				// $attacks['attacker'] = GetClanId($config, $attacks['attacker']);
				// $attacks['from'] = CityId($config, $attacks['from']);
				// $attacks['to'] = CityId($config, $attacks['to']);
				// print_r($attacks);
				$was_attacks = 0;
				foreach ($attacks_server as $attack) {
					if (($attacks['attacker'] == $attack->attacker_id) && ($attacks['defender'] == $attack->defender_id) && ($attacks['from'] == $attack->from) && ($attacks['to'] == $attack->to) && ($attacks['declared'] == $attack->declared) && ($attacks['resolved'] == $attack->resolved)) {
						$was_attacks = 1;
						$attack->was = 1;
					}
				}
				if ($was_attacks == 0) {
					// $connection = Connect($config);
					$query = "INSERT INTO attacks (attacker,defender,\"from\",\"to\",declared,resolved,folder) values ('{$attacks['attacker']}','{$attacks['defender']}','{$attacks['from']}','{$attacks['to']}','{$attacks['declared']}','{$attacks['resolved']}','{$folders[$i]['folder']}');\n";
					$query_log .= $query;
					// echo $query . PHP_EOL;

					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
					}
					$result = pg_query($query);
					// $result = $connection->query($query);
					// mysqli_close($connection);
					$all_attacks_array[$all_attacks_array_i]['query'] = $query;
					$all_attacks_array[$all_attacks_array_i]['folder'] = $folders[$i]['folder'];
					$all_attacks_array_i++;
				}
			}
			$tmp_count = 0;
			// echo PHP_EOL . "foreach (attacks_json" . PHP_EOL;
			// $TimeApp->Revil();
			foreach ($attacks_server as $attack) {
				if (($attack->was == 0) && ($attack->ended == null)) {
					// $connection = Connect($config);
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$winer = WhoHasThisCity($config, $attack->to);
					if ($winer == $attack->defender_id) {
						$winer2 = $attack->defender_id;
					} else {
						$winer2 = $attack->attacker_id;
					}
					$query = "UPDATE attacks set ended='$d', winer=$winer2 WHERE attacker='{$attack->attacker_id}' and defender='{$attack->defender_id}' and \"from\"='{$attack->from}' and \"to\"='{$attack->to}' and declared='{$attack->declared}' and resolved='{$attack->resolved}';\n";
					$query_log .= $query;
					// echo $query . PHP_EOL;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . PHP_EOL;
					}
					$result = pg_query($query);
					// or die('Ошибка запроса: ' . pg_last_error());
					// $result = $connection->query($query);
					// mysqli_close($connection);
					$tmp_count++;
				}
			}
			if ((count($attacks_server) > 0) && ($eras_stage == 0)) {
				$eras_stage == 1;
				array_push($eras_log, "Era started " . $folders[$i]['file_dir']);
				print_r($eras_log);
			}
			if ((count($attacks_server) <= 0) && ($eras_stage == 1)) {
				$eras_stage == 0;
				array_push($eras_log, "Era ended " . $folders[$i]['file_dir']);
				print_r($eras_log);

			}
			// echo PHP_EOL . "foreach (attacks_server" . PHP_EOL;
			// $TimeApp->Revil();
			if ($tmp_count != 0) {
				$may_have_err++;
			}
			// ATTACKS DATA END
			// BAD FACTORS CHECK
			$dups = 0;
			for ($k = 0; $k < count($cities2); $k++) {
				for ($j = $k; $j < count($cities2); $j++) {
					if ($cities2[$k] > $cities2[$j]) {
						$tmp = $cities2[$k];
						$cities2[$k] = $cities2[$j];
						$cities2[$j] = $tmp;
					}
				}
			}
			for ($k = 0; $k < count($cities2) - 1; $k++) {
				if ($cities2[$k] == $cities2[$k + 1]) {
					$dups++;
				}
			}
			if ($dups > 0) {
				$crit++;
			}
		}
	}

	$query = "select * from clans_list_all();\n";
	// $result = $connection->query($query);
	$result = pg_query($query);
	$clans_after_update = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$tmp = new ClanClassTest($line["id"], $line["title"], $line["points"], 0, $line["gone"], $line["created"]);
		array_push($clans_after_update, $tmp);
	}

	// print_r($clans_befor_update);
	// print_r($clans_after_update);
	foreach ($clans_befor_update as $clan1) {
		foreach ($clans_after_update as $clan2) {
			if ($clan1->id == $clan2->id) {
				// print_r($clan1);
				// print_r($clan2);
				if ($clan1->gone != $clan2->gone) {
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$query = "insert into clans_updates (timemark,id,title,created,gone) values ('{$d}',$clan1->id,'$clan1->title','$clan2->created','$clan2->gone');\n";
					$query_log .= $query;
					// echo $query . PHP_EOL;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . PHP_EOL;
					}
					// $result = $connection->query($query);
					$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
				}
				if ($clan1->title != $clan2->title) {
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$query = "insert into clans_updates (timemark,id,created,title,new_title) values ('{$d}',$clan1->id,'$clan2->created','$clan1->title','$clan2->title');\n";
					$query_log .= $query;
					// echo $query . PHP_EOL;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . PHP_EOL;
					}
					// $result = $connection->query($query);
					$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
				}
				$clan1->was = 1;
				$clan2->was = 1;
			}
		}
	}

	foreach ($clans_after_update as $clan2) {
		if ($clan2->was == 0) {
			$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
			$query = "insert into clans_updates (timemark,id,title,created) values ('{$d}',$clan2->id,'$clan2->title','$clan2->created');\n";
			$query_log .= $query;
			// echo $query . PHP_EOL;
			if ($debug == 1) {
				$log["log"] .= "{" . $query . "}";
				echo $query . PHP_EOL;
			}
			// $result = $connection->query($query);
			$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		}
	}

	$query = "select * from players_all();\n";
	$result = pg_query($query);
	$players_after_update = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		$tmp = new PlayerClass($line["timemark"], $line["id"], Restring($line["nick"]), $line["frags"], $line["deaths"], $line["level"], $line["clan"], "");
		array_push($players_after_update, $tmp);
	}

	foreach ($players_befor_update as $player1) {
		foreach ($players_after_update as $player2) {
			if ($player1->id == $player2->id) {
				if ($player1->nick != $player2->nick) {
					$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
					$query = "insert into players_updates (timemark,id,nick,new_nick) values ('{$d}',$player1->id,'$player1->nick','$player2->nick');\n";
					$query_log .= $query;
					// echo $query . PHP_EOL;
					if ($debug == 1) {
						$log["log"] .= "{" . $query . "}";
						echo $query . PHP_EOL;
					}
					// $result = $connection->query($query);
					$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
				}
				if ($player1->clan_id != $player2->clan_id) {
					if (($player1->was == 0) && ($player2->was == 0)) {
						// print_r($player1);
						// print_r($player2);
						$clan1 = "Нет клана";
						$clan2 = "Нет клана";
						foreach ($clans_after_update as $clan) {
							if ($clan->id == $player1->clan_id) {
								$clan1 = $clan->title . "(" . $clan->id . ")";
							}
							if ($clan->id == $player2->clan_id) {
								$clan2 = $clan->title . "(" . $clan->id . ")";
							}
						}
						$d = date('Y-m-d H:i:s', $folders[$i]['time'] - 3 * 60 * 60);
						$query = "insert into players_updates (timemark,id,nick,old_clan,new_clan) values ('{$d}',$player1->id,'$player1->nick','$clan1','$clan2');\n";
						$query_log .= $query;
						// echo $query . PHP_EOL;
						if ($debug == 1) {
							$log["log"] .= "{" . $query . "}";
							echo $query . PHP_EOL;
						}
						// $result = $connection->query($query);
						$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
					}
				}
				$player1->was = 1;
				$player2->was = 1;
			}
		}
	}

	// echo PHP_EOL;
	// echo $query_log;
	// echo "players: " . count($players_server) . PHP_EOL;
	// echo "attacks: " . count($attacks_server) . PHP_EOL;
	// echo "cities: " . count($cities_server) . PHP_EOL;
	// echo "clans: " . count($clans_server) . PHP_EOL;
	// echo "time: " . (microtime(true) * 10000 - $s_start) . PHP_EOL;

	$file_link = $folders[$i]['folder'] . "/query_" . $folders[$i]['file_dir'] . ".sql";
	// if (filesize($file_link)!=0) {
	//     shell_exec('rm '.$file_link);
	// }
	// $text=json_encode($log, JSON_UNESCAPED_UNICODE);
	file_put_contents($file_link, $query_log);
	$query = "mv " . $folders[$i]['folder'] . " " . str_replace($folders[$i]['file_dir'], "", str_replace("new", "scaned", $folders[$i]['folder']));
	// echo $query . PHP_EOL;
	if ($move == 1) {
		exec($query);
		// exit();
	}

	// $connection=Connect($config);
	// $text=$connection->escape_string($text);
	// $d=date('Y-m-d H:i:s', $folders[$i]['time']-3*60*60);
	// $query = "INSERT INTO {$config['base_database']}.Logs (timemark,log) values (\"$d\",\"$text\")\n";
	// if ($debug==1) {
	//     echo $query.PHP_EOL;
	// }
	// $result = $connection->query($query);
	$file_link_scan = "scanned_folders.json";
	file_put_contents($file_link_scan, json_encode($scanned_folders, JSON_UNESCAPED_UNICODE));
}
// for ($i = $start_p; $i < $end_p; $i++) {
$query = "rm -r " . $folder_root . "/new/*";
// echo $query . PHP_EOL;
if ($move == 1) {
	exec($query);
}

pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
// }

// DATA ANALISE END
// print_r($all_attacks_array);
// echo PHP_EOL . "$bad_folders/" . count($folders) . " are bad folders " . $bad_folders / count($folders) * 100 . PHP_EOL;
echo PHP_EOL . $crit . PHP_EOL;
echo PHP_EOL . "attacks may have errors" . $may_have_err . PHP_EOL;
if ($notification == 1) {
	$push = new Pushover();

	$push->setToken('a5g19h6if4cdvvfrdw8n5najpm68rb');
	$push->setUser('uuaj196grt8gjg6femsnjgc8tte1k8');

	$push->setTitle('Data scaner');
	$push->setMessage('Scan complited! From ' . $start_p . ' to ' . $end_p . ' ' . time());
	// $push->setUrl('http://chris.schalenborgh.be/blog/');
	// $push->setUrlTitle('cool php blog');
	$push->setDevice('pixel2xl');
	$push->setPriority(0);
	$push->setRetry(60); //Used with Priority = 2; Pushover will resend the notification every 60 seconds until the user accepts.
	$push->setExpire(3600); //Used with Priority = 2; Pushover will resend the notification every 60 seconds for 3600 seconds. After that point, it stops sending notifications.
	$push->setTimestamp(time());
	$push->send();
}

function quicksort($array, $l = 0, $r = l) {
	if ($r === 0) {
		$r = count($array) - 1;
	}
	$i = $l;
	$j = $r;
	$x = $array[($l + $r) / 2]['time'];
	do {
		while ($array[$i]['time'] < $x) {
			$i++;
		}
		while ($array[$j]['time'] > $x) {
			$j--;
		}
		if ($i <= $j) {
			if ($array[$i]['time'] > $array[$j]['time']) {
				print_r($array[$i]);
				print_r($array[$j]);
				$tmp = array();
				$tmp = $array[$i];
				$array[$i] = $array[$j];
				$array[$j] = $tmp;
				print_r($array[$i]);
				print_r($array[$j]);
				// list($array[$i], $array[$j]) = array($array[$j], $array[$i]);
			}
			$i++;
			$j--;
		}
	} while ($i <= $j);
	if ($i < $r) {
		quicksort($array, $i, $r);
	}
	if ($j > $l) {
		quicksort($array, $l, $j);
	}
}

function arraycheck($array) {
	for ($i = 0; $i < count($array) - 1; $i++) {
		if ($array[$i]['time'] > $array[$i + 1]['time']) {
			die('Ошибка:');
		}
	}
	echo "OK!";
}
