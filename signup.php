
<!DOCTYPE html>
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

<div class="ui middle aligned center aligned grid inverted">
  <div class="six wide column">
    <form class="ui form">
      <h2 class="ui dividing header">Sign Up Information</h2>

      <div class="two field">
        <label>Account Details</label>
        <div class="fields">
          <div class="nine wide field">
            <input type="text" name="user[username]" placeholder="Username">
          </div>
          <div class="seven wide field">
            <input type="text" name="user[password]" placeholder="Password">
          </div>
        </div>

      </div>    
      <div class="field">
        <label>Contact Details</label>
        <div class="two fields">
          <div class="field">
            <input type="text" name="user[first-name]" placeholder="First Name">
          </div>
          <div class="field">
            <input type="text" name="user[last-name]" placeholder="Last Name">
          </div>
        </div>        
        <div class="fields">
          <div class="seven wide field">
            <input type="text" name="user[email]" placeholder="Email">
          </div>
          <div class="four wide field">
            <input type="text" name="user[contact]" placeholder="Phone">
          </div>
          <div class="five wide field">
            <input type="text" name="user[occupation]" placeholder="Occupation">
          </div>
        </div>
      </div>
       <div class="ui segment">
        <div class="field">
          <div class="ui checkbox">
            <input type="checkbox" name="gift" tabindex="0" class="hidden">
            <label>I agree to the <a href="#">Terms and Conditions</a></label>
          </div>
        </div>
      </div>
      <div class="ui button primary" tabindex="0">Register</div>
    </form>
  </div>
</div>

</body>

</html>
