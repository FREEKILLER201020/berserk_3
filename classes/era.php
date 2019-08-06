<?php
class EraClass {
	public $id;
	public $started;
	public $ended;
	public $lbz;
	public $points;

	public function __construct($id, $start, $end, $lbz, $points) {
		$this->id = $id;
		$this->started = $start;
		$this->ended = $end;
		$this->lbz = $lbz;
		$this->points = $points;
	}
}
?>