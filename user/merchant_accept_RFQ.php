<!-- begin header -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/style.css" as="style">
<!-- end header -->

<?php 
    $RFQ_ID = $_GET["id"];
    $buyer_GUID = $_GET["buyer_id"]
?>
<h1 style="text-align: center;">Submit proposal to buyer</h1>
<br>
<div style="padding: 30px; margin: auto; margin-top: 20px;">
Proposed Price: <input name="price" id="price" value="">
<br>
Message to buyer: <input name="message" id="message" value ="">
<input id = "rfq_id" type="hidden" value="<?php echo $RFQ_ID; ?>">
<input id = "buyer-guid" type="hidden" value="<?php echo $buyer_GUID; ?>">
<button id="submit_quote">Send Quote></button>
</div>


<!-- begin footer -->
    <script src="scripts/merchant_Accept_RFQ.js"></script>
<!-- end footer -->




