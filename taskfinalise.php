<!DOCTYPE html>  

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

  if (!isset($_SESSION['skill']) || !isset($_SESSION['locs']) || !isset($_SESSION['details'])) {
    echo '<script language="javascript">';
    echo 'alert("task details not set, redirecting to page...");';
    echo '</script>';
    echo '<script type="text/javascript">location.href = "/demo/addtasks.php";</script>';
  }

  function adminField() {
    if($_SESSION['isAdmin'] == 't'){
      echo "
      <h2 class = 'ui center aligned dividing header'> Enter Desired Taskee Email </h2>
      <div class='sixteen wide field'>
        <input type='text' id = 'taskeeEmail' placeholder='Taskee Email'>
      </div>
      <div id='emailVTag'></div>

      ";
    }
  }

  function adminButton(){
    if($_SESSION['isAdmin'] == 't'){
      echo "
        <button id='adminButton' class='ui right floated black button'> 
        Submit </button>
      ";
    } else {
      echo "
        <button id='autoButton' class='ui right floated red button' >
          Select for me!
        </button>
        <button id='manualButton' class='ui right floated blue button' >
          Manual
        </button>
      ";
    }
  }

?>

<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>View Tasks - Semantic</title>
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/menu.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/card.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dropdown.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/icon.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/sidebar.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/transition.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/label.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dimmer.css">

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>
  <script src="semantic/dist/components/modal.js"></script>
  <script src="semantic/dist/components/dimmer.js"></script>


  <!-- used for calander function -->
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.css" rel="stylesheet" type="text/css" />
  <script src="https://code.jquery.com/jquery-2.1.4.js"></script>
  <script src="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.js"></script>

  <script>
    // performs sign out functionality.
    $(document).ready(function() {
      $('#signOut').click(function() {
        $.ajax({
          url: '/demo/viewtasks.php?argument=signOut',
          success: function(html){
            window.location.replace("/demo/index.php");
          }
        });
      });
      $('#editProfile').click(function() {
        window.location.replace('/demo/edittaskeeprofile.php');
      });
    })
  </script>

  <script>
    var dateInput;
    var dateEndInput;
    var dateInputJSON;
    var dateEndInputJSON;
    var emailInputJSON;
    var email;

    var startDay = 0;
    var startMnth = 0;
    var startYear = 0;
    var startTime = "250"; //dummy values

    var endDay = 0;
    var endMnth = 0;;
    var endYear = 0;
    var endTime = "250"; //dummy values

    var startNotValidFlag = false; // false indicates no flag raised. Valid
    var endNotValidFlag = false;
    var compareFlag = false;

    var startErrorShowing = false;
    var endErrorShowing = false;
    var compareErrorShowing = false;

    //check if values are selected
    function checkStartValidation() {
      if (startDay == 0 || startMnth == 0 || startYear == 0 || startTime == "250") {
        startNotValidFlag = true; // if any of the fields not set, set flag to true.
      } else {
        startNotValidFlag = false; // set back to valid
      }
    }
    //check if values are selected
    function checkEndValidation() {
      if (endDay == 0 || endMnth == 0 || endYear == 0 || endTime == "250") {
        endNotValidFlag = true;
      } else {
        endNotValidFlag = false;
      }
    }
    
    //check if start date is before end date
    function compareDateValidation() {
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
        var sHour = startTime.substring(0,2);
        var eHour = endTime.substring(0,2);
        if (sHour >= eHour) {
          compareFlag = true;
          return;
        } else {
          compareFlag = false;
        }
      }
    }

    function createValidationMsg() {
      if (startNotValidFlag && !startErrorShowing) {
        startErrorShowing = true;
        $('#startValidationTag').append('<div class="ui left pointing red basic label" id="startVTag"><p>Please fill in the date values</p></div>');
      }

      if (!startNotValidFlag && startErrorShowing) {
        startErrorShowing = false;
        $('#startVTag').remove();
      }

      if (endNotValidFlag && !endErrorShowing) {
        endErrorShowing = true;
        $('#endValidationTag').append('<div class="ui left pointing red basic label" id="endVTag"><p>Please fill in the date values</p></div>');
      }

      if (!endNotValidFlag && endErrorShowing) {
        endErrorShowing = false;
        $('#endVTag').remove();
      }

      if (compareFlag && !compareErrorShowing) {
        compareErrorShowing = true;
        $('#endValidationTag').append('<div class="ui left pointing red basic label" id="compareVTag"><p>Make sure start date is before end date</p></div>');
      }

      if (!compareFlag && compareErrorShowing) {
        compareErrorShowing = false;
        $('#compareVTag').remove();
      }
    }

    $(document).ready(function() {
      $('#calendarDate').calendar({
        type: 'date',
        minDate: new Date(),
        onChange: function(date) {
            startYear = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            if (month < 10) {
                month = '0' + month;
            }
            if (day < 10) {
                day = '0' + day;
            }
            startDay = day;
            startMnth = month;
          }
      });

      $('#calendarTime').calendar({
        type: 'time',
        ampm: false,
        disableMinute: true,
        onChange: function(time,text){
          startTime = text;
          if (startTime.charAt(1) == ":") {
            var zeroHour = "0";
            startTime = zeroHour + startTime;
          }
        }
      });

      $('#calendarEndDate').calendar({
        type: 'date',
        minDate: new Date(),
        onChange: function(date) {
            endYear = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            if (month < 10) {
                month = '0' + month;
            }
            if (day < 10) {
                day = '0' + day;
            }
            endDay = day;
            endMnth = month;
          }
      });

      $('#calendarEndTime').calendar({
        type: 'time',
        ampm: false,
        disableMinute: true,
        onChange: function(time,text){
          endTime = text;
          if (endTime.charAt(1) == ":") {
            var zeroHour = "0";
            endTime = zeroHour + endTime;
          }
        }
      });

      $('#manualButton').click(function() {
        checkStartValidation();
        checkEndValidation();
        compareDateValidation();
        createValidationMsg();
        if (!startNotValidFlag && !endNotValidFlag && !compareFlag) {
          var seconds = ":00";
          dateInput = startYear + '-' + startMnth + '-' + startDay + " " + startTime + seconds;
          dateEndInput = endYear + '-' + endMnth + '-' + endDay + " " + endTime + seconds;
          dateInputJSON = JSON.stringify(dateInput);
          dateEndInputJSON = JSON.stringify(dateEndInput);
           $.ajax({
            url: '/demo/submitquery.php',
            type: 'POST',
            dataType: 'json',
            data: {date: dateInputJSON, endDate: dateEndInputJSON},
            success: function(data){
              console.log(data);
              window.location.replace("/demo/viewcreatedtasks.php");
            }
          });
        }
        if (startNotValidFlag || endNotValidFlag || compareFlag) {
          console.log("dates are not valid");
        }
      });

      $('#autoButton').click(function() {
        checkStartValidation();
        checkEndValidation();
        compareDateValidation();
        createValidationMsg();
        if (!startNotValidFlag && !endNotValidFlag && !compareFlag) {
          var seconds = ":00";
          dateInput = startYear + '-' + startMnth + '-' + startDay + " " + startTime + seconds;
          dateEndInput = endYear + '-' + endMnth + '-' + endDay + " " + endTime + seconds;
          dateInputJSON = JSON.stringify(dateInput);
          dateEndInputJSON = JSON.stringify(dateEndInput);
           $.ajax({
            url: '/demo/submitautoquery.php',
            type: 'POST',
            dataType: 'json',
            data: {date: dateInputJSON, endDate: dateEndInputJSON},
            success: function(data){
              console.log(data.abc);
              alert(data.abc);
              window.location.replace("/demo/viewcreatedtasks.php");
            }
          });
        }
        if (startNotValidFlag || endNotValidFlag || compareFlag) {
          console.log("dates are not valid");
        }
      });
    
      $('#adminButton').click(function() {
        checkStartValidation();
        checkEndValidation();
        compareDateValidation();
        createValidationMsg();
        if (!startNotValidFlag && !endNotValidFlag && !compareFlag ) {
          var seconds = ":00";
          dateInput = startYear + '-' + startMnth + '-' + startDay + " " + startTime + seconds;
          dateEndInput = endYear + '-' + endMnth + '-' + endDay + " " + endTime + seconds;
          dateInputJSON = JSON.stringify(dateInput);
          dateEndInputJSON = JSON.stringify(dateEndInput);
          emailInputJSON = JSON.stringify(document.getElementById('taskeeEmail').value);
           $.ajax({
            url: '/demo/submitquery.php',
            type: 'POST',
            dataType: 'json',
            data: {date: dateInputJSON, endDate: dateEndInputJSON, taskeeemail: emailInputJSON},
            success: function(data){
              console.log(data);
              window.location.replace("/demo/admin.php");
            }
          });
        }
        if (startNotValidFlag || endNotValidFlag || compareFlag ) {
          console.log("dates are not valid");
        }
      });
    });
  </script>


  <style type="text/css">

    .my_container {
      margin: 50px;
    }

    .masthead.segment {
      min-height: 200px;
      padding: 1em 0em;
    }
    .masthead .logo.item img {
      margin-right: 1em;
    }
    .masthead .ui.menu .ui.button {
      margin-left: 0.5em;
    }
    .masthead h1.ui.header {
      margin-top: 3em;
      margin-bottom: 0em;
      font-size: 4em;
      font-weight: normal;
    }
    .masthead h2 {
      font-size: 1.7em;
      font-weight: normal;
    }

    .ui.vertical.stripe {
      padding: 8em 0em;
    }
    .ui.vertical.stripe h3 {
      font-size: 2em;
    }
    .ui.vertical.stripe .button + h3,
    .ui.vertical.stripe p + h3 {
      margin-top: 3em;
    }
    .ui.vertical.stripe .floated.image {
      clear: both;
    }
    .ui.vertical.stripe p {
      font-size: 1.33em;
    }
    .ui.vertical.stripe .horizontal.divider {
      margin: 3em 0em;
    }

    .quote.stripe.segment {
      padding: 0em;
    }
    .quote.stripe.segment .grid .column {
      padding-top: 5em;
      padding-bottom: 5em;
    }

    .footer.segment {
      padding: 5em 0em;
    }

    .secondary.pointing.menu .toc.item {
      display: none;
    }

    @media only screen and (max-width: 700px) {
      .ui.fixed.menu {
        display: none !important;
      }
      .secondary.pointing.menu .item,
      .secondary.pointing.menu .menu {
        display: none;
      }
      .secondary.pointing.menu .toc.item {
        display: block;
      }
      .masthead.segment {
        min-height: 350px;
      }
      .masthead h1.ui.header {
        font-size: 2em;
        margin-top: 1.5em;
      }
      .masthead h2 {
        margin-top: 0.5em;
        font-size: 1.5em;
      }
    }

  </style>
</head>



<body>

<!-- Top menu -->
<div class="pusher">

<div class="ui inverted vertical masthead center aligned segment">
  <div class="ui container">
    <div class="ui large secondary inverted pointing menu">
      <a class="toc item">
        <i class="sidebar icon"></i>
      </a>
      <a class="item" href="/demo/index.php">Home</a>
      <a class="item" href="/demo/viewcreatedtasks.php">View Created Tasks</a>
      <a class="item" href="/demo/addtasks.php">Add Task</a>        
      <div class="right item">
        <?php showUser(); ?> 
      </div>
    </div>
  </div>
</div>

<br>

<form class="ui form" action="/demo/taskfinalise.php" method = "post">

  <h2 class = 'ui center aligned dividing header'> Pick a Start Time and Date </h2>
  <div class="ui justified container">

    <div class="fields">
      <!--Start date and time-->
      <div class="seven wide field">
        <div class="ui calendar" id="calendarDate">
          <div class="ui input left icon">
            <i class="calendar icon"></i>
            <input type="text" placeholder="Start Date">
          </div>
        </div>
        </div>
        <div class="seven wide field">
          <div class="ui calendar" id="calendarTime">
            <div class="ui input left icon">
              <i class="clock icon"></i>
              <input type="text" placeholder="Start Time">
            </div>
          </div>
        </div>
        <div id="startValidationTag"></div>
      </div>

    <h2 class = 'ui center aligned dividing header'> Pick a End Time and Date </h2>
      <!--End date and time-->
      <div class="fields">
      <div class="seven wide field">
        <div class="ui calendar" id="calendarEndDate">
          <div class="ui input left icon">
            <i class="calendar icon"></i>
            <input type="text" placeholder="End Date">
          </div>
        </div>
      </div>
      <div class="seven wide field">
        <div class="ui calendar" id="calendarEndTime">
          <div class="ui input left icon">
            <i class="clock icon"></i>
            <input type="text" placeholder="End Time">
          </div>
        </div>
      </div>
      <div id="endValidationTag"></div>
    </div>

      <div>
        <?php adminField() ?>
      </div>

      <div>
        <?php adminButton() ?>
      </div>

    <br>
  </div>
  <div class="ui error message"></div>
</form> 
</div>

<br><br><br><br><br>


  <!-- Footer -->
  <div class="ui inverted vertical footer segment">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="three wide column">
          <h4 class="ui inverted header">About</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">Sitemap</a>
            <a href="#" class="item">Contact Us</a>
          </div>
        </div>
        <div class="three wide column">
          <h4 class="ui inverted header">Services</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">DNA FAQ</a>
            <a href="#" class="item">How To Access</a>
          </div>
        </div>
        <div class="seven wide column">
          <h4 class="ui inverted header">Footer Header</h4>
          <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
