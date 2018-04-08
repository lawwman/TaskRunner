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


  function showBidders() {

    if (isset($_SESSION['taskid']) && isset($_SESSION['tasktype'])) {
      // Connect to the database. Please change the password in the following line accordingly
      $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error()); 
      $userEmail = $_SESSION['userEmail'];
      $taskid = $_SESSION['taskid'];
      $tasktype = $_SESSION['tasktype'];
      $result = pg_query($db, "SELECT * FROM Bids B inner join Taskers T on B.taskeremail = T.email inner join hasSkills HS
        on B.taskeremail = HS.tEmail WHERE B.task_id = '$taskid' and HS.sname = '$tasktype' and B.status = 'pending' ");
      while ($row = pg_fetch_assoc($result)) {
        echo "
            <div class='card'>
              <div class='content'>
                <input type='hidden' value='$row[taskeremail]' class='hideTasker'>
                <input type='hidden' value='".$_SESSION['taskid'] ."' class='hideTask'>
                <a class='header'>$row[firstname] $row[lastname]</a>
                <div class='meta'>
                  <p class='description'> email: $row[taskeremail]</p>
                </div>
                <br>
                <div class='description'> Rate: $row[hrate] $/hr</div>
                <br>
                <div class='description'> Proficiency: $row[proflevel] %</div>
                <br>
                <button class='ui primary blue button bidBtn'>
                  Select Bidder!
                </button>
              </div>
            </div>

            <div class='ui basic modal' id='taskUserModal'>
              <div class='ui icon header'>
                <i class='archive icon'></i>
                  Select Bidder.
              </div>
              <div class='content'>
                <p>Are you sure you want to select $row[taskeremail]?</p>
              </div>
              <div class='actions'>
                <div class='ui red basic cancel inverted button'>
                  <i class='remove icon'></i>
                    No
                 </div>
                <div class='ui green ok inverted button confirmBtn'>
                  <i class='checkmark icon'></i>
                    Yes
                </div>
              </div>
            </div>";
      }
      pg_close($db);
      //unset the session variables
      unset($_SESSION['taskid']);
      unset($_SESSION['tasktype']);
    }
  }

  function selectBidder() {

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
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/modal.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dimmer.css">

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>
  <script src="semantic/dist/components/modal.js"></script>
  <script src="semantic/dist/components/dimmer.js"></script>

  <script>

    var currentUserSelected = "";
    var currentTaskUpdating = "";

    $(document).ready(function() {
      $(".card").click(function() {
        currentUserSelected = $(this).find('.hideTasker').val();
        currentTaskUpdating = $(this).find('.hideTask').val();
        console.log(currentUserSelected);
        console.log(currentTaskUpdating);
      })
    })

    $(document).ready(function() {
      $(".confirmBtn").click(function() {
        console.log("confirm");
        $.ajax({
          url: '/demo/selectbidder.php',
          data: { user: currentUserSelected, taskid: currentTaskUpdating },
          type: 'POST',
          success: function(data) {
            alert("you have successfully chosen a bidder");
            window.location.replace("/demo/viewcreatedtasks.php");
          }
        });
      })
    })

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

    $(document).ready(function() {
      $('.bidBtn').click(function(){
        $('#taskUserModal').modal({
          onApprove: function() {
          }
        }).modal('show');
      });
    })

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
      position: absolute;
      bottom: 0;
      width: 100%;
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

<div class="my_container">
  <div class='ui link three cards'>
    <?php showBidders(); ?> 
  </div>
</div>



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

</body>
</html>
