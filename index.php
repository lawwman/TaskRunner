<!DOCTYPE html>  

<head>
  <title>UPDATE PostgreSQL data with PHP</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>li {list-style: none;}</style>


	<link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
	<script
  	src="https://code.jquery.com/jquery-3.1.1.min.js"
  	integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  	crossorigin="anonymous"></script>
	<script src="semantic/dist/semantic.min.js"></script>
</head>



<body>
  <h2>Supply bookid and enter</h2>
  <ul>
    <form name="display" action="index.php" method="POST" >
      <li>Book ID:</li>
      <li><input type="text" name="bookid" /></li>
      <li><input type="submit" name="submit" /></li>
    </form>
  </ul>
  <?php
  	// Connect to the database. Please change the password in the following line accordingly
    $db     = pg_connect("host=localhost port=5432 dbname=Project1 user=postgres password=1234");	
    $result = pg_query($db, "SELECT * FROM book where book_id = '$_POST[bookid]'");		// Query template
    $row    = pg_fetch_assoc($result);		// To store the result row
    if (isset($_POST['submit'])) {
        echo "<ul><form name='update' action='index.php' method='POST' >  
    	<li>Book ID:</li>  
    	<li><input type='text' name='bookid_updated' value='$row[book_id]' /></li>  
    	<li>Book Name:</li>  
    	<li><input type='text' name='book_name_updated' value='$row[name]' /></li>  
    	<li>Price (USD):</li><li><input type='text' name='price_updated' value='$row[price]' /></li>  
    	<li>Date of publication:</li>  
    	<li><input type='text' name='dop_updated' value='$row[date_of_publication]' /></li>  
    	<li><input type='submit' name='new' /></li>  
    	</form>  
    	</ul>";
    }
    if (isset($_POST['new'])) {	// Submit the update SQL command
        $result = pg_query($db, "UPDATE book SET book_id = '$_POST[bookid_updated]',  
    name = '$_POST[book_name_updated]',price = '$_POST[price_updated]',  
    date_of_publication = '$_POST[dop_updated]'");
        if (!$result) {
            echo "Update failed!!";
        } else {
            echo "Update successful!";
        }
    }
    ?>  

 <button class="ui labeled icon button">
  <i class="pause icon"></i>
  Pause
</button>
<button class="ui right labeled icon button">
  <i class="right arrow icon"></i>
  Next
</button>
</body>
</html>
