
<!DOCTYPE html>

<?php
  require('debugging.php');
  require('session.php');

  if ($_GET["argument"]=='signOut'){
    logout();
    consoleLog('hi');
  }

  redirectIfNot('tasker');
  
  function showUser() {
    if (isLoggedIn()) {
      echo '
      <div class="ui dropdown inverted button">Hello, '. $_SESSION['userName'] . '</div>
      <div class="ui dropdown inverted button" id="signOut">Sign Out</div>
      ';
      consoleLog($_SESSION['userEmail']);
      consoleLog($_SESSION['userType']);
    } else {
      echo "<a class='ui inverted button' href='/demo/taskeelogin.php'>Log in</a>
      <a class='ui inverted button' href='/demo/taskersignup.php'>Become a Tasker</a>";
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
  <title>Homepage - Tasker</title>
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/menu.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dropdown.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/icon.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/sidebar.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/transition.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/label.css">

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>

  <!-- Following 3 links are needed for auto-complete to work. Auto-complete uses jQuery-UI-->
  <link rel="stylesheet" type="text/css" href="assets/jquery-ui/jquery-ui.css">
  <script src="assets/jquery-ui/jquery.js"></script>
  <script src="assets/jquery-ui/jquery-ui.min.js"></script>

  <!-- list.js is a js file that stores a list of suggestions for the autocomplete function. Needed only if suggestions are from a local source-->
  <script src="list.js"></script>

  <style>
    .ui-autocomplete {
      max-height: 200px;
      overflow-y: auto;
      /* prevent horizontal scrollbar */
      overflow-x: hidden;
    }
  </style>

  <script>
    // performs sign out functionality.
    $(document).ready(function() {
      $('#signOut').click(function() {
        $.ajax({
          url: '/demo/taskerdashboard.php?argument=signOut',
          success: function(html){
            location.reload();
          }
        });
      });
    })
  </script>

    <!-- Following script block shows how to get implement autocomplete with suggestions from a local js file-->
  <script>
    var availableTags = getList(); //getList() function is a function from "list.js". Returns an array of string
    var selectedOptions = []; //To store the selected options
    var count = 0;
    var id = "removeTag";
    var validateMsg = false; //boolean variation if validation message is showing
   $(document).ready(function() {
      $('#suggestionFromLocalSource').autocomplete({
        source: availableTags,

        //when an option is selected
        select: function(select, ui) {
          var label = ui.item.label;
          selectedOptions.push(label); //store selected function
          console.log(selectedOptions);
          count++; //increment count to give unique id to each tag!
          $('#tags').append('<div class="ui image label">'+ label + '<i id="' + id + count + '" class="delete icon removeTag"></i></div>');

          //function when option tag is clicked
          $("#" + id+ count).click(function() {
            var toRemove = $(this).parent().text(); //get option
            selectedOptions = selectedOptions.filter(function(item) {
              return item != toRemove;
            });
            console.log(selectedOptions);

            //finally, remove tag.
            $(this).parent().remove(); 
          });
        }
      });
      // do not submit form. Manually insert link.
    $('#localForm').on('submit', function(){
      event.preventDefault();
      if (selectedOptions.length === 0 && !validateMsg) {
        $('#autocompleteValidation').append('<div class="ui pointing red basic label"><p>Please select an option</p></div>');
        validateMsg = true;
      }
      return false;
      });
    });
  </script>


  <style type="text/css">

    .hidden.menu {
      display: none;
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

<!-- Page Contents -->
<div class="pusher">
  <div class="ui inverted vertical masthead center aligned segment">

    <div class="ui container">
      <div class="ui large secondary inverted pointing menu">
        <a class="toc item">
          <i class="sidebar icon"></i>
        </a>
        <a class="active item">Home</a>        
        <a class="item" href="/demo/bidtasks.php">View Available Tasks to Bid</a>
        <a class="item" href="/demo/viewrunningtasks.php">View Tasks I Am Running</a>
        <div class="right item">
          <?php showUser(); ?> 
        </div>
      </div>
    </div>
  </div>

  <div class="ui vertical stripe segment">
    <div class="ui left aligned stackable fourteen column grid container">
      
      <div class="two wide column"></div>
      <div class="five wide column">
        <h3 class="ui header" style="color: grey;">Welcome to the world of task sourcing!</h3>          
      </div>            
      
      <div class="seven wide column">         
        <h2>
          <div class="ui big grey circular label">1</div> 
          Select skill
        </h2>       
        <p> You begin by selecting a skill! It can be whatever you are good at! </p>       
        <h2>      
          <div class="ui big grey circular label aligned left ">2</div> 
          Proficiency
        </h2>
        <p> Select a proficiency level! It should be a rough estimate of how good you are! </p>
        <h2> 
          <div class="ui big grey circular label aligned left">3</div> 
          Rate 
        </h2>         
        <p> Finally, select the hourly rates for your selected skill! </p>
        <h2>      
          <div class="ui big grey circular label ">4</div> 
          Pitch
        </h2>
        <p> Write a good pitch about your skill! </p>
      </div>  
    </div>
  </div>

    <!-- Skills select area-->
  <div class="ui middle aligned center aligned grid inverted">
    <!-- Suggestions from a local source-->
    <div class="ui container"></div> <br>
      <div class="six wide column">
        <h2 class="ui dividing header">Select a skill here!</h2>
        <div class="ui-widget">
          <form id="localForm">
            <label >Local Source: </label>
            <input id="suggestionFromLocalSource">
          </form>

          <br>
          <!--Tags for skills selected--> 
          <div id="tags"></div>

          <!--validation for autocorrect-->
          <div id="autocompleteValidation"></div>
        </div>
      </div>
    </div>
  </div>
  <br><br><br>

  <div class="ui inverted vertical footer segment">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="three wide column">
          <h4 class="ui inverted header">Discover</h4>
          <div class="ui inverted link list">
            <a href='/demo/taskeesignup.php' class="item">Sign up to Create Tasks</a>
          </div>
        </div>
        <div class="three wide column">
          
        </div>
        <div class="seven wide column">          
          <h4 class="ui inverted header">Navigate</h4>
          <div class="ui inverted link list">
            <a href='/demo/bidtasks.php' class="item">List of Available Tasks</a>            
            <a href='/demo/viewrunningtasks.php' class="item">My Running Tasks</a>            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>

</html>
