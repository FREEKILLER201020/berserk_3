<?php
header('Content-Type: text/html; charset=utf-8');
// подрубаем API
require_once "vendor/autoload.php";
require "../api/api.php";

$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
// print_r($config);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$dbconn = pg_connect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

$token = "681634726:AAHafNwa8T3LXlezmIAUH-JjBGrI0qU-lfY";
$bot = new \TelegramBot\Api\Client($token);

$query = "SELECT * from bot_notification";
$result = pg_query($query);
$notifications = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new Notification($line["id"], $line["chat_id"], $line["user_id"], $line["notification_type"], $line["pre_start_time"]);
	array_push($notifications, $tmp);
}
foreach ($notifications as $notification) {
	if ($notification->type == 1) {
// 		$array = array();
		// 		$array['type'] = "history";
		// 		$array['id'] = "52";
		// 		$array['clan'] = "171";

// // print_r($dbconn);
		// 		// print_r(OnCall($array, null));
		// 		$answer = OnCall($array, null);
		// 		$json = json_decode($answer, true);
		// 		$answer = "<pre>" . PHP_EOL;
		// 		$js = $json[0];
		// 		$keys = array();
		// 		$strings = array();
		// 		foreach ($json as $key => $row) {
		// 			array_push($strings, $key);
		// 			foreach ($row as $key2 => $cell) {
		// 				if (($key2 == "Атакует") || ($key2 == "Защищается") || ($key2 == "Начало_боя") || ($key2 == "Победитель") || ($key2 == "Город1") || ($key2 == "Город2")) {
		// 					array_push($strings, $key2);
		// 					array_push($strings, $cell);
		// 				}
		// 			}
		// 		}
		// 		$strings = array_unique($strings);
		// 		// print_r($strings);
		// 		$max_len = 0;
		// 		foreach ($strings as $string) {
		// 			if (strlen($string) > $max_len) {
		// 				$max_len = strlen($string);
		// 			}
		// 		}
		// 		echo $max_len;
		// 		foreach ($js as $key => $value) {
		// 			array_push($keys, $key);
		// 		}
		// 		foreach ($keys as $key => $value) {
		// 			$keys[$key] = str_replace("Начало_боя", "Начало", $value);
		// 		}
		// 		foreach ($keys as $key) {
		// 			if (($key == "Атакует") || ($key == "Защищается") || ($key == "Начало") || ($key == "Победитель")) {
		// 				$answer .= " $key |";
		// 			}
		// 		}
		// 		$answer .= PHP_EOL . "</pre>" . PHP_EOL;
		// 		if (strlen($answer) > 4096) {
		// 			$answer = "message is longer then 4096 characters";
		// 		}
		$array = array();
		$array['type'] = "history";
		$array['id'] = "52";
		$array['clan'] = "171";

// print_r($dbconn);
		// print_r(OnCall($array, null));
		$answer = OnCall($array, null);
		$json = json_decode($answer, true);
		$answer = "<pre>" . PHP_EOL;
		$js = $json[0];
		$keys = array();
		foreach ($js as $key => $value) {
			array_push($keys, $key);
		}
		foreach ($keys as $key => $value) {
			$keys[$key] = str_replace("Начало_боя", "Начало", $value);
		}
		foreach ($keys as $key) {
			if (($key == "Атакует") || ($key == "Защищается") || ($key == "Начало") || ($key == "Победитель")) {
				$answer .= "$key|";
			}
		}
		$answer = substr($answer, 0, strlen($answer) - 2);
		$answer .= PHP_EOL . "</pre>" . PHP_EOL;
		echo $answer;
		if (strlen($answer) > 4096) {
			$answer = "message is longer then 4096 characters";
		}
		$bot->sendMessage($notification->chat_id, $answer, "html", null, null, null);
	}
}

// $answer = 'test!';
// $bot->sendMessage(249857309, $answer);
pg_close($dbconn);
$bot->run();

class Notification {

	public $id;
	public $chat_id;
	public $user_id;
	public $type;
	public $time;

	public function __construct($id, $chat_id, $user_id, $type, $time) {
		$this->id = $id;
		$this->chat_id = $chat_id;
		$this->user_id = $user_id;
		$this->type = $type;
		$this->time = $time;

	}
}
?>