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



$(document).ready(function(){
      $('#clans').on("change", make_players);
      // $('#uploadedFile').on("change", upload_test);

      //Change id to your id
    });

  create();
  // usleep(1000);
  clans();

      function clans () {
        console.log("Clans!");
        console.log($('#date').val());
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"clans", datee: "<?php echo date('m/d/Y'); ?>"},
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

      function create () {
        console.log("loading...");
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"decks_all",id:$('#player_id').val()},
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

      function set_session (key,val) {
        console.log("session set");
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"set_session",key:key,val:val},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            // console.log("!!!");
            // console.log($('#debug').val());
            // CreateTableFromJSON(result);
            // LoadDeck(result[1]["id"]);
          }
        });
      }

      // function LoadDeck (id) {
      //   $.ajax({
      //     url:"api/api.php", //the page containing php script
      //     type: "post", //request type,
      //     dataType: 'json',
      //     data: {type:"deck",deck_id:id},
      //     async: false, // HERE
      //     success:function(result){
      //       // document.getElementById("id1").remove();
      //       // console.log(data);
      //       console.log(result);
      //       // console.log("!!!");
      //       // console.log($('#debug').val());
      //       CreateTableFromJSON(result)
      //     }
      //   });
      // }

      function upload_test () {
        // console.log($('#descr').val());
        // console.log($('#uploadedFile')[0].file[0]["name"]);
        // return;
        // if (($('#descr').val()=="") && (!$('#uploadedFile')[0].file[0]["name"])){
        //   alert("Добавте файл или описание!");
        // }
            var formData = new FormData();
formData.append('file', $('#uploadedFile')[0].files[0]);
formData.append('type', 'upload');
formData.append('player_id', $('#player_id').val());
formData.append('descr', $('#descr').val());

        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: formData,
          processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            // console.log("!!!");
            // console.log($('#debug').val());
            // CreateTableFromJSON(result)
          }

        });
            create();
      }








    var data_fill=[];
    var idds=[];
    make_players();
    function make_players(){
    data_fill=[];
    idds=[];
    console.log("Players");
    $.ajax({
      url:"api/api.php", //the page containing php script
      type: "post", //request type,
      dataType: 'json',
      data: {type:"players",clan_id:$('#clans').val()},
      async: false, // HERE
      success:function(result){
        // document.getElementById("id1").remove();
        // console.log(data);
        console.log(result);
        result.forEach(function(res) {
          data_fill.push(res.nick);
          idds.push(res.id);
        });
      }
    });
    $( function() {
    // $( "#player" ).val("");
    // $( "#player" ).val("<?php if ($_POST['player'] != -1) {echo $_POST['player'];}?>");
    // $( "#player_id" ).val("");
    $( "#player" ).autocomplete({
      source: data_fill,
      close: function( event, ui ) {player_check();}
    });
  } );
    $('#player').val("");
    player_check();
  }

  $(document).ready(function(){
    $('#player').on("change", player_check);
  });
  $(document).ready(function(){
    $('#player').on("input", player_check);
  });

    function player_check(){
      // $('#player_id').val(-1);
      console.log(data_fill,$('#player').val());
      console.log(data_fill.includes($('#player').val()));
      var divContainer = document.getElementById("showData");
      divContainer.innerHTML = "";
            var divContainer = document.getElementById("showData2");
      divContainer.innerHTML = "";
      if (data_fill.includes($('#player').val())){
        // sessionStorage.setItem("player_id",idds[data_fill.indexOf($('#player').val())]);
        $('#player_id').val(idds[data_fill.indexOf($('#player').val())]);
        // $('#table').show();
        $('#player_row').text($('#player').val());
        console.log($('#player_row'));
              set_session("player_id",idds[data_fill.indexOf($('#player').val())]);
        create();
      }
    }

PlayerData();
          function PlayerData () {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"player",player_id:<?php if ($_SESSION['player_id'] > 0) {echo $_SESSION['player_id'];} else {
	echo -1;
}
?>},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            // console.log("!!!");
            // console.log($('#debug').val());
            $('#player').val(result[0]['nick']);
            // $('#player_id').val(result[0]['id']);
            // ClanData(result[0]['clan_id'])
            player_check();
            <?php $_SESSION['player_id'] = -1;?>
          }
        });
      }
    console.log($( "#player" ).val());











    </script>
    <script src="js/script.js"></script>
</html>
