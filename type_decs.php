<?php
// date_default_timezone_set('Europe/London');
// echo date('l-jS-\of-F-Y-h:i:s-A');
require "functions/matrix.php";
require "classes/cards.php";
require "classes/colors.php";

$tp_count = 20;

$file = file_get_contents(realpath(dirname(__FILE__)) . "/.config.json");
$config = json_decode($file, true);
$query = "host={$config['host']} dbname={$config['dbname']} user={$config['user']} password={$config['password']}";
$dbconn = pg_pconnect($query) or die('Не удалось соединиться: ' . pg_last_error());

$query = "select * from cards();\n";
// $result = $connection->query($query);
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$cards_all = array();
// echo $query;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	// print_r($row);
	array_push($cards_all, new Card($line['id'], $line['name'], $line['proto']));

}

$query = "select * from deck where player_id=12138;\n";
$query = "select * from deck;\n";
$result = pg_query($query);
$all_cards = array();
$res_bcp = $result;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$line[cards] = str_replace('{', '', $line[cards]);
	$line[cards] = str_replace('}', '', $line[cards]);
	$cards = explode(',', $line[cards]);
	// print_r($line);
	foreach ($cards as $card) {
		if ($card != "") {
			array_push($all_cards, $card);
		}
	}
}

$all_cards_unique = array_unique($all_cards);
// print_r($all_cards_unique);
// echo count($all_cards) . PHP_EOL;
// echo count($all_cards_unique) . PHP_EOL;
$matrix = CreateMatrix($all_cards_unique);
$result = pg_query($query);
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$line[cards] = str_replace('{', '', $line[cards]);
	$line[cards] = str_replace('}', '', $line[cards]);
	$cards = explode(',', $line[cards]);
	// print_r($line);
	$current_cards = array();
	foreach ($cards as $card) {
		if ($card != "") {
			array_push($current_cards, $card);
		}
	}
	$current_matrix = CreateMatrix($current_cards);
	// PrintTable($current_matrix);
	foreach ($current_matrix as $row_id => $row) {
		foreach ($row as $cell_id => $cell) {
			// echo $row_id . "," . $cell_id . PHP_EOL;
			$matrix[$row_id][$cell_id]++;
		}
	}
}
echo count($matrix) . PHP_EOL;
PrintTable($matrix);
$analaz = MatrixAnaliz($matrix);
$main = array();
foreach ($matrix as $row_id => $row) {
	foreach ($row as $cell_id => $cell) {
		if ($cell > $analaz[max] - $analaz[avr_nn] * $analaz[avr_nn]) {
			array_push($main, $cell_id);
		}
	}
}
$main = array_unique($main);
foreach ($main as $main1) {
	foreach ($cards_all as $card) {
		if ($main1 == $card->id) {
			echo $card->name . PHP_EOL;
		}
	}
}
exit();
$max = 0;
$strongest = array();
$len = 0;
foreach ($matrix as $row_id => $row) {
	$len++;
	$tmp = array();
	$tmp[n] = 0;
	$tmp[cell_id] = 0;
	$tmp[row_id] = 0;
	foreach ($row as $cell_id => $cell) {
		if ($row_id > $max) {
			$max = $row_id;
		}
		if ($cell > $tmp[n]) {
			$tmp[n] = $cell;
			$tmp[cell_id] = $cell_id;
			$tmp[row_id] = $row_id;
		}
	}
	array_push($strongest, $tmp);

}
for ($i = 0; $i < count($strongest); $i++) {
	for ($j = 0; $j < count($strongest); $j++) {
		// if ((isset($strongest[$i][n])) && (isset($strongest[$j][n]))) {
		if ($strongest[$i][n] > $strongest[$j][n]) {
			$bcp = $strongest[$i];
			$strongest[$i] = $strongest[$j];
			$strongest[$j] = $bcp;
			// }
		}
	}
}
// print_r($strongest[0]);
// print_r($strongest[1]);
// echo $len . PHP_EOL;
// exit();
$array_of_strong = array();
$was = array();
$a = GetStrongest($matrix, $was);
// print_r($a);
if (count($a) > 0) {
	array_push($array_of_strong, $a);
}
$i = 0;
// for ($i = 0; $i < 100; $i++) {
while (count($a) > 0) {
	$a = GetStrongest($matrix, $was);
	print_r($a); // }
	$t = count($matrix) * count($matrix);
	echo $i++ . "/" . $t . PHP_EOL;
	if (count($a) > 0) {
		array_push($array_of_strong, $a);
	}
}
$file_load = "array_of_strong.json";

file_put_contents($file_load, json_encode($array_of_strong, JSON_UNESCAPED_UNICODE));
// print_r($array_of_strong);
$sum_of_strong = 0;
foreach ($array_of_strong as $strong) {
	$sum_of_strong += $strong[n];
}
$avr_of_strong = $sum_of_strong / count($array_of_strong);
echo $sum_of_strong . PHP_EOL;
echo $avr_of_strong . PHP_EOL;
exit();
$strongest_line = $matrix[$strongest[0][row_id]];
$strongest_line_sorted = MakeLine($strongest_line);

print_r($strongest_line_sorted);

// print_r($strongest_line_sorted);
$res = array();
for ($i = 0; $i < $tp_count; $i++) {
	foreach ($cards_all as $card) {
		if ($strongest_line_sorted[$i][n] > 0) {
			if ($strongest_line_sorted[$i][cell_id] == $card->id) {
				array_push($res, $card->name);
			}
		}
	}
}

print_r($res);

pg_free_result($result);
PrintTable($matrix);
echo SemiMatrix($matrix);

// Закрытие соединения
pg_close($dbconn);
?>