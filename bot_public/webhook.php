<?php
header('Content-Type: text/html; charset=utf-8');
// подрубаем API
require_once "vendor/autoload.php";
require "../api/api.php";
// require "../classes/fight.php";

// $file = file_put_contents(realpath(dirname(__FILE__)) . "/../cards_parser/cards/log.txt", var_dump($_REQUEST));

$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
// print_r($config);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
// $dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
$dbconn = pg_connect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$token = $config['token'];
// $token = "681634726:AAHafNwa8T3LXlezmIAUH-JjBGrI0qU-lfY";
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
	$query = "INSERT INTO messages_history_public (timemark, message, chat_id, user_id) values (current_timestamp,'$mtext',$cid,$user);\n";
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
		SetState($message, $bot, "");
	}
	// start 2) Вы хотели бы получать персональные уведомления?
	// да
	if ((mb_stripos($mtext, "Да хочу!") !== false) && (GetState($message, $bot) == "start")) {
		$answer = 'Пожалуйста, напишите свой игровой никнейм (с учетом регистра). Я попробую вас найти.';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	}
	// нет
	if ((mb_stripos($mtext, "Нет, спасибо.") !== false) && (GetState($message, $bot) == "start")) {
		SetState($message, $bot, "null");
		$answer = 'Хорошо. Вы всегда сможете настроить это позже выполнив команду "/start" или "/notifications".';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		SetState($message, $bot, "");
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
		$query = "UPDATE users_public set game_id=$id where id={$message->getFrom()->getId()};\n";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();

		if (isset($id)) {
			SetState($message, $bot, "null");
			$answer = 'Приятно познакомится, ' . $mtext . '! Ваш клан: ' . $clan_name . '.';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
			SetState($message, $bot, "");
			Notif1($message, $bot);
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
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
			[
				[
					["text" => "60"],
					["text" => "45"],
					["text" => "30"],
					["text" => "15"],
				],
			]
			, true, true);

		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		// $bot->sendMessage($message->getChat()->getId(), "Отлично! Напишите, пожалуста, свой игровой ник, что бы получать больше персональной информации ;)");
	}
	// нет
	if ((mb_stripos($mtext, "Нет.") !== false) && (GetState($message, $bot) == "notifications1")) {
		$query = "DELETE FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()},and notification_type=1;\n";

		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		$answer = "Ок";
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		SetState($message, $bot, "");
		Notif2($message, $bot);
	}
	// notif1 2) За какое время до начала боя (в минутах) мне стоит вас уведомлять?
	if ((LastUserMessage($cid, $user, 2) == "Да.") && (GetState($message, $bot) == "notifications1")) {
		$time = intval($mtext);
		$nick = $message->getFrom()->getUsername();
		$name = $message->getFrom()->getFirstName();
		if ($time > 0) {
			$query = "SELECT * FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=1";
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				$is = 1;
			}
			if ($is == 1) {
				$query = "UPDATE bot_notification_public set pre_start_time=$time where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=1;\n";

			} else {
				$query = "INSERT INTO bot_notification_public (chat_id,user_id,notification_type,pre_start_time) values ({$message->getFrom()->getId()},{$message->getFrom()->getId()},1,{$time});\n";
			}
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			// $answer = $query;
			if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
				$query = "UPDATE users_public set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
				$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			}
			if (mb_stripos($answer, "Не удалось соединиться:") == false) {
				$answer = "Хорошо, запомнил.";
			}
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
			SetState($message, $bot, "");
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
	if ((mb_stripos($mtext, "Да, давайте.") !== false) && (GetState($message, $bot) == "notifications2")) {
		$nick = $message->getFrom()->getUsername();
		$name = $message->getFrom()->getFirstName();
		$is = 0;
		$query = "SELECT * FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=2";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$is = 1;
		}
		if ($is == 1) {
			$query = "UPDATE bot_notification_public set pre_start_time=0 where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=2;\n";
		} else {
			$query = "INSERT INTO bot_notification_public (chat_id,user_id,notification_type,pre_start_time) values ({$message->getFrom()->getId()},{$message->getFrom()->getId()},2,0);\n";
		}
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		// $answer = $query;
		if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
			$query = "UPDATE users_public set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		}
		if (mb_stripos($answer, "Не удалось соединиться:") == false) {
			$answer = "Хорошо, запомнил.";
		}
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		SetState($message, $bot, "");
		Notif3($message, $bot);
	}
	// нет
	if ((mb_stripos($mtext, "Нет, не надо.") !== false) && (GetState($message, $bot) == "notifications2")) {
		$query = "DELETE FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()},and notification_type=2;\n";

		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		$answer = "Ок";
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		SetState($message, $bot, "");
		Notif3($message, $bot);
	}

	// notif3 1) Вы хотите получать информацию об отмене боев вашего клана?
	// да
	if ((mb_stripos($mtext, "Да, хочу.") !== false) && (GetState($message, $bot) == "notifications3")) {
		$nick = $message->getFrom()->getUsername();
		$name = $message->getFrom()->getFirstName();
		$is = 0;
		$query = "SELECT * FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=3";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$is = 1;
		}
		if ($is == 1) {
			$query = "UPDATE bot_notification_public set pre_start_time=0 where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=3;\n";
		} else {
			$query = "INSERT INTO bot_notification_public (chat_id,user_id,notification_type,pre_start_time) values ({$message->getFrom()->getId()},{$message->getFrom()->getId()},3,0);\n";
		}
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		// $answer = $query;
		if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
			$query = "UPDATE users_public set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		}
		if (mb_stripos($answer, "Не удалось соединиться:") == false) {
			$answer = "Хорошо, запомнил.";
		}
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		SetState($message, $bot, "");
		Notif4($message, $bot);
	}
	// нет
	if ((mb_stripos($mtext, "Нет, спасибо.") !== false) && (GetState($message, $bot) == "notifications3")) {
		$query = "DELETE FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()},and notification_type=3;\n";

		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		$answer = "Ок";
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		SetState($message, $bot, "");
		Notif4($message, $bot);
	}

	// notif4 1) Вы хотите получать список на день?
	// да
	if ((mb_stripos($mtext, "Да, хочу получать.") !== false) && (GetState($message, $bot) == "notifications4")) {
		$answer = 'Хорошо. В каком часу по Москве вам их присылать 0-23? (час в формате двух цифр. Если в часе одна цифра, то впереди должен быть 0.)';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
			[
				[
					["text" => "07"],
					["text" => "08"],
					["text" => "09"],
					["text" => "10"],
					["text" => "11"],
				],
			]
			, true, true);

		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		// $bot->sendMessage($message->getChat()->getId(), "Отлично! Напишите, пожалуста, свой игровой ник, что бы получать больше персональной информации ;)");
	}
	// нет
	if ((mb_stripos($mtext, "Нет, не хочу.") !== false) && (GetState($message, $bot) == "notifications4")) {
		$query = "DELETE FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()},and notification_type=4;\n";

		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
		$answer = "Ок";
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		SetState($message, $bot, "");
		// Notif2($message, $bot);
	}
	// notif4 2) в ктором часу вам присылать список на день?
	if ((LastUserMessage($cid, $user, 2) == "Да, хочу получать.") && (GetState($message, $bot) == "notifications4")) {
		$len = strlen($mtext);
		$time = -1;
		$time = intval($mtext);
		$nick = $message->getFrom()->getUsername();
		$name = $message->getFrom()->getFirstName();
		if (($len >= 2) && ($time >= 0) && ($time < 24)) {
			$query = "SELECT * FROM bot_notification_public where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=4";
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				$is = 1;
			}
			if ($is == 1) {
				$query = "UPDATE bot_notification_public set pre_start_time=$time where chat_id={$message->getFrom()->getId()} and user_id={$message->getFrom()->getId()} and notification_type=4;\n";

			} else {
				$query = "INSERT INTO bot_notification_public (chat_id,user_id,notification_type,pre_start_time) values ({$message->getFrom()->getId()},{$message->getFrom()->getId()},4,{$time});\n";
			}
			$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			// $answer = $query;
			if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
				$query = "UPDATE users_public set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
				$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
			}
			if (mb_stripos($answer, "Не удалось соединиться:") == false) {
				$answer = "Хорошо, запомнил.";
			}
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
			if (mb_stripos($answer, "Не удалось соединиться:") == false) {
				$answer = "Настройка завершена! Приятного использования :)";
			}
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
			SetState($message, $bot, "");
			// Notif2($message, $bot);
		} else {
			$answer = 'Простите, я вас не понял. Попробовать еще раз?';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
				[
					[
						["text" => "Да, хочу получать."],
						["text" => "Нет, не хочу."],
					],
				]
				, true, true);

			$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
		}

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
/help - Вывод справки
/start - Первичное знакомство с Ботом
/notifications - Настройка уведомлений (напоминания о боях, результаты боев, отмена боев, расписание на день)
/timetable - Посмотреть расписание на 24 часа
/history - История битв за 24 часа
/info - Посмотреть, что о вас знает Бот (персональная статистика)';
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
});

$bot->command('users', function ($message) use ($bot) {
	$user_id = $message->getFrom()->getId();
	if (($user_id == 249857309) || ($user_id == 221998912)) {

		$query = "SELECT * from users_public";
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		// $notifications = array();
		$users = array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			// foreach ($notifications as $notification) {
			// 	if ($notification->user_id == $line["id"]) {
			// 		$notification->in_game_id = $line["game_id"];
			// 	}
			// }
			$tmp = array();
			$tmp[nick] = $line[username];
			$tmp[name] = $line[name];
			$tmp[game_id] = $line[game_id];
			$tmp[game_nick] = "";
			$tmp[clan_id] = "";
			$tmp[clan] = "";
			array_push($users, $tmp);
		}
		$query = "select distinct on (id) timemark,id,nick,frags,deaths,level,clan,folder from players order by id, timemark desc;\n";
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		// $notifications = array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			// foreach ($users as $user) {
			for ($i = 0; $i < count($users); $i++) {
				if ($users[$i][game_id] == $line[id]) {
					$users[$i][clan_id] = $line[clan];
					$users[$i][game_nick] = $line[nick];
				}
			}

		}
		$query = "SELECT distinct on (id) timemark,id,title, points, created, gone from clans order by id,timemark desc;\n";
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		// $notifications = array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			for ($i = 0; $i < count($users); $i++) {
				if ($users[$i][clan_id] == $line[id]) {
					$users[$i][clan] = $line[title];
				}
			}

		}
		$answer = "Список пользователей:" . PHP_EOL;
		for ($i = 0; $i < count($users); $i++) {
			$t = $i + 1;
			$answer .= $t . ") Telegram nick: " . $users[$i][nick] . PHP_EOL . " Name: " . $users[$i][name] . PHP_EOL . " Game nick: " . $users[$i][game_nick] . PHP_EOL . " Clan: " . $users[$i][clan] . PHP_EOL . PHP_EOL;
		}
		$bot->sendMessage($message->getChat()->getId(), $answer, null, null, null, null);
	}
	// $answer = "Список пользователей:" . PHP_EOL;
	// for ($i = 0; $i < count($users); $i++) {
	// 	$t = $i + 1;
	// 	$answer .= $t . ") Telegram nick: " . $users[$i][nick] . " Name: " . $users[$i][name] . " Game nick: " . $users[$i][game_nick] . " Clan: " . $users[$i][clan].PHP_EOL;
	// }
	// $bot->sendMessage($message->getChat()->getId(), $answer, null, null, null, null);

});

$bot->command('timetable', function ($message) use ($bot) {
	$user_id = $message->getFrom()->getId();
	$query = "SELECT * from users_public";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		// foreach ($notifications as $notification) {
		// 	if ($notification->user_id == $line["id"]) {
		// 		$notification->in_game_id = $line["game_id"];
		// 	}
		// }
		if ($user_id == $line[id]) {
			$game_id = $line[game_id];
		}
	}
	$query = "select distinct on (id) timemark,id,nick,frags,deaths,level,clan,folder from players order by id, timemark desc;\n";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		if ($game_id == $line[id]) {
			$clan_id = $line[clan];
		}
	}
	// $bot->sendMessage($message->getChat()->getId(), $game_id, null, null, null, null);
	if (($game_id == "") || ($clan_id == "")) {
		$answer = "Простите, кажется я вас еще плохо знаю. Пожалуйста, запустите сперва команду /start" . PHP_EOL . "Проверить, что я о вас знаю можно командой /info";
		// }
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	} else {

		// $bot->sendMessage($message->getChat()->getId(), $user_id, null, null, null, null);
		$query = "select * from attacks order by resolved desc;\n";

		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		$fights = array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			print_r($line);
			$query2 = "SELECT distinct on (id) timemark,id,title, points, created, gone from clans where timemark<='$line[resolved]' order by id,timemark desc;\n";
			$line[winer_id] = $line[winer];
			$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
			while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
				if ($line[attacker] == $line2[id]) {
					$attacker = $line2[title];
				}
				if ($line[defender] == $line2[id]) {
					$defender = $line2[title];
				}
				if ($line[winer] == $line2[id]) {
					$line[winer] = $line2[title];
				}
			}

			$query2 = "SELECT distinct on (id) timemark, id, name, clan from cities where timemark<='$line[resolved]' order by id,timemark desc;\n";
			$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
			while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
				if ($line[from] == $line2[id]) {
					$line[from] = $line2[name];
				}
				if ($line[to] == $line2[id]) {
					$line[to] = $line2[name];
				}
			}
			if ($line[from] == -1) {
				$line[from] = "Варвары";
			}
			if ($line[to] == -1) {
				$line[to] = "Варвары";
			}
			// $attacker = $line[attacker];
			// $defender = $line[defender];
			$from = $line[from];
			$to = $line[to];
			$tmp = array();
			// 	public function __construct($a, $d, $f, $t, $de, $r, $w, $end, $id1, $id2, $id3) {
			// 		$this->attacker = $a;
			// 		$this->defender = $d;
			// 		$this->from = $f;
			// 		$this->to = $t;
			// 		$this->declared = $de;
			// 		$this->resolved = $r;
			// 		$this->winer = $w;
			// 		$this->ended = $end;
			// 		$this->attacker_id = $id1;
			// 		$this->defender_id = $id2;
			// 		$this->winer_id = $id3;
			// 	}
			$tmp[attacker] = $attacker;
			$tmp[defender] = $defender;
			$tmp[from] = $from;
			$tmp[to] = $to;
			$tmp[declared] = $line[declared];
			$tmp[resolved] = $line[resolved];
			$tmp[winer] = $line[winer];
			$tmp[ended] = $line[ended];
			$tmp[attacker_id] = $line[attacker];
			$tmp[defender_id] = $line[defender];
			$tmp[winer_id] = $line[winer_id];
			// $tmp = new FightClassNot($, $, $, $, , , , , , , );
			array_push($fights, $tmp);
		}
		// $bot->sendMessage($message->getChat()->getId(), $clan_id, null, null, null, null);

		$good_fights = array();
		$d = date('Y-m-d H:i:s');
		$timestamp1 = strtotime($d);
		foreach ($fights as $fight) {
			// echo "here1" . PHP_EOL;
			if (($clan_id == $fight[attacker_id]) || ($clan_id == $fight[defender_id])) {
				// echo "here2" . PHP_EOL;
				if ($fight[ended] == "") {
					// echo "here3" . PHP_EOL;
					$timestamp3 = strtotime($fight[resolved]);
					$dt = 60 * 60 * 24;
					$d = round(($timestamp3 - $timestamp1) / 60);
					$d2 = round(($timestamp3 - $timestamp1 + $dt) / 60);
					echo PHP_EOL . "NOTIFICATION 4_1" . PHP_EOL . $d . PHP_EOL;
					if (($d >= 0) && ($d < $d2)) {
						array_push($good_fights, $fight);
					}
				}
			}
		}
		// print_r($good_fights);
		if (count($good_fights) > 0) {
			$answer = "Расписание на 24 часа:" . PHP_EOL;
			// echo $answer;
			// $bot->sendMessage($notification->chat_id, $answer, null, null, null, null);
			// $answer = "";
			$t = 1;
			for ($i = count($good_fights) - 1; $i >= 0; $i--) {
				$timestamp4 = strtotime($good_fights[$i][resolved]) + 3 * 60 * 60;
				// $timestamp4 = strtotime($good_fights[$i]->resolved);
				$dt2 = date('d-M H:i', $timestamp4);
				if ($clan_id == $good_fights[$i][attacker_id]) {
					$answer .= $t . ") " . $dt2 . " МСК Против " . $good_fights[$i][defender] . " за " . $good_fights[$i][to] . " (атакуем)" . PHP_EOL;
				} else {
					$answer .= $t . ") " . $dt2 . " МСК Против " . $good_fights[$i][attacker] . " за " . $good_fights[$i][to] . " (защищаемся)" . PHP_EOL;
				}
				$t++;
			}
			// print_r($good_fights);
			// $answer = "Расписание!";
			// echo $answer;
			$bot->sendMessage($message->getChat()->getId(), $answer, null, null, null, null);
		}
	}
});

$bot->command('history', function ($message) use ($bot) {
	$user_id = $message->getFrom()->getId();
	$query = "SELECT * from users_public";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		// foreach ($notifications as $notification) {
		// 	if ($notification->user_id == $line["id"]) {
		// 		$notification->in_game_id = $line["game_id"];
		// 	}
		// }
		if ($user_id == $line[id]) {
			$game_id = $line[game_id];
		}
	}
	// $bot->sendMessage($message->getChat()->getId(), $game_id, null, null, null, null);

	$query = "select distinct on (id) timemark,id,nick,frags,deaths,level,clan,folder from players order by id, timemark desc;\n";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		if ($game_id == $line[id]) {
			$clan_id = $line[clan];
		}
	}
	// $bot->sendMessage($message->getChat()->getId(), $user_id, null, null, null, null);
	if (($game_id == "") || ($clan_id == "")) {
		$answer = "Простите, кажется я вас еще плохо знаю. Пожалуйста, запустите сперва команду /start" . PHP_EOL . "Проверить, что я о вас знаю можно командой /info";
		// }
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	} else {
		$query = "select * from attacks order by resolved desc;\n";

		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		$fights = array();
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			print_r($line);
			$query2 = "SELECT distinct on (id) timemark,id,title, points, created, gone from clans where timemark<='$line[resolved]' order by id,timemark desc;\n";
			$line[winer_id] = $line[winer];
			$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
			while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
				if ($line[attacker] == $line2[id]) {
					$attacker = $line2[title];
				}
				if ($line[defender] == $line2[id]) {
					$defender = $line2[title];
				}
				if ($line[winer] == $line2[id]) {
					$line[winer] = $line2[title];
				}
			}

			$query2 = "SELECT distinct on (id) timemark, id, name, clan from cities where timemark<='$line[resolved]' order by id,timemark desc;\n";
			$result2 = pg_query($query2) or die('Ошибка запроса: ' . pg_last_error());
			while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
				if ($line[from] == $line2[id]) {
					$line[from] = $line2[name];
				}
				if ($line[to] == $line2[id]) {
					$line[to] = $line2[name];
				}
			}
			if ($line[from] == -1) {
				$line[from] = "Варвары";
			}
			if ($line[to] == -1) {
				$line[to] = "Варвары";
			}
			// $attacker = $line[attacker];
			// $defender = $line[defender];
			$from = $line[from];
			$to = $line[to];
			$tmp = array();
			// 	public function __construct($a, $d, $f, $t, $de, $r, $w, $end, $id1, $id2, $id3) {
			// 		$this->attacker = $a;
			// 		$this->defender = $d;
			// 		$this->from = $f;
			// 		$this->to = $t;
			// 		$this->declared = $de;
			// 		$this->resolved = $r;
			// 		$this->winer = $w;
			// 		$this->ended = $end;
			// 		$this->attacker_id = $id1;
			// 		$this->defender_id = $id2;
			// 		$this->winer_id = $id3;
			// 	}
			$tmp[attacker] = $attacker;
			$tmp[defender] = $defender;
			$tmp[from] = $from;
			$tmp[to] = $to;
			$tmp[declared] = $line[declared];
			$tmp[resolved] = $line[resolved];
			$tmp[winer] = $line[winer];
			$tmp[ended] = $line[ended];
			$tmp[attacker_id] = $line[attacker];
			$tmp[defender_id] = $line[defender];
			$tmp[winer_id] = $line[winer_id];
			// $tmp = new FightClassNot($, $, $, $, , , , , , , );
			array_push($fights, $tmp);
		}

		// $bot->sendMessage($message->getChat()->getId(), $clan_id, null, null, null, null);

		$good_fights = array();
		$d = date('Y-m-d H:i:s');
		$timestamp1 = strtotime($d);
		foreach ($fights as $fight) {
			// echo "here1" . PHP_EOL;
			if (($clan_id == $fight[attacker_id]) || ($clan_id == $fight[defender_id])) {
				// echo "here2" . PHP_EOL;
				if ($fight[ended] != "") {
					// echo "here3" . PHP_EOL;
					$timestamp3 = strtotime($fight[resolved]);
					$timestamp4 = strtotime($fight[ended]);
					if ($timestamp4 >= $timestamp3) {
						$dt = 60 * 60 * 24;
						$d = round(($timestamp1 - $timestamp3) / 60);
						$d2 = round(($timestamp1 - $timestamp3 - $dt) / 60);
						echo PHP_EOL . "NOTIFICATION 4_1" . PHP_EOL . $d . PHP_EOL;
						if (($d <= 0) && ($d > $d2)) {
							array_push($good_fights, $fight);
						}
					}
				}
			}
		}
		// print_r($good_fights);
		if (count($good_fights) > 0) {
			$answer = "История за 24 часа:" . PHP_EOL;
			// echo $answer;
			// $bot->sendMessage($notification->chat_id, $answer, null, null, null, null);
			// $answer = "";
			$t = 1;
			for ($i = count($good_fights) - 1; $i >= 0; $i--) {
				$timestamp4 = strtotime($good_fights[$i][resolved]) + 3 * 60 * 60;
				// $timestamp4 = strtotime($good_fights[$i]->resolved);
				$dt2 = date('d-M H:i', $timestamp4);
				if ($clan_id == $good_fights[$i][winer_id]) {
					if ($clan_id == $good_fights[$i][attacker_id]) {
						$answer .= $t . ") " . $dt2 . ' МСК Мы отбили ' . $fight[to] . ' у ' . $fight[defender] . PHP_EOL;
					} else if ($clan_id == $good_fights[$i][defender_id]) {
						$answer .= $t . ") " . $dt2 . ' МСК Мы защитили ' . $fight[to] . ' от ' . $fight[attacker] . PHP_EOL;
					}
				} else if ($clan_id != $good_fights[$i][winer_id]) {
					if ($clan_id == $good_fights[$i][defender_id]) {
						$answer .= $t . ") " . $dt2 . ' МСК Мы отдали ' . $fight[to] . ' клану ' . $fight[attacker] . PHP_EOL;
					} else if ($clan_id == $good_fights[$i][attacker_id]) {
						$answer .= $t . ") " . $dt2 . ' МСК Мы не смогли отбить ' . $fight[to] . ' у ' . $fight[defender] . PHP_EOL;
					}
				}
				// if ($clan_id == $good_fights[$i][attacker_id]) {
				// 	$answer .= $t . ") " . $dt2 . " Против " . $good_fights[$i][defender] . " за " . $good_fights[$i][to] . " (атакуем)" . PHP_EOL;
				// } else {
				// 	$answer .= $t . ") " . $dt2 . " Против " . $good_fights[$i][attacker] . " за " . $good_fights[$i][to] . " (защищаемся)" . PHP_EOL;
				// }
				$t++;
			}
			// print_r($good_fights);
			// $answer = "Расписание!";
			// echo $answer;
			$bot->sendMessage($message->getChat()->getId(), $answer, null, null, null, null);
		}
	}
});

// $bot->command('db', function ($message) use ($bot) {
// 	$array = array();
// 	$array['type'] = "history";
// 	$array['id'] = "52";
// 	$array['clan'] = "171";

// // print_r($dbconn);
// 	// print_r(OnCall($array, null));
// 	$answer = OnCall($array, null);
// 	$json = json_decode($answer, true);
// 	$answer = "<pre>" . PHP_EOL;
// 	$js = $json[0];
// 	$keys = array();
// 	foreach ($js as $key => $value) {
// 		array_push($keys, $key);
// 	}
// 	foreach ($keys as $key => $value) {
// 		$keys[$key] = str_replace("Начало_боя", "Начало", $value);
// 	}
// 	foreach ($keys as $key) {
// 		if (($key == "Атакует") || ($key == "Защищается") || ($key == "Начало") || ($key == "Победитель")) {
// 			$answer .= "$key|";
// 		}
// 	}
// 	$answer = substr($answer, 0, strlen($answer) - 2);
// 	$answer .= PHP_EOL . "</pre>" . PHP_EOL;
// 	if (strlen($answer) > 4096) {
// 		$answer = "message is longer then 4096 characters";
// 	}
// 	$bot->sendMessage($message->getChat()->getId(), $answer, "html", null, null, null);
// });

$bot->command('info', function ($message) use ($bot) {
	$query = "SELECT distinct on (id) id,game_id from users_public where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()} order by id desc";
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
	$user_id = $message->getFrom()->getId();
	$query = "SELECT * from users_public";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		// foreach ($notifications as $notification) {
		// 	if ($notification->user_id == $line["id"]) {
		// 		$notification->in_game_id = $line["game_id"];
		// 	}
		// }
		if ($user_id == $line[id]) {
			$game_id = $line[game_id];
		}
	}
	// $bot->sendMessage($message->getChat()->getId(), $game_id, null, null, null, null);

	$query = "select distinct on (id) timemark,id,nick,frags,deaths,level,clan,folder from players order by id, timemark desc;\n";
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		if ($game_id == $line[id]) {
			$clan_id = $line[clan];
		}
	}
	if (($game_id == "") || ($clan_id == "")) {
		$answer = "Простите, кажется я вас еще плохо знаю. Пожалуйста, запустите сперва команду /start" . PHP_EOL . "Проверить, что я о вас знаю можно командой /info";
		// }
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardHide();
		$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
	} else {
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
}

function Notif2($message, $bot) {
	SetState($message, $bot, "notifications2");
	$answer = 'Вы хотите получать результаты боев вашего клана?';
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
		[
			[
				["text" => "Да, давайте."],
				["text" => "Нет, не надо."],
			],
		]
		, true, true);

	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
}

function Notif3($message, $bot) {
	SetState($message, $bot, "notifications3");
	$answer = 'Вы хотите получать уведомления об отмене боев вашего клана?';
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
		[
			[
				["text" => "Да, хочу."],
				["text" => "Нет, спасибо."],
			],
		]
		, true, true);

	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
}

function Notif4($message, $bot) {
	SetState($message, $bot, "notifications4");
	$answer = 'Вы хотите получать уведомления о боях на день?';
	$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
		[
			[
				["text" => "Да, хочу получать."],
				["text" => "Нет, не хочу."],
			],
		]
		, true, true);

	$bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
}

function Start($message, $bot) {
	$nick = $message->getFrom()->getUsername();
	$name = $message->getFrom()->getFirstName();
	$query = "INSERT INTO users_public (id, username,name,chat_id) values ({$message->getFrom()->getId()},'$nick','$name',{$message->getChat()->getId()});\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	if (mb_stripos($answer, "Не удалось соединиться:") !== false) {
		$query = "UPDATE users_public set username='$nick' and name='$name' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
		$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
	}
	SetState($message, $bot, "start");
	if ($message->getChat()->getType() != "private") {
		$answer = 'Простите, кажется это групповой чат. На данный момент я не могу гарантировать коректную работу в групповых чатах. Простите :(';
		$bot->sendMessage($message->getChat()->getId(), $answer);
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
	$query = "select id,message from messages_history_public where user_id=$user_id and chat_id=$chat_id order by id,timemark desc limit $back";
	$result = pg_query($query);
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$res = $line["message"];
	}
	return $res;
}

function SetState($message, $bot, $state) {
	// $nick = $message->getFrom()->getUsername();
	// $name = $message->getFrom()->getFirstName();

	$query = "UPDATE users_public set chat_state='$state' where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()};\n";
	$result = pg_query($query) or $answer = 'Не удалось соединиться: ' . pg_last_error();
}

function GetState($message, $bot) {
	$query = "SELECT distinct on (id) id,chat_state from users_public where id={$message->getFrom()->getId()} and chat_id={$message->getChat()->getId()} order by id desc";
	$result = pg_query($query);
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$state = $line["chat_state"];
	}
	return $state;
}; // class FightClassNot {; // 	// public $id;; // 	public $attacker; // id атакующего клана; // 	public $attacker_id; // id атакующего клана; // 	public $defender; // id защищающегося клана; // 	public $defender_id; // id защищающегося клана; // 	public $from;; // 	public $to;; // 	public $declared; // время, когда был объявлен бой; // 	public $resolved; // вермя, когда состаится бой; // 	public $ended; // вермя, когда состаится бой; // 	public $winer_id;; // 	// public $in_progress; // флаг, активен ли бой; // 	public $winer;; // 	public $was = 0;; // 	public function __construct($a, $d, $f, $t, $de, $r, $w, $end, $id1, $id2, $id3) {; // 		$this->attacker = $a;; // 		$this->defender = $d;; // 		$this->from = $f;; // 		$this->to = $t;; // 		$this->declared = $de;; // 		$this->resolved = $r;; // 		$this->winer = $w;; // 		$this->ended = $end;; // 		$this->attacker_id = $id1;; // 		$this->defender_id = $id2;; // 		$this->winer_id = $id3;; // 	}; // }
?>