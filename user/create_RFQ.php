<!-- begin header -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/style.css">
<!-- end header -->

<h1 style="text-align: center;">Create a Request for Quotation</h1>

<form style="padding: 30px; margin: auto; margin-top: 20px;"action="submit.php">
  <label for="item_name">Item name:</label><br>
  <input type="text" id="item_name" name="item_name" value=""><br>
  <label for="item_desc">Description:</label><br>
  <input type="text" id="item_desc" name="item_desc" value=""><br><br>
  <label for="item_custom_text">Requirements:</label><br>
  <input type="text" id="item_custom_text" name="item_custom_text" value=""><br><br>
  <input type="hidden" name="buyer_id" id="buyer_id" value="">
  <input type="submit" value="Submit">
</form>

<!-- begin footer -->
<!-- <script src="scripts/scripts.js"></script> -->
<!-- end footer -->
