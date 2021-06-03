<!-- begin header -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/style.css" as="style">
<!-- end header -->

<?php 
    $RFQ_ID = $_GET["id"];
    $buyer_GUID = $_GET["buyer_id"];

    $rfq_item_name = '';
    $rfq_item_desc = '';
    $rfq_item_custom = '';
    $buyer_name = '';

    require '../vendor/arcadier/arcadier-php/src/api.php';
    $sdk = new ApiSdk();

    $tableName = 'Submitted_RFQs';
    $packageId = $sdk->getPackageID();

    $rfq_list = $sdk->getCustomTable($packageId, $tableName);

    foreach ($rfq_list['Records'] as $entry) {
        if($entry['Id'] == $RFQ_ID){
            $rfq_item_name = $entry['Name'];
            $rfq_item_desc = $entry['Description'];
            $rfq_item_custom = $entry['custom_text'];

            $buyer_details = $sdk->getUserInfo($buyer_id, null);
            $buyer_name = $buyer_details['DisplayName'];

            break;
        }
    }
?>

<div class="seller-titlearea">
    <div class="container">
        <div class="row">
            <h1>
                <span class="text-capitalize"><strong>Submit RFQ</strong></span>
            </h1>
        </div>
    </div>
</div>
<div class="grey_section">
    <div class="container">
        <div class="seller-sublit-rfq">
            <a href="seller-view-rfq.html" class="back-link">
                <svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 2.11458L3 5.80434L8 9.4941L7 10.97L0 5.80434L7 0.638672L8 2.11458Z" fill="#EC6665"/></svg> Back</a>
            <div class="row">
                <div class="col-sm-7">
                    <div class="rfq-view">
                        <div class="rfq-attr">
                            <p class="rfq-title">Item Name: </p>
                            <p id="rfq-title-view"><?php echo $rfq_item_name; ?></p>
                        </div>
                        <div class="rfq-attr">
                            <p class="rfq-title">Description: </p>
                            <div class="rfq--desc"><p id="rfq-desc-view"><?php echo $rfq_item_desc; ?></p></div>
                        </div>
                        <div class="rfq-attr">
                            <p class="rfq-title">Requirements:</p>
                            <div class="rfq--desc"><p id="rfq-desc-requirement"><?php echo $rfq_item_custom; ?></p></div>
                        </div>
                        <div class="rfq-attr">
                            <p class="rfq-title">Consumer Name:</p>
                            <p id="rfq-desc-consumer"><?php echo $buyer_name; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="rfq-submit-form">
                        <div class="item-form-group">
                            <div class="col-md-12">
                                <label for="proposed-price">Proposed Price</label>
                                <input class="required numbersOnly" name="price" id="price" type="text" value="">
                            </div>
                            <div class="col-md-12">
                                <label for="message-consumer">Message to Consumer</label>
                                <textarea name="message" id="message" value=""></textarea>
                            </div>
                            <div class="col-md-12">
                                <input id = "rfq_id" type="hidden" value="<?php echo $RFQ_ID; ?>">
                                <input id = "buyer-guid" type="hidden" value="<?php echo $buyer_GUID; ?>">
                                <a href="javascript:void(0);" class="btn-red send-quote" id="submit_quote">Send Quote</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>

<!-- begin footer -->
    <script src="scripts/merchant_Accept_RFQ.js"></script>
<!-- end footer -->




