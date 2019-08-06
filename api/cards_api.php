<?php
require '../classes/cards.php';
$dbconn = pg_pconnect("host=localhost dbname=berserk user=postgres password=")
    or die('Не удалось соединиться: ' . pg_last_error());

LoadCards($dbconn);

function LoadCards($dbconn){
  $cards=array();
  $query="Select * from Cards";
  $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      array_push($cards,new Card($line['id'],$line['name'],$line['proto']));
  }
  print_r($cards);
}
 ?>
