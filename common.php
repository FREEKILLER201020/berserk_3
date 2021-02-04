<?php

function Head() {
	echo '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./favicon.ico">
    <title>APP Clanberserk - Статистика</title>
    <!-- Bootstrap grid CSS -->
    <link href="css/bootstrap-grid.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/main.css" rel="stylesheet">

    <link rel="stylesheet" href="css/snack.css">
  	<link rel="stylesheet" href="css/three-dots.css">
  	<link href="js/jquery-ui.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap;subset=cyrillic" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-xl navbar-light sticky-top">
        <div class="container">
            <div class="navbar-top">
                <div class="navbar-header text-sm-left text-center">
                    <a class="navbar-brand" href="#">
                        <img src="img/bersy.png" class="">
                    </a>
                    <p class="d-sm-inline-flex text-wrap">Clanberserk - Статистика</p>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <!-- <span class="navbar-toggler-icon"></span> -->
                        <!-- <objec height="36px" src="img/square.svg"></object> -->
                        <img src="img/square.svg" width="36" height="36">
                    </button>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="col-md-12">
                        <ul class="nav navbar-nav justify-content-between">
                            <li><a href="index.php">Статистика</a></li>
                            <li><a href="era_res.php">Результаты Эр</a></li>
                            <li><a href="timetable.php">Расписание</a></li>
                            <li><a href="history.php">История</a></li>
                            <li><a href="players_updates.php">Изменения в игроках</a></li>
                        </ul>
                    </div>
                </div>
                <div id="navbar" class="navbar-collapse collapse justify-content-center settings">
	';
}

function Dates($h) {
	if ($h == 1) {
		echo '
		<text class=""> Дата: </text><input type="text" class=" " name="date" id="date" size="12" value="" />
	';
	} else {
		echo '
		<text class=""> Дата: </text><input type="hidden" class=" " name="date" id="date" size="12" value="" />
	';
	}
}

function Eras() {
	echo '
		<text class=""> Эра: </text>
		<select class=" " id="era" name="era">
		<option value="-1"> --- </option>
		</select>
		<text class=""> Клан: </text>
		<select class=" " id="clans" name="Clans">
		</select>
	';
}

function Sorter() {
	echo '
		<text class=""> Сортировка: </text>
		<select class=" " id="order" name="Order">
		</select>
		<select class=" " id="order_way" name="Order_way">
		<option selected value="desc"> По убыванию </option>
		<option value="asc"> По возростанию </option>
		</select>
	';
}

function EndHead() {
	echo '
		<input class=" " type="hidden" id="debug" name="debug" value="1"><text class=""></text>
                </div>
            </div>
        </div>
    </nav>
    <div id="dot" class="snippet" data-title=".dot-flashing">
    	<div class="stage">
        	<div class="dot-flashing"></div>
    	</div>
	</div>
	';
}

?>