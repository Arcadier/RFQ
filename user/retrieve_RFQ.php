<?php
    require '../vendor/arcadier/arcadier-php/src/api.php';
    $sdk = new ApiSdk();

    $tableName = 'Submitted_RFQs';
    $packageId = $sdk->getPackageID();

    $rfq_list = $sdk->getCustomTable($packageId, $tableName);
    $new_found = false;

    $content = '';

    foreach ($rfq_list['Records'] as $entry) {
        if($entry['status'] == 'Pending'){
            $new_found = true;
            $rfq_item_name = $entry['Name'];
            $rfq_item_desc = $entry['Description'];
            $rfq_item_custom = $entry['custom_text'];
            $buyer_id = $entry['buyer_id'];

            $buyer_details = $sdk->getUserInfo($buyer_id, null);
            $buyer_name = $buyer_details['DisplayName'];

            $content .= '<div class="col-sm-3">';
            $content .= '   <div class="rfq-view">';
            $content .= '       <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">Item Name: </p>';
            $content .= '           <p>' . $rfq_item_name . '</p>';
            $content .= '       </div>';
            $content .= '       <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">Description: </p>';
            $content .= '           <p>' . $rfq_item_desc . '</p>';
            $content .= '       </div>';
            $content .= '       <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">Requirements: </p>';
            $content .= '           <p>' . $rfq_item_custom . '</p>';
            $content .= '       </div>';
            $content .= '       <div class="rfq-attr">';
            $content .= '           <p class="rfq-title">Consumer Name: </p>';
            $content .= '           <p>' . $buyer_name . '</p>';
            $content .= '       </div>';
            $content .= '       <div class="text-center">';
            //$content .= '           <a class="more-detail" href="javascript:void(0);">Click here for more details</a>';
            $content .= '           <a href="merchant_accept_RFQ.php?id=' . $entry['Id'] . '&buyer_id=' . $buyer_id . '" target="_blank" class="btn-red">Send Quote</a>';
            $content .= '           <input type="hidden" id="buyer_id" value="' . $buyer_id . '">';
            $content .= '       </div>';
            $content .= '   </div>';
            $content .= '</div>';           
        }

        echo $content;
    }
    if($new_found == false){
        echo "No New RFQ's found";
    }
    // echo json_encode($rfq_list);
?>


