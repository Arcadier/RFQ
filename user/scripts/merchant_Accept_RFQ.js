(function() {
    var scriptSrc = document.currentScript.src;
    var packagePath = scriptSrc.replace('/scripts/scripts.js', '').trim();
    var re = /([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/i;
    var packageId = re.exec(scriptSrc.toLowerCase())[1];
    var hostname = window.location.hostname;

    $("#submit_quote").click(function(){
        var price = $("input#price").val();
        var message = $("input#message").val();
        var rfq_id = $("input#rfq_id").val();
        var merchant_id = $("#userGuid").val();

        var data = {
            "Price": price,
            "Message": message,
            "RFQ_ID": rfq_id,
            "Merchant_ID": merchant_id,
            "Buyer_ID": $("input#buyer-guid").val(),
            "status" : "Pending"
        };

        var settings = {
            "url": "https://"+hostname+"/api/v2/plugins/" + packageId + "/custom-tables/accepted_RFQs/rows",
            "data": JSON.stringify(data),
            "method": "POST",
            "headers":{
                "Content-Type": "application/json"
            },
            "async": false
        };

        $.ajax(settings).done(function(response){
            console.log(response);
            toastr.success("Quotation Sent", "Success");
        });
    });
})();