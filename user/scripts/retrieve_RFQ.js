
(function() {
    var scriptSrc = document.currentScript.src;
    var packagePath = scriptSrc.replace('/scripts/scripts.js', '').trim();
    var re = /([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/i;
    var packageId = re.exec(scriptSrc.toLowerCase())[1];
    var hostname = window.location.hostname;

    var settings = {
        "url": "https://"+hostname+"/user/plugins/" + packageId + "/retrieve_RFQ.php",
        "data": JSON.stringify({"one": "one"}),
        "method": "POST",
        "async": false
    };
    $.ajax(settings).done(function(response){
        console.log(response);
        document.getElementById("results").innerHTML = response;    
    });

})();