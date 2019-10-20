<?php
header('Content-Type: text/html; charset=utf-8');
// подрубаем API
require_once "vendor/autoload.php";

// $file = file_put_contents(realpath(dirname(__FILE__)) . "/../cards_parser/cards/log.txt", var_dump($_REQUEST));

$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
// print_r($config);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$dbconn = pg_connect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

$token = "681634726:AAHafNwa8T3LXlezmIAUH-JjBGrI0qU-lfY";
$bot = new \TelegramBot\Api\Client($token);

$bot->on(function ($Update) use ($bot) {
	$message = $Update->getMessage();
	$mtext = $message->getText();
	$cid = $message->getChat()->getId();
	$user = $message->getFrom()->getId();
	$time = time();
	$query = "INSERT INTO messages_history (timemark, message, chat_id, user_id) values (current_timestamp,'$mtext',$cid,$user);\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

	if (mb_stripos($mtext, "власть советам") !== false) {
		$bot->sendMessage($message->getChat()->getId(), "Смерть богатым!");
	} else if (mb_stripos($mtext, "привет") !== false) {
		$bot->sendMessage($message->getChat()->getId(), "Пока");
	} else if (mb_stripos($mtext, "/start") !== false) {
		// $bot->sendMessage($message->getChat()->getId(), "Пока");
	} else {
		$bot->sendMessage($message->getChat()->getId(), var_export($Update, true));
	}

}, function ($message) use ($name) {
	return true; // когда тут true - команда проходит
});

$bot->on(function ($update) use ($bot) {

	$callback = $update->getCallbackQuery();
	$bot->sendMessage($message->getChat()->getId(), var_export($callback, true));
	$message = $callback->getMessage();
	$data = $callback->getData();
	$cid = $message->getChat()->getId();
	$user = $message->getFrom()->getId();
	$time = time();
	$query = "INSERT INTO messages_history (timemark, message, chat_id, user_id) values (current_timestamp,'$data',$cid,$user);\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

	if ($data == "yes_start") {
		$bot->sendMessage($message->getChat()->getId(), "yes_start");
	} else if ($data == "no_start") {
		$bot->sendMessage($message->getChat()->getId(), "no_start");
	}

}, function ($update) {
	$callback = $update->getCallbackQuery();
	if (is_null($callback) || !strlen($callback->getData())) {
		return false;
	}

	return true;
});

// команда для start
$bot->command('start', function ($message) use ($bot) {
	Start($message, $bot);
});
// команда для помощи
$bot->command('help', function ($message) use ($bot) {
	$answer = 'Команды:
/help - вывод справки';
	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['text' => 'link', 'url' => 'https://core.telegram.org'],
			],
		]
	);

	$bot->sendMessage($message->getChat()->getId(), $answer, null, false, null, $keyboard);
});
$bot->command('test', function ($message) use ($bot) {
	$answer = 'Ура! Я сам что то написал!' . var_export($message, true);
	$bot->sendMessage($message->getChat()->getId(), $answer);
});

$bot->run();
pg_close($dbconn);

function Start($message, $bot) {
	$nick = $message->getFrom()->getUsername();
	$name = $message->getFrom()->getFirstName();
	$query = "INSERT INTO users (id, username,name) values ({$message->getFrom()->getId()},'$nick','$name');\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
		$query = "UPDATE users set username='$nick' and name='$name' where id={$message->getFrom()->getId()};\n";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	}
	$answer = 'Добро пожаловать ' . $name . '!';
	$bot->sendMessage($message->getChat()->getId(), $answer);
	$answer = 'Вы состаите в каком-нибудь клане?';
	$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['callback_data' => 'yes_start', 'text' => 'Да'],
			],
			[
				['callback_data' => 'no_start', 'text' => 'Нет'],
			],
		]
	);

	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);}
?>