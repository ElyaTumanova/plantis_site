jQuery(function($){

    var ism = 'input[name^="shipping_method"]', ismc = ism+':checked';

    setTimeout(function(){
        datepicker_init ();
    }, 100);

    $( 'form.checkout' ).on( 'change', ism, function() {
        datepicker_init ();
    })

})

function datepicker_init () {
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
}

