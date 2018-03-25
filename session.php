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
   * Redirects to login page if user is not logged in.
   */
  function redirectToLogin() {
    if (!isLoggedIn()) {
      header('Location: /demo/login.php');
    }
  }
?>