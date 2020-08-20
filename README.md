# Request for Quotation Plug-In

A Request For Quotation (RFQ) interface can be created that permits the buyers to request sellers to offer quotes on a particular item or service. This can be done with the correct set of APIs, custom fields and tables which are identified here.

This sample code is a **_very_** basic example of how an RFQ experience can be created for buyers and sellers of a marketplace. The source code's structure is intended to run on an **Arcadier Marketplace** as a Plug-In.

The CSS and other UI aspects can be easily modified to match your own requirements.

The same sequence of APIs and user flows and be used to recreate the RFQ UX on an external CMS or external web page.

## RFQ flow
![proposedRFQ-03](https://user-images.githubusercontent.com/43057493/90701586-794be480-e2bb-11ea-8cd4-9304397cdb21.png)


The 4 main events of this flow are as follows:
* A buyer logs in with the intention of creating an RFQ
* A seller logs in with the intention to view new RFQs and submite a quote for one or more of them
* The buyer logs in to view different quotes submitted by different sellers for their RFQs
* The buyer accepts one of them and initiates the Arcadier quotation and requisition flow.

## Features created by the Plug-In
### For the buyer
Two links are created for the buyer:
* Create RFQ
* View Proposals

Both of these link to 2 custom pages created via the plugin: `/user/create_RFQ.php` and `/user/view_propsals.php`.

`create_RFQ.php` contains a form to collect inputs from the buyer, and save that input in a custom table called `Submitted_RFQs`, using [Custom Table APIs](https://apiv2.arcadier.com/?version=latest#8b187974-39b1-4e90-b63c-b6d1aeeb585f)

`view_proposals.php` also uses [Custom Table APIs](https://apiv2.arcadier.com/?version=latest#8b187974-39b1-4e90-b63c-b6d1aeeb585f) to fetch data from a custom table called `accepted_RFQs`, which contains proposals from sellers who accepted an RFQ.


### For the seller
One new link and one custom page
* View RFQs (link)
* `/user/merchant_accept_RFQ.php`

`/user/view_RFQ.php` fetches data from the`Submitted_RFQs` table and displays them for the seller to see.

Once a seller chooses to submite a quote for an RFQ, `merchant_accept_RFQ.php` opens up and displays a form to collect data from the seller and save it in a custom table called `accepted_RFQs`.

### Video Demo
<p style="align: center;">
<a target="_blank" href="https://www.loom.com/share/f38d387dc228471facd1d856fd8d5204"> <p>RFQ flow - Watch Video</p> <img style="max-width:600px;"  src="https://cdn.loom.com/sessions/thumbnails/f38d387dc228471facd1d856fd8d5204-with-play.gif"> </a></p>

## Installation

### Composer
This Plug-In uses Arcadier's PHP SDK to function. You can install SDK via composer using the following command line:
```php
composer require arcadier/test-packagist-sdk
```



## 💡 Getting Started
Once the SDK is installed in your directory, go to `/vendor/arcadier/test-packagist-sdk/sdk/admin_token.php` **and** `/vendor/arcadier/test-packagist-sdk/sdk/ApiSdk.php` and change the following variables to your marketplace's:
* `$client_id`
* `$client_secret`
* `$marketplace`
* `$protocol` either `https` or `http` depending on your server

`$marketplace` is your marketplace domain.

`$client_id` and `$client_secret` are found in the Account Settings of you Administrator portal.

Once done, zip the `admin`, `user`, and `vendor` folders together and upload them to your [sandbox dashboard](https://dashboard.sandbox.arcadier.io) to view the plug-in at work.

## 🔨 List of APIs being used in this project:
* GET `/api/v2/plugins/{{packageID}}/custom-tables/{{custom-table-name}}/`([Get all Custom Table Contents](https://apiv2.arcadier.com/?version=latest#c562617c-9227-40eb-aafb-83c485757371))
* POST `/api/v2/plugins/{{packageID}}/custom-tables/{{custom-table-name}}/rows`([Create New Row Entry](https://apiv2.arcadier.com/?version=latest#4a9905bd-7054-47b3-b7e9-4d48ffd46fd7))
* PUT `/api/v2/plugins/{{packageID}}/custom-tables/{{custom-table-name}}/rows/{{rowID}}` ([Edit Row Entry](https://apiv2.arcadier.com/?version=latest#1d3b0075-e065-436e-aa03-407e3f6173a3))
* GET `/api/v2/users/{{userID}}` ([Get User Information](https://apiv2.arcadier.com/?version=latest#129fa6b1-1c39-4a41-b7b8-8aa7f2545394))

## API Documentation
View our full API collection on Postman [here](https://apiv2.arcadier.com). 