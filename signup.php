
<!DOCTYPE html>

<!-- PHP Portions -->
<?php
  require('debugging.php');

  function isUserUnique(&$nameError, &$emailError) {
    $username = "";
    $email = "";

    $username = $_POST['username'];
    $email = $_POST['email'];
      
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 

    $usernameQuery = "SELECT username FROM Users WHERE username='$username'";
    $emailQuery = "SELECT username FROM Users WHERE email='$email'";
    $usernameResult = pg_query($db, $usernameQuery);
    $emailResult = pg_query($db, $emailQuery);

    if (pg_num_rows($usernameResult) > 0) {
      $nameError = "Sorry... username already taken";  
    }

    if (pg_num_rows($emailResult) > 0) {
      $emailError = "Sorry... email already taken";  
    }

    return empty($nameError) and empty($emailError);
  }

  if (isset($_POST['register'])) {
    $nameError = "";
    $emailError = "";
    consoleLog(isUserUnique($nameError, $emailError));
    if (isUserUnique($nameError, $emailError)) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];
      $firstName = $_POST['first-name'];
      $lastName = $_POST['last-name'];
      $contact = $_POST['contact'];
      $occupation = $_POST['occupation'];
      $birthdate = $_POST['birthdate'];

      $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 

      $insertQuery = "INSERT INTO Users Values('$username', '$password', '$firstName', '$lastName', '$email', '$contact', '$occupation', '$birthdate')";
      consoleLog($insertQuery);
      $result = pg_query($db, $insertQuery); 
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
  <title>Signup</title>
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="semantic/dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/menu.css">

  <link rel="stylesheet" type="text/css" href=".semantic/dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/form.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/input.css">
  <link rel="stylesheet" type="text/css" href="semantic/dist/components/checkbox.css">
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
  </style>
  
  <script>
  
  $(document).ready(function() {
    $('.ui.form').form({
      fields: {
        username: {
          identifier  : 'username',
          rules: [
            {
              type   : 'empty',
              prompt : 'Please enter a valid username'
            },
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
        },

        // "-", "$" and spaces are not allowed in key names for Javascript.
        first_name: {
          identifier  : 'first-name',
          rules: [
            {
              type   : 'empty',
              prompt : 'Please enter your first name'
            },
          ]
        }, 

        last_name: {
          identifier  : 'last-name',
          rules: [
            {
              type   : 'empty',
              prompt : 'Please enter your last name'
            }
          ]
        },

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

        date: {
          identifier  : 'date',
          rules: []
        }, 

        contact: {
          identifier  : 'contact',
          rules: []
        }, 

        occupation: {
          identifier  : 'occupation',
          rules: [
            {
              type   : 'empty',
              prompt : 'Please enter your occupation'
            },
          ]
        },

        tncs: {
          identifier  : 'tnc',
          rules: [
            {
              type   : 'checked',
              prompt : 'Please enter your occupation'
            },
          ]
        }
      
      
      }
    });
  });
  
  </script>
</head>
<body class='ui'>

<div class="ui middle aligned center aligned grid inverted">
  <div class="six wide column">
    <form class="ui form" action="/demo/signup.php" method="POST" >
      <h2 class="ui dividing header">Sign Up Information</h2>

      <div class="three field">
        <label>Account Details</label>
        <div class="fields">
          <div class="seven wide field">
            <input type="text" name="email" placeholder="Email" value='<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>'>
              <?php if (!empty($emailError)): ?>
                <span><?php echo $emailError; ?></span>
              <?php endif ?>
          </div>          
          <div class="five wide field">
            <input type="text" id='username' name="username" placeholder="Username" value='<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>'>
              <?php if (!empty($nameError)): ?>
                <span><?php echo $nameError; ?></span>
              <?php endif ?>
          </div>
          <div class="four wide field">
            <input type="password" name="password" placeholder="Password" value='<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>'>
          </div>
        </div>
      </div>

      <div class="field">
        <label>Profile Details</label>
        <div class="two fields">
          <div class="field">
            <input type="text" name="first-name" placeholder="First Name" value='<?php echo isset($_POST['first-name']) ? $_POST['first-name'] : ''; ?>'>
          </div>
          <div class="field">
            <input type="text" name="last-name" placeholder="Last Name" value='<?php echo isset($_POST['last-name']) ? $_POST['last-name'] : ''; ?>'>
          </div>
        </div>        
        <div class="fields">
          <div class="five wide field">
            <input type="date" name="birthdate" value='<?php echo isset($_POST['birthdate']) ? $_POST['birthdate'] : ''; ?>'>
          </div>
          <div class="six wide field">
            <input type="text" name="occupation" placeholder="Occupation" value='<?php echo isset($_POST['occupation']) ? $_POST['occupation'] : ''; ?>'>
          </div>          
          <div class="five wide field">
            <input type="text" name="contact" placeholder="Phone" value='<?php echo isset($_POST['contact']) ? $_POST['contact'] : ''; ?>'>
          </div>
        </div>
      </div>
       <div class="ui segment">
        <div class="field">
          <div class="ui checkbox">
            <input type="checkbox" name="tnc" tabindex="0">
            <label>I agree to the <a href="#">Terms and Conditions</a></label>
          </div>
        </div>
      </div>
      <input type="submit" name="register" value="Register" class="ui button primary" tabindex="0" />
    </form>

  </div>
</div>
</body>

</html>
