<?php
class PlayerClass {
	public $timemark;
	public $id;
	public $nick;
	public $frags;
	public $deaths;
	public $level;
	public $clan_id;
	public $clan_title;
	public $was = 0;

	public function __construct($timemark, $id, $nick, $frags, $deaths, $level, $clan_id, $clan_title) {
		$this->timemark = $timemark;
		$this->id = $id;
		$this->nick = $nick;
		$this->frags = $frags;
		$this->deaths = $deaths;
		$this->level = $level;
		$this->clan_id = $clan_id;
		$this->clan_title = $clan_title;
	}
}

class PlayerClassEra {
	public $timemark;
	public $nick;
	public $frags;
	public $deaths;
	public $level;
	public $clan_id;
	public $clan_title;
	public $frags_era;
	public $deaths_era;
	public $games;
	public $points;
	public $lbz;

	public function __construct($timemark, $nick, $frags, $deaths, $level, $clan_id, $clan_title, $f, $d, $g, $p, $l) {
		$this->timemark = $timemark;
		$this->nick = $nick;
		$this->frags = $frags;
		$this->deaths = $deaths;
		$this->level = $level;
		$this->clan_id = $clan_id;
		$this->clan_title = $clan_title;
		$this->frags_era = $f;
		$this->deaths_era = $d;
		$this->games = $g;
		$this->points = $p;
		$this->lbz = $l;
	}
}

class PlayerClassIndex {
	public $№;
	public $Никнейм;
	public $Фраги;
	public $Смерти;
	public $Уровень;
	public $Клан;

	public function __construct($num, $nick, $frags, $deaths, $level, $clan_title) {
		$this->№ = $num;
		$this->Никнейм = $nick;
		$this->Фраги = $frags;
		$this->Смерти = $deaths;
		$this->Уровень = $level;
		$this->Клан = $clan_title;
	}
}

class PlayerClassIndexD {
	public $№;
	public $Никнейм;
	public $Фраги;
	public $Смерти;
	public $Уровень;
	public $Клан;
	public $Отметка_времени;

	public function __construct($num, $nick, $frags, $deaths, $level, $clan_title, $time) {
		$this->№ = $num;
		$this->Никнейм = $nick;
		$this->Фраги = $frags;
		$this->Смерти = $deaths;
		$this->Уровень = $level;
		$this->Клан = $clan_title;
		$this->Отметка_времени = $time;
	}
}

class BigPlayer {
	public $rows = array();
	public $cuts = array();
	public function __construct($rows) {
		$this->rows = $rows;
	}
	public function Cut() {
		$cuts = array();
		$clan_id_p = -1;
		foreach ($this->rows as $row) {
			// print_r($row);
			if (($clan_id_p == -1) || ($row->clan_id == $clan_id_p)) {
				array_push($cuts, $row);
			} else {
				array_push($this->cuts, new Cut($cuts, $clan_title, $nick, $level, $id));
				$this->cuts[count($this->cuts) - 1]->Count();
				$cuts = array();
			}
			$clan_id_p = $row->clan_id;
			$clan_title = $row->clan_title;
			$nick = $row->nick;
			$level = $row->level;
			$id = $row->clan_id;
		}
		array_push($this->cuts, new Cut($cuts, $clan_title, $nick, $level, $id));
		$this->cuts[count($this->cuts) - 1]->Count();
		$cuts = array();
	}
}

class Cut {
	public $rows = array();
	public $max_frags = 0;
	public $min_frags = 999999999999999;
	public $max_deaths = 0;
	public $min_deaths = 999999999999999;
	public $clan_id;
	public $clan_title;
	public $nick;
	public $level;
	public $time = array();

	public function __construct($rows, $clan, $nick, $level, $id) {
		$this->rows = $rows;
		$this->clan_title = $clan;
		$this->nick = $nick;
		$this->level = $level;
		$this->clan_id = $id;
	}

	public function Count() {
		foreach ($this->rows as $row) {
			if ($row->level > $this->level) {
				$this->level = $row->level;
			}
			if ($row->frags > $this->max_frags) {
				$this->max_frags = $row->frags;
			}
			if ($row->frags < $this->min_frags) {
				$this->min_frags = $row->frags;
			}
			if ($row->deaths > $this->max_deaths) {
				$this->max_deaths = $row->deaths;
			}
			if ($row->deaths < $this->min_deaths) {
				$this->min_deaths = $row->deaths;
			}
			array_push($this->time, $row->timemark);
		}
	}
}

class PlayerClassEraReturn {
	public $№;
	public $Никнейм;
	public $Фраги;
	public $Смерти;
	public $Уровень;
	public $Клан;
	public $Фраги_в_эре;
	public $Смерти_в_эре;
	public $Учасия;
	public $Очки;
	public $ЛБЗ;

	public function __construct($num, $nick, $frags, $deaths, $level, $clan_title, $frags1, $deaths1, $playes, $points, $lbz) {
		$this->№ = $num;
		$this->Никнейм = $nick;
		$this->Фраги = $frags;
		$this->Смерти = $deaths;
		$this->Уровень = $level;
		$this->Клан = $clan_title;
		$this->Фраги_в_эре = $frags1;
		$this->Смерти_в_эре = $deaths1;
		$this->Учасия = $playes;
		$this->Очки = $points;
		$this->ЛБЗ = $lbz;

	}
}

class PlayerClassEraReturnD {
	public $№;
	public $Никнейм;
	public $Фраги;
	public $Смерти;
	public $Уровень;
	public $Клан;
	public $Фраги_в_эре;
	public $Смерти_в_эре;
	public $Учасия;
	public $Очки;
	public $ЛБЗ;
	public $Отметка_времени;

	public function __construct($num, $nick, $frags, $deaths, $level, $clan_title, $frags1, $deaths1, $playes, $points, $lbz, $time) {
		$this->№ = $num;
		$this->Никнейм = $nick;
		$this->Фраги = $frags;
		$this->Смерти = $deaths;
		$this->Уровень = $level;
		$this->Клан = $clan_title;
		$this->Фраги_в_эре = $frags1;
		$this->Смерти_в_эре = $deaths1;
		$this->Учасия = $playes;
		$this->Очки = $points;
		$this->ЛБЗ = $lbz;
		$this->Отметка_времени = $time;
	}
}
?>