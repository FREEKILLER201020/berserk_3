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
            <?php

$_SESSION['player_id'] = $_POST['player_id'];
?>
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
          <text class="color_text" id="clan_field"></text>
          <text class="color_text">Игрок:</text>
          <text class="color_text" id="nick_field"></text>
                    <input type="checkbox" class="color_text sp_input" id="switch" name="theme" /><text id="moon" class="color_text"></text>
           <br>
          <text class="color_text">Скриншот:</text>
		<p id="screen" ></p>
           <!-- <br> -->
           <text class="color_text">Описание:</text>
          <text class="color_text" id="descr_field"></text>
          <br>
			<text class="color_text">Тип:</text>
          <text class="color_text" id="type"></text>
		<form method='POST' action='decks.php' enctype='multipart/form-data'>
          	<!-- <input type='hidden' name='id' value='$this->id' /> -->
          	<input type='hidden' id='player' name='player' value='' />
          	<input class='color_text sp_input' type='submit' name='decks'value='Назад' />
          </form>
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
		<!-- <br> -->
      <hr>
		<text class="color_text">Добавить карту: </text>
	    <input class="color_text sp_input" data-lpignore="true" id="card" name="card" value="">
		<input class="color_text sp_input" data-lpignore="true" id="card_id" name="card_id" style="display:none" value="">
		<text class="color_text">IMG: </text>
		<img id="image" src="" alt="" width="150" height="150">
		<button onclick="add_card()">Upload</button>
		<button onclick="save_cards()">Save</button>
		<!-- <p id="showData2"></p> -->
	</div>
    </div>
	    <p id="showData"></p>

	<!-- <input class="color_text sp_input" type="checkbox" id="debug" name="debug" value="1"><text class="color_text"> -->
  </body>
  <script>

  	$('img').each(function(){
    $(this).click(function(){
        $(this).width($(this).width()+$(this).width())
    });
});
  	var deck_loaded;
  	cards_array=[];
  	var index=0;
  	LoadCards();
  	LoadDeck();
  	PlayerData();
  	function save_cards(){
  		 $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"save_deck",deck_id:<?php echo $_POST['id']; ?>,cards:JSON.stringify(cards_array)},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            if (result["ok"]==1){
            	alert("Saved!");
            }
            // console.log("!!!");
            // console.log($('#debug').val());
            // CreateTableFromJSON(result)
          }
        });
  	}
  	function remove_card(id){
  		cards_array.splice(id,1);
  		index--;
  		var divContainer = document.getElementById("showData");
        divContainer.innerHTML = "";
        divContainer.appendChild(CreateTableFromJSON2(cards_array));
		// CreateTableFromJSON(cards_array);
  	}
	function add_card2(id,name,path){
  		console.log(id,name,path);
  		cards_array.push({"id":id,"name":name,"path":"<img src='"+path+"' alt='"+name+"' width='150' height='150'>","button":"<button onclick='remove_card("+index+")'>Remove</button>"});
  		index++;
  		console.log(cards_array);
  		var divContainer = document.getElementById("showData");
        divContainer.innerHTML = "";
        divContainer.appendChild(CreateTableFromJSON2(cards_array));
  		// deck_loaded[0]["cards"]=CreateTableFromJSON2(cards_array).outerHTML;
		// CreateTableFromJSON(deck_loaded);
  	}
  	function add_card(){
  		var id=$('#card_id').val();
  		var name=$('#card').val();
  		var path=$('#image').attr("src");
  		console.log(id,name,path);
  		cards_array.push({"id":id,"name":name,"path":"<img src='"+path+"' alt='"+name+"' width='150' height='150'>","button":"<button onclick='remove_card("+index+")'>Remove</button>"});
  		index++;
  		console.log(cards_array);
  		var divContainer = document.getElementById("showData");
        divContainer.innerHTML = "";
        divContainer.appendChild(CreateTableFromJSON2(cards_array));
  		// deck_loaded[0]["cards"]=CreateTableFromJSON2(cards_array).outerHTML;
		// CreateTableFromJSON(deck_loaded);
  	}

  	  function LoadCards () {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"load_cards",deck_id:<?php echo $_POST['id']; ?>},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
             for (var i = 0; i < result.length; i++) {
             	// console.log(result[i]["id"],result[i]["name"],result[i]["id"]);
             	add_card2(result[i]["id"],result[i]["name"],"cards_parser/cards/info/"+result[i]["proto"]+".jpg");
             }
            // deck_loaded=result;
            // console.log("!!!");
            // console.log($('#debug').val());
            // CreateTableFromJSON(result);
          }
        });
      }

       function LoadDeck () {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"deck",deck_id:<?php echo $_POST['id']; ?>},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result[0]);
            var divContainer = document.getElementById("screen");
            console.log(divContainer);
        divContainer.innerHTML = result[0]["screenshot_id"];
        // divContainer.appendChild(result[0]["screenshot_id"]);
            $('#descr_field').text(result[0]["description"]);
            $('#type').text(result[0]["deck_type"]);

            // deck_loaded=result;
            // console.log("!!!");
            // console.log($('#debug').val());
            // CreateTableFromJSON(result);
          }
        });
      }

      function PlayerData () {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"player",player_id:<?php echo $_POST['player_id']; ?>},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            // console.log("!!!");
            // console.log($('#debug').val());
            $('#nick_field').text(result[0]['nick']);
            $('#player').val(result[0]['nick']);
            ClanData(result[0]['clan_id'])
          }
        });
      }

            function ClanData (id) {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {type:"clan",clan_id:id},
          async: false, // HERE
          success:function(result){
            // document.getElementById("id1").remove();
            // console.log(data);
            console.log(result);
            // console.log("!!!");
            // console.log($('#debug').val());
            $('#clan_field').text(result[0]['title']);
          }
        });
      }












    var path=[];
    var data_fill=[];
    var idds=[];
    make_cards();
    function make_cards(){
    data_fill=[];
    idds=[];
    path=[];
    console.log("cards");
    $.ajax({
      url:"api/api.php", //the page containing php script
      type: "post", //request type,
      dataType: 'json',
      data: {type:"cards"},
      async: false, // HERE
      success:function(result){
        // document.getElementById("id1").remove();
        // console.log(data);
        console.log(result);
        result.forEach(function(res) {
          data_fill.push(res.name);
          idds.push(res.id);
          path.push("cards_parser/cards/small/"+res.proto+".jpg");
        });
      }
    });
    $( function() {
    $( "#card" ).val("");
    $( "#card_id" ).val("");
    $( "#card" ).autocomplete({
      source: data_fill,
      close: function( event, ui ) {card_check();}
    });
  } );
  }

  $(document).ready(function(){
    $('#card').on("change", card_check);
  });
  $(document).ready(function(){
    $('#card').on("input", card_check);
  });

    function card_check(){
      console.log(data_fill.includes($('#card').val()));
      // var divContainer = document.getElementById("showData");
      // divContainer.innerHTML = "";
      if (data_fill.includes($('#card').val())){
        $('#card_id').val(idds[data_fill.indexOf($('#card').val())]);
        $('#table').show();
        $('#card_row').text($('#card').val());
        $('#image').attr("src",path[data_fill.indexOf($('#card').val())]);
        console.log($('#card_row'));
        // create();
      }
      else{
        $('#table').hide();
      }
    }
    // clans();
    card_check();
  </script>
  <script src="js/script.js"></script>
</html>
