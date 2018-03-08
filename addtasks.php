<!DOCTYPE html>

<!-- PHP Portions -->
<?php
  require('debugging.php');
  require('session.php');

  redirectToLogin();

  // Connect to the database. Please change the password in the following line accordingly
  $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect ' . pg_last_error());  

  if (isset($_POST['addtask'])) {
    $task_id = rand(123465,123900);
    $task_name = $_POST['taskname'];
    $task_details = $_POST['description'];
    $duration_minutes = $_POST['dropdown'];
    $creator = $_SESSION['user'];
    $runner = null;
    $reward = $_POST['reward'];
    $status = 'not bidded';
    date_default_timezone_set('Asia/Singapore');
    $createddatetime = date('Y-m-d H:i:s');

    $result = "INSERT INTO tasks VALUES($task_id, '$task_name', '$task_details', $duration_minutes, '$creator', null, $reward, '$status', '$createddatetime')";    
    $addTaskResult = pg_query($db, $result);
    
    header('Location: /demo/index.php');
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
  
  $(document).ready(function() {
    $('.ui.form').form({
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
          <a class="item" href="/demo/viewmytasks.php">My Tasks</a>
          <a class="item" href="/demo/viewbids.php">My Bids</a>
          <a class="active item" href="/demo/addtasks.php">Add Tasks</a>
          <div class="right item">
            <a class="ui inverted button" href="/demo/login.php">Log in</a>
            <a class="ui inverted button" href="/demo/signup.php">Sign Up</a>
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
            <div class="eleven wide field">
              <input type="text" name="reward" placeholder="Reward">
            </div>                  
              
            <div class="field">                
              <select class="ui dropdown" name="dropdown">
                <option value="">Durations (hours)</option>
                <option value="24">24</option>
                <option value="48">48</option>
                <option value="72">72</option>
              </select>
            </div>         
          </div> 

          <input type="submit" name="addtask" value="Submit" class="ui button primary" tabindex="0" />
        </form>        
      </div>
    </div>
  </div>

</body>

</html>