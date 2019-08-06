<?php

class City
{
    public $id;
    public $name;
    public $clan;

    public function __construct($i,$n,$c)
    {
      $this->id=$i;
      $this->name=$n;
      $this->clan=$c;
    }
}

class City_web
{
    public $Название;
    public $Клан;

    public function __construct($n,$c)
    {
      $this->Название=$n;
      $this->Клан=$c;
    }
}

class Card
{
  public $id;
  public $name;
  public $type;
  public $file;
  public function __construct($i,$n,$t,$f)
  {
    $this->id=$i;
    $this->name=$n;
    $this->type=$t;
    $this->file=$f;
  }
}

class Card_web
{
  public $name;
  public $file;
  public function __construct($n,$f)
  {
    $this->name=$n;
    $this->file=$f;
  }
}

class Fight_class
{
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
    public $was=0;

    public function __construct($a, $d, $f, $t, $de, $r, $w,$end)
    {
        $this->attacker_id=$a;
        $this->defender_id=$d;
        $this->from=$f;
        $this->to=$t;
        $this->declared=$de;
        $this->resolved=$r;
        $this->winer=$w;
        $this->ended=$end;
    }
}
class Fight_class_web
{
    // public $id;
    public $№; // id атакующего клана
    public $Атакует; // id защищающегося клана
    public $Город1; // id защищающегося клана
    public $Защищается;
    public $Город2;
    public $Начало_боя; // время, когда был объявлен бой
    public $Конец_боя; // вермя, когда состаится бой
    public $Победитель; // вермя, когда состаится бой


    public function __construct($n,$a, $d, $f, $t, $r,$end, $w)
    {
      $this->№=$n; // id атакующего клана
$this->Атакует=$a; // id защищающегося клана
$this->Город1=$f; // id защищающегося клана
$this->Защищается=$d;
$this->Город2=$t;
$this->Начало_боя=$r; // время, когда был объявлен бой
$this->Конец_боя=$end; // вермя, когда состаится бой
$this->Победитель=$w; // вермя, когда состаится бой

    }
}

class Era_class
{
    public $id;
    public $started;
    public $ended;
    public $lbz;
    public $points;

    public function __construct($id, $start, $end, $lbz, $points)
    {
        $this->id=$id;
        $this->started=$start;
        $this->ended=$end;
        $this->lbz=$lbz;
        $this->points=$points;
    }
}

class Clan_class
{
    public $id;
    public $title;
    public $points;

    public function __construct($id, $title,$points)
    {
        $this->id=$id;
        $this->title=$title;
        $this->points=$points;
    }
}

class Clan_class_test
{
    public $id;
    public $title;
    public $points;
    public $was;

    public function __construct($id, $title,$points,$was)
    {
        $this->id=$id;
        $this->title=$title;
        $this->points=$points;
        $this->was=$was;
    }
}

class Player_class
{
    public $timemark;
    public $id;
    public $nick;
    public $frags;
    public $deaths;
    public $level;
    public $clan_id;
    public $clan_title;
    public $was=0;

    public function __construct($timemark,$id, $nick, $frags, $deaths, $level, $clan_id, $clan_title)
    {
        $this->timemark=$timemark;
        $this->id=$id;
        $this->nick=$nick;
        $this->frags=$frags;
        $this->deaths=$deaths;
        $this->level=$level;
        $this->clan_id=$clan_id;
        $this->clan_title=$clan_title;
    }
}


class Player_class_era
{
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

    public function __construct( $timemark,$nick, $frags, $deaths, $level,$clan_id, $clan_title,$f,$d,$g,$p,$l)
    {
        $this->timemark=$timemark;
        $this->nick=$nick;
        $this->frags=$frags;
        $this->deaths=$deaths;
        $this->level=$level;
        $this->clan_id=$clan_id;
        $this->clan_title=$clan_title;
        $this->frags_era=$f;
        $this->deaths_era=$d;
        $this->games=$g;
        $this->points=$p;
        $this->lbz=$l;
    }
}

class Player_class_index
{
    public $№;
    public $Никнейм;
    public $Фраги;
    public $Смерти;
    public $Уровень;
    public $Клан;

    public function __construct($num, $nick, $frags, $deaths, $level, $clan_title)
    {
        $this->№=$num;
        $this->Никнейм=$nick;
        $this->Фраги=$frags;
        $this->Смерти=$deaths;
        $this->Уровень=$level;
        $this->Клан=$clan_title;
    }
}

class Player_class_index_d
{
    public $№;
    public $Никнейм;
    public $Фраги;
    public $Смерти;
    public $Уровень;
    public $Клан;
    public $Отметка_времени;

    public function __construct($num, $nick, $frags, $deaths, $level, $clan_title,$time)
    {
        $this->№=$num;
        $this->Никнейм=$nick;
        $this->Фраги=$frags;
        $this->Смерти=$deaths;
        $this->Уровень=$level;
        $this->Клан=$clan_title;
        $this->Отметка_времени=$time;
    }
}

class Big_player
{
  public $rows=array();
  public $cuts=array();
  public function __construct($rows)
  {
      $this->rows=$rows;
  }
  public function Cut(){
    $cuts=array();
    $clan_id_p=-1;
    foreach ($this->rows as $row) {
      // print_r($row);
      if (($clan_id_p==-1) || ($row->clan_id==$clan_id_p)){
        array_push($cuts, $row);
      }
      else{
        array_push($this->cuts, new Cut($cuts,$clan_title,$nick,$level,$id));
        $this->cuts[count($this->cuts)-1]->Count();
        $cuts=array();
      }
      $clan_id_p=$row->clan_id;
      $clan_title=$row->clan_title;
      $nick=$row->nick;
      $level=$row->level;
      $id=$row->clan_id;
    }
    array_push($this->cuts, new Cut($cuts,$clan_title,$nick,$level,$id));
    $this->cuts[count($this->cuts)-1]->Count();
    $cuts=array();
  }
}

class Cut{
  public $rows=array();
  public $max_frags=0;
  public $min_frags=999999999999999;
  public $max_deaths=0;
  public $min_deaths=999999999999999;
  public $clan_id;
  public $clan_title;
  public $nick;
  public $level;
  public $time=array();

  public function __construct($rows,$clan,$nick,$level,$id)
  {
      $this->rows=$rows;
      $this->clan_title=$clan;
      $this->nick=$nick;
      $this->level=$level;
      $this->clan_id=$id;
  }

  public function Count(){
    foreach ($this->rows as $row) {
      if ($row->level>$this->level){
        $this->level=$row->level;
      }
      if ($row->frags>$this->max_frags){
        $this->max_frags=$row->frags;
      }
      if ($row->frags<$this->min_frags){
        $this->min_frags=$row->frags;
      }
      if ($row->deaths>$this->max_deaths){
        $this->max_deaths=$row->deaths;
      }
      if ($row->deaths<$this->min_deaths){
        $this->min_deaths=$row->deaths;
      }
      array_push($this->time,$row->timemark);
    }
  }
}

class Player_class_era_return
{
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


    public function __construct($num, $nick, $frags, $deaths, $level, $clan_title,$frags1,$deaths1,$playes,$points,$lbz)
    {
        $this->№=$num;
        $this->Никнейм=$nick;
        $this->Фраги=$frags;
        $this->Смерти=$deaths;
        $this->Уровень=$level;
        $this->Клан=$clan_title;
        $this->Фраги_в_эре=$frags1;
        $this->Смерти_в_эре=$deaths1;
        $this->Учасия=$playes;
        $this->Очки=$points;
        $this->ЛБЗ=$lbz;

    }
}

class Player_class_era_return_d
{
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


    public function __construct($num, $nick, $frags, $deaths, $level, $clan_title,$frags1,$deaths1,$playes,$points,$lbz,$time)
    {
        $this->№=$num;
        $this->Никнейм=$nick;
        $this->Фраги=$frags;
        $this->Смерти=$deaths;
        $this->Уровень=$level;
        $this->Клан=$clan_title;
        $this->Фраги_в_эре=$frags1;
        $this->Смерти_в_эре=$deaths1;
        $this->Учасия=$playes;
        $this->Очки=$points;
        $this->ЛБЗ=$lbz;
        $this->Отметка_времени=$time;
    }
}


class Colors {
		private $foreground_colors = array();
		private $background_colors = array();

		public function __construct() {
			// Set up shell colors
			$this->foreground_colors['black'] = '0;30';
			$this->foreground_colors['dark_gray'] = '1;30';
			$this->foreground_colors['blue'] = '0;34';
			$this->foreground_colors['light_blue'] = '1;34';
			$this->foreground_colors['green'] = '0;32';
			$this->foreground_colors['light_green'] = '1;32';
			$this->foreground_colors['cyan'] = '0;36';
			$this->foreground_colors['light_cyan'] = '1;36';
			$this->foreground_colors['red'] = '0;31';
			$this->foreground_colors['light_red'] = '1;31';
			$this->foreground_colors['purple'] = '0;35';
			$this->foreground_colors['light_purple'] = '1;35';
			$this->foreground_colors['brown'] = '0;33';
			$this->foreground_colors['yellow'] = '1;33';
			$this->foreground_colors['light_gray'] = '0;37';
			$this->foreground_colors['white'] = '1;37';

			$this->background_colors['black'] = '40';
			$this->background_colors['red'] = '41';
			$this->background_colors['green'] = '42';
			$this->background_colors['yellow'] = '43';
			$this->background_colors['blue'] = '44';
			$this->background_colors['magenta'] = '45';
			$this->background_colors['cyan'] = '46';
			$this->background_colors['light_gray'] = '47';
		}

		// Returns colored string
		public function getColoredString($string, $foreground_color = null, $background_color = null) {
			$colored_string = "";

			// Check if given foreground color found
			if (isset($this->foreground_colors[$foreground_color])) {
				$colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
			}
			// Check if given background color found
			if (isset($this->background_colors[$background_color])) {
				$colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
			}

			// Add string and end coloring
			$colored_string .=  $string . "\033[0m";

			return $colored_string;
		}

		// Returns all foreground color names
		public function getForegroundColors() {
			return array_keys($this->foreground_colors);
		}

		// Returns all background color names
		public function getBackgroundColors() {
			return array_keys($this->background_colors);
		}
	}
