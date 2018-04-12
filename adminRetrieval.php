<?php
  require('debugging.php');
  $numRecordToRetrieve = 5;

  function getNextRows($pageNum, $tableName) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
    pg_query($db, "BEGIN") or die("Could not start transaction\n");
    $query =  "SELECT get" . $tableName . "Cursor('tempcursor')";
    $res2 = pg_query($db, $query);
    $query = "MOVE FORWARD " . ($pageNum * $GLOBALS['numRecordToRetrieve']) . " tempcursor";
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

  function getPrevRows($pageNum, $tableName) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
    pg_query($db, "BEGIN") or die("Could not start transaction\n");
    $query =  "SELECT get" . $tableName . "Cursor('tempcursor')";
    $res2 = pg_query($db, $query);
    $query = "MOVE FORWARD " . (($pageNum - 2) * $GLOBALS['numRecordToRetrieve']) . " tempcursor";
    $res1 = pg_query($db, $query);
    $query = "FETCH FORWARD " . ($GLOBALS['numRecordToRetrieve']) . " tempcursor";
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

  function retrieveData($table, $direction, $pageNum) {
    if ($direction == "forward") {
      return getNextRows($pageNum, $table);
      
    } else {
      return getPrevRows($pageNum, $table);
    }
  }

  function getNumPages($tableName) {
    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error());
    $query = "SELECT COUNT(*) FROM " . $tableName;
    $res = pg_query($db, $query);
    $count = pg_fetch_all($res);
    pg_close($db);
    return (int) ceil(((float) $count[0]['count']) /  $GLOBALS['numRecordToRetrieve']);
  }

  function deleteRecords($table, $records) {
    $success = true;

    $db = pg_connect("host=127.0.0.1 port=5432 dbname=project1 user=postgres password=1234") or die('Could not connect: ' . pg_last_error()); 
    foreach($records as $record) {
      $query = "DELETE FROM " . $table . " WHERE ";
      if ($table == "tasks") {
        $query = $query . "task_id = " . $record[0]; 
      } else if ($table == "taskees") {
        $query = $query . "email = '" . $record[0] . "'"; 
      } else if ($table == "taskers") {
        $query = $query . "email = '" . $record[0] . "'"; 
      } else if ($table == "bids") {
        $query = $query . "task_id = " . $record[0] . " AND taskeeEmail = '" . $record[1] . "' AND taskerEmail = '" . $record[2] . "'";
      } else if ($table == "skills") {
        $query = $query . "sname = '". $record[0] . "'";
      }
      $res = pg_query($db, $query);
      if ($res == false) {
        $success = false;
      }
    }

    pg_close($db);
    return $success;
  }
  function main() {
    if ($_SESSION['isAdmin'] == "t" || $_SESSION['isStaff'] == "t") {
      if ($_GET['func'] == "retrieve-data") {
        echo json_encode(retrieveData($_GET['table'], $_GET['dir'], $_GET['off']));
      } else if ($_GET['func'] == 'get-max-pages') {
        echo json_encode(getNumPages($_GET['table']));
      } else if ($_GET['func'] == 'delete-records') {
        $records = $_GET['arguments'];
        $table = $_GET['table'];
        $success = deleteRecords($table, $records);
        echo json_encode($success);
      }
    }
  }
?>