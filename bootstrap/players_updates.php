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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

create();

function setOffset(val) {
    offset = val;
    create();
}


      function create () {
        $.ajax({
          url:"../api/api.php", //the page containing php script
          type: "post", //request type,
          dataType: 'html',
          data: {type:"players_updates", limit: limit, offset: offset, server_render: 1 },
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