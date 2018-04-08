
<!DOCTYPE html>  

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

  function showTasks() { //to edit
      // Connect to the database. Please change the password in the following line accordingly
    $db     = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234"); 
    $result = pg_query($db, "SELECT * FROM tasks where status = 'not bidded'");
    while ($row = pg_fetch_assoc($result)) {
      echo "<tr>
      <td> 
        <div class= 'ui checkbox'> 
          <input type = 'checkbox' name = 'checked[]' value='$row[task_id]'>
        </div>
      </td>
      <td> $row[task_name]</td>
      <td> $row[task_details] </td> 
      <td> $row[creator] </td>
      <td> $row[reward] </td>
      <td> $row[status] </td>
      <td> $row[createddatetime] </td>
      </tr>";
    }
  }

  function showTaskers() { //to implement
  }

  function showTaskees() { //to implement
  }

  function showSkills() { //to implement
  }

  function showBids() { //to implement
  }

  function submitTask() {
      // Connect to the database. Please change the password in the following line accordingly

  $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error()); 

    if(isset($_POST['bid_submit'])) {
      foreach($_POST['checked'] as $taskid) {
        $result = pg_query($db, "SELECT * FROM tasks WHERE task_id = '$taskid' ");
        while($row = pg_fetch_assoc($result)) {
          $taskid = $row['task_id'];
          $creator = $row['creator'];
          $runner = $_SESSION['user'];
          $status = 'pending';
          date_default_timezone_set('Asia/Singapore');
          $createddatetime = date('Y-m-d H:i:s');
          BEGIN;
          pg_query($db,"INSERT INTO bids VALUES($taskid, '$creator', '$runner', '$status', '$createddatetime') ");
          pg_query($db, "UPDATE tasks SET runner = '$runner' , status = '$status' WHERE task_id = '$taskid' ");
          COMMIT;
        }
      }
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
  <title>Admin Page</title>
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
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/table.css">  
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/label.css"> 

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>
  <script src="semantic/dist/components/modal.js"></script>
  <script src="semantic/dist/components/dimmer.js"></script>

  <script>

    var result = [];

    function retrieveData(tableName, direction, offset) {
      return $.ajax({ 
        type: 'GET', 
        url: '/demo/adminRetrieval.php', 
        data: { table: tableName, dir: direction, off: offset}, 
        dataType: 'json',
        success: function (data) {
          populateTable(data, tableName);
        }
      });
    }

    function populateTable(data, tableName) {
      let table = document.getElementById(tableName);
      $.each(data, function(k,v) {
        let row = table.insertRow(1);
        let generatedRow = `
          <td> 
          <div class= 'ui checkbox'> 
            <input type = 'checkbox' name = 'checked[]' value='` + k + `'>
          </div>
          </td>`;
        $.each(v, function(attr,val) {
          generatedRow = generatedRow + `<td>` + val + '</td>';
        });

        row.innerHTML = generatedRow;
      });
      return data;
    }

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

      let taskData = retrieveData("tasks", "forward", 0);
      let bidData = retrieveData("bids", "forward", 0);
      let taskeeData = retrieveData("taskees", "forward", 0);
      let taskerData = retrieveData("taskers", "forward", 0);
      let skillData = retrieveData("skills", "forward", 0);
    })
  </script>

  <style type="text/css">

    .my_container {
      margin: 50px;
    }

    .masthead.segment {
      min-height: 100px;
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
          <a class ="active item" href="/demo/admin.php"> View/Manage</a>          
        <div class="right item">
          <?php showUser(); ?> 
        </div>
      </div>
    </div>
  </div>
  
  <div class="my_container">
    <h2>View/Manage Tasks
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> Edit Task </button>
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> – </button>          
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> + </button>   
    </h2>               
    
    <form method ="post">  
      <table class="ui celled table" id="tasks">
        <thead>
          <th> Select </th>        
          <th> Task Id </th>
          <th> Task Type </th>
          <th> Task Details </th>
          <th> Task Creator </th>          
          <th> Tasker Assigned </th>
          <th> Status </th>
          <th> Created Date and Time </th>
          <th> Start Date and Time </th>
          <th> End Date and Time </th>
          <th> Location </th>
        </thead>
        <tbody>
        </tbody>
      </table>
      
   
      <br>
    </form>
    <?php submitTask(); ?>

    <h2>View/Manage Taskers
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> Edit User - Tasker </button>
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> – </button>          
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> + </button>
    </h2>
    <form method ="post">  
      <table class="ui celled table" id="taskers">
        <thead>
          <th> Select </th>
          <th> Tasker Email </th>
          <th> Tasker Name </th> <!-- thinking can concatenate first and last name-->
          <th> Birthdate </th>          
          <th> Phone </th>
          <th> Address </th>
          <th> Zipcode </th>
          <th> isAdmin </th>
          <th> isStaff </th>
        </thead>
        <tbody>
        </tbody>
      </table>        
        <br>
    </form>

    <h2>View/Manage Taskees
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> Edit User - Taskee </button>
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> – </button>          
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> + </button>
    </h2>
    <form method ="post">  
      <table class="ui celled table" id="taskees">
        <thead>
          <th> Select </th>
          <th> Taskee Email </th>
          <th> Taskee Name </th>
          <th> Phone </th>          
          <th> Zipcode </th>
          <th> isAdmin </th>
          <th> isStaff </th>          
        </thead>
        <tbody>
        </tbody>
      </table>        
        <br>
    </form>

    <h2>View/Manage Skills
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> Edit Skill </button>
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> – </button>          
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> + </button>
    </h2>
    <form method ="post">  
      <table class="ui celled table" id="skills">
        <thead>
          <th> Select </th>
          <th> Skill Name </th>
          <th> Skill Details </th>          
        </thead>
        <tbody>
        </tbody>
      </table>        
        <br>
    </form>

    <h2>View/Manage Bids
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> Edit Bid </button>
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> – </button>          
      <button class ="ui right floated tiny teal button" name = "bid_submit" type="submit"> + </button>
    </h2>    
    <form method ="post">  
      <table class="ui celled table" id="bids">
        <thead>
          <th> Select </th>
          <th> Taskee Email </th>
          <th> Tasker Email </th>
          <th> Task Status </th>
          <th> Bid Date and Time </th>          
        </thead>
        <tbody>
        </tbody>
      </table>        
        <br>
    </form>

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
