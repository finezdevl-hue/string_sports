function SendOfferMessege() {
   
  
        //var data = new FormData($("#captcha_form")[0]);

      
            
            
                
              
                //var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' + xhr.responseText + '</div>';
                var whatsapptext =  ". I want to Know about the latest offers" ;
                window.open("https://wa.me/+971505989752?text=" + whatsapptext.toString(), '_blank');
           

       
  
}

function toggleOffers() {
    var offerContainer = document.getElementById("offerContainer");
    if (offerContainer.style.display === "none" || offerContainer.style.display === "") {
        offerContainer.style.display = "block";
    } else {
        offerContainer.style.display = "none";
    }
}