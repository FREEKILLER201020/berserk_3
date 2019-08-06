<?php
// Соединение, выбор базы данных
$dbconn = pg_connect("host=localhost dbname=berserk user=postgres password=")
    or die('Не удалось соединиться: ' . pg_last_error());

// Выполнение SQL-запроса
$query = file_get_contents("sql/create.sql");
$result = pg_query($query) or die('Ошибка запроса: ' . pg_last_error());


// Очистка результата
pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
?>
