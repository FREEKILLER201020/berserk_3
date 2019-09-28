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

// $id = $bot->getChat()->getId();
// print_r($bot);

// Не понял([a-z0-9]*)
// $bot->command('([a-z0-9]*)', function ($message) use ($bot) {
// $answer = 'Простите, кажется я вас не понял. Введите "/help" что бь посмотреть что я умею.';
// $bot->sendMessage($bot->getChat()->getId(), $answer);
// });
$chatId = 0;
$bot->on(function ($update) use ($bot, $callback_loc, $find_command) {
	$callback = $update->getCallbackQuery();
	$message = $callback->getMessage();
	$chatId = $message->getChat()->getId();
	$data = $callback->getData();

	// if ($data == "data_test") {
	// 	$bot->answerCallbackQuery($callback->getId(), "This is Ansver!", true);
	// }
	// if ($data == "data_test2") {
	// 	$bot->sendMessage($chatId, "Это ответ!");
	// 	$bot->answerCallbackQuery($callback->getId()); // sending empty message to stop "timeTable" at the button
	// }

}, function ($update) {
	$callback = $update->getCallbackQuery();
	if (is_null($callback) || !strlen($callback->getData())) {
		return false;
	}

	return true;
});

// команда для start
$bot->command('start', function ($message) use ($bot) {
	$query = "INSERT INTO users (id) values ({$message->getChat()->getId()});\n";
	$answer = 'Добро пожаловать!';
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
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
	$answer = 'Ура! Я сам что то написал!' . $chatId;
	$bot->sendMessage($message->getChat()->getId(), $answer);
});

$bot->run();
pg_close($dbconn);
?>