<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>APP Clanberserk - Статистика</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/jquery.js"></script>
		<script src="js/jquery-ui.js"></script>
    <script src="js/functions.js"></script>
		<link href="js/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  </head>

  <script  src="js/index.js"></script>
  <script>
  <?php
session_start();
// session_destroy();
$link = htmlentities($_SERVER['PHP_SELF']);
$links = explode("/", $link);
$res = "";
for ($i = 0; $i < count($links) - 1; $i++) {
	$res = $res . $links[$i] . "/";
}
?>
  var active;
  // document.addEventListener('keydown', function(event) {
  //   if (event.code == 'KeyJ' && (event.ctrlKey || event.metaKey)) {
	// 	    active=true;
	// 	    document.getElementById("dot").style.visibility="visible";
	// 	    console.log(document.getElementById("dot"));
  //   }
  // });
  function gotourl(url,extras) {
	   if (active==true){
		     window.open("<?php echo $res; ?>"+url+"?results=true&"+extras,"_self");
	   }
	   else{
		     window.open("<?php echo $res; ?>"+url+"?"+extras,"_self");
	   }
}
</script>
  <body>

    <div class="header sticky sticky--top js-header">
    	<div class="grid">
    		<nav class="navigation">
    			<!-- <ul class="navigation__list navigation__list--inline parent"> -->
          <ul class="navigation__list parent">
    				<li class="navigation__item child"><a class="element is-active" style="cursor: pointer;" onclick="gotourl('index.php')">Статистика</a></li>
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('era_res.php')" >Результаты Эр</a></li>
            <!-- <li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('era_res_clans.php')" >Результаты Эр по кланам</a></li> -->
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('timetable.php','Clan=171')" >Расписание</a></li>
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('history.php','Clan=171')">История</a></li>
    				<!-- <li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('cities.php','Clan=171')">Города</a></li> -->
    				<!-- <li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('clans.php')">Кланы</a></li> -->
            <li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('players_updates.php')">Изменения в игроках</a></li>
            <!-- <li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('clans_updates.php')">Изменения в кланах</a></li> -->
            <!-- <li class="navigation__item child">   |   </li> -->
            <!-- <li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('')">О проекте</a></li> -->
            <?php
if (($_SESSION['u'] != null) && ($_SESSION['p'] != null)) {
	echo "<li class=\"navigation__item child\"><a class=\"element\" style=\"cursor: pointer;color:red;\" onclick=\"gotourl('clans.php')\">{$_SESSION['u']}</a></li>";
	echo "<li class=\"navigation__item child\"><a class=\"element\" style=\"cursor: pointer;\" onclick=\"gotourl('clans.php')\">Выход</a></li>";
} else {
	// echo "<li class=\"navigation__item child\"><a class=\"element\" style=\"cursor: pointer;\" onclick=\"gotourl('htmltest.php?link=index.php')\">Вход</a></li>";
}
?>

          <input type="checkbox" class="color_text sp_input" id="switch" name="theme" /><text id="moon" class="color_text"></text>
          </ul>
    		</nav>
      </div>
      <script>
      var $$header = document.querySelector('.js-header');

        $(function() {
          function available(date) {
            dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
            if ($.inArray(dmy, availableDates) != -1) {
              return [true, "","Available"];
            } else {
              return [false,"","unAvailable"];
            }
          }
          $('#date').datepicker({ beforeShowDay: available });
        });
      </script>
      <div class="parent">
          <text class="color_text">Дата:</text><input type="text" class="color_text sp_input" name="date" id="date" size="12" value=""/>
          <text class="color_text">Эра:</text>
          <select class="color_text sp_input" id="era" name="era">
                <option value="-1"> --- </option>
          </select>
          <text class="color_text">Клан:</text>
          <select class="color_text sp_input" id="clans" name="Clans">
          </select>
          <!-- Игрок:
          <input id="player"> -->
          <br>
          <text class="color_text">Сортировка:</text>
          <select class="color_text sp_input" id="order" name="Order">
          </select>

          <select class="color_text sp_input" id="order_way" name="Order_way">
            <option selected value="desc"> По убыванию</option>
            <option value="asc"> По возростанию </option>
          </select>
          <input class="color_text sp_input" type="hidden" id="debug" name="debug" value="1"><text class="color_text"></text>
      </div>
      <hr>
      <div class="parent">
        <p id="showData2"></p>
      </div>
    </div>
    <p id="showData"></p>
  </body>
  <script>

    $(document).ready(function(){
      $('#order').on("change", order);
      $('#order').on("change", data);
      $('#order_way').on("change", data);
      $('#era').on("input", get_eras_data);
      $('#era').on("change", order);
      $('#era').on("change", data);
      $('#clans').on("change", data);
      $('#debug').on("change", data);
      $('#date').on("change", data);


    });

  get_eras_data(clans);
  eras();
  order();
  data();
  // usleep(1000);
  // clans();

function getDates(startDate, endDate) {
      var dates = [],
          currentDate = startDate,
          addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
          };
      while (currentDate <= endDate) {
        // console.log("here");
        dates.push(currentDate);
        currentDate = addDays.call(currentDate, 1);
      }
      return dates;
    };

   function get_eras_data (_callback) {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"era_dates", id: $('#era').val()},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            console.log(result[0].started);
            console.log(result[0].ended);
            var start = result[0].started.split("-");
            var end = result[0].ended.split("-");
            var start_date=new Date(start[0],start[1]-1,start[2]);
            var end_date=new Date(end[0],end[1]-1,end[2]);
            if ($('#era').val() != -1){
              end_date.setDate(end_date.getDate() + 1);
            }
            console.log(start_date);
            console.log(end_date);
            var dates = getDates(start_date,end_date );
            var string="";
            availableDates=[];
            dates.forEach(function(date) {
              availableDates.push(string.concat(date.getDate(),"-",date.getMonth()+1,"-",date.getFullYear()));
              console.log(string.concat(date.getDate(),"-",date.getMonth()+1,"-",date.getFullYear()));
            });
            document.getElementById('date').value=string.concat(end_date.getDate(),"/",end_date.getMonth()+1,"/",end_date.getFullYear());
            var tmp=end_date.getMonth()+1;
            document.getElementById('date').value=string.concat(("0" + tmp).slice(-2),"/",("0" + end_date.getDate()).slice(-2),"/",end_date.getFullYear());
            // func();

            // CreateTableFromJSON(result)
          }
        });
        _callback();
      }


    function eras () {
        console.log("Eras!");
        console.log($('#era').val());
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"eras"},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            var bcp=$('#era').val();
            var x = document.getElementById("era");
            var option = document.createElement("era");
            x.options.length = 0;
            var option = document.createElement("option");
            option.text = "---";
            option.value = -1;
            x.add(option);
            for (var i = 0; i < result.length; i++) {
              // console.log(result[i].title);
              // console.log(result[i].id);
              var option = document.createElement("option");
              option.text = result[i].id+" ("+result[i].started+" : "+result[i].ended+")";
              option.value = result[i].id;
              // console.log(option);
              x.add(option);
            }
            // var option = document.createElement("option");
            // option.text = "Нет клана";
            // option.value = -2;
            // x.add(option);
            if (bcp==null){
              document.getElementById("era").value=-1;
            }
            else{
              document.getElementById("era").value=bcp;
            }
          }
        });
      }

   function clans () {
        console.log("Clans!");
        console.log($('#date').val());
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"clans", datee: $('#date').val()},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            var bcp=$('#clans').val();
            var x = document.getElementById("clans");
            var option = document.createElement("clans");
            x.options.length = 0;
            var option = document.createElement("option");
            option.text = "Все кланы";
            option.value = -1;
            x.add(option);
            for (var i = 0; i < result.length; i++) {
              // console.log(result[i].title);
              // console.log(result[i].id);
              var option = document.createElement("option");
              option.text = result[i].title;
              option.value = result[i].id;
              // console.log(option);
              x.add(option);
            }
            var option = document.createElement("option");
            option.text = "Нет клана";
            option.value = -2;
            x.add(option);
            if (bcp==null){
              document.getElementById("clans").value=-1;
            }
            else{
              document.getElementById("clans").value=bcp;
            }
          }
        });
      }

      function order(){
        if ($('#era').val()==-1){
          var bcp=$('#order').val();
          console.log(bcp);
          var x = document.getElementById("order");
          x.options.length = 0;
          var option = document.createElement("option");
          option.text = "Никнейм";
          option.value = 'nick COLLATE "C"';
          x.add(option);
          var option = document.createElement("option");
          option.text = "Фраги";
          option.value = "frags";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Смерти";
          option.value = "deaths";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Уровень";
          option.value = "level";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Клан";
          option.value = "clan_id";
          x.add(option);
          if (bcp==null){
            document.getElementById("order").value="frags";
          }
          else{
            document.getElementById("order").value=bcp;
          }
          console.log("create");
          // create();
        }
        else{
          var bcp=$('#order').val();
          var x = document.getElementById("order");
          var option = document.createElement("option");
          x.options.length = 0;
          option.text = "Никнейм";
          option.value = 'nick COLLATE "C"';
          x.add(option);
          var option = document.createElement("option");
          option.text = "Фраги";
          option.value = "frags";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Смерти";
          option.value = "deaths";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Уровень";
          option.value = "level";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Клан";
          option.value = "clan_id";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Фраги в эре";
          option.value = "fragse";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Смерти в эре";
          option.value = "deathse";
          x.add(option);
          // var option = document.createElement("option");
          // option.text = "Содары";
          // option.value = "sodars";
          // x.add(option);
          var option = document.createElement("option");
          option.text = "Участия";
          option.value = "actions";
          x.add(option);
          var option = document.createElement("option");
          option.text = "Очки";
          option.value = "points";
          x.add(option);
          if (bcp==null){
            document.getElementById("order").value="frags";
          }
          else{
            document.getElementById("order").value=bcp;
          }
          // document.getElementById("order").value=bcp;
          console.log("create2");
          // create2();
        }
      }
      function data(){
        if ($('#era').val() == -1){
          create();
        }
        else{
          create2();
        }
      }
      function create () {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"index", datee: $('#date').val(),order: $('#order').val(),order_way: $('#order_way').val(),clan:$('#clans').val(),debug:document.getElementById("debug").checked,limit:20,offset:0},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            // console.log("!!!");
            // console.log($('#debug').val());
            CreateTableFromJSON(result)
          }
        });
      }
      function create2 () {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"index_era", datee: $('#date').val(),order: $('#order').val(),order_way: $('#order_way').val(), id: $('#era').val(),clan:$('#clans').val(),nickname:$('#player').val(),debug:document.getElementById("debug").checked},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            CreateTableFromJSON(result)
          }
        });
      }
    </script>
    <script src="js/script.js"></script>
</html>
