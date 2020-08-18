

(function() {
    var scriptSrc = document.currentScript.src;
    var packagePath = scriptSrc.replace('/scripts/scripts.js', '').trim();
    var re = /([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/i;
    var packageId = re.exec(scriptSrc.toLowerCase())[1];
    var hostname = window.location.hostname;

    $(document).ready(function(){
        var buyer_id = $("#userGuid").val();
        console.log(buyer_id)
        $("#buyer_id").val(buyer_id);
    
    
    });

    if($('#merchantId') && $('#merchantId').length){
        var a = document.createElement("a");
                a.href = "https://" + hostname + "/user/plugins/" + packageId + "/view_RFQs.php";
                a.innerHTML = "View RFQs";
                a.target = "_blank";

            var b = document.createElement("li");
                b.appendChild(a);

            var c = document.querySelector("ul.navigation");
                c.insertBefore(b, document.querySelector(".dropdown"));
    } 
    else {
        if($('#userId') && $('#userId').length){
            var a = document.createElement("a");
                a.href = "https://" + hostname + "/user/plugins/" + packageId + "/create_RFQ.php";
                a.innerHTML = "Create RFQs";
                a.target = "_blank";

            var b = document.createElement("li");
                b.appendChild(a);

            var c = document.querySelector("ul.navigation");
                c.insertBefore(b, document.querySelector(".dropdown"));

            /////

            var a2 = document.createElement("a");
                a2.href = "https://" + hostname + "/user/plugins/" + packageId + "/view_proposals.php";
                a2.innerHTML = "View Proposals";
                a2.target = "_blank";

            var b2 = document.createElement("li");
                b2.appendChild(a2);

            var c2 = document.querySelector("ul.navigation");
                c2.insertBefore(b2, document.querySelector(".dropdown"));
        }
    }
})();