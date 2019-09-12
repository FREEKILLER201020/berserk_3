<?php
error_reporting(1);

header('Content-Type: text/html; charset=UTF-16 LE');
require "../classes/colors.php";
require "../functions/progress_bar.php";
include '../classes/pushover.php';
$colors = new Colors();

$update_server_file = 0;
$update_json_files = 0;
$update_images_files = 0;
$notification = 0;
for ($i = 0; $i < count($argv); $i++) {
	if ($argv[$i] == "-server") {
		$update_server_file = 1;
	}
	if ($argv[$i] == "-json") {
		$update_json_files = 1;
	}
	if ($argv[$i] == "-images") {
		$update_images_files = 1;
	}
	if ($argv[$i] == "-notification") {
		$notification = 1;
	}
}

if ($update_server_file == 1) {
	echo $colors->getColoredString("Скачивание актуального файла с характеристиками карт", "green", null) . "\n";
	$query = " wget -q -O cards_ru_RU.js https://berserktcg.ru/static/js/game/cards/cards_ru_RU.js";
	exec($query);
	$file = 'cards_ru_RU.js';
	echo $colors->getColoredString("Done!", "cyan", null) . "\n";
	echo $colors->getColoredString("Создание JSON файлов с характеристиками", "green", null) . "\n";
	$phantom_script = dirname(__FILE__) . '/get-website.js';
	$response = shell_exec('phantomjs ' . $phantom_script);

	// echo $response;

	function getStringBetween($string, $start, $end) {
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) {
			return '';
		}
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	$parsed = getStringBetween($response, '<text id="res">', '</text>');

	$array = json_decode($parsed, true);
	$all = array();
	$keys = array();
	foreach ($array as $key => $value) {
		if (is_array($array)) {
			foreach ($value as $key2 => $value2) {
				if (!isset($all[$key2])) {
					$all[$key2] = array();
				}
				if (!in_array($value2, $all[$key2])) {
					array_push($all[$key2], $value2);
					if (!isset($keys[$key2])) {
						$keys[$key2] = "";
					}
				}
			}
		}
	}
}

if ($update_json_files == 1) {
	file_put_contents("all_cards.json", json_encode($array, JSON_UNESCAPED_UNICODE));
	file_put_contents("keys.json", json_encode($keys, JSON_UNESCAPED_UNICODE));
	file_put_contents("keys_value.json", json_encode($all, JSON_UNESCAPED_UNICODE));
	echo $colors->getColoredString("Done!", "cyan", null) . "\n";
}

if ($update_images_files == 1) {
	echo $colors->getColoredString("Скачивание файлов карт", "green", null) . "\n";
	$t0 = microtime(true) * 10000;
	$iji = 1;
	foreach ($all['proto'] as $key => $value) {
		$t = (microtime(true) * 10000 - $t0) / $iji;
		progressBar($iji, count($all['proto']), $t, $t0, 0);
		$iji++;
		$query = " wget -q -O \"cards/small/$value.jpg\" https://bytexbv-a.akamaihd.net/static/images/cards/small/$value.jpg";
		exec($query);

		$query = " wget -q -O \"cards/info/$value.jpg\" https://bytexbv-a.akamaihd.net/static/images/cards/info/$value.jpg";
		exec($query);

		$query = " wget -q -O \"cards/big/$value.jpg\" https://bytexbv-a.akamaihd.net/static/images/cards/big/$value.jpg";
		exec($query);
	}
	echo PHP_EOL;
	echo $colors->getColoredString("Done!", "cyan", null) . "\n";
}

if ($notification == 1) {
	$push = new Pushover();

	$push->setToken('a5g19h6if4cdvvfrdw8n5najpm68rb');
	$push->setUser('uuaj196grt8gjg6femsnjgc8tte1k8');

	$push->setTitle('Cards parser');
	$push->setMessage('Update complited! ' . time());
	// $push->setUrl('http://chris.schalenborgh.be/blog/');
	// $push->setUrlTitle('cool php blog');
	$push->setDevice('pixel2xl');
	$push->setPriority(0);
	$push->setRetry(60); //Used with Priority = 2; Pushover will resend the notification every 60 seconds until the user accepts.
	$push->setExpire(3600); //Used with Priority = 2; Pushover will resend the notification every 60 seconds for 3600 seconds. After that point, it stops sending notifications.
	$push->setTimestamp(time());
	$push->send();
}
