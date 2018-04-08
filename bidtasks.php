
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
      <div class="ui dropdown inverted button" id="editProfile">Hello, '. $_SESSION['userName'] . '</div>
      <div class="ui dropdown inverted button" id="signOut" formaction="/demo/signup.php">Sign Out</div>
      ';
    } else {
      echo "<a class='ui inverted button' href='/demo/login.php'>Log in</a>
      <a class='ui inverted button' href='/demo/signup.php'>Sign Up</a>";
    }
  }

  function showTasks() {
      // Connect to the database. Please change the password in the following line accordingly
    $taskerEmail = $_SESSION['userEmail'];
    $db     = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234"); 
    $result = pg_query($db, "
      SELECT * FROM tasks t1 WHERE (t1.status = 'not bidded' OR t1.status = 'bidded') AND 
      NOT EXISTS ( SELECT '1' FROM tasks t2 WHERE t2.taskeremail = '$taskerEmail' AND 
      ((t2.enddatetime > t1.startdatetime AND t2.startdatetime < t1.enddatetime) OR 
       (t2.enddatetime < t1.enddatetime AND t2.startdatetime > t1.startdatetime) OR
       (t2.enddatetime > t1.enddatetime AND t2.startdatetime < t1.startdatetime))
      ) AND
      NOT EXISTS ( SELECT '1' FROM bids b WHERE b.task_id = t1.task_id AND b.taskeremail = '$taskerEmail' AND b.status = 'pending') ");
    while ($row = pg_fetch_assoc($result)) {
      echo "<tr>
      <td> 
        <div class= 'ui checkbox'> 
          <input type = 'checkbox' name = 'checkboxTicked' value='$row[task_id]'>
        </div>
      </td>
      <td> $row[ttype]</td>
      <td> $row[task_details] </td> 
      <td> $row[taskeeemail] </td>
      <td> $row[status] </td>
      <td> $row[createddatetime] </td>
      <td> $row[startdatetime] </td>
      <td> $row[enddatetime] </td>
      <td> $row[loc] </td>
      </tr>";
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
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/modal.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dimmer.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/table.css">  
  <!-- <link rel="stylesheet" type="text/css" href="semantic/dist/components/checkbox.css">  --> 

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>
  <script src="semantic/dist/components/modal.js"></script>
  <script src="semantic/dist/components/dimmer.js"></script>

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
        window.location.replace('/demo/edittaskerprofile.php');
      });
    })
  </script>

  <script>
    var allTaskid= new Array();

    $(document).ready(function(){
      $('#bid_submit').click(function() {
        
        $('input:checkbox[name="checkboxTicked"]:checked').each(function(){
            allTaskid.push(this.value);
          });

        var taskidJSON = JSON.stringify(allTaskid);

        $.ajax({
          url:'/demo/taskerbid.php',
          type: 'POST',
          dataType: 'json',
          data: {taskid: taskidJSON},
          success: function(data){
            //window.location.replace("/demo/taskerdashboard.php");
            console.log(data.abc);
          }
        });
      });
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
      padding: 1em 0em;
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
          <a class="active item" href="/demo/bidtasks.php">View Available Tasks to Bid</a>
          <a class="item" href="/demo/viewrunningtasks.php">View Tasks I Am Running</a>
        <div class="right item">
          <?php showUser(); ?> 
        </div>
      </div>
    </div>
  </div>

  <div class="my_container">
    <form method ="post">  
      <table class="ui celled table">
        <thead>
          <th> Bid </th>
          <th> Task Type </th>
          <th> Task Details </th>
          <th> Taskee Email </th>
          <th> Status </th>
          <th> Created Date and Time </th>
          <th> Start Date and Time </th>
          <th> End Date and Time </th>
          <th> Location </th>
        </thead>
        <tbody>
          <?php showTasks(); ?> 
        </tbody>
      </table>
        <button class ="ui right floated large teal button" id = "bid_submit" > Bid! </button>
        </br>
    </form>
  </div>
</div>



  <!-- Footer -->
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

</body>
</html>
