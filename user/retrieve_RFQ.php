<?php
    require '../vendor/arcadier/arcadier-php/sdk/ApiSdk.php';
    $sdk = new ApiSdk();

    $tableName = 'Submitted_RFQs';
    $packageId = $sdk->getPackageID();

    $rfq_list = $sdk->getCustomTable($packageId, $tableName);
    $new_found = false;

    foreach ($rfq_list['Records'] as $entry) {
        if($entry['status'] == 'Pending'){
            $new_found = true;
            $rfq_item_name = $entry['Name'];
            $rfq_item_desc = $entry['Description'];
            $rfq_item_custom = $entry['custom_text'];
            $buyer_id = $entry['buyer_id'];

            $buyer_details = $sdk->getUserInfo($buyer_id, null);
            $buyer_name = $buyer_details['DisplayName'];

            echo '<div class="box"><p class="item_name"> Item Name: '. $rfq_item_name. '</p><br><p class="item_desc"> Item Description: ' . $rfq_item_desc . '</p><p class="item_cf"> Specs: '.$rfq_item_custom.'</p><br><p class="item_cf"> Buyer Name: '. $buyer_name.'</p><div class="btn-container"><a href="merchant_accept_RFQ.php?id='.$entry['Id'].'&buyer_id='.$buyer_id.'" target="_blank" class="btn center">Submit Quote for this</a></div><input type="hidden" id="buyer_id" value="'.$buyer_id.'"></div>';
        }
    }
    if($new_found == false){
        echo "No New RFQ's found";
    }
    // echo json_encode($rfq_list);
?>


