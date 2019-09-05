<?php
class Notification {
public user_id;
public user_key;
public user_clan;
public types=array();

	public function __construct($id,$key,$clan,$types) {
		$this->user_id=$id;
		$this->user_key=$key;
		$this->user_clan=$clan;
		$this->types=json_decode($types,ture);
}
}
?>