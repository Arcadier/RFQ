<?php
    require '../vendor/arcadier/arcadier-php/src/api.php';
    $sdk = new ApiSdk();

    $contentBodyJson = file_get_contents('php://input');
    $content = json_decode($contentBodyJson, true);

    $buyer_id = $content['buyer_id'];
    $tableName = 'accepted_RFQs';
    $packageId = $sdk->getPackageID();

    $proposal_list = $sdk->getCustomTable($packageId, $tableName);
    $new_found = false;

    foreach ($proposal_list['Records'] as $entry) {
        if($entry['Buyer_ID'] == $buyer_id && $entry['status'] == "Pending"){
            $new_found = true;
            $proposal_id = $entry['Id'];
            $proposed_price = $entry['Price'];
            $proposal_message = $entry['Message'];
            $rfq_id = $entry['RFQ_ID'];
            $merchant_id = $entry['Merchant_ID'];

            //query RFQ table
            $tableName = 'Submitted_RFQs';
            $rfq_list = $sdk->getCustomTable($packageId, $tableName);
            foreach ($rfq_list['Records'] as $rfq) {
                if($rfq['Id'] == $rfq_id){
                    $rfq_item_name = $rfq['Name'];
                    $rfq_item_desc = $rfq['Description'];
                    $rfq_item_custom = $rfq['custom_text'];
                }
            }
            // 

            $merchant_details = $sdk->getUserInfo($merchant_id, null);
            $merchant_name = $merchant_details['DisplayName'];

            echo '<div class="box"><p class="item_name"> RFQ: '. $rfq_item_name. '</p><br><p class="item_desc"> RFQ Description: ' . $rfq_item_desc . '</p><p class="item_cf"> RFQ Specs: '.$rfq_item_custom.'</p><br><p class="item_cf"> Merchant\'s Name: '. $merchant_name.'</p><br><p>Merchant\'s Price: '.$proposed_price.'</p><br><p> Merchant\'s message: '.$proposal_message.'</p><div class="btn-container"><a href="javascript:void(0)" onclick="changeStatus('.$proposal_id.')" class="btn center">Accept this proposal</a></div></div>';
        }
    }
    if($new_found == false){
        echo "No proposals found";
    }
    // echo json_encode($rfq_list);
?>


