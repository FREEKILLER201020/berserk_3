<?php

function PrintTable($table) {
	$analaz = MatrixAnaliz($table);
	print_r($analaz);
	// exit();
	$main_color = null;
	$background_color = null;
	$colors = new Colors();
	reset($table);
	$first_key = key($table);
	$strings = array();
	foreach ($table as $key => $row) {
		array_push($strings, $key);
		foreach ($row as $key2 => $cell) {
			array_push($strings, $key2);
			array_push($strings, $cell);
		}
	}
	$strings = array_unique($strings);
	// print_r($strings);
	$max_len = 0;
	foreach ($strings as $string) {
		if (strlen($string) > $max_len) {
			$max_len = strlen($string);
		}
	}
	// echo $max_len . PHP_EOL;
	// for ($l=0; $l < count($table[0]); $l++) {
	echo $colors->getColoredString("|", $main_color, $background_color);
	echo $colors->getColoredString(str_pad("", $max_len, "-", STR_PAD_BOTH), $main_color, $background_color);
	echo $colors->getColoredString("|", $main_color, $background_color);
	foreach ($table[$first_key] as $key2 => $cell) {
		// echo $key2." ";
		echo $colors->getColoredString(str_pad("", $max_len, "-", STR_PAD_BOTH), $main_color, $background_color);
		echo $colors->getColoredString("|", $main_color, $background_color);
	}
	echo PHP_EOL;
	echo $colors->getColoredString("|", $main_color, $background_color);
	echo $colors->getColoredString(str_pad("", $max_len, " ", STR_PAD_BOTH), $main_color, $background_color);
	echo $colors->getColoredString("|", $main_color, $background_color);
	foreach ($table[$first_key] as $key2 => $cell) {
		// echo $key2." ";
		echo $colors->getColoredString(str_pad($key2, $max_len, " ", STR_PAD_BOTH), $main_color, $background_color);
		echo $colors->getColoredString("|", $main_color, $background_color);
	}
	echo PHP_EOL;

	// }
	foreach ($table as $key => $row) {
		echo $colors->getColoredString("|", $main_color, $background_color);
		echo $colors->getColoredString(str_pad("", $max_len, "-", STR_PAD_BOTH), $main_color, $background_color);
		echo $colors->getColoredString("|", $main_color, $background_color);
		foreach ($table[$first_key] as $key2 => $cell) {
			// echo $key2." ";
			echo $colors->getColoredString(str_pad("", $max_len, "-", STR_PAD_BOTH), $main_color, $background_color);
			echo $colors->getColoredString("|", $main_color, $background_color);
		}
		echo PHP_EOL;
		echo $colors->getColoredString("|", $main_color, $background_color);
		echo $colors->getColoredString(str_pad($key, $max_len, " ", STR_PAD_BOTH), $main_color, $background_color);
		echo $colors->getColoredString("|", $main_color, $background_color);
		foreach ($row as $key2 => $cell) {
			echo $colors->getColoredString(str_pad($cell, $max_len, " ", STR_PAD_BOTH), $colors->mapColor($cell, $analaz), $colors->mapColorBG($cell, $analaz));
			echo $colors->getColoredString("|", $main_color, $background_color);
		}
		echo PHP_EOL;
		// print_r($row);
		// code...
	}
	echo $colors->getColoredString("|", $main_color, $background_color);
	echo $colors->getColoredString(str_pad("", $max_len, "-", STR_PAD_BOTH), $main_color, $background_color);
	echo $colors->getColoredString("|", $main_color, $background_color);
	foreach ($table[$first_key] as $key2 => $cell) {
		// echo $key2." ";
		echo $colors->getColoredString(str_pad("", $max_len, "-", STR_PAD_BOTH), $main_color, $background_color);
		echo $colors->getColoredString("|", $main_color, $background_color);
	}
	echo PHP_EOL;

}

function CreateMatrix($array) {
	$res = array();
	foreach ($array as $key1) {
		foreach ($array as $key2) {
			$res[$key1][$key2] = 0;
		}
	}
	return $res;
}

function MakeLine($strongest_line) {
	$strongest_line_sorted = array();
	foreach ($strongest_line as $key => $value) {
		$tmp = array();
		$tmp[n] = $value;
		$tmp[cell_id] = $key;
		array_push($strongest_line_sorted, $tmp);
	}

	for ($i = 0; $i < count($strongest_line_sorted); $i++) {
		for ($j = 0; $j < count($strongest_line_sorted); $j++) {
			// if ((isset($strongest_line_sorted[$i][n])) && (isset($strongest_line_sorted[$j][n]))) {
			if ($strongest_line_sorted[$i][n] > $strongest_line_sorted[$j][n]) {
				$bcp = $strongest_line_sorted[$i];
				$strongest_line_sorted[$i] = $strongest_line_sorted[$j];
				$strongest_line_sorted[$j] = $bcp;
				// }
			}
		}
	}
	return $strongest_line_sorted;
}

function SemiMatrix($array) {
	$len = count($array[0]);
	$ok = 1;
	for ($i = 0; $i < $len; $i++) {
		for ($j = 0; $j < $len; $j++) {
			if ($array[$i][$j] != $array[$j][$i]) {
				$ok = 0;
			}
		}
	}
	return $ok;
}

function GetStrongest($array, &$was) {
	$max = 0;
	$return = array();
	foreach ($array as $row => $row_val) {
		foreach ($row_val as $cell => $cell_val) {
			$new = 1;
			$tmp = $row . ";" . $cell;
			$tmp2 = $cell . ";" . $row;
			// echo $tmp . PHP_EOL;
			if ((in_array($tmp, $was)) || (in_array($tmp2, $was))) {
				// foreach ($was as $to_comp) {
				// if ((($to_comp[row] == $row) && ($to_comp[cell] == $cell)) || (($to_comp[cell] == $row) && ($to_comp[row] == $cell))) {
				$new = 0;
				// }
			}
			if ($new == 1) {
				if ($cell_val > $max) {
					$return[n] = $cell_val;
					$return[cell_id] = $cell;
					$return[row_id] = $row;
					$max = $cell_val;
				}
			}
		}
	}
	$tmp = $return[row_id] . ";" . $return[cell_id];
	$tmp2 = $return[cell_id] . ";" . $return[row_id];
	array_push($was, $tmp);
	array_push($was, $tmp2);
	// print_r($was);
	return $return;
}

function MatrixAnaliz($array) {
	$max = 0;
	$min = INF;
	$sum = 0;
	$good = 0;
	$total;
	foreach ($array as $row => $row_val) {
		foreach ($row_val as $cell => $cell_val) {
			$total++;
			if ($cell_val > 0) {
				$sum += $cell_val;
				$good++;
			}
			if ($cell_val > $max) {
				$max = $cell_val;
			}
			if ($cell_val < $min) {
				$min = $cell_val;
			}
		}
	}
	$res = array();
	$res[max] = $max;
	$res[min] = $min;
	$res[avr] = $sum / $total;
	$res[avr_nn] = $sum / $good;
	return $res;
}

?>