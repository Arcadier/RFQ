<?php
    $item_name = $_GET["item_name"];
    $item_desc = $_GET["item_desc"];
    $item_custom_text = $_GET["item_custom_text"];
    $buyer_id = $_GET["buyer_id"];

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
        'buyer_id' => $buyer_id
    ];

    $saved = $sdk->createRowEntry($packageId, $tableName, $data);
    if($saved['Name'] == $item_name){
        echo "Submission Saved";
    }

?>
