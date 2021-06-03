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
    $content = '';

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

            $merchant_details = $sdk->getUserInfo($merchant_id, null);
            $merchant_name = $merchant_details['DisplayName'];

            $content .= '<div class="col-sm-3">';
            $content .= '   <div class="rfq-view">';
            $content .= '        <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">RFQ: </p>';
            $content .= '           <p>' . $rfq_item_name . '</p>';
            $content .= '        </div>';
            $content .= '        <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">RFQ Description: </p>';
            $content .= '           <p>' . $rfq_item_desc . '</p>';
            $content .= '        </div>';
            $content .= '        <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">RFQ Specs: </p>';
            $content .= '           <p>' . $rfq_item_custom . '</p>';
            $content .= '        </div>';
            $content .= '        <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">Merchant Name: </p>';
            $content .= '           <p>' . $merchant_name. '</p>';
            $content .= '        </div>';
            $content .= '        <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">Proposed Price:</p>';
            $content .= '           <p>' . $proposed_price . '</p>';
            $content .= '        </div>';
            $content .= '        <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">Message to Consumer:</p>';
            $content .= '           <p>' . $proposal_message . '</p>';
            $content .= '        </div>';
            $content .= '        <div class="text-center">';
            //$content .= '           <a class="more-detail" href="javascript:void(0);">Click here for more details</a>';
            $content .= '           <a href="javascript:void(0)" onclick="changeStatus(\'' . $proposal_id . '\')" class="btn-red">Accept Proposal</a>';
            $content .= '        </div>';
            $content .= '   </div>';
            $content .= '</div>';
        }
    }
    
    if($new_found == true){
        echo $content;
    }
    else{
        echo "No proposals found";
    }
?>


