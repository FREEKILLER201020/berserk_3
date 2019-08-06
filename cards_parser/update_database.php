<?php
$file="all_cards.json";
$json=json_decode(file_get_contents($file), true);
// print_r($json);
$dbconn = pg_pconnect("host=localhost dbname=berserk user=postgres password=")
    or die('Не удалось соединиться: ' . pg_last_error());

$query = file_get_contents("../sql/create_cards.sql");
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());

$keys=array();
foreach ($json as $key => $value) {
    if (is_array($json)) {
        foreach ($value as $key2 => $value2) {
            if (!isset($keys[$key2])) {
                $keys[$key2]="";
            }
        }
    }
}

// Выполнение SQL-запроса
foreach ($json as $key => $value) {
    $query = "INSERT INTO cards_type (type) values ('{$value['type']}')";
    $result = pg_query($query);
    $query = "SELECT * from cards_type WHERE type='{$value['type']}'";
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $cards_type_id=$line['id'];
    }

    $query = "INSERT INTO cards_race (race) values ('{$value['race']}')";
    $result = pg_query($query);
    $query = "SELECT * from cards_race WHERE race='{$value['race']}'";
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $cards_race_id=$line['id'];
    }

    $query = "INSERT INTO cards_rarity (rarity) values ('{$value['rarity']}')";
    $result = pg_query($query);
    $query = "SELECT * from cards_rarity WHERE rarity='{$value['rarity']}'";
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $cards_rarity_id=$line['id'];
    }

    $query = "INSERT INTO cards_crystal (crystal) values ('{$value['crystal']}')";
    $result = pg_query($query);
    $query = "SELECT * from cards_crystal WHERE crystal='{$value['crystal']}'";
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $cards_crystal_id=$line['id'];
    }

    $query = "INSERT INTO cards_typeEquipment (typeEquipment) values ('{$value['typeEquipment']}')";
    $result = pg_query($query);
    $query = "SELECT * from cards_typeEquipment WHERE typeEquipment='{$value['typeEquipment']}'";
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $cards_typeEquipment_id=$line['id'];
    }

    $query = "INSERT INTO cards_hate_class (hate_class) values ('{$value['hate_class']}')";
    $result = pg_query($query);
    $query = "SELECT * from cards_hate_class WHERE hate_class='{$value['hate_class']}'";
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $cards_hate_class_id=$line['id'];
    }

    $query = "INSERT INTO cards_hate_race (hate_race) values ('{$value['hate_race']}')";
    $result = pg_query($query);
    $query = "SELECT * from cards_hate_race WHERE hate_race='{$value['hate_race']}'";
    $result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $cards_hate_race_id=$line['id'];
    }

    $value['kick']=json_encode($value['kick'], JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    $value['attribute']=json_encode($value['attribute'], JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    $value['rows']=json_encode($value['rows'], JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    $value['case']=json_encode($value['case'], JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    $value['horde']=json_encode($value['horde'], JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    $value['rangeAttack']=json_encode($value['rangeAttack'], JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
    $value['classes']=json_encode($value['classes'], JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);



    foreach ($keys as $key => $value2) {
        if ($value[$key]=="") {
            $value[$key]="null";
        }
    }

    $fly=(boolval($value['fly']) ? 'true' : 'false');
    $query = "INSERT INTO cards (type,health,kick,steps,race,name,proto,rarity,fly,\"desc\",crystal,\"crystalCount\",abilities,f,rows,\"case\",horde,rangeAttack,classes,series,typeEquipment,hate_class,hate_race ,\"only\",unlim,number,author)
  values({$cards_type_id},
    {$value['health']},
    '{$value['kick']}',
    {$value['steps']},
    {$cards_race_id},
    '{$value['name']}',
    '{$value['proto']}',
    {$cards_rarity_id},
    {$fly},
    '{$value['desc']}',
    {$cards_crystal_id},
    {$value['crystalCount']},
    '{$value['attribute']}',
    {$value['f']},
    '{$value['rows']}',
    '{$value['case']}',
    '{$value['horde']}',
    '{$value['rangeAttack']}',
    '{$value['classes']}',
    {$value['series']},
    {$cards_typeEquipment_id},
    {$cards_hate_class_id},
    {$cards_hate_race_id},
    {$value['only']},
    {$value['unlim']},
    {$value['number']},
    '{$value['author']}'
  )";
    // $query=pg_escape_string($query);
    echo $query.PHP_EOL;
    $result = pg_query($query);
}

// Очистка результата
pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
