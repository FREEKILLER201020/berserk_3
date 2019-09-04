<?php
class Timer {
	public $saved;

	public function Save() {
		$this->saved = microtime(true) * 10000;
	}
	public function Revil() {
		$tmp = microtime(true) * 10000 - $this->saved;
		echo PHP_EOL . "Time spend: " . $tmp . PHP_EOL;
		$this->saved = microtime(true) * 10000;
	}
}
?>