<?php
class FightClass {
	// public $id;
	public $attacker_id; // id атакующего клана
	public $defender_id; // id защищающегося клана
	public $from;
	public $to;
	public $declared; // время, когда был объявлен бой
	public $resolved; // вермя, когда состаится бой
	public $ended; // вермя, когда состаится бой

	// public $in_progress; // флаг, активен ли бой
	public $winer;
	public $was = 0;

	public function __construct($a, $d, $f, $t, $de, $r, $w, $end) {
		$this->attacker_id = $a;
		$this->defender_id = $d;
		$this->from = $f;
		$this->to = $t;
		$this->declared = $de;
		$this->resolved = $r;
		$this->winer = $w;
		$this->ended = $end;
	}
}

class FightClassMerge {
	// public $id;
	public $attacker_id; // id атакующего клана
	public $defender_id; // id защищающегося клана
	public $from;
	public $to;
	public $declared; // время, когда был объявлен бой
	public $resolved; // вермя, когда состаится бой
	public $ended; // вермя, когда состаится бой
	public $winer;
	public $folder;
	public $was = 0;

	public function __construct($a, $d, $f, $t, $de, $r, $end, $winer, $folder) {
		$this->attacker_id = $a;
		$this->defender_id = $d;
		$this->from = $f;
		$this->to = $t;
		$this->declared = $de;
		$this->resolved = $r;
		$this->ended = $end;
		$this->winer = $winer;
		$this->folder = $folder;
	}
}

class FightClassWeb {
	// public $id;
	public $№; // id атакующего клана
	public $Атакует; // id защищающегося клана
	public $Город1; // id защищающегося клана
	public $Защищается;
	public $Город2;
	public $Начало_боя; // время, когда был объявлен бой
	public $Конец_боя; // вермя, когда состаится бой
	public $Победитель; // вермя, когда состаится бой

	public function __construct($n, $a, $d, $f, $t, $r, $end, $w) {
		$this->№ = $n; // id атакующего клана
		$this->Атакует = $a; // id защищающегося клана
		$this->Город1 = $f; // id защищающегося клана
		$this->Защищается = $d;
		$this->Город2 = $t;
		$this->Начало_боя = $r; // время, когда был объявлен бой
		$this->Конец_боя = $end; // вермя, когда состаится бой
		$this->Победитель = $w; // вермя, когда состаится бой

	}
}
?>