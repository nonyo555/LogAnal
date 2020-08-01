<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>KMITL Log Analytics - Building List</title>
    <link href="autocomplete.css" media="all" rel="Stylesheet" type="text/css" />
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
    <link href="table.css" media="all" rel="Stylesheet" type="text/css" />
    <script>
        var building_names = [
        <?php 
            $hostname = "161.246.127.103";
            $username = "root";
            $password = "123456789";
            $db = "database";

            $mysqli = new mysqli($hostname,$username,$password,$db);
            $stmt = $mysqli->prepare("SELECT BdName FROM building");
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $building_name = $row["BdName"];
                echo "'$building_name',";
            }
        ?>
    ];
    </script>
</head>

<body>
    <div class='head_main'>
        <a href="../phpvscode/index.php"><img src="../img/header.png"></a>
    </div>
    <!-- <div class='sub_main'>
        <label class='sub'>Top-problem</label>
    </div> -->
    <div class='body_main'>
        <h2>Building List Page</h2>
        <form name="inputForm" id="inputForm" autocomplete="off" method="post">
            <div class="autocomplete" style="width:300px;">
                <input id="buildingInput" type="text" name="buildingInput" placeholder="ค้นหารายชื่อตึก" value="<?php echo isset($_POST['buildingname']) ? $_POST['buildingname'] : '' ?>">
            </div>
            <input type="submit">
        </form>
        <div id="data_table"></div>
        <script type="text/javascript">
        $(document).ready(function() {
            console.log("do here");
            $('#inputForm').submit(function(e) {
                    var building = document.forms["inputForm"]["buildingInput"].value;
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: 'queryfunc.php',
                        data: {functionname: 'buildinglistquery',arguement: building},
                        success: function(table)
                        {
                            $("#data_table").html(table);
                        }
                    });
            });
        });
        </script>
    </div>
    <div class='tail_main'></div>

</body>

<script>
    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function (e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false; }
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            a.setAttribute("style", "height: 200px; overflow-x: hidden; overflow-y: auto; ")
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    b.setAttribute("style","text-align:left;")
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function (e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function (e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }
    
    autocomplete(document.getElementById("buildingInput"), building_names);
</script>
</html>