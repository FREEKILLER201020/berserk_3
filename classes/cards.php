<?php
class Card {

    public $id;
    public $type;
    public $health;
    public $kick;
    public $steps;
    public $race;
    public $name;
    public $proto;
    public $rarity;
    public $fly;
    public $desc;
    public $crystal;
    public $crystalCount;
    public $abilities;
    public $f;
    public $rows;
    public $case;
    public $horde;
    public $rangeAttack;
    public $classes;
    public $series;
    public $typeEquipment;
    public $hate_class;
    public $hate_race;
    public $only;
    public $unlim;
    public $number;
    public $author;

		public function __construct($id,$name,$proto) {
			$this->id=$id;
      $this->name=$name;
      $this->proto=$proto;
		}
	}
?>
