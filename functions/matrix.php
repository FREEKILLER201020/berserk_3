<?php

function PrintTable($table) {
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
	print_r($strings);
	$max_len = 0;
	foreach ($strings as $string) {
		if (strlen($string) > $max_len) {
			$max_len = strlen($string);
		}
	}
	echo $max_len . PHP_EOL;
	// for ($l=0; $l < count($table[0]); $l++) {
	echo "|";
	echo str_pad("", $max_len, "-", STR_PAD_BOTH);
	echo "|";
	foreach ($table[$first_key] as $key2 => $cell) {
		// echo $key2." ";
		echo str_pad("", $max_len, "-", STR_PAD_BOTH);
		echo "|";
	}
	echo PHP_EOL;
	echo "|";
	echo str_pad("", $max_len, " ", STR_PAD_BOTH);
	echo "|";
	foreach ($table[$first_key] as $key2 => $cell) {
		// echo $key2." ";
		echo str_pad($key2, $max_len, " ", STR_PAD_BOTH);
		echo "|";
	}
	echo PHP_EOL;

	// }
	foreach ($table as $key => $row) {
		echo "|";
		echo str_pad("", $max_len, "-", STR_PAD_BOTH);
		echo "|";
		foreach ($table[$first_key] as $key2 => $cell) {
			// echo $key2." ";
			echo str_pad("", $max_len, "-", STR_PAD_BOTH);
			echo "|";
		}
		echo PHP_EOL;
		echo "|";
		echo str_pad($key, $max_len, " ", STR_PAD_BOTH);
		echo "|";
		foreach ($row as $key2 => $cell) {
			echo str_pad($cell, $max_len, " ", STR_PAD_BOTH);
			echo "|";
		}
		echo PHP_EOL;
		// print_r($row);
		// code...
	}
	echo "|";
	echo str_pad("", $max_len, "-", STR_PAD_BOTH);
	echo "|";
	foreach ($table[$first_key] as $key2 => $cell) {
		// echo $key2." ";
		echo str_pad("", $max_len, "-", STR_PAD_BOTH);
		echo "|";
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

?>