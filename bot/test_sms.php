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
		$array = array();
		$array['type'] = "history";
		$array['id'] = "52";
		$array['clan'] = "171";

// print_r($dbconn);
		// print_r(OnCall($array, null));
		$answer = OnCall($array, null);
		if (strlen($answer) > 4096) {
			$answer = "message is longer then 4096 characters";
		}
		echo PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . $answer;
		// $answer = 'Fight is comming';
		$bot->sendMessage($notification->chat_id, $answer);
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