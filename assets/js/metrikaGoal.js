// jQuery(document).ready(function() {
// 	jQuery('.card__price-wrap .add_to_cart_button').click(function(){
// 		ym(87781741, 'reachGoal', 'click-button-cart-detalka-new'); 
//         return true;
// 	});
// });


document.addEventListener("DOMContentLoaded", function() {
    document.querySelector ('.card__price-wrap .add_to_cart_button').addEventListener('click',()=>{
        // const productName = document.querySelector ('.card__grid .product_title').innerHTML;

        // console.log(productName);
        ym(87781741, 'reachGoal', 'click-button-cart-detalka-new');
        // window.dataLayer.push(
        //     {
        //         "ecommerce": {
        //             "currencyCode": "RUB",
        //             "add": {
        //                 "products" : [
        //                     {
        //                         "name":productName,
        //                         // "quantity":,
        //                         // "price":
        //                     }
        //                 ]
        //             }
        //         }
        //     }
        // )
        // console.log(JSON.stringify(window.dataLayer));
        
        return true; 
    })
})


