<?php
class screenshot {
	public $id;
	public $name;
	public $file;
	public $timemark;

	public function __construct($id, $name, $file, $timemark) {
		$this->id = $id;
		$this->name = $name;
		$this->file = $file;
		$this->timemark = $timemark;
	}

}
class Deck {

	public $id;
	public $player_id;
	public $cards = array();
	public $screenshot_id;
	public $description;
	public $timemark;
	public $edited;
	public $deck_type;

	public function EditButton() {
		$this->button = "<form method='POST' action='deck_edit.php' enctype='multipart/form-data'><input type='hidden' name='id' value='$this->id' /><input type='hidden' name='player_id' value='$this->player_id' /><input class='color_text sp_input' type='submit' name='edit_deck'value='Edit' /></form>";
	}

	public function __construct($id, $player, $cards, $screenshot, $description, $timemark, $edited, $type) {
		$this->id = $id;
		$this->player_id = $player;
		$this->cards = json_encode($cards, true);
		$this->screenshot_id = $screenshot;
		$this->description = $description;
		$this->timemark = $timemark;
		$this->edited = $edited;
		$this->deck_type = $type;
	}

	public function GetScreenshot() {
		$query = "\nSELECT * FROM screenshots where id=" . $this->screenshot_id . "limit 1;\n";
		$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
			$this->screenshot_id = new screenshot($line['id'], $line['name'], $line['file'], $line['timemark']);
		}
		$this->screenshot_id = "<img src='" . $this->screenshot_id->file . "' alt='" . $this->screenshot_id->name . "' width='150' height='150'>";
	}
}
?>
