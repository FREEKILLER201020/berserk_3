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
// $bot->callbackQuery(function ($message) use ($bot) {
// 	$bot->sendMessage($message->getChat()->getId(), "no_start");
// 	Start($message, $bot);
// });
// $bot->on(function ($Update) use ($bot) {
// 	$message = $Update->getMessage();
// 	$mtext = $message->getText();
// 	$cid = $message->getChat()->getId();
// 	$user = $message->getFrom()->getId();
// 	$time = time();
// 	$query = "INSERT INTO messages_history (timemark, message, chat_id, user_id) values (current_timestamp,'$mtext',$cid,$user);\n";
// 	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

// 	if (mb_stripos($mtext, "власть советам") !== false) {
// 		$bot->sendMessage($message->getChat()->getId(), "Смерть богатым!");
// 	} else if (mb_stripos($mtext, "привет") !== false) {
// 		$bot->sendMessage($message->getChat()->getId(), "Пока");
// 	} else if (mb_stripos($mtext, "/start") !== false) {
// 		// $bot->sendMessage($message->getChat()->getId(), "Пока");
// 	} else {
// 		$bot->sendMessage($message->getChat()->getId(), var_export($Update, true));
// 	}

// }, function ($message) use ($name) {
// 	return true; // когда тут true - команда проходит
// });

// $bot->on(function ($update) use ($bot) {

// 	$callback = $update->getCallbackQuery();
// 	$bot->sendMessage($message->getChat()->getId(), var_export($callback, true));
// 	$message = $callback->getMessage();
// 	$data = $callback->getData();
// 	$cid = $message->getChat()->getId();
// 	$user = $message->getFrom()->getId();
// 	$time = time();
// 	$query = "INSERT INTO messages_history (timemark, message, chat_id, user_id) values (current_timestamp,'$data',$cid,$user);\n";
// 	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

// 	if ($data == "yes_start") {
// 		$bot->sendMessage($message->getChat()->getId(), "yes_start");
// 	} else if ($data == "no_start") {
// 		$bot->sendMessage($message->getChat()->getId(), "no_start");
// 	}

// }, function ($update) {
// 	$callback = $update->getCallbackQuery();
// 	if (is_null($callback) || !strlen($callback->getData())) {
// 		return false;
// 	}

// 	return true;
// });

// Обработка кнопок у сообщений
// $bot->on(function ($update) use ($bot, $callback_loc, $find_command) {
// 	$callback = $update->getCallbackQuery();
// 	$message = $callback->getMessage();
// 	$chatId = $message->getChat()->getId();
// 	$data = $callback->getData();

// 	$mtext = $data;
// 	$cid = $message->getChat()->getId();
// 	$user = $message->getFrom()->getId();
// 	$query = "INSERT INTO messages_history (timemark, message, chat_id, user_id) values (current_timestamp,'$mtext',$cid,$user);\n";
// 	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

// 	if ($data == "yes_start") {
// 		$bot->sendMessage($message->getChat()->getId(), "Отлично! Напишите пожалуста свой игровой ник, что бы получать больше персональной информации ;)");
// 		$bot->answerCallbackQuery($callback->getId(), "yes", false);
// 	}
// 	if ($data == "no_start") {
// 		$bot->sendMessage($chatId, "Обидно...");
// 		$bot->answerCallbackQuery($callback->getId(), "no", false); // можно отослать пустое, чтобы просто убрать "часики" на кнопке
// 	}

// }, function ($update) {
// 	$callback = $update->getCallbackQuery();
// 	if (is_null($callback) || !strlen($callback->getData())) {
// 		return false;
// 	}

// 	return true;
// });

$bot->on(function ($Update) use ($bot) {

	$message = $Update->getMessage();

	$mtext = $message->getText();
	$cid = $message->getChat()->getId();
	$user = $message->getFrom()->getId();
	$query = "INSERT INTO messages_history (timemark, message, chat_id, user_id) values (current_timestamp,'$mtext',$cid,$user);\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

	// $bot->sendMessage($message->getChat()->getId(), LastUserMessage($cid, $user));

	if (mb_stripos($mtext, "Сиськи") !== false) {
		$pic = "http://aftamat4ik.ru/wp-content/uploads/2017/05/14277366494961.jpg";

		$bot->sendPhoto($message->getChat()->getId(), $pic);
	}
	if ((mb_stripos($mtext, "Да хочу!") !== false) && (LastUserMessage($cid, $user, 2) == "Да!")) {
		$answer = 'Пожалуйста, напишите свой игровой никнейм. Я попробую вас найти.';
		$bot->sendMessage($message->getChat()->getId(), $answer);
	}
	if ((LastUserMessage($cid, $user, 3) == "Да!") && (LastUserMessage($cid, $user, 2) == "Да хочу!")) {
		$answer = "SELECT distinct on (id) id,nick,clan_id,frags,deaths,level from players where nick'$mtext' order by id,timemark desc";
		$bot->sendMessage($message->getChat()->getId(), $answer);
	}
	if ((mb_stripos($mtext, "Да!") !== false) && (LastUserMessage($cid, $user, 2) == "/start")) {
		$answer = 'Отлично! Вы хотели бы получать персональные уведомления?';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
			[
				[
					["text" => "Да хочу!"],
					["text" => "Нет, спасибо."],
				],
			]
			, true, true);

		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		// $bot->sendMessage($message->getChat()->getId(), "Отлично! Напишите, пожалуста, свой игровой ник, что бы получать больше персональной информации ;)");
	}
	if ((mb_stripos($mtext, "Нет :(") !== false) && (LastUserMessage($cid, $user, 2) == "/start")) {
		$answer = 'Ничего страшного. При желании, присоединяйтесь к нам!';
		$bot->sendMessage($message->getChat()->getId(), $answer);
	}

}, function ($message) use ($name) {
	return true; // когда тут true - команда проходит
});

// Reply-Кнопки
$bot->command("buttons", function ($message) use ($bot) {
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup([[["text" => "Власть советам!"], ["text" => "Сиськи!"]]], true, true);

	$bot->sendMessage($message->getChat()->getId(), "тест", false, null, null, $keyboard);
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
	$answer = 'Вы состоите в каком-нибудь клане?';
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
		[
			[
				["text" => "Да!"],
				["text" => "Нет :("],
			],
		]
		, true, true);

	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
}

function LastUserMessage($chat_id, $user_id, $back) {
	$query = "select id,message from messages_history where user_id=$user_id and chat_id=$chat_id order by id,timemark desc limit $back";
	$result = pg_query($query);
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$res = $line["message"];
	}
	return $res;
}
?>