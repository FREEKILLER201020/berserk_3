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
	if ((mb_stripos($mtext, "Да хочу!") !== false) && (LastUserMessage($cid, $user, 2) == "Да!") || (mb_stripos($mtext, "Да хочу!") !== false) && (LastUserMessage($cid, $user, 4) == "Да!") && (LastUserMessage($cid, $user, 3) == "Да хочу!")) {
		$answer = 'Пожалуйста, напишите свой игровой никнейм. Я попробую вас найти.';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	}
	if ((mb_stripos($mtext, "Нет, спасибо.") !== false) && (LastUserMessage($cid, $user, 2) == "Да!") || (mb_stripos($mtext, "Нет, спасибо.") !== false) && (LastUserMessage($cid, $user, 4) == "Да!") && (LastUserMessage($cid, $user, 3) == "Да хочу!")) {
		$answer = 'Хорошо. Вы всегда сможете настроить это позже выполнив команду "/start" или "/settings".';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	}
	if ((LastUserMessage($cid, $user, 3) == "Да!") && (LastUserMessage($cid, $user, 2) == "Да хочу!") || (LastUserMessage($cid, $user, 2) == "Да хочу!") && (LastUserMessage($cid, $user, 4) == "Да хочу!") && (LastUserMessage($cid, $user, 5) == "Да!")) {
		$query = "SELECT distinct on (id) id,nick,clan,frags,deaths,level from players where nick='$mtext' order by id,timemark desc";
		$result = pg_query($query);
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$clan_id = $line["clan"];
			$id = $line["id"];
		}
		$query = "SELECT distinct on (id) id,title from clans where id=$clan_id order by id,timemark desc";
		$result = pg_query($query);
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$clan_name = $line["title"];
		}
		$query = "UPDATE users set game_id=$id where id={$message->getFrom()->getId()};\n";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

		if (isset($id)) {
			$answer = 'Приятно познакомится, ' . $mtext . '! Ваш клан: ' . $clan_name . '.';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		} else {
			$answer = 'Мне не удалось вас найти... Хотите попробовать еще раз?';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
				[
					[
						["text" => "Да хочу!"],
						["text" => "Нет, спасибо."],
					],
				]
				, true, true);

			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		}

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
	if ((mb_stripos($mtext, "Да.") !== false) && (LastUserMessage($cid, $user, 2) == "/notifications")) {
		$answer = 'Хорошо. За какое время до начала боя (в минутах) мне стоит вас уведомлять?';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		// $bot->sendMessage($message->getChat()->getId(), "Отлично! Напишите, пожалуста, свой игровой ник, что бы получать больше персональной информации ;)");
	}
	if ((LastUserMessage($cid, $user, 3) == "/notifications") && (LastUserMessage($cid, $user, 2) == "Да.")) {
		$answer = intval($mtext);
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	}
	if ((mb_stripos($mtext, "Нет :(") !== false) && (LastUserMessage($cid, $user, 2) == "/start")) {
		$answer = 'Ничего страшного. При желании, присоединяйтесь к нам!';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);}

}, function ($message) use ($name) {
	return true; // когда тут true - команда проходит
});

// Reply-Кнопки
$bot->command("buttons", function ($message) use ($bot) {
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup([[["text" => "Власть советам!"], ["text" => "Сиськи!"]]], true, true);
	$keyboard->setOneTimeKeyboard(true);

	$bot->sendMessage($message->getChat()->getId(), "тест", false, null, null, $keyboard);
});

// команда для start
$bot->command('start', function ($message) use ($bot) {
	Start($message, $bot);
});
$bot->command('notifications', function ($message) use ($bot) {
	Notif1($message, $bot);
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

$bot->command('I', function ($message) use ($bot) {
	$query = "SELECT distinct on (id) id,game_id from users where id={$message->getFrom()->getId()} order by id desc";
	$result = pg_query($query);
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$game_id = $line["game_id"];
	}
	$query = "SELECT distinct on (id) id,nick,clan,frags,deaths,level from players where id=$game_id order by id,timemark desc";
	$result = pg_query($query);
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$frags = $line["frags"];
		$deaths = $line["deaths"];
		$level = $line["level"];
		$clan_id = $line["clan"];
		$nick = $line["nick"];
		// $id = $line["id"];
	}
	$query = "SELECT distinct on (id) id,title from clans where id=$clan_id order by id,timemark desc";
	$result = pg_query($query);
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$clan_name = $line["title"];
	}
	$answer = 'Вот что мне известно:
	Никнейм: ' . $nick . '
	Фраги: ' . $frags . '
	Смерти: ' . $deaths . '
	Уровень: ' . $level . '
	Клан: ' . $clan_name;
	$bot->sendMessage($message->getChat()->getId(), $answer);
});

$bot->run();
pg_close($dbconn);

function Notif1($message, $bot) {
	$answer = 'Вы хотите получать напоминания перед началом боев вашего клана?';
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
		[
			[
				["text" => "Да."],
				["text" => "Нет."],
			],
		]
		, true, true);

	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
}

function Notif2($message, $bot) {
	$answer = 'Вы хотите получать резельтаты боев вашего клана?';
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
		[
			[
				["text" => "Да."],
				["text" => "Нет."],
			],
		]
		, true, true);

	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
}

function Start($message, $bot) {
	$nick = $message->getFrom()->getUsername();
	$name = $message->getFrom()->getFirstName();
	$query = "INSERT INTO users (id, username,name) values ({$message->getFrom()->getId()},'$nick','$name');\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
		$query = "UPDATE users set username='$nick' and name='$name' where id={$message->getFrom()->getId()};\n";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	}
	if ($message->getChat()->getType != "private") {
		$answer = 'Простите, кажется это групповой чат. На данный момент я не могу гарантировать коректную работу в групповых чатах. Простите :(';
		$bot->sendMessage($message->getChat()->getId(), $answer);
	}
	$answer = 'Добро пожаловать ' . $name . '!' . $message->getChat()->getType();
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