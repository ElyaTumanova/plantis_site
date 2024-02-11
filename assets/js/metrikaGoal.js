// jQuery(document).ready(function() {
// 	jQuery('.card__price-wrap .add_to_cart_button').click(function(){
// 		ym(87781741, 'reachGoal', 'click-button-cart-detalka-new'); 
//         return true;
// 	});
// });


document.addEventListener("DOMContentLoaded", function() {
    document.querySelector ('.card__price-wrap .add_to_cart_button').addEventListener('click',()=>{
        ym(87781741, 'reachGoal', 'click-button-cart-detalka-new');
        console.log('hi');
        
        return true; 
    })
})


