
<!DOCTYPE html>

<!-- PHP Portions -->
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

  if (isset($_POST['saveContact'])) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
    $phone = $_POST['contact'];
    $zipcode = $_POST['zipcode'];
    $updateQuery = "UPDATE Taskees SET (phone, zipcode) = ('$phone', '$zipcode') WHERE email = '" . $_SESSION['userEmail'] . "'";
    $result = pg_query($db, $updateQuery);
    if ($result) {
      consoleLog("contact and address updated successfully.");
      header('Location: /demo/edittaskeeprofile.php');
    } else {
      consoleLog("failed to update.");
    }
  }

  if (isset($_POST['savePassword'])) {
    consoleLog($_POST['newpw']);
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
  <title>Edit Profile</title>
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
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/tab.css">  

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/form.js"></script>
  <script src="semantic/dist/components/transition.js"></script>
  <script src="semantic/dist/components/dropdown.js"></script>  
  <script src="semantic/dist/components/tab.js"></script>  

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
        url: '/demo/taskeedashboard.php?argument=signOut',
        success: function(html){
          location.reload();
        }
      });
    });
   });
  $(document).ready(function () {
    $('.menu .item').tab();
  })

  $(document).ready(function() {
      $('.ui.form.contact').form({
          contact: {
            identifier  : 'contact',
            rules: [
              {
                type    : 'empty',
                prompt  : 'Please enter your contact number'
              }
            ]
          },
          zipcode: {
            identifier  : 'zipcode',
            rules: [
              {
                type    : 'empty',
                prompt  : 'Please enter your zipcode'
              }
            ]
          }
      });
    });

  $(document).ready(function() {
      $('.ui.form.pw').form({
          oldpw: {
            identifier : 'oldpw',
            rules: [
              {
                type   : 'empty',
                prompt : 'Please enter your old password'
              },
              {
                type   : 'length[6]',
                prompt : 'Your old password should be at least 6 characters'
              }
            ]
          },
          newpw: {
            identifier : 'newpw',
            rules: [
              {
                type   : 'empty',
                prompt : 'Please enter your new password'
              },
              {
                type   : 'length[6]',
                prompt : 'Your new password must be at least 6 characters long'
              }
            ]
          }
      });
    });
  </script>
</head>

<body class='ui'>

  <div class="ui inverted vertical masthead center aligned segment">

    <div class="ui container">
      <div class="ui large secondary inverted pointing menu">
        <a class="toc item">
          <i class="sidebar icon"></i>
        </a>
        <a class="active item">Home</a>
        <a class="item" href="/demo/viewcreatedtasks.php">View Created Tasks</a>
        <a class="item" href="/demo/addtasks.php">Add Task</a>        
        <div class="right item">
          <?php showUser(); ?> 
        </div>
      </div>
    </div>
  </div>

  <div class="ui container">
  <br><br>
  <h2 class="ui dividing header">Edit Profile Page</h2>

  <div class="ui top attached tabular menu">
    <a class="item active" data-tab="first">Change contact number / address</a>
    <a class="item" data-tab="second">Change password</a>
    <a class="item" data-tab="third">Change Credit Card info</a>
  </div>

  <div class="ui bottom attached tab segment active" data-tab="first">
    <form class="ui form contact" action="/demo/edittaskeeprofile.php" method="POST" >
      <div class="fields">                                 
        <div class="four wide field">
          <input type="text" name="contact" placeholder="Phone">
        </div>        
        <div class="four wide field">
          <input type="text" name="zipcode" placeholder="Address Zipcode">
        </div>  
      </div>
      <input type="submit" name="saveContact" value="Save Changes" class="ui button primary" tabindex="0" />
      <div class="ui error message"></div>
    </form>
  </div>

  <div class="ui bottom attached tab segment" data-tab="second">
    <form class="ui form pw" action="/demo/edittaskeeprofile.php" method="POST" >
      <div class="fields"> 
        <div class="three wide field">
          <input type="text" name="oldpw" placeholder="Enter Old Password">
        </div>                                     
        <div class="three wide field">
          <input type="text" name="newpw" placeholder="Enter New Password">
        </div>  
      </div>
      <input type="submit" name="savePassword" value="Save Changes" class="ui button primary" tabindex="0" />
      <div class="ui error message"></div>
    </form>
  </div>

  <div class="ui bottom attached tab segment" data-tab="third">
  </div>
</div>
</body>

</html>
