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

  // Connect to the database. Please change the password in the following line accordingly
  $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error());  

  if (isset($_POST['addtask'])) {
    //$task_id = uniqid (rand (),true);
    $task_id = rand();
    $task_name = $_POST['taskname'];
    $task_details = $_POST['description'];
    $duration_minutes = $_POST['dropdown'];
    $runner = null;
    $reward = $_POST['reward'];
    $creator = $_SESSION['user'];
    $status = 'not bidded';
    date_default_timezone_set('Asia/Singapore');
    $createddatetime = date('Y-m-d H:i:s');

    $insertQuery = "INSERT INTO tasks VALUES($task_id, '$task_name', '$task_details', $duration_minutes, '$creator', null, $reward, '$status', '$createddatetime')";    
    $add_task_result = pg_query($db, $insertQuery);
    
    if($add_task_result){
//      echo 'Task Added Successfully!';
      header('Location: /demo/index.php');  
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
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/dropdown.css">  

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/form.js"></script>
  <script src="semantic/dist/components/checkbox.js"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>  

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
    })  

    $(document).ready(function() {      
      var $select = $(".1-120");
      $select.append('<option>Duration (Hours)</option>')
      for (i=1;i<=120;i++){
        $select.append($('<option></option>').val(i).html(i))
      }
    })     
    
  $(document).ready(function() {
    $('.ui.form').form({
      on: 'blur',      
      fields: {
        taskname: {
          identifier  : 'taskname',
          rules: [
            {
              type   : 'empty',
              prompt : 'Please enter your task name'
            },
          ] 
        },             

        description: {
          identifier  : 'description',
          rules: [
            {
              type   : 'empty',
              prompt : 'Please enter your task description'
            },
            {
              type   : 'length[10]',
              prompt : 'Your description must be at least 10 characters'
            }
          ]
        },
      
        reward: {
          identifier  : 'reward',
          rules: [
            {
             type    : 'empty',
             promt   : 'Please enter your reward amount'  
            },
            {
             type    : 'integer',
             prompt  : 'Please enter only integers'
            }
          ] 
        }

      }
    });
  });



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

    <!-- Form for task adding-->
    <div class="ui middle aligned center aligned grid inverted">
      <div class="six wide column">
        <form class="ui form" action="/demo/addtasks.php" method="POST">
          <h2 class="ui dividing header">Task Details</h2>

          <div class="one field">
            <label>Task Name</label>
            <div class="fields">
              <div class="sixteen wide field">
                <input type="text" name="taskname" placeholder="Task Name">
              </div>
            </div>
          </div>    
          
          <div class="field">
            <label>Task Details</label>
            <div class="fields">
              <div class=" sixteen wide field">
                <input style = "height: 300px;" type="text" name="description" placeholder="Description">
              </div>
            </div> 
          </div>
            
          <div class="fields">
            <div class="ten wide field">
              <input type="text" name="reward" placeholder="Reward">
            </div>                  
              
            <div class="field">                
              <select class="1-120" name="dropdown">
              </select>
            </div>         
          </div>           
          <input type="submit" name="addtask" value="Submit" class="ui button primary" tabindex="0" />
          
          <div class="ui error message"></div>
        </form>        
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