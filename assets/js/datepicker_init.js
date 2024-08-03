jQuery(function($){

    var ism = 'input[name^="shipping_method"]', ismc = ism+':checked';

    $( 'form.checkout' ).on( 'change', ism, function() {
        // if( $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2 || $(ismc).val() == urgentPickup3 || $(ismc).val() == urgentPickup4) // Chosen "Urgent pickup" (Hiding "Date")
        // {
        //     if (deliveryDate) {deliveryDate.classList.add('d-none'); deliveryDate.style.display='none'};
        // } else {
        // 	if (deliveryDate) {deliveryDate.classList.remove('d-none'); deliveryDate.style.display='block'};
        // };

        var datepicker = new Datepicker('#datepicker', {

            min: (function(){
              var date = new Date();
              date.setDate(date.getDate()-1);
              return date;
            })(),
        
            // 30 days in the future
            max: (function(){
              var date = new Date();
              date.setDate(date.getDate() + 30);
              return date;
            })(),
        
            openOn: "today",
        
            without: [(function(){
                var date = new Date(2024,7,5);
                return date;
              })(),]
          });
    })

})

