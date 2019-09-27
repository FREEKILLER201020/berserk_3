<?php
header('Content-Type: text/html; charset=utf-8');
// подрубаем API
require_once "vendor/autoload.php";
$token = "681634726:AAHafNwa8T3LXlezmIAUH-JjBGrI0qU-lfY";
$bot = new \TelegramBot\Api\Client($token);
// команда для start
$bot->command('start', function ($message) use ($bot) {
	$answer = 'Добро пожаловать!';
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
	$answer = 'Ура! Я сам что то написал!';
	$bot->sendMessage($message->getChat()->getId(), $answer);
});
$bot->run();
?>