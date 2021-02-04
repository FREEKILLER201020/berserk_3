<?php

require "common.php";
?>
<?php Head();?>
<div id="showData"></div>
<script src="js/jquery-3.5.1.slim.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="../js/index.js"></script>
<script src="../js/script.js"></script>
<script src="../js/functions.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
$(document).ready(function() {
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

var offset = 0;
var limit = 30;

get_eras_data(clans);
eras();
order();
data();

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

function get_eras_data() {
    $.ajax({
        url: "../api/api.php", //the page containing php script
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
        url: "../api/api.php", //the page containing php script
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
        url: "../api/api.php", //the page containing php script
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
            if (bcp == null) {
                document.getElementById("clans").value = -1;
            } else {
                document.getElementById("clans").value = bcp;
            }
        }
    });
}

function order() {
    if ($('#era').val() == -1) {
        var bcp = $('#order').val();
        // console.log(bcp);
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
        if (bcp == null) {
            document.getElementById("order").value = "frags";
        } else {
            document.getElementById("order").value = bcp;
        }
        // console.log("create");
    } else {
        var bcp = $('#order').val();
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
        var option = document.createElement("option");
        option.text = "Участия";
        option.value = "actions";
        x.add(option);
        var option = document.createElement("option");
        option.text = "Очки";
        option.value = "points";
        x.add(option);
        if (bcp == null) {
            document.getElementById("order").value = "frags";
        } else {
            document.getElementById("order").value = bcp;
        }
        // console.log("create2");
    }
}

function data() {
    if ($('#era').val() == -1) {
        create();
    } else {
        create2();
    }
}

function setOffset(val) {
    offset = val;
    data();
}

function create() {
    $.ajax({
        url: "../api/api.php", //the page containing php script
        type: "post", //request type,
        dataType: 'html',
        data: { type: "index", datee: $('#date').val(), order: $('#order').val(), order_way: $('#order_way').val(), clan: $('#clans').val(), debug: document.getElementById("debug").checked, limit: limit, offset: offset, server_render: 1 },
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

function create2() {
    $.ajax({
        url: "../api/api.php", //the page containing php script
        type: "post", //request type,
        dataType: 'html',
        data: { type: "index_era", datee: $('#date').val(), order: $('#order').val(), order_way: $('#order_way').val(), id: $('#era').val(), clan: $('#clans').val(), nickname: $('#player').val(), debug: document.getElementById("debug").checked, limit: limit, offset: offset, server_render: 1 },
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
</script>
</body>

</html>