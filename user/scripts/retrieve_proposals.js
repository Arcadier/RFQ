(function() {
    var scriptSrc = document.currentScript.src;
    var packagePath = scriptSrc.replace('/scripts/scripts.js', '').trim();
    var re = /([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/i;
    var packageId = re.exec(scriptSrc.toLowerCase())[1];
    var hostname = window.location.hostname;

    var settings = {
        "url": "https://"+hostname+"/user/plugins/" + packageId + "/retrieve_proposals.php",
        "data": JSON.stringify({"buyer_id": $("#userGuid").val()}),
        "method": "POST",
        "async": false
    };

    $.ajax(settings).done(function(response){
        console.log(response);
        document.getElementById("proposals").innerHTML = response;    
    });

})();

function changeStatus(id){
    var settings = { 
        "url": "https://"+hostname+"/api/v2/plugins/"+packageId+"/custom-tables/accepted_RFQs/rows/"+id,
        "method": "PUT",
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify({"Status": "Accepted"})
    };

    $.ajax(settings).done(function (response) {
        console.log(response);
    });
}