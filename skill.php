<!DOCTYPE html>

<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Homepage - Semantic</title>

  <link rel="stylesheet" type="text/css" href="assets/jquery-ui/jquery-ui.css">

  <script src="assets/jquery-3.3.1.min"></script>

  <script src="assets/jquery-ui/jquery.js"></script>
  <script src="assets/jquery-ui/jquery-ui.min.js"></script>
  <script src="list.js"></script>
  <script>
  var availableTags = getList();
  console.log(availableTags);
  console.log("updated2");

  $(document).ready(function(){
    var suggestions;
    $.ajax({ type: 'GET', url: "suggestSkill.php", success: function(data) {
      suggestions = JSON.parse(data);
      console.log(suggestions);
      $('#developer').autocomplete({
        source: suggestions
      });
      }
    });

    $('#worker').autocomplete({
      source: availableTags
    });
  });
  </script>

</head>
<body>
<div class="ui-widget">
  <form>
  <label >Working AutoComplete: </label>
  <input id="worker">
  </form>
</div>

<br>

<div class="ui-widget">
  <form>
  <label >Developer: </label>
  <input id="developer">
  </form>
</div>

</body>

</html>
