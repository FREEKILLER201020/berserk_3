<?php

require "common.php";
?>
<?php Head();
Dates(1);
Eras();
EndHead();?>
<div id="showData"></div>
<!-- <script src="js/jquery-3.5.1.slim.min.js"></script> -->
<script src="js/jquery.js"></script>
<script src="js/index.js"></script>
<script src="js/script.js"></script>
<script src="js/functions.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script>


var offset = 0;
var limit = 30;


    $(document).ready(function(){
      // $('#order').on("change", order);
      // $('#order').on("change", data);
      // $('#order_way').on("change", data);
      $('#era').on("input", get_eras_data);
      // $('#era').on("change", order);
      $('#era').on("change", create);
      $('#clans').on("change", create);
      // $('#debug').on("change", data);
      // $('#date').on("change", data);


    });

// var $$header = document.querySelector('.js-header');

        // $(function() {
        //   function available(date) {
        //     dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
        //     if ($.inArray(dmy, availableDates) != -1) {
        //       return [true, "","Available"];
        //     } else {
        //       return [false,"","unAvailable"];
        //     }
        //   }
        //   $('#date').datepicker({ beforeShowDay: available });
        // });
  get_eras_data(clans);

  eras();
  // clans();
  create();

function getDates(startDate, endDate) {
    var dates = [],
        currentDate = startDate,
        addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        };
    while (currentDate <= endDate) {
        dates.push(currentDate);
        currentDate = addDays.call(currentDate, 1);
    }
    return dates;
};
      function create () {
        $.ajax({
          url:"api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'html',
          data: {type:"timetable",id: $('#era').val(),clan:$('#clans').val(), server_render: 1},
          // async: false, // HERE
          beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
            $('#showData').empty();
            $('#dot').removeClass('hidden')
        },
        success: function(result) {
            $('#showData').empty();
            // console.log(result);
            $('#showData').append(result);
        },
        complete: function() { // Set our complete callback, adding the .hidden class and hiding the spinner.
            $('#dot').addClass('hidden')
        }
        });
      }


function get_eras_data(clans) {
    $.ajax({
        url: "api/api.php", //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: { type: "era_dates", id: $('#era').val() },
        async: false, // HERE
        success: function(result) {
            // console.log(result);
            // console.log(result[0].started);
            // console.log(result[0].ended);
            var start = result[0].started.split("-");
            var end = result[0].ended.split("-");
            var start_date = new Date(start[0], start[1] - 1, start[2]);
            var end_date = new Date(end[0], end[1] - 1, end[2]);
            if ($('#era').val() != -1) {
                end_date.setDate(end_date.getDate() + 1);
            }
            console.log(start_date);
            console.log(end_date);
            var dates = getDates(start_date, end_date);
            var string = "";
            availableDates = [];
            dates.forEach(function(date) {
                availableDates.push(string.concat(date.getDate(), "-", date.getMonth() + 1, "-", date.getFullYear()));
                console.log(string.concat(date.getDate(), "-", date.getMonth() + 1, "-", date.getFullYear()));
            });
            document.getElementById('date').value = string.concat(end_date.getDate(), "/", end_date.getMonth() + 1, "/", end_date.getFullYear());
            var tmp = end_date.getMonth() + 1;
            document.getElementById('date').value = string.concat(("0" + tmp).slice(-2), "/", ("0" + end_date.getDate()).slice(-2), "/", end_date.getFullYear());
        }
    });
    clans();
}

function eras() {
    // console.log("Eras!");
    // console.log($('#era').val());
    $.ajax({
        url: "api/api.php", //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: { type: "eras" },
        async: false, // HERE
        success: function(result) {
            // console.log(data);
            // console.log(result);
            var bcp = $('#era').val();
            var x = document.getElementById("era");
            var option = document.createElement("era");
            x.options.length = 0;
            var option = document.createElement("option");
            // option.text = "---";
            // option.value = -1;
            // x.add(option);
            var f = null;
            for (var i = 0; i < result.length; i++) {
                if (f == null) {
                    f = result[i].id;
                }
                var option = document.createElement("option");
                option.text = result[i].id + " (" + result[i].started + " : " + result[i].ended + ")";
                option.value = result[i].id;
                x.add(option);
            }
            document.getElementById("era").value = f;
        }
    });
}

function clans() {
    // console.log("Clans!");
    // console.log($('#date').val());
    $.ajax({
        url: "api/api.php", //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: { type: "clans", datee: $('#date').val() },
        async: false, // HERE
        success: function(result) {
            // console.log(result);
            var bcp = $('#clans').val();
            var x = document.getElementById("clans");
            var option = document.createElement("clans");
            x.options.length = 0;
            var option = document.createElement("option");
            option.text = "Все кланы";
            option.value = -1;
            x.add(option);
            for (var i = 0; i < result.length; i++) {
                var option = document.createElement("option");
                option.text = result[i].title;
                option.value = result[i].id;
                x.add(option);
            }
            var option = document.createElement("option");
            option.text = "Нет клана";
            option.value = -2;
            x.add(option);
            document.getElementById("clans").value = 171;
        }
    });
}
</script>
</body>

</html>