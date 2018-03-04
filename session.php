<?php
  session_start();

  function logout() {
    if (session_status() == PHP_SESSION_ACTIVE) {
      session_unset();
      session_destroy();
    }
  }

  function login($username) {
    $_SESSION['user'] = $username;
  }

  function isLoggedIn() {
    return isset($_SESSION['user']);
  }
?>