<?php
function InsertUpdate($table, $keys, $where) {
	$where_string = "where ";
	if (count($where) > 0) {
		foreach ($where as $key => $value) {
			$where .= " " . $key . "=" . $value;
		}
	}
	// 1 проверяем наличие данных
	$query = "SELECT * FROM $table WHERE $where_string";

}
?>