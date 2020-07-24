<html>
<head>
    <title>KMITL Log Analytics - Graph</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    <link href="index.css" media="all" rel="Stylesheet" type="text/css" />
  </head>
<body onload = 'initialize()'>
    <div class='head_main'>
        <a href="../phpvscode/index.php"><img src="../img/header.png"></a>
    </div>
    <div class='sub_main'>
        <label class='sub'>Top-problem</label>
    </div>
    <div class='body_main' id ='bname'>
      
        <h2>Graph Page</h2>
        <div style="text-align: center;" >
        <div class = inline  >
        <label class = 'gtext'>Building Name:</label>
        <div class="custom-select" >
        <select id = 'BD_name'  onfocus="this.size=5;" style=" width: 90%;"  > 
            
        </select>
        </div>
        <div class="custom-select" >
        <select id = 'BD_name2'  onfocus='this.size=5;' style=" width: 90%;"  > 
            <option class = 'sel_text' value="None">ไม่เลือก</option>
        </select>
        </div>
        </div>
        <div class = inline  >
        <label class = 'gtext'>Graph Type:</label>
        <div class="custom-select" id='type'>
        <select name="G_type"  id = "G_type" >
            <option value='Population'>Population</option>
            <option value="Bandwidth">Bandwidth</option>
        </select>
        </div>
        </div>
    </div>
        <div class = time >
            <label class = 'gtext'>Time</label>
            <input id = 'time_start'  class = 'time_start'  type = 'datetime-local'/>
            <label class = 'text'> : </label>
            <input id = 'time_stop' class = 'time_stop' placeholder = 'Stop'  type = 'datetime-local'/>
            <button class = 'search-bt' id = cf_button onclick = 'makeGraph()'>Change Time</button>
    </div>
    <div class = 'graphs'>
      <canvas id="canvas" style=" width: 1200px;  height: 600px; position: relative; color:blue;" ></canvas>
    </div>

    <div class='tail_main'>
        <div class='sub_main'>
            <label class='sub' id = 'Ana_log'>Log Analytic</label>
        </div>
    </div>

</body>
<script >
  async function initialize(){
    // setting time
    var today = new Date();
    var yesterday = new Date();
    today.setUTCHours(18)
    yesterday.setUTCHours(-6)
    var yester =  yesterday.toISOString().slice(0,16); 
    var dateTime = today.toISOString().slice(0,16);
    document.getElementById("time_stop").defaultValue = dateTime;
    document.getElementById("time_stop").max = dateTime;
    document.getElementById("time_start").defaultValue = yester;
    document.getElementById("time_start").max = dateTime;
    // makeSelector_Building_Name
      file = 'building_Name.txt'
      var rawFile = new XMLHttpRequest();
      rawFile.open("GET", file, false);
      rawFile.onreadystatechange = async function ()
      {
          if(rawFile.readyState === 4)
          {
              if(rawFile.status === 200 || rawFile.status == 0)
              {
                  var allText = await rawFile.responseText.split('\n');
                  for (var i = 0;i<allText.length;i++){
                    document.getElementById("BD_name").innerHTML += ' <option class = \'sel_text\' value= \''+allText[i]+'\'>'+allText[i]+'</option>\n'
                    document.getElementById("BD_name2").innerHTML += ' <option class = \'sel_text\' value= \''+allText[i]+'\'>'+allText[i]+'</option>\n'
                  }
                  makedropdown();
                 // console.log( document.getElementById("BD_name").innerHTML)
              }
          }
      }
    rawFile.send(null);
    // makeGraph
    function getRandomInt(max) {
    return Math.floor(Math.random() * Math.floor(max));
    }
    var data = [];
    var start =document.getElementById("time_start").value
    var stop = document.getElementById("time_stop").value
    var building = document.getElementById('BD_name').value

    await jQuery.ajax({
    type: "POST",
    url: 'queryfunc.php',
    dataType: 'json',
    data: {functionname: 'scatterquerybw',arguement:[start,stop,'อาคาร ECC']},
    success: function (obj) {
      data= obj;
      console.log(obj)
            },
    error: function(){
        alert("Db is Error")
    }
    });
    var datasets = [];
    for (var i = 0 ; i<data.length;i++){
      datasets.push({x:moment(data[i][0]).format('X'),y:data[i][1]})
    }
    var color = Chart.helpers.color;
    var scatterChartData = {
      datasets: [{
        borderColor: 'red',
        backgroundColor: color('orange').alpha(0.5).rgbString(),
        label: 'อาคาร ECC',
        //type: 'line',
      }]
    };
    scatterChartData.datasets[0]['data'] = datasets;
      var ctx = document.getElementById('canvas').getContext('2d');
      window.myScatter = Chart.Scatter(ctx, {
        data: scatterChartData,
        options: {
          hovermode:'index',
          responsive:true,
          stacked:'false',
          legend: {
            labels: {
                fontColor: "black",
                fontSize: 18
            }
        },
          title: {
            display: true,
            text: 'อาคาร ECC'
          },
          scales: {
            xAxes: [{
              ticks: {
                userCallback: function(tick) {
                    return moment(tick*1000).format(" MMMM Do YYYY HH:MM:SS");
                },
                minRotation	: 50,
                fontColor: "black",
                fontSize: 12
              },
            }],
            yAxes: [{
              type: 'linear',
              ticks: {
                userCallback: function(tick) {
                  return tick.toString() + 'คน';
                },
                fontColor: "black",
              },
              scaleLabel: {
                labelString: 'Number of People',
              //  display: true
              }
            }]
          },
          tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                  var label = moment(tooltipItem.xLabel*1000).format("dddd, MMMM Do YYYY HH:MM:SS");
                    label +=  moment((tooltipItem.xLabel+3600)*1000).format( " - HH:MM:SS");
                    if (label) {
                        label += ': ';
                    }
                    label +=  Math.round(tooltipItem.yLabel * 100) / 100;
                    label += 'คน'
                    return label;
                },
            }
            },
        }

      });
  }
  //    
  async function makeGraph(){
    var fname= '';
    var data = [];
    var arguements = [];
    var building = document.getElementById("BD_name").value;
    var building2 = document.getElementById("BD_name2").value;
    var start = document.getElementById("time_start").value 
    var stop = document.getElementById("time_stop").value
    var type = 'scatterquerycn';
    window.myScatter.options.tooltips.callbacks.label = function(tooltipItem, data) {
                    var label = moment(tooltipItem.xLabel*1000).format("dddd, MMMM Do YYYY HH:MM:SS");
                    label +=  moment((tooltipItem.xLabel+3600)*1000).format( " - HH:MM:SS");
                    if (label) {
                        label += ': ';
                    }
                    label +=  Math.round(tooltipItem.yLabel * 100) / 100;
                    label += 'คน'
                    return label;
                }
    window.myScatter.options.scales.yAxes[0].ticks.userCallback = function(tick) {
                  return tick.toString() + 'คน';
                }
    if ( document.getElementById("G_type").value == 'Bandwidth'){
        type  = 'scatterquerybw';
        window.myScatter.options.tooltips.callbacks.label = function(tooltipItem, data) {
                    var label = moment(tooltipItem.xLabel*1000).format("dddd, MMMM Do YYYY HH:MM:SS");
                    if (label) {
                        label += ': ';
                    }
                    label +=  Math.round(tooltipItem.yLabel * 100) / 100;
                    label += 'Mbs'
                    return label;
                }
        window.myScatter.options.scales.yAxes[0].ticks.userCallback = function(tick) {
                  return tick.toString() + 'Mbs';
                }
    }
    await jQuery.ajax({
    type: "POST",
    url: 'queryfunc.php',
    dataType: 'json',
    data: {functionname: type ,arguement: [start,stop,building.replace('\n','')]},
    success: function (obj) {
        data = obj;
            },
    error: function(){
        alert("Db is Error")
    }
    });

    var datasets = [];
    for (var i = 0 ; i<data.length;i++){
      datasets.push({x:moment(data[i][0]).format('X'),y:data[i][1]})
    }
    window.myScatter.data.datasets[0]['label'] =building;
    window.myScatter.options.title['text'] = building;
    window.myScatter.data.datasets[0]['data'] = datasets;

    var data2 = [];
    var datasets2 = [];
    if (window.myScatter.data.datasets.length == 2){
      window.myScatter.data.datasets.pop()
    }
    if(building2 != 'None' && building != building2){
        await jQuery.ajax({
        type: "POST",
        url: 'queryfunc.php',
        dataType: 'json',
        data: {functionname: type ,arguement: [start,stop,building2.replace('\n','')]},
        success: function (obj) {
            data2 = obj;
                },
        error: function(){
            alert("Db is Error")
        }
        });
        for (var i = 0 ; i<data2.length;i++){
        datasets2.push({x:moment(data2[i][0]).format('X'),y:data2[i][1]})
        }
        var color = Chart.helpers.color;
        var scatterChartData2 = 
        {
        borderColor: 'blue',
        backgroundColor: color('blue').alpha(0.5).rgbString(),
        label: building2,
        data: datasets2
        }
       ;
        window.myScatter.data.datasets.push(scatterChartData2)
        window.myScatter.options.title['text'] += '  และ  '  
        window.myScatter.options.title['text'] += building2
    }
    window.myScatter.update();
  }

</script>
<script>
  function makedropdown(){
    var x, i, j, l, ll, selElmnt, a, b, c;
    /*look for any elements with the class "custom-select":*/
    x = document.getElementsByClassName("custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
      selElmnt = x[i].getElementsByTagName("select")[0];
      ll = selElmnt.length;
      /*for each element, create a new DIV that will act as the selected item:*/
      a = document.createElement("DIV");
      a.setAttribute("class", "select-selected");
      a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
      x[i].appendChild(a);
      /*for each element, create a new DIV that will contain the option list:*/
      b = document.createElement("DIV");
      b.setAttribute("class", "select-items select-hide");
      for (j = 0; j < ll; j++) {
        /*for each option in the original select element,
        create a new DIV that will act as an option item:*/
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
            /*when an item is clicked, update the original select box,
            and the selected item:*/
            var y, i, k, s, h, sl, yl;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            sl = s.length;
            h = this.parentNode.previousSibling;
            for (i = 0; i < sl; i++) {
              if (s.options[i].innerHTML == this.innerHTML) {
                s.selectedIndex = i;
                h.innerHTML = this.innerHTML;
                y = this.parentNode.getElementsByClassName("same-as-selected");
                yl = y.length;
                for (k = 0; k < yl; k++) {
                  y[k].removeAttribute("class");
                }
                this.setAttribute("class", "same-as-selected");
                break;
              }
            }
            h.click();
        });
        b.appendChild(c);
      }
      x[i].appendChild(b);
      a.addEventListener("click", function(e) {
          /*when the select box is clicked, close any other select boxes,
          and open/close the current select box:*/
          e.stopPropagation();
          closeAllSelect(this);
          this.nextSibling.classList.toggle("select-hide");
          this.classList.toggle("select-arrow-active");
        });
    }
    function closeAllSelect(elmnt) {
      /*a function that will close all select boxes in the document,
      except the current select box:*/
      var x, y, i, xl, yl, arrNo = [];
      x = document.getElementsByClassName("select-items");
      y = document.getElementsByClassName("select-selected");
      xl = x.length;
      yl = y.length;
      for (i = 0; i < yl; i++) {
        if (elmnt == y[i]) {
          arrNo.push(i)
        } else {
          y[i].classList.remove("select-arrow-active");
        }
      }
      for (i = 0; i < xl; i++) {
        if (arrNo.indexOf(i)) {
          x[i].classList.add("select-hide");
        }
      }
    }
    document.addEventListener("click", closeAllSelect);
  }
</script>

</html>