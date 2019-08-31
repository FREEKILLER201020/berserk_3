<?php
class ClanClass {
	public $id;
	public $title;
	public $points;

	public function __construct($id, $title, $points) {
		$this->id = $id;
		$this->title = $title;
		$this->points = $points;
	}
}

class ClanClassMerge {
	public $timemark;
	public $id;
	public $title;
	public $points;
	public $gone;

	public function __construct($timemark, $id, $title, $points, $gone) {
		$this->timemark = $timemark;
		$this->id = $id;
		$this->title = $title;
		$this->points = $points;
		$this->gone = $gone;
	}
}

class ClanClassTest {
	public $id;
	public $title;
	public $points;
	public $was;

	public function __construct($id, $title, $points, $was) {
		$this->id = $id;
		$this->title = $title;
		$this->points = $points;
		$this->was = $was;
	}
}
function GetClanId($dbconn, $title) {
	// $connection = Connect($config);
	$query = "select * from get_clan_id('$title');\n";
	// $result = $connection->query($query);
	// mysqli_close($connection);
	$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

	// if (!$result) {
	//     echo ("Error during creating era table".$connection->connect_errno.$connection->connect_error);
	// }
	// print_r($result);
	$clans_server = array();
	// echo $query.PHP_EOL;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {

		// print_r($row);
		if ($line["title"] == $title) {
			return $line["id"];
		}
		// }
	}
	return -1;
}
?>