<!DOCTYPE html>

<?php
  require('debugging.php');
  require('session.php');
  
  redirectIfNot(null);

  if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];    
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 

    $insertQuery = "SELECT email, pword, firstname, isAdmin, isStaff FROM Taskees WHERE email='$email'";
    $result = pg_query($db, $insertQuery);        

    if (pg_num_rows($result) > 0) {
      $row = pg_fetch_row($result);      
      $email = $row[0];      
      $hash = $row[1];
      $firstName = $row[2];

      if (password_verify($password, $hash)) {
        login($firstName, 'taskee', $email, $row[3], $row[4]);
        if($row[3] == 't'){
          header('Location: /demo/admin.php');  
        } else {
          header('Location: /demo/taskeedashboard.php');  
          }    
      } else {        
        echo '<script language="javascript">';
        echo 'alert("Login failed. Please re-enter your details.")';   
        echo '</script>';  
      }       
    } else {        
        echo '<script language="javascript">';
        echo 'alert("Email does not exist. Please sign up instead.")';   
        echo '</script>';  
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
  <title>Login</title>
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
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/message.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/icon.css">

  <script src="assets/jquery-3.3.1.min"></script>
  <script src="semantic/dist/components/form.js"></script>
  <script src="semantic/dist/components/transition.js"></script>

  <style type="text/css">
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            email: {
              identifier  : 'email',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your e-mail'
                },
                {
                  type   : 'email',
                  prompt : 'Please enter a valid e-mail'
                }
              ]
            },
            password: {
              identifier  : 'password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your password'
                },
                {
                  type   : 'length[6]',
                  prompt : 'Your password must be at least 6 characters'
                }
              ]
            }
          }
        });
    });
  </script>
</head>
<body class='ui'>

<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui blue image header">
      <div class="content">
        Welcome Back, Taskee
      </div>
    </h2>
    <form class="ui large form" action="/demo/taskeelogin.php" method="POST">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" placeholder="E-mail address">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Password">
          </div>
        </div>
        <input type="submit" name="login" value="Login" class="ui fluid large button primary" tabindex="0" />
      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      New to us? <a href="/demo/taskeesignup.php">Sign Up</a>
    </div>
  </div>
</div>

</body>

</html>
