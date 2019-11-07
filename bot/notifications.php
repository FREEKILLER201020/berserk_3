<?php
require "../classes/fight.php";
require "../classes/notofication.php";
include '../classes/pushover.php';
require_once "vendor/autoload.php";

date_default_timezone_set('Europe/London');
$time = 30;
$file = file_get_contents(realpath(dirname(__FILE__)) . "/../.config.json");
$config = json_decode($file, true);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());
// $query = "select * from attacks where ended is null order by resolved desc;\n";
$query = "select * from attacks order by resolved desc;\n";

$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$fights = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$query2 = "SELECT distinct on (id) timemark,id,title, points, created, gone from clans where timemark<='$line[resolved]';\n";
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

	$query2 = "SELECT distinct on (id) timemark, id, name, clan from cities where timemark<='$line[resolved]';\n";
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

	$tmp = new FightClassNot($attacker, $defender, $from, $to, $line[declared], $line[resolved], $line[winer], $line[ended], $line[attacker], $line[defender], $line[winer]);
	array_push($fights, $tmp);
}

$tmp = new FightClassNot("Fireborn", "\"Берсерк\"", "Мир Кефки", "Лихолесье", "2019-11-07 19:00:00", "2019-11-07 16:05:00", "Fireborn", "2019-11-07 16:20:00", 6, 171, 171);
array_push($fights, $tmp);
$tmp = new FightClassNot("Fireborn", "\"Берсерк\"", "Мир Кефки", "Лихолесье", "2019-11-07 19:00:00", "2019-11-07 16:05:00", "Fireborn", "2019-11-07 16:21:10", 6, 171, 6);
array_push($fights, $tmp);
$tmp = new FightClassNot("\"Берсерк\"", "Fireborn", "Мир Кефки", "Лихолесье", "2019-11-07 19:00:00", "2019-11-07 16:05:00", "Fireborn", "2019-11-07 16:22:29", 6, 171, 171);
array_push($fights, $tmp);
$tmp = new FightClassNot("\"Берсерк\"", "Fireborn", "Мир Кефки", "Лихолесье", "2019-11-07 19:00:00", "2019-11-07 16:05:00", "Fireborn", "2019-11-07 16:23:59", 6, 171, 6);
array_push($fights, $tmp);
print_r($fights);
// exit();

$query = "select * from notifications;\n";

$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$i = 1;
$not = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	$tmp = new Notification($line[user_id], $line[user_key], $line[clan_id], $line[type]);
	array_push($not, $tmp);
}

print_r($not);

foreach ($not as $user) {
	foreach ($fights as $fight) {
		if (($user->user_clan == $fight->attacker_id) || ($user->user_clan == $fight->defender_id)) {
			$d = date('Y-m-d H:i:s');
			echo $d . PHP_EOL;
			$timestamp1 = strtotime($d) - 60 * 60;
			$timestamp2 = strtotime($fight->resolved);
			$d = round(($timestamp2 - $timestamp1) / 60);
			echo $d . PHP_EOL;

			if ($d == $time) {
				$push = new Pushover();

				$push->setToken('a5g19h6if4cdvvfrdw8n5najpm68rb');
				$push->setUser($user->user_key);

				$push->setTitle('Скоро битва!');
				if ($user->user_clan == $fight->attacker_id) {
					$push->setMessage('Через ' . $time . ' минут начнется бой против ' . $fight->defender . ' за ' . $fight->to);

				} else if ($user->user_clan == $fight->defender_id) {
					$push->setMessage('Через ' . $time . ' минут начнется бой против ' . $fight->attacker . ' за ' . $fight->to);

				}
				// $push->setUrl('http://chris.schalenborgh.be/blog/');
				// $push->setUrlTitle('cool php blog');
				// $push->setDevice('pixel2xl');
				$push->setPriority(0);
				// $push->setRetry(60); //Used with Priority = 2; Pushover will resend the notification every 60 seconds until the user accepts.
				// $push->setExpire(3600); //Used with Priority = 2; Pushover will resend the notification every 60 seconds for 3600 seconds. After that point, it stops sending notifications.
				// $push->setTimestamp(time());
				print_r($push);
				$push->send();
			}
		}
		if (($user->user_clan == $fight->attacker_id) || ($user->user_clan == $fight->defender_id)) {
			$d = date('Y-m-d H:i:s');
			echo $d . PHP_EOL;
			$timestamp1 = strtotime($d) - 60 * 60;
			$timestamp2 = strtotime($fight->ended);
			$d = round(($timestamp1 - $timestamp2) / 60);
			echo $d . PHP_EOL;

			if (($d >= -0) && ($d <= 1)) {
				$push = new Pushover();

				$push->setToken('a5g19h6if4cdvvfrdw8n5najpm68rb');
				$push->setUser($user->user_key);

				$push->setTitle('Скоро битва!');
				if ($user->user_clan == $fight->winer_id) {
					if ($user->user_clan == $fight->attacker_id) {
						$push->setMessage($d . 'Ура! Победа! Мы отбили ' . $fight->to . ' у ' . $fight->defender);
					} else if ($user->user_clan == $fight->defender_id) {
						$push->setMessage($d . 'Ура! Победа! Мы защитили ' . $fight->to . ' от ' . $fight->attacker);
					}
				} else if ($user->user_clan != $fight->winer_id) {
					if ($user->user_clan == $fight->defender_id) {
						$push->setMessage($d . 'Поражение... Мы отдали ' . $fight->to . ' клану ' . $fight->attacker);
					} else if ($user->user_clan == $fight->defender_id) {
						$push->setMessage($d . 'Поражение... Мы не смогли отбить ' . $fight->to . ' у ' . $fight->defender);
					}
				}
				// $push->setUrl('http://chris.schalenborgh.be/blog/');
				// $push->setUrlTitle('cool php blog');
				// $push->setDevice('pixel2xl');
				$push->setPriority(0);
				// $push->setRetry(60); //Used with Priority = 2; Pushover will resend the notification every 60 seconds until the user accepts.
				// $push->setExpire(3600); //Used with Priority = 2; Pushover will resend the notification every 60 seconds for 3600 seconds. After that point, it stops sending notifications.
				// $push->setTimestamp(time());
				print_r($push);
				$push->send();
			}
		}
	}
}

$token = "681634726:AAHafNwa8T3LXlezmIAUH-JjBGrI0qU-lfY";
$bot = new \TelegramBot\Api\Client($token);

$query = "SELECT * from bot_notification";
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$notifications = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$tmp = new NotificationBot($line["id"], $line["chat_id"], $line["user_id"], $line["notification_type"], $line["pre_start_time"]);
	array_push($notifications, $tmp);
}
print_r($notifications);

$query = "SELECT * from users";
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	foreach ($notifications as $notification) {
		if ($notification->user_id == $line["id"]) {
			$notification->in_game_id = $line["game_id"];
		}
	}
}
print_r($notifications);

$query = "select distinct on (id) timemark,id,nick,frags,deaths,level,clan,folder from players order by id, timemark desc;\n";
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
// $notifications = array();
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	foreach ($notifications as $notification) {
		if ($notification->in_game_id == $line["id"]) {
			$notification->clan_id = $line["clan"];
		}
	}
}

print_r($notifications);

foreach ($notifications as $notification) {
	if ($notification->type == 1) {
		// if ($notification->chat_id == 249857309) {
		foreach ($fights as $fight) {
			if (($notification->clan_id == $fight->attacker_id) || ($notification->clan_id == $fight->defender_id)) {
				$d = date('Y-m-d H:i:s');
				echo $d . PHP_EOL;
				$timestamp1 = strtotime($d) - 60 * 60;
				$timestamp2 = strtotime($fight->resolved);
				$d = round(($timestamp2 - $timestamp1) / 60);
				echo $d . PHP_EOL;

				if ($d == $notification->time) {
					$answer = "Скоро битва!";
					echo $answer;
					$bot->sendMessage($notification->chat_id, $answer, null, null, null, null);

					if ($notification->clan_id == $fight->attacker_id) {
						$answer = 'Через ' . $time . ' минут начнется бой против ' . $fight->defender . ' за ' . $fight->to;

					} else if ($notification->clan_id == $fight->defender_id) {
						$answer = 'Через ' . $time . ' минут начнется бой против ' . $fight->attacker . ' за ' . $fight->to;

					}
					$bot->sendMessage($notification->chat_id, $answer, null, null, null, null);

				}
			}
		}
		// }
	}
}
pg_free_result($result);

pg_close($dbconn);
$bot->run();

class NotificationBot {

	public $id;
	public $chat_id;
	public $user_id;
	public $in_game_id;
	public $type;
	public $time;
	public $clan_id;

	public function __construct($id, $chat_id, $user_id, $type, $time) {
		$this->id = $id;
		$this->chat_id = $chat_id;
		$this->user_id = $user_id;
		$this->type = $type;
		$this->time = $time;

	}
}
?>