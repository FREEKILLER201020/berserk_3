function CreateTableFromJSON(myBooks) {
            console.log(myBooks);

            // EXTRACT VALUE FOR HTML HEADER.
            // ('Book ID', 'Book Name', 'Category' and 'Price')
            // myBooks.sort(function(a, b){
            //   var srt=document.getElementById("order").value;
            //   console.log(srt);
            //   return b.srt - a.srt;
            // });
            var col = [];
            for (var i = 0; i < myBooks.length; i++) {
                for (var key in myBooks[i]) {
                    if (col.indexOf(key) === -1) {
                        col.push(key);
                    }
                }
            }
            // CREATE DYNAMIC TABLE.
            var table = document.createElement("table");
            table.setAttribute("align", "center");
            table.setAttribute("id", "table1");
            table.setAttribute("class", "fixed_header");
            table.setAttribute("style", "overflow-x:auto, word-wrap: break-word");

            // var table = document.getElementById("myTable");
            // var header = table.createTHead();
            var tblBody = table.createTBody();
            // var row = header.insertRow(0);
            //
            //
            // for (var i = 0; i < col.length; i++) {
            //     var th = document.createElement("th");      // TABLE HEADER.
            //     th.innerHTML = col[i];
            //     row.appendChild(th);
            //     // var cell = row.insertCell(0);
            //     // cell.innerHTML = "<b>This is a table header</b>";
            // }

            // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.

            var tr = tblBody.insertRow(-1);                   // TABLE ROW.
            //
            // for (var i = 0; i < col.length; i++) {
            //     var th = document.createElement("th");      // TABLE HEADER.
            //     th.innerHTML = col[i];
            //     tr.appendChild(th);
            // }

            // ADD JSON DATA TO THE TABLE AS ROWS.
            for (var i = 0; i < myBooks.length; i++) {

                tr = table.insertRow(-1);

                for (var j = 0; j < col.length; j++) {
                    // console.log(myBooks[i][col[j]]);
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = myBooks[i][col[j]];
                    tabCell.setAttribute("class", "color_text");
                }
            }

            // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
            var divContainer = document.getElementById("showData");
            // console.log(divContainer);
            divContainer.innerHTML = "";
            divContainer.appendChild(table);
            var rrow=document.getElementById('table1').rows[1].cells;
            var width = [];
            for(let i = 0; i < rrow.length; i++){
              width.push(rrow[i].offsetWidth+5);
            }

            var table2 = document.createElement("table");
            table2.setAttribute("align", "center");
            table2.setAttribute("id", "table2");
            table2.setAttribute("style", "overflow-x:auto, word-wrap: break-word");
            

            var header2 = table2.createTHead();
            var tblBody2 = table2.createTBody();
            var row2 = header2.insertRow(-1);


            for (var i = 0; i < col.length; i++) {
                var th = document.createElement("th");      // TABLE HEADER.
                th.innerHTML = col[i];
                th.setAttribute("class", "color_text");
                row2.appendChild(th);
            }
            // console.log(divContainer);
            var divContainer = document.getElementById("showData2");
            divContainer.innerHTML = "";
            divContainer.appendChild(table2);
            document.getElementById('table2').rows[0].cells;
            var rrow=document.getElementById('table2').rows[0].cells;
            var width2 = [];
            for(let i = 0; i < rrow.length; i++){

              width2.push(rrow[i].offsetWidth+5);
            }
            console.log(width,width2);
            var rrow=document.getElementById('table2').rows[0].cells;
            console.log(rrow.length);
            for(let i = 0; i < rrow.length; i++){
                console.log(document.getElementById('table2').rows[0].cells[i]);
                // var tbl = document.getElementById('table2');
                // var td = tbl.rows[0].cells[i];
                // td.width = '500px';
                // td.style.backgroundColor = 'blue';
                document.getElementById('table2').rows[0].cells[i].width=Math.max(width[i],width2[i]);
            }
            var rrow=document.getElementById('table1').rows;
            for(let i = 0; i < rrow.length; i++){
              var ccells=document.getElementById('table1').rows[i].cells;
              for(let j = 0; j < ccells.length; j++){
                document.getElementById('table1').rows[i].cells[j].width=Math.max(width[j],width2[j]);
              }
            }
            // console.log(table,table2);
        }


function CreateTableFromJSONOne(myBooks) {
            console.log(myBooks);

            // EXTRACT VALUE FOR HTML HEADER.
            // ('Book ID', 'Book Name', 'Category' and 'Price')
            // myBooks.sort(function(a, b){
            //   var srt=document.getElementById("order").value;
            //   console.log(srt);
            //   return b.srt - a.srt;
            // });
            var col = [];
            for (var i = 0; i < myBooks.length; i++) {
                for (var key in myBooks[i]) {
                    if (col.indexOf(key) === -1) {
                        col.push(key);
                    }
                }
            }
            // CREATE DYNAMIC TABLE.
            var table = document.createElement("table");
            table.setAttribute("align", "center");
            table.setAttribute("id", "table1");
            table.setAttribute("class", "fixed_header");

            // var table = document.getElementById("myTable");
            // var header = table.createTHead();
            var tblBody = table.createTBody();
            // var row = header.insertRow(0);
            //
            //
            // for (var i = 0; i < col.length; i++) {
            //     var th = document.createElement("th");      // TABLE HEADER.
            //     th.innerHTML = col[i];
            //     row.appendChild(th);
            //     // var cell = row.insertCell(0);
            //     // cell.innerHTML = "<b>This is a table header</b>";
            // }

            // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.

            var tr = tblBody.insertRow(-1);                   // TABLE ROW.
            //
            // for (var i = 0; i < col.length; i++) {
            //     var th = document.createElement("th");      // TABLE HEADER.
            //     th.innerHTML = col[i];
            //     tr.appendChild(th);
            // }

            // ADD JSON DATA TO THE TABLE AS ROWS.
            for (var i = 0; i < myBooks.length; i++) {

                tr = table.insertRow(-1);

                for (var j = 0; j < col.length; j++) {
                    // console.log(myBooks[i][col[j]]);
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = myBooks[i][col[j]];
                    tabCell.setAttribute("class", "color_text");
                }
            }

            // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
            var divContainer = document.getElementById("showData");
            // console.log(divContainer);
            divContainer.innerHTML = "";
            divContainer.appendChild(table);
            var rrow=document.getElementById('table1').rows[1].cells;
            var width = [];
            for(let i = 0; i < rrow.length; i++){
              width.push(rrow[i].offsetWidth+5);
            }

            // var table2 = document.createElement("table");
            // table2.setAttribute("align", "center");
            // table2.setAttribute("id", "table2");
            // table2.setAttribute("style", "overflow-x:auto");
            

            var header2 = table.createTHead();
            // var tblBody2 = table2.createTBody();
            var row2 = header2.insertRow(-1);


            for (var i = 0; i < col.length; i++) {
                var th = document.createElement("th");      // TABLE HEADER.
                th.innerHTML = col[i];
                th.setAttribute("class", "color_text");
                row2.appendChild(th);
            }
            // console.log(divContainer);
            var divContainer = document.getElementById("showData");
            // divContainer.innerHTML = "";
            divContainer.appendChild(table2);
            document.getElementById('table1').rows[0].cells;
            var rrow=document.getElementById('table1').rows[0].cells;
            // console.log(table,table2);
        }


        function CreateTableFromJSON2(myBooks) {


            // EXTRACT VALUE FOR HTML HEADER.
            // ('Book ID', 'Book Name', 'Category' and 'Price')
            // myBooks.sort(function(a, b){
            //   var srt=document.getElementById("order").value;
            //   console.log(srt);
            //   return b.srt - a.srt;
            // });
            var parent_div=document.createElement("p");
            var col = [];
            for (var i = 0; i < myBooks.length; i++) {
                for (var key in myBooks[i]) {
                    if (col.indexOf(key) === -1) {
                        col.push(key);
                    }
                }
            }
            // CREATE DYNAMIC TABLE.
            var table = document.createElement("table");
            table.setAttribute("align", "center");
            table.setAttribute("id", "table3");

            // var table = document.getElementById("myTable");
            // var header = table.createTHead();
            var tblBody = table.createTBody();
            // var row = header.insertRow(0);
            //
            //
            // for (var i = 0; i < col.length; i++) {
            //     var th = document.createElement("th");      // TABLE HEADER.
            //     th.innerHTML = col[i];
            //     row.appendChild(th);
            //     // var cell = row.insertCell(0);
            //     // cell.innerHTML = "<b>This is a table header</b>";
            // }

            // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.

            var tr = tblBody.insertRow(-1);                   // TABLE ROW.
            //
            // for (var i = 0; i < col.length; i++) {
            //     var th = document.createElement("th");      // TABLE HEADER.
            //     th.innerHTML = col[i];
            //     tr.appendChild(th);
            // }

            // ADD JSON DATA TO THE TABLE AS ROWS.
            for (var i = 0; i < myBooks.length; i++) {

                tr = table.insertRow(-1);

                for (var j = 0; j < col.length; j++) {
                    // console.log(myBooks[i][col[j]]);
                    var tabCell = tr.insertCell(-1);
                    tabCell.innerHTML = myBooks[i][col[j]];
                    tabCell.setAttribute("class", "color_text");
                }
            }



            // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
            var divContainer = parent_div;
            console.log(divContainer);
            // divContainer.innerHTML = "";
            divContainer.appendChild(table);
            return parent_div;
        }