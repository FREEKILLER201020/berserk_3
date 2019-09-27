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

class ClanUpdate {
	public $id;
	public $title_old;
	public $title_new;
	public $cteated;
	public $gone;

	public function __construct($id, $title_old, $title_new, $created, $gone) {
		$this->id = $id;
		$this->title_old = $title_old;
		$this->title_new = $title_new;
		$this->created = $created;
		$this->gone = $gone;
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
	public $gone;
	public $created;

	public function __construct($id, $title, $points, $was, $gone, $created) {
		$this->id = $id;
		$this->title = $title;
		$this->points = $points;
		$this->was = $was;
		$this->gone = $gone;
		$this->created = $created;
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

class ClanEraRes {
	public $Название;
	public $ID;
	public $Очки_на_начало_эры;
	public $Очки_на_конец_эры;
	public $Города_на_начало_эры;
	public $Города_на_конец_эры;

	public function __construct($title, $id, $p1, $p2, $c1, $c2) {
		$this->Название = $title;
		$this->ID = $id;
		$this->Очки_на_начало_эры = $p1;
		$this->Очки_на_конец_эры = $p2;
		$this->Города_на_начало_эры = $c1;
		$this->Города_на_конец_эры = $c2;
	}
}
?>