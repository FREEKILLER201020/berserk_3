<?php
// date_default_timezone_set('Europe/London');
// echo date('l-jS-\of-F-Y-h:i:s-A');
require "functions/matrix.php";
require "classes/cards.php";
require "classes/colors.php";

$tp_count = 10;

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
	$cards_all[count($cards_all) - 1]->type_id = $line['type'];

}

$query = "select * from cards_type;\n";
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
$cards_types = array();
// echo $query;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

	// print_r($row);
	$cards_types[$line['id']] = $line['desc'];
}

foreach ($cards_all as $card) {
	$card->type = $cards_types[$card->type_id];
}

$query = "select * from deck where player_id=12138;\n";
$query = "select * from deck;\n";
$result = pg_query($query);
$all_cards = array();
$res_bcp = $result;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$line['cards'] = str_replace('{', '', $line['cards']);
	$line['cards'] = str_replace('}', '', $line['cards']);
	$cards = explode(',', $line['cards']);
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
	$line['cards'] = str_replace('{', '', $line['cards']);
	$line['cards'] = str_replace('}', '', $line['cards']);
	$cards = explode(',', $line['cards']);
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
// echo count($matrix) . PHP_EOL;
// PrintTable($matrix);
// exit();
$analaz = MatrixAnaliz($matrix);
$main = array();
foreach ($matrix as $row_id => $row) {
	foreach ($row as $cell_id => $cell) {
		if ($cell > $analaz['max'] - $analaz['avr_nn'] * $analaz['avr_nn']) {
			$tmp = array();
			$tmp['cell_id'] = $cell_id;
			$tmp['row_id'] = $row_id;
			array_push($main, $tmp);
		}
	}
}
// $main = array_unique($main);
// print_r($main);
// exit();
$realstrongcards = array();
foreach ($main as $main1) {
	if ($main1['cell_id'] == $main1['row_id']) {
		// if (StrongInARow($matrix, $main1['cell_id']) == 1) {
		foreach ($cards_all as $card) {
			if (($main1['cell_id'] == $card->id) && ($card->type_id == 1)) {
				// echo $card->name . PHP_EOL;
				array_push($realstrongcards, $main1['cell_id']);
			}
			// }
		}
	}
}
$realstrongcards = array_unique($realstrongcards);
if (count($realstrongcards) < 1) {
	echo "Мало данных" . $extra . PHP_EOL;
	exit();
}
// print_r($realstrongcards);
// exit();

$extra = "";
echo "Сборки методом прямого добора" . PHP_EOL . "++++++++++++++++++++++++++++++" . PHP_EOL;

foreach ($realstrongcards as $card) {
	// print_r(RowAnalize($matrix[$card]));
	// foreach ($cards_all as $cr) {
	// 	if ($cr->id == $card) {
	// 		print_r($cr);
	// 	}
	// }
	$res = array();
	// print_r($matrix[$card]);
	// exit();
	$line = SortLine($matrix[$card]);
	$pos = 0;
	// echo $card . PHP_EOL;
	while ((count($res) < $tp_count) && ($pos < count($line) - 1)) {
		foreach ($line as $key => $value) {
			$new = 1;
			foreach ($res as $key2 => $value2) {
				if ($key2 == $key) {
					$new = 0;
				}
			}
			if ($new == 1) {
				foreach ($value as $sub_key => $sub_value) {
					if ($sub_value > Hard($analaz["avr_nn"], 2)) {
						array_push($res, $line[$key]);
					}
				}
			}
		}
		$pos++;
		// print_r($line[$pos]);
		$card2 = array_keys($line[$pos])[0];
		// print_r($card2);
		// echo $card2 . PHP_EOL;
		// exit();
		$line = SortLine($matrix[$card2]);
	}
	// print_r($res);
	echo "Сборка на основе: ";
	foreach ($cards_all as $card1) {
		if ($card1->id == $card) {
			echo $card1->name . $extra . PHP_EOL;
		}
	}
	$pos = 0;
	foreach ($res as $key => $value) {
		if ($pos < $tp_count) {
			foreach ($value as $sub_key => $sub_value) {
				foreach ($cards_all as $card1) {
					if ($card1->id == $sub_key) {
						echo $card1->name . $extra . PHP_EOL;
					}
				}
			}
			$pos++;
		}
	}
	echo "==============================" . $extra . PHP_EOL;
}

// print_r($cards_all);
// exit();
echo PHP_EOL . "Сборки методом весового добора" . PHP_EOL . "++++++++++++++++++++++++++++++" . PHP_EOL;

foreach ($realstrongcards as $card) {
	// print_r(RowAnalize($matrix[$card]));
	// foreach ($cards_all as $cr) {
	// 	if ($cr->id == $card) {
	// 		print_r($cr);
	// 	}
	// }
	$res = array();
	// print_r($matrix[$card]);
	// exit();
	$base_line = SortLine($matrix[$card]);
	// print_r($base_line);
	// exit();
	$line = array();
	foreach ($base_line as $key => $value) {
		foreach ($value as $sub_key => $sub_value) {
			if ($sub_value > Hard($analaz["avr_nn"], 2)) {
				$new_line = SortLine($matrix[$sub_key]);
				foreach ($new_line as $key2 => $val2) {
					// if (in_array($key2, $line)) {
					// 	$max = 0;
					// 	foreach ($line as $key_search => $value_search) {
					// 		if (($key_search == $key2) && ($value_search > $max)) {
					// 			$max = $value_search;
					// 		}
					// 	}
					// 	$line[$key2] = $max;
					// } else {
					array_push($line, $new_line[$key2]);
					// }
				}
			}
			// print_r($new_line);
		}
	}
	// print_r($line);
	$base_line = array();
	foreach ($line as $key => $value) {
		foreach ($value as $sub_key => $sub_value) {
			if ($base_line[$sub_key] < $sub_value) {
				$base_line[$sub_key] = $sub_value;
			}
		}
	}
	// print_r($base_line);
	// echo count($base_line) . PHP_EOL;
	$base_line = SortLine($base_line);
	// print_r($base_line);
	// exit();
	$pos = 0;
	// echo $card . PHP_EOL;
	while ((count($res) < $tp_count) && ($pos < count($base_line) - 1)) {
		foreach ($base_line[$pos] as $key => $value) {
			// echo $key . " " . $value . PHP_EOL;
			// exit();
			// if (!in_array($key, $res)) {
			// foreach ($value as $sub_key => $sub_value) {
			// if ($sub_value > $analaz["avr_nn"]) {
			$tmp = array();
			$tmp[$key] = $value;
			array_push($res, $tmp);
			// }
			// }
			// }
		}
		$pos++;
		// print_r($line[$pos]);
		// $card2 = array_keys($line[$pos])[0];
		// print_r($card2);
		// echo $card2 . PHP_EOL;
		// exit();
		// $line = SortLine($matrix[$card2]);
	}
	// print_r($res);
	echo "Сборка на основе: ";
	foreach ($cards_all as $card1) {
		if ($card1->id == $card) {
			echo $card1->name . $extra . PHP_EOL;
		}
	}
	$pos = 0;
	foreach ($res as $key => $value) {
		if ($pos < $tp_count) {
			foreach ($value as $sub_key => $sub_value) {
				foreach ($cards_all as $card1) {
					if ($card1->id == $sub_key) {
						echo $card1->name . $extra . PHP_EOL;
					}
				}
			}
			$pos++;
		}
	}
	echo "==============================" . $extra . PHP_EOL;
}
// echo Hard(2, 1) . PHP_EOL;
exit();

$max = 0;
$strongest = array();
$len = 0;
foreach ($matrix as $row_id => $row) {
	$len++;
	$tmp = array();
	$tmp['n'] = 0;
	$tmp['cell_id'] = 0;
	$tmp['row_id'] = 0;
	foreach ($row as $cell_id => $cell) {
		if ($row_id > $max) {
			$max = $row_id;
		}
		if ($cell > $tmp['n']) {
			$tmp['n'] = $cell;
			$tmp['cell_id'] = $cell_id;
			$tmp['row_id'] = $row_id;
		}
	}
	array_push($strongest, $tmp);

}
for ($i = 0; $i < count($strongest); $i++) {
	for ($j = 0; $j < count($strongest); $j++) {
		// if ((isset($strongest[$i][n])) && (isset($strongest[$j][n]))) {
		if ($strongest[$i]['n'] > $strongest[$j][n]) {
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
	$sum_of_strong += $strong['n'];
}
$avr_of_strong = $sum_of_strong / count($array_of_strong);
echo $sum_of_strong . PHP_EOL;
echo $avr_of_strong . PHP_EOL;
exit();
$strongest_line = $matrix[$strongest[0]['row_id']];
$strongest_line_sorted = MakeLine($strongest_line);

print_r($strongest_line_sorted);

// print_r($strongest_line_sorted);
$res = array();
for ($i = 0; $i < $tp_count; $i++) {
	foreach ($cards_all as $card) {
		if ($strongest_line_sorted[$i]['n'] > 0) {
			if ($strongest_line_sorted[$i]['cell_id'] == $card->id) {
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