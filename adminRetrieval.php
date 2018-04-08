<?php
  require('debugging.php');
  $numRecordToRetrieve = 5;

  function getNextRows($offset, $tableName) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
    pg_query($db, "BEGIN") or die("Could not start transaction\n");
    $query =  "SELECT get" . $tableName . "Cursor('tempcursor')";
    $res2 = pg_query($db, $query);
    $query = "MOVE FORWARD " . ($offset * $GLOBALS['numRecordToRetrieve']) . " tempcursor";
    $res1 = pg_query($db, $query);
    $res1 = pg_query($db, "FETCH FORWARD " . $GLOBALS['numRecordToRetrieve'] . " tempcursor");
    if ($res1 and $res2) {
      pg_query($db, "COMMIT") or die("Transaction commit failed");
    } else {
      pg_query($db, "ROLLBACK") or die("Transaction rollback failed\n");
    }
    $arr = pg_fetch_all($res1);

    pg_close($db);
    return $arr;
  }

  function getPrevRows($offset, $tableName) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
    pg_query($db, "BEGIN") or die("Could not start transaction\n");
    $query =  "SELECT get" . $tableName . "Cursor('tempcursor')";
    $res2 = pg_query($db, $query);
    $query = "MOVE FORWARD " . ($offset * $GLOBALS['numRecordToRetrieve'] + 1) . " tempcursor";
    $res1 = pg_query($db, $query);
    $query = "FETCH BACKWARD " . ($GLOBALS['numRecordToRetrieve']) . " tempcursor";
    $res1 = pg_query($db, $query);
    if ($res1 and $res2) {
      pg_query($db, "COMMIT") or die("Transaction commit failed");
    } else {
      pg_query($db, "ROLLBACK") or die("Transaction rollback failed\n");
    }
    $arr = pg_fetch_all($res1);

    pg_close($db);
    return $arr;
  }
?>