<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>APP Clanberserk - test</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/functions.js"></script>
		<script src="js/jquery-ui.js"></script>
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
    				<!-- <li class="navigation__item child"><a class="element is-active" style="cursor: pointer;" onclick="gotourl('index.php')">Статистика</a></li>
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('era_res.php')" >Результаты Эр</a></li>
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('timetable.php','Clan=171')" >Расписание</a></li>
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('history.php','Clan=171')">История</a></li>
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('cities.php','Clan=171')">Города</a></li>
    				<li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('clans.php')">Кланы</a></li> -->
            <!-- <li class="navigation__item child">   |   </li> -->
            <!-- <li class="navigation__item child"><a class="element" style="cursor: pointer;" onclick="gotourl('')">О проекте</a></li> -->
            <?php
// if (($_SESSION['u'] != null) && ($_SESSION['p'] != null)) {
// 	echo "<li class=\"navigation__item child\"><a class=\"element\" style=\"cursor: pointer;color:red;\" onclick=\"gotourl('clans.php')\">{$_SESSION['u']}</a></li>";
// 	echo "<li class=\"navigation__item child\"><a class=\"element\" style=\"cursor: pointer;\" onclick=\"gotourl('clans.php')\">Выход</a></li>";
// } else {
// 	echo "<li class=\"navigation__item child\"><a class=\"element\" style=\"cursor: pointer;\" onclick=\"gotourl('htmltest.php?link=index.php')\">Вход</a></li>";
// }
;?>
          <br>
          <text class="color_text">Клан:</text>
          <select class="color_text sp_input" id="clans" name="Clans">
          </select>
          <text class="color_text">Игрок:</text>
          <input class="color_text sp_input" data-lpignore="true" id="player" name="player" value="">
          <input class="color_text sp_input" data-lpignore="true" id="player_id" name="player_id" style="display:none" value="">
          <input type="checkbox" class="color_text sp_input" id="switch" name="theme" /><text id="moon" class="color_text"></text>
          <!-- Игрок:
          <input id="player"> -->
          <!-- <br> -->
          <!-- <text class="color_text">Сортировка:</text> -->
          <!-- <select class="color_text sp_input" id="order" name="Order"> -->
          <!-- </select> -->

          <!-- <select class="color_text sp_input" id="order_way" name="Order_way"> -->
            <!-- <option selected value="desc"> По убыванию</option> -->
<!-- <option value="desc"> По убыванию </option> -->
<!-- <option selected value="asc"> По возростанию </option> -->
            <!-- <option value="asc"> По возростанию </option> -->
          <!-- </select> -->
          <input class="color_text sp_input" type="hidden" id="debug" name="debug" value="1"><text class="color_text"></text>


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
        <hr>
          <span class="color_text sp_input">Upload a File:</span>
          <input class="color_text sp_input" type="file" id="uploadedFile" name="uploadedFile" />
        <input class="color_text sp_input" type="hidden" name="back" value="../decks.php" />
          <!-- <span class="color_text sp_input">and / or</span> -->
          <span class="color_text sp_input">Description:</span>
        <input class="color_text sp_input" type="textfield" id="descr" name="descr" value="" />
        <!-- <input class="color_text sp_input" type="submit" name="type" value="upload" /> -->
        <button onclick="upload_test()">Upload</button>
      </div>
      <div class="parent">
        <p id="showData2"></p>
      </div>
    </div>
    <p id="showData"></p>
  </body>
  <script>



// $(document).ready(function(){
//       $('#clans').on("change", make_players);
//       // $('#uploadedFile').on("change", upload_test);

//       //Change id to your id
//     });

  create();
  // usleep(1000);
  // clans();

      function create () {
        console.log("loading...");
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"decks_full"},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            // console.log("!!!");
            // console.log($('#debug').val());
            if (isEmpty(result)){
              console.log("HERE!");
              result[0]={"Data":"Нет данных"};
            }
            console.log(result);
              CreateTableFromJSON(result);
            // LoadDeck(result[1]["id"]);
          }
        });
      }

      function isEmpty(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}



    </script>
    <script src="js/script.js"></script>
</html>
