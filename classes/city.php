<?php
class City {
	public $id;
	public $name;
	public $clan;

	public function __construct($i, $n, $c) {
		$this->id = $i;
		$this->name = $n;
		$this->clan = $c;
	}
}

class CityWeb {
	public $Название;
	public $Клан;

	public function __construct($n, $c) {
		$this->Название = $n;
		$this->Клан = $c;
	}
}
function CityId($dbconn, $title) {
	// $connection=Connect($config);
	$query = "select * from get_city_id('$title');\n";
	// $result = $connection->query($query);
	// mysqli_close($connection);
	// if (!$result) {
	// echo ("Error during creating era table".$connection->connect_errno.$connection->connect_error);
	// }
	// print_r($result);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$clans_server = array();
	// echo $query.PHP_EOL;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		if ($line["name"] == $title) {
			return $line["id"];
		}
		// }
	}
	return -1;
}
function WhoHasThisCity($dbconn, $id) {
	// $connection=Connect($config);
	$query = "select * from get_city_by_id($id);\n";
	// $result = $connection->query($query);
	// mysqli_close($connection);
	// if (!$result) {
	// echo ("Error during creating era table".$connection->connect_errno.$connection->connect_error);
	// }
	// print_r($result);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
	$clans_server = array();
	// echo $query.PHP_EOL;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		if ($line["id"] == $id) {
			return $line["clan"];
		}
	}
	// }
	return -1;
}
?>