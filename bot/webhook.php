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

	// if (mb_stripos($mtext, "Сиськи") !== false) {
	// 	$pic = "http://aftamat4ik.ru/wp-content/uploads/2017/05/14277366494961.jpg";

	// 	$bot->sendPhoto($message->getChat()->getId(), $pic);
	// }
	// start 1) вы состоите в каком-нибудь клане?
	// да
	if ((mb_stripos($mtext, "Да!") !== false) && (GetState($message, $bot) == "start")) {
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
	// Нет
	if ((mb_stripos($mtext, "Нет :(") !== false) && (GetState($message, $bot) == "start")) {
		SetState($message, $bot, "null");
		$answer = 'Ничего страшного. При желании, присоединяйтесь к нам!';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	}
	// start 2) Вы хотели бы получать персональные уведомления?
	// да
	if ((mb_stripos($mtext, "Да хочу!") !== false) && (GetState($message, $bot) == "start")) {
		$answer = 'Пожалуйста, напишите свой игровой никнейм. Я попробую вас найти.';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	}
	// нет
	if ((mb_stripos($mtext, "Нет, спасибо.") !== false) && (GetState($message, $bot) == "start")) {
		SetState($message, $bot, "null");
		$answer = 'Хорошо. Вы всегда сможете настроить это позже выполнив команду "/start" или "/settings".';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	}
	// start 3) Пожалуйста, напишите свой игровой никнейм. Я попробую вас найти.
	if ((GetState($message, $bot) == "start") && (LastUserMessage($cid, $user, 2) == "Да хочу!")) {
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
			SetState($message, $bot, "null");
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
	// notif1 1) Вы хотите получать напоминания перед началом боев вашего клана?
	// да
	if ((mb_stripos($mtext, "Да.") !== false) && (GetState($message, $bot) == "notifications1")) {
		$answer = 'Хорошо. За какое время до начала боя (в минутах) мне стоит вас уведомлять?';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		// $bot->sendMessage($message->getChat()->getId(), "Отлично! Напишите, пожалуста, свой игровой ник, что бы получать больше персональной информации ;)");
	}
	// нет
	if ((mb_stripos($mtext, "Нет.") !== false) && (GetState($message, $bot) == "notifications1")) {
		// $answer = "part2";
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		Notif2($message, $bot);
	}
	// notif1 2) За какое время до начала боя (в минутах) мне стоит вас уведомлять?
	if ((LastUserMessage($cid, $user, 2) == "Да.") && (GetState($message, $bot) == "notifications1")) {
		$time = intval($mtext);
		$nick = $message->getFrom()->getUsername();
		$name = $message->getFrom()->getFirstName();
		if ($time > 0) {
			$query = "SELECT * FROM bot_notification where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=1";
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				$is = 1;
			}
			if ($is == 1) {
				$query = "UPDATE bot_notification set pre_start_time=$time where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=1;\n";

			} else {
				$query = "INSERT INTO bot_notification (chat_id,user_id,notification_type,pre_start_time) values ({$message->getFrom()->getId()},{$message->getFrom()->getId()},1,{$time});\n";
			}
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			// $answer = $query;
			if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
				$query = "UPDATE users set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
				$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			}
			if (mb_stripos($answer, "Не удалось соединиться:") == false) {
				$answer = "Хорошо, запомнил.";
			}
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
			Notif2($message, $bot);
		} else {
			$answer = 'Простите, я вас не понял. Попробовать еще раз?';
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

	}
	// notif2 1) Вы хотите получать напоминания по результатам боев вашего клана?
	// да
	if ((mb_stripos($mtext, "Да.") !== false) && (GetState($message, $bot) == "notifications2")) {
		$nick = $message->getFrom()->getUsername();
		$name = $message->getFrom()->getFirstName();
		$is = 0;
		$query = "SELECT * FROM bot_notification where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=2";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$is = 1;
		}
		if ($is == 1) {
			$query = "UPDATE bot_notification set pre_start_time=0 where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=2;\n";
		} else {
			$query = "INSERT INTO bot_notification (chat_id,user_id,notification_type,pre_start_time) values ({$message->getFrom()->getId()},{$message->getFrom()->getId()},2,0);\n";
		}
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		// $answer = $query;
		if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
			$query = "UPDATE users set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		}
		if (mb_stripos($answer, "Не удалось соединиться:") == false) {
			$answer = "Хорошо, запомнил.";
		}
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		// Notif2($message, $bot);
	}
	// нет
	if ((mb_stripos($mtext, "Нет.") !== false) && (GetState($message, $bot) == "notifications2")) {
		// $answer = "part2";
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		// Notif2($message, $bot);
	}

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
	$query = "SELECT distinct on (id) id,game_id from users where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()} order by id desc";
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
	SetState($message, $bot, "notifications1");
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
	SetState($message, $bot, "notifications2");
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
	$query = "INSERT INTO users (id, username,name,chat_id) values ({$message->getFrom()->getId()},'$nick','$name',{$message->getChat()->getId()});\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
		$query = "UPDATE users set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	}
	SetState($message, $bot, "start");
	if ($message->getChat()->getType() != "private") {
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

function SetState($message, $bot, $state) {
	// $nick = $message->getFrom()->getUsername();
	// $name = $message->getFrom()->getFirstName();

	$query = "UPDATE users set chat_state='$state' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
}

function GetState($message, $bot) {
	$query = "SELECT distinct on (id) id,chat_state from users where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()} order by id desc";
	$result = pg_query($query);
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$state = $line["chat_state"];
	}
	return $state;
}
?>