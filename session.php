<?php
  session_start();

  /**
   * Logs the current user out. 
   */
  function logout() {
    if (session_status() == PHP_SESSION_ACTIVE) {
      session_unset();
      session_destroy();
    }
  }

  /**
   * Logs into param $email account. 
   */
  function login($userName, $userType, $email) {
    $_SESSION['userName'] = $userName;
    $_SESSION['userType'] = $userType;
    $_SESSION['userEmail'] = $email;
  }

  /**
   * Returns true if user is logged in.
   */
  function isLoggedIn() {
    return isset($_SESSION['userEmail']);
  }

  /**
   * Redirects user if does nto fulfil $pageCondition
   */
  function redirectIfNot($pageCondition) {
    // if user is not logged in and the page requires login
    if (!is_null($pageCondition) && !isLoggedIn()) {
      if ($_SESSION['userType'] == 'taskee') {
        header('Location: /demo/taskeelogin.php');
      } else if ($_SESSION['userType'] == 'tasker') {
        header('Location: /demo/taskerlogin.php');
      } else {
        header('Location: /demo/index.php');
      }
    }

    if (is_null($pageCondition) && isLoggedIn()) {
      if ($_SESSION['userType'] == 'taskee') {
        header('Location: /demo/taskeedashboard.php');
      } else {
        header('Location: /demo/taskerdashboard.php');
      }
    }
  }
?>