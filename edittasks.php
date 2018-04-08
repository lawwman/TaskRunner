<!DOCTYPE html>  

<!-- php login and sign out -->
<?php
  require('debugging.php');
  require('session.php');

  if ($_GET["argument"]=='signOut'){
    logout();
  }

  redirectIfNot('taskee');

  function showUser() {
    if (isLoggedIn()) {
      echo '
      <div class="ui dropdown inverted button" id="editProfile">Hello, '. $_SESSION['userName'] . '</div>
      <div class="ui dropdown inverted button" id="signOut" formaction="/demo/signup.php">Sign Out</div>
      ';
    } else {
      echo "<a class='ui inverted button' href='/demo/login.php'>Log in</a>
      <a class='ui inverted button' href='/demo/signup.php'>Sign Up</a>";
    }
  }

  function editTask(){
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error());
      $taskid = $_SESSION['taskid'];
      consoleLog($taskid);
      $query = "SELECT * FROM tasks where task_id = '$taskid'";    
      $result = pg_query($db, $query);   // Query template    
      $row    = pg_fetch_assoc($result);    // To store the result row

        echo " 
              <div class = 'one field'>
                <label>Task Type </label>
                <div class='ui search task'>
                  <div class='ui icon input'>
                    <input class='prompt' type='text' placeholder='Select new task!' id='taskauto' value='$row[ttype]'>
                    <i class='search icon'></i>
                  </div>
                  <div class='results'></div>
                </div>
                <div class='ui pointing label'>
                  Old task type is $row[ttype]
                </div>
              </div>

              <div class = 'field'>
                <label>Task Details </label>
                <div class='field'>
                  <textarea id='task_details_updated' rows='2'>$row[task_details]</textarea> 
                </div>
              </div>
              
              <div class = 'field'>
                <label>Task Location </label>
                <div class='ui search loc'>
                  <div class='ui icon input'>
                    <input class='prompt' type='text' placeholder='Select new location!' id='locauto' value='$row[loc]'>
                    <i class='search icon'></i>
                  </div>
                  <div class='results'></div>
                </div>
                <div class='ui pointing label'>
                  Old task location is $row[loc]
                </div>
              </div>

              <div class='fields'>
                <div class='eight wide field'>
                  <label> Start Date </label>
                  <div class='ui calendar' id='calendarDateStart'>
                    <div class='ui input left icon'>
                      <i class='calendar icon'></i>
                      <input type='text' placeholder='Date' id=date_start value='$row[startdatetime]'>
                    </div>
                  </div>
                </div>
                <div class='eight wide field'>
                <label> Start Time </label>
                  <div class='ui calendar' id='calendarTimeStart'>
                    <div class='ui input left icon'>
                      <i class='clock icon'></i>
                      <input type='text' placeholder='Time' id=time_start value='$row[startdatetime]'>
                    </div>
                  </div>
                </div>
              </div>

              <div class='fields'>
                <div class='eight wide field'>
                <label> End Date </label>
                  <div class='ui calendar' id='calendarDateEnd' >
                    <div class='ui input left icon'>
                      <i class='calendar icon'></i>
                      <input type='text' placeholder='Date' id=date_end value='$row[enddatetime]'>
                    </div>
                  </div>
                </div>
                <div class='eight wide field'>
                <label> End Time </label>
                  <div class='ui calendar' id='calendarTimeEnd' >
                    <div class='ui input left icon'>
                      <i class='clock icon'></i>
                      <input type='text' placeholder='Time' id=time_end value='$row[enddatetime]'>
                    </div>
                  </div>
                </div>
              </div>";        
    
  }

?> 

<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Add New Task</title>
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/menu.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/form.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/input.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/checkbox.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/message.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/icon.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dropdown.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/search.css">

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/form.js"></script>
  <script src="semantic/dist/components/checkbox.js"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>
  <script src="semantic/dist/components/search.js"></script>    

    <!-- used for calander function -->
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.css" rel="stylesheet" type="text/css" />
  <script src="https://code.jquery.com/jquery-2.1.4.js"></script>
  <script src="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.js"></script>

  <script src="listforsemantic.js"></script>
  <script src="locationlistforsemantic.js"></script>

  <style type="text/css">
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
  </style>

  <script>
    // performs sign out functionality.
    $(document).ready(function() {
      $('#signOut').click(function() {
        $.ajax({
          url: '/demo/index.php?argument=signOut',
          success: function(html){
            location.reload();
          }
        });
      });
      $('#editProfile').click(function() {
        window.location.replace('/demo/edittaskeeprofile.php');
      });
    })
  </script>

  <script>
    var availableTags = getListUI(); //getList() function is a function from "list.js". Returns an array of string
    var locations = getLocList(); //getLocList() function is a function from "locationlist.js". Returns an array of string
    var dateInputStart;
    var timeInputStart;
    var dateInputEnd;
    var timeInputEnd;
    var sec;
    var ttype;
    var ttypeJSON;
    var details;
    var detailsJSON;
    var loc;
    var locJSON;

    var compareFlag = false;
    var ErrorShowing = false;

    sec = ":00";

    function monthConv(date){
      var splitStringEmpty = date.split(" ");
      var month = splitStringEmpty[0];
      // var day = splitString[1].substring(0,1);
      var converted;
      switch(month){
        case 'January'  : converted = '01'; break;
        case 'February' : converted = '02'; break;
        case 'March'    : converted = '03'; break;
        case 'April'    : converted = '04'; break;
        case 'May'      : converted = '05'; break;
        case 'June'     : converted = '06'; break;
        case 'July'     : converted = '07'; break;
        case 'August'   : converted = '08'; break;
        case 'September': converted = '09'; break;
        case 'October'  : converted = '10'; break;
        case 'November' : converted = '11'; break;
        case 'December' : converted = '12'; break;
      }

      var stringSplitComma = splitStringEmpty[1].split(",");
      var day = stringSplitComma[0];
      if(day <10){
        day= '0' + day;
      }

      return (splitStringEmpty[2] + '-' + converted + '-' + day);
    }

    function convertFieldsToJSON() {
      ttype = $('#taskauto').val();
      ttypeJSON = JSON.stringify(ttype);
      details = document.getElementById('task_details_updated').value;
      detailsJSON = JSON.stringify(details);
      loc = $('#locauto').val();
      locJSON = JSON.stringify(loc);
    }

    function convertDatesToJSON() {
      //check if date/time was changed, else use the current read from database
      var startConvert = document.getElementById('date_start').value;
      dateInputStart = monthConv(startConvert);
      timeInputStart = document.getElementById('time_start').value;
      var endConvert = document.getElementById('date_end').value;
      dateInputEnd = monthConv(endConvert);
      timeInputEnd = document.getElementById('time_end').value;

      var zero = "0";

      //convert start to JSON
      start = dateInputStart + ' ' + timeInputStart + sec;
      if (start.charAt(1) == ":") {
        start = zero.concat(start);
      }
      startJSON = JSON.stringify(start);

      //convert end to JSON
      end = dateInputEnd + ' ' + timeInputEnd + sec;
      if (end.charAt(1) == ":") {
        end = zero.concat(end);
      }
      endJSON = JSON.stringify(end);
      console.log(start);
      console.log(end); 
    }

    function validateDate() {
      var startYear = parseInt(start.substring(0, 4));
      var endYear = parseInt(end.substring(0, 4));

      var startMnth = parseInt(start.substring(5,7));
      var endMnth = parseInt(end.substring(5,7));

      var startDay = parseInt(start.substring(8,10));
      var endDay = parseInt(end.substring(8,10));

      var sHour = parseInt(start.substring(11,13));
      var eHour = parseInt(end.substring(11,13));
      //check the year
     if (startYear > endYear) {
        compareFlag = true;
        return;
      } else {
        compareFlag = false;
      }

      //check month. Year is correct if code reaches this point
      if (startMnth > endMnth) {
        compareFlag = true;
        return;
      } else {
        compareFlag = false;
      }

      //check day. Year and month is correct if code reaches this point
      if (startDay > endDay) {
        compareFlag = true;
        return;
      } else {
        compareFlag = false;
      }

      //check Time. Year and Month and Day is correct if code reaches this point
      //corner case: startDay and endDay is the same day. Time matters.
      if (startDay == endDay) {
        if (sHour >= eHour) {
          compareFlag = true;
          return;
        } else {
          compareFlag = false;
        }
      }
    }

    $(document).ready(function() {
      $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    })

    $(document).ready(function() {
      $('#calendarDateStart').calendar({
        type: 'date'
      });

      $('#calendarTimeStart').calendar({
        type: 'time',
        ampm: false,
        disableMinute: true
      });

      $('#calendarDateEnd').calendar({
        type: 'date'
      });

      $('#calendarTimeEnd').calendar({
        type: 'time',
        ampm: false,
        disableMinute: true
      });

      $('.ui.search.task').search({
        source: availableTags
      });

      $('.ui.search.loc').search({
        source: locations
      });


      $('#editSubmit').click(function() {
        convertFieldsToJSON();
        convertDatesToJSON();
        validateDate();

         $.ajax({
          url: '/demo/editquery.php',
          type: 'POST',
          dataType: 'json',
          data: {start: startJSON, end: endJSON, ttype: ttypeJSON, details: detailsJSON, loc: locJSON},
          success: function(data){
            // console.log('sent');
            window.location.replace("/demo/viewcreatedtasks.php");
            console.log(data.abc);  
          }
        });
      });
      
      $('#testy').click(function() {
        convertDatesToJSON();
        validateDate();
        console.log($('#taskauto').val());
        console.log($('#locauto').val());
      })

    });
  </script>

</head>

<body class = 'ui'>

  <!-- Top menu -->
  <div class="pusher">
    <div class="ui inverted vertical masthead center aligned segment">

      <div class="ui container">
        <div class="ui large secondary inverted pointing menu">
          <a class="item" href="/demo/index.php">Home</a>
          <a class="item" href="/demo/viewcreatedtasks.php">View Created Tasks</a>
          <a class="item" href="/demo/addtasks.php">Add Task</a>        
          <div class="right item">
            <?php showUser(); ?> 
          </div>
        </div>
      </div>
    </div>
    
 
    <div class="ui middle alighed center aligned grid inverted">
      <div class = "six wide column">
        <br>
        <form class="ui form" id="editForm">  
          <?php editTask() ?> 
          <br>
          <button class="ui blue primary button" id="editSubmit" >
            Submit
          </button> 
        </form>
      
      </div>
    </div> <br/>

  <!-- Footer -->
  <div class="ui inverted vertical footer segment">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="three wide column">
          <h4 class="ui inverted header">Discover</h4>
          <div class="ui inverted link list">
            <a href='/demo/taskersignup.php' class="item">Become a Tasker</a>            
          </div>
        </div>
        <div class="three wide column"></div>
        <div class="seven wide column">          
          <h4 class="ui inverted header">Navigate</h4>
          <div class="ui inverted link list">
            <a href='/demo/viewcreatedtasks.php' class="item">My Created Tasks</a>            
            <a href='/demo/addtasks.php' class="item">Create a Task</a>            
          </div>
        </div>
        <br>
        <div class = "row">        
          &copy; 2018&nbsp;<b>Task Sourcing</b>&nbsp;| Created by &nbsp;<b>Jonathan Kennard Lawrence Wei Ping</b>        
        </div>
        
      </div>
    </div>
  </div>
</div>
</body>
</html>