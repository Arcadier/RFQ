<?php
    $item_name = $_GET["item_name"];
    $item_desc = $_GET["item_desc"];
    $item_custom_text = $_GET["item_custom_text"];
    $buyer_id = $_GET["buyer_id"];

    $item_custom_text_1 =  $_GET["field-value-1"];
    $item_custom_text_2 =  $_GET["field-value-2"];
    $item_custom_text_3 =  $_GET["field-value-3"];
    $item_custom_text_4 =  $_GET["field-value-4"];
    $item_custom_text_5 =  $_GET["field-value-5"];
    $item_custom_text_6 =  $_GET["field-value-6"];
    $item_custom_text_7 =  $_GET["field-value-7"];
    $item_custom_text_8 =  $_GET["field-value-8"];
    $item_custom_text_9 =  $_GET["field-value-9"];
    $item_custom_text_10 =  $_GET["field-value-10"];

    require '../vendor/arcadier/arcadier-php/src/api.php';
    $sdk = new ApiSdk();

    //save item details in Buyer Created RFQ
    $tableName = 'Submitted_RFQs';
    $packageId = $sdk->getPackageID();
    $data = [
        'Name' => $item_name,
        'Description' => $item_desc,
        'custom_text' => $item_custom_text,
        'status' => 'Pending',
        'buyer_id' => $buyer_id,
        'custom_text_1' => $item_custom_text_1,
        'custom_text_2' => $item_custom_text_2,
        'custom_text_3' => $item_custom_text_3,
        'custom_text_4' => $item_custom_text_4,
        'custom_text_5' => $item_custom_text_5,
        'custom_text_6' => $item_custom_text_6,
        'custom_text_7' => $item_custom_text_7,
        'custom_text_8' => $item_custom_text_8,
        'custom_text_9' => $item_custom_text_9,
        'custom_text_10' => $item_custom_text_10
    ];

    $saved = $sdk->createRowEntry($packageId, $tableName, $data);
    if($saved['Name'] == $item_name){
        echo "Submission Saved";
    }

?>
