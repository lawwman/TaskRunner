<!DOCTYPE html>

<!-- PHP Portions -->
<?php
  require('debugging.php');
  require('session.php');

  if ($_GET["argument"]=='signOut'){
    logout();
  }

  function showUser() {
    if (isLoggedIn()) {
      echo '
      <div class="ui dropdown inverted button">Hello, '. $_SESSION['user'] . '</div>
      <div class="ui dropdown inverted button" id="signOut" formaction="/demo/signup.php">Sign Out</div>
      ';
    } else {
      echo "<a class='ui inverted button' href='/demo/login.php'>Log in</a>
      <a class='ui inverted button' href='/demo/signup.php'>Sign Up</a>";
    }
  }
?> 

<!-- HTML Portions -->
<html>
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
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/label.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dropdown.css">  

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/form.js"></script>
  <script src="semantic/dist/components/checkbox.js"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>

  <!-- Following 3 links are needed for auto-complete to work. Auto-complete uses jQuery-UI-->
  <link rel="stylesheet" type="text/css" href="assets/jquery-ui/jquery-ui.css">
  <script src="assets/jquery-ui/jquery.js"></script>
  <script src="assets/jquery-ui/jquery-ui.min.js"></script>

  <!-- list.js is a js file that stores a list of suggestions for the autocomplete function. Needed only if suggestions are from a local source-->
  <script src="list.js"></script>
  <script src="locationlist.js"></script>

  <style type="text/css">
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .ui-autocomplete {
      max-height: 200px;
      overflow-y: auto;
      /* prevent horizontal scrollbar */
      overflow-x: hidden;
    }
  </style>

  <!-- Following script block shows how to get implement autocomplete with suggestions from a local js file-->
  <script>

    // import {getLocList} from 'locationlist';
    var availableTags = getList(); //getList() function is a function from "list.js". Returns an array of string
    var locations = getLocList(); //getLocList() function is a function from "locationlist.js". Returns an array of string
    var selectedSkill = ""; //To store the selected options
    var detailsOfTask = ""; //To store the selected options
    var selectedLoc = "";


    var validateTask = false; //boolean variation if validation message is showing
    var validateTaskDetail = false;
    var validateLocation = false;

   $(document).ready(function() {
      $('#suggestionForSkills').autocomplete({
        source: availableTags,

        //when an option is selected
        select: function(select, ui) {
          var label = ui.item.label;
          selectedSkill = label;
        }
      });
      // do not submit form. Manually insert link.
    $('#localSkillsForm').on('submit', function(){
      event.preventDefault();
      if (selectedSkill == "" && !validateTask) {
        $('#skillsValidation').append('<div class="ui pointing red basic label"><p>Please select an option</p></div>');
        validateTask = true;
      }
      return false;
      });
    });

   $(document).ready(function() {
      $('#suggestionForLocation').autocomplete({
        source: locations,

        //when an option is selected
        select: function(select, ui) {
          var label = ui.item.label;
          selectedLoc = label;
        }
      });
      // do not submit form. Manually insert link.
    $('#localLocationForm').on('submit', function(){
      event.preventDefault();
      if (selectedLoc == "" && !validateLocation) {
        $('#locationValidation').append('<div class="ui pointing red basic label"><p>Please select an option</p></div>');
        validateLocation = true;
      }
      return false;
      });
    });

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
    })

    //script to ensure lower sequence not shown yet
    $(document).ready(function() {
      $('#toggleDetail').hide();
    })

    //script to toggle
    $(document).ready(function() {
      $('#nextSeq').click(function() {
        if (selectedSkill == "" && !validateTask) {
          $('#skillsValidation').append('<div class="ui pointing red basic label"><p>Please select an option</p></div>');
          validateTask = true;
        } else if (selectedSkill != "") {
          $('#toggleTask').slideUp(1000);
          $('#toggleDetail').slideDown(1000);
        }
      });
    })

    $(document).ready(function() {
      $('#finalSeq').click(function() {
        if ($('#taskDetailTextBox').val() === "" && !validateTaskDetail) {
          $('#taskDetailValidation').append('<div class="ui pointing red basic label"><p>Please fill in task detail</p></div>');
          validateTaskDetail = true;
        }
        if (selectedLoc == "" && !validateLocation) {
          $('#locationValidation').append('<div class="ui pointing red basic label"><p>Please fill in task detail</p></div>');
          validateLocation = true;
        }
        console.log($('#taskDetailTextBox').val());
        if ($('#taskDetailTextBox').val() != "" && selectedLoc != "") {
          //information to send
          var selectedSkillJSON = JSON.stringify(selectedSkill);
          var taskDetailJSON = JSON.stringify($('#taskDetailTextBox').val());
          var selectedLocJSON = JSON.stringify(selectedLoc);

          console.log('sending');
          $.ajax({
            url: '/demo/storetaskdetail.php',
            type: 'POST',
            data: { skill: selectedSkillJSON, details: taskDetailJSON, locs: selectedLocJSON },
            dataType: 'json',
            success: function(data) {
              window.location.replace("/demo/taskfinalise.php");
            }
          });
        }
      })
    })
  </script>
</head>

<body class='ui'>

  <!-- Top menu -->
  <div class="pusher">
    <div class="ui inverted vertical masthead center aligned segment">

      <div class="ui container">
        <div class="ui large secondary inverted pointing menu">
          <a class="toc item">
            <i class="sidebar icon"></i>
          </a>
          <a class="item" href="/demo/index.php">Home</a>
          <div class="ui simple dropdown item">
            <span class = "text">My Tasks</span>
            <i class = "dropdown icon"></i>
            <div class = "menu">
              <a class ="item" href="/demo/viewtasks.php"> View My Tasks</a>
              <a class ="item" href="/demo/addtasks.php"> Add a Task</a>
            </div>
          </div>          
          <a class="item" href="/demo/viewbids.php">My Bids</a>          
          <div class="right item">
            <?php showUser(); ?>
          </div>
        </div>
      </div>
    </div>

    <div class="ui middle aligned center aligned grid inverted">
      <div class="six wide column">
        <br><br>
        <h2 class="ui dividing header">Tasks to Create</h2>
        <!-- Input HTML for suggestions from a local source-->
        <div id="toggleTask" class="ui-widget">
          <form id="localSkillsForm">
            <label >Choose your tasks: </label>
            <input id="suggestionForSkills">
          </form>

          <br>
          <!--Tags for skills selected--> 
          <div id="tags"></div>

          <!--validation for autocorrect-->
          <div id="skillsValidation"></div>
          <br>
          <button id="nextSeq" class="ui button">Next</button>
        </div>
      </div>
    </div>

    <!-- Form for task adding-->
    <div class="ui middle aligned center aligned grid inverted">
      <div class="six wide column">
        <h2 class="ui dividing header">Task Details</h2>
        <div id="toggleDetail">
          <form class="ui form" action="/demo/addtasks.php" method="POST">

            <div class="field">
              <textarea id="taskDetailTextBox" rows="2"></textarea> 
            </div>
            <!--validation for taskDetail-->
            <div id="taskDetailValidation"></div>
          </form>

          <div class="ui-widget">
          <form id="localLocationForm">
            <label >Choose your task's location: </label>
            <input id="suggestionForLocation">
          </form>
          <!--validation for autocorrect-->
          <div id="locationValidation"></div>
          <br>
        </div>

          <button id="finalSeq" class="ui button">Next</button>
        </div>

        <br><br>     
      </div>
    </div>

  </div>

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