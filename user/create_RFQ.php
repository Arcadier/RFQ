<!-- begin header -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/style.css">
<!-- end header -->
    <div class="seller-titlearea">
        <div class="container">
            <div class="row">
                <h1>
                    <span class="text-capitalize"><strong>Create RFQ</strong></span>
                </h1>
                <p>Create a Request for Quote to all sellers in the marketplace</p>
            </div>
        </div>
    </div>    
    <div class="grey_section">
    <form action="submit.php">
        <div class="container">
          <div class="review-top-white-sec">
                
                    <div class="item-form-group">
                        <div class="col-md-6">
                            <label for="item_name">Item Name*</label>
                            <input class="required" id="item_name" name="item_name" type="text" value=""/>
                            <div class="msg-error-dname"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="item_desc">Description*</label>
                            <input class="required" id="item_desc" name="item_desc" type="text" value=""/>
                            <div class="msg-error-dname"></div>
                        </div>
                    </div>
                    <div class="item-form-group">
                        <div class="col-md-6">
                            <label for="item_custom_text">Requirements</label>
                            <input id="item_custom_text" name="item_custom_text" type="text" value=""/>
                            <div class="msg-error-dname"></div>
                        </div>
                    </div>
                    <div class="item-form-group">
                        <div class="col-sm-12"><hr></div>
                    </div>
                    <div class="area-common-field" >
                        <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Field Name</th>
                                    <th>Value</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <a href="javascript:void(0);" class="add-more-requirement">+ Add more requirements (you can add up to 10 more fields)</a>
                    </div>
                    <div class="clearfix"></div>
          </div>
          <div class="text-center">
              <input type="hidden" name="buyer_id" id="buyer_id" value="">
              <input type="submit" class="my-btn btn-red" value="Submit">
          </div>
        </div>
    </form>
  </div>

<!-- begin footer -->
    <script src="scripts/scripts.js"></script>
<!-- end footer -->
