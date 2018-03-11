<!DOCTYPE html>  

<?php 

  require('debugging.php');

  function printEn($pass) {
    $encrypted = password_hash($pass, PASSWORD_DEFAULT);
    consoleLog($pass);
    consoleLog($encrypted);
  }

  printEn('johndoe5');
  printEn('johndoe6');
  printEn('johndoe7');
  printEn('johndoe8');
  printEn('johndoe9');
  printEn('johndoe10');
?>