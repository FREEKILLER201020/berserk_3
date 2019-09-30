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

	if (mb_stripos($mtext, "власть советам") !== false) {
		$bot->sendMessage($message->getChat()->getId(), "Смерть богатым!");
	} else if (mb_stripos($mtext, "привет") !== false) {
		$bot->sendMessage($message->getChat()->getId(), "Пока");
	} else {
		$bot->sendMessage($message->getChat()->getId(), "Кажется я вас не понял... " . var_export($message));
	}
}, function ($message) use ($name) {
	return true; // когда тут true - команда проходит
});

// команда для start
$bot->command('start', function ($message) use ($bot) {
	// $query = "INSERT INTO users (id) values ({$message->getChat()->getId()});\n";
	$answer = 'Добро пожаловать!';
	// $result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	$bot->sendMessage($message->getChat()->getId(), $answer);
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
?>