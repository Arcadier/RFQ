Creating requests to be reviewed by a second party
===
The concept of Requests for Quotation (RFQ) is very simple. The concept below describes an RFQ from Buyer to Seller, and Requests for New Category from Seller to Admin. The same concept can be extended to **Request for User Information Update from Seller to Admin**, **Request for Company Change from Buyer to Admin**

### Main concept:
Party A and Party B can be any of the roles: Buyer, Seller, or Admin

* **Party A** requests information.
  * All requests for information are consolidated in a table

* **Party B** receives Request
  * All requests are shown in a "Inbox"

* **Party B** responds to request.
  * Response is saved in a "**Party B** History"

* **Party A** sees response.
  * Presented in "Inbox"

* **Party A** accepts/declines response
  * Response in saved in a "**Party A** History"

### Developement work:
* Create a new page that displays a form for **Party A** to fill up
* Create relevant custom tables for each scenario. 
* Each field that **Party A** fills up corresponds to a column in the custom table. So, the developer needs to figure out the complete set of fields required by the project before creating the table.
* Create a new page for **Party B** that queries the table mentionned above for any requests to be reviewed

### Example 1: RFQ
Party A: Buyer
Party B: Seller
Request: Quotations

#### Buyer custom table (Submitted RFQs):
|RFQ_ID|Buyer_ID|Item_ID|Message|Status|is_trial_batch|status|
|---|---|---|---|---|---|---|
|||||||||

This table is filled up with the data coming from the new page/form created for the buyer to fill up. The API used to fill this table up is our [Custom Table API]().

On the new seller page, the UI performs `GET` API calls to find RFQs he has **not** responded to, and once those rows are found, they are used to populate the seller's UI to show the RFQ details.

#### Seller custom table (Accepted RFQs):
|RFQ_ID|Buyer_ID|Seller_ID|Message|Price|status|
|---|---|---|---|---|---|---|
|||||||

If the seller decides to accepts/rejects one, the buyer's custom table's column `status` is updated to indicate to the buyer that his/her RFQ has been accepted/rejected.

The UI also updates Party B's custom table with the 
* `RFQ_ID` he accepted/rejected
* Extra input required from the seller:
  * `Message`
  * `Price`

### Example 2: Request for new category
Party A: Seller
Party B: Buyer
Request: Categories

#### Seller custom table (Submitted Category Requests):
|Request_ID|Seller_ID|Category_name|Category_purpose|Buyer_visible|Seller_Vsible|Company_visible|Specific_user_visible|
|---|---|---|---|---|---|---|--|
|||||||||

This table is filled up with the data coming from the new page/form created for the seller to fill up. The API used to fill this table up is our [Custom Table API]().

On the new seller page, the UI performs `GET` API calls to find requests he has **not** responded to, and once those rows are found, they are used to populate the admin's UI to show the category request's details.

#### Admin custom table (Accepted Category Requests):
|Request_ID|Seller_ID|Message|Status|New_category_ID|
|---|---|---|---|---|---|---|
|||||||

If the seller decides to accepts/rejects one, the buyer's custom table's column `status` is updated to indicate to the buyer that his/her RFQ has been accepted/rejected.

The UI also updates Party B's custom table with the 
* `RFQ_ID` he accepted/rejected
* Extra input required from the seller:
  * `Message`
  * `Price`
