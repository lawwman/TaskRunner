
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
  $emailToEdit = $_SESSION['userEmail'];
  if ($_SESSION['isAdmin'] == "t") {
    $emailToEdit = $_SESSION['taskeeemail_admin_use'];
  }
  $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
  $taskeeInfo = pg_query($db, "SELECT * FROM Taskees WHERE email = '" . $emailToEdit . "'");
  if ($taskeeInfo) {
    $displayPhone = pg_fetch_result($taskeeInfo, 0, 4);
    $displayZipcode = pg_fetch_result($taskeeInfo, 0, 8);
  }  
  pg_close($db);

  if (isset($_POST['saveContact'])) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
    $phone = $_POST['phone'];
    $zipcode = $_POST['zipcode'];
    $updateQuery = "UPDATE Taskees SET (phone, zipcode) = ('$phone', '$zipcode') WHERE email = '" . $emailToEdit . "'";
    $result = pg_query($db, $updateQuery);
    if ($result) {
      consoleLog("contact and address updated successfully.");
      header('Location: /demo/edittaskeeprofile.php');
    } else {
      consoleLog("failed to update.");
    }
    pg_close($db);
  }

  if (isset($_POST['savePassword'])) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
    $taskeeInfo = pg_query($db, "SELECT * FROM Taskees WHERE email = '" . $emailToEdit . "'");
    $oldHash = pg_fetch_result($taskeeInfo, 0, 3);
    $oldpw = $_POST['oldpw'];
    if (password_verify($oldpw, $oldHash)) {
      consoleLog("password matches");
      $newpw = password_hash($_POST['newpw'], PASSWORD_DEFAULT); 
      $updateQuery = "UPDATE Taskees SET pword = '$newpw' WHERE email = '" . $emailToEdit . "'";
      $result = pg_query($db, $updateQuery);
      if ($result) {
        consoleLog("password updated successfully.");
        header('Location: /demo/edittaskeeprofile.php');
      } else {
        consoleLog("failed to update.");
      }
      pg_close($db);
    } else {
      echo "<script>alert('old password does not match!')</script>";
    }
  }

  if (isset($_POST['saveBilling'])) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
    $creditNum = $_POST['creditNum'];
    $creditSecurity = $_POST['creditSecurity'];
    $creditExpiry = date($_POST['expiryYear'] . '-' . $_POST['expiryMonth'] . '-01');
    $updateQuery = "UPDATE Taskees SET (creditnum, creditsecurity, creditexpiry) = ('$creditNum', '$creditSecurity', '$creditExpiry') WHERE email = '" . $emailToEdit . "'";
    $result = pg_query($db, $updateQuery);
    if ($result) {
      consoleLog("card info updated successfully.");
      header('Location: /demo/edittaskeeprofile.php');
    } else {
      consoleLog("failed to update.");
    }
    pg_close($db);
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

  $(document).ready(function() {
    $.ajax({
      type: "GET",
      url: '/demo/adminedit.php',
      data: { func: "isAdmin"},
      dataType: 'json',
      success: function(data) {
        if (data == "Yes") {
          console.log("is admin");
          $('#addBackToAdminPage').append('<button class="ui blue primary button" id="backToAdmin" >back to admin page</button> ');
          $('#backToAdmin').click(function() {
            window.location.replace("/demo/admin.php");
          })
        }
      }
    })
  });

  $(document).ready(function () {
    $('.menu .item').tab();
  })

  $(document).ready(function() {
      $('.ui.form.contact').form({
          phone: {
            identifier  : 'phone',
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

  $(document).ready(function() {
    $('.ui.form.billing').form({
      creditNum: {
        identifier : 'creditNum',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please enter a credit card number'
          }
        ]
      },
      creditSecurity: {
        identifier : 'creditSecurity',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please enter a security number'
          },
          {
            type   : 'length[3]',
            prompt : 'Please enter a 3 digit security number'
          }
        ]
      },
      expiryMonth: {
        identifier : 'expiryMonth',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please input an expiry month'
          }
        ]
      },
      expiryYear: {
        identifier : 'expiryYear',
        rules: [
          {
            type   : 'empty',
            prompt : 'Please input an expiry year'
          },
          {
            type   : 'length[4]',
            prompt : 'Please input a valid year'
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
        <a class="item" href="/demo/index.php">Home</a>
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
            <input type="text" name="phone" placeholder="Phone" value='<?php echo $displayPhone; ?>'>
          </div>        
          <div class="four wide field">
            <input type="text" name="zipcode" placeholder="Zipcode" value='<?php echo $displayZipcode; ?>'>
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
            <input type="password" name="oldpw" placeholder="Enter Old Password">
          </div>                                     
          <div class="three wide field">
            <input type="password" name="newpw" placeholder="Enter New Password">
          </div>  
        </div>
        <input type="submit" name="savePassword" value="Save Changes" class="ui button primary" tabindex="0" />
        <div class="ui error message"></div>
      </form>
    </div>

    <div class="ui bottom attached tab segment" data-tab="third">
      <form class="ui form billing" action="/demo/edittaskeeprofile.php" method="POST" >
        <h4 class="ui dividing header">Billing Information</h4>
        <div class="fields">
          <div class="seven wide field">
            <label>Card Number</label>
            <input type="text" name="creditNum" maxlength="16" placeholder="Card Number">
          </div>
          <div class="three wide field">
            <label>CVC</label>
            <input type="text" name="creditSecurity" maxlength="3" placeholder="CVC">
          </div>
          <div class="six wide field">
            <label>Expiration</label>
            <div class="two fields">
              <div class="field">
                <select class="ui fluid search dropdown" name="expiryMonth">
                  <option value="">Month</option>
                  <option value="1">January</option>
                  <option value="2">February</option>
                  <option value="3">March</option>
                  <option value="4">April</option>
                  <option value="5">May</option>
                  <option value="6">June</option>
                  <option value="7">July</option>
                  <option value="8">August</option>
                  <option value="9">September</option>
                  <option value="10">October</option>
                  <option value="11">November</option>
                  <option value="12">December</option>
                </select>
              </div>
              <div class="field">
                <input type="text" name="expiryYear" maxlength="4" placeholder="Year">
              </div>
            </div>
          </div>
        </div>
        <input type="submit" name="saveBilling" value="Save Changes" class="ui button primary" tabindex="0" />
        <div class="ui error message"></div>
      </form>
    </div>
    <div id="addBackToAdminPage">
    </div>
  </div>
</body>

</html>
