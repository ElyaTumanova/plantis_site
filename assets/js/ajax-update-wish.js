// function plntAjaxUpdateWishCount() {
//     //console.log('hi plntAjaxUpdateWishCount');
//     jQuery( function( $ ) {
//         $(document).ready( function() {
//             $.get( woocommerce_params.ajax_url, {
//             action: 'yith_wcwl_update_wishlist_count'
//             }, function( data ) {
//                 console.log(data);
//             $('.yith-wcwl-items-count').children('i').html( data.count );
//             if (data.count > 0) {
//                 $('.header__main .header__wishlist .header-btn__wrap').addClass("header-btn__wrap_active");
//                 $('.header__nav-wrap .header__wishlist .header-btn__wrap').addClass("header-btn__wrap_active");
//                 $('.header__main .header__wishlist .header__count').addClass("header__count_active");
//                 $('.header__nav-wrap .header__wishlist .header__count').addClass("header__count_active");
//             }
//             } );
//         } );
//     });
// };
  
//plntAjaxUpdateWishCount();


function plntAjaxGetWishlist() {
//console.log('hi plntAjaxGetWishlist');
jQuery( function( $ ) {
    $(document).ready( function() {
        $.get( woocommerce_params.ajax_url, {
        action: 'plnt_yith_wcwl_get_wishlist'
        }, 
        function( data ) {
        // console.log(data.wish);
        // console.log(data.count);

        updateWishBtns(data.wish);

        $('.yith-wcwl-items-count').children('i').html( data.count );
        if (data.count > 0) {
            $('.header__main .header__wishlist .header-btn__wrap').addClass("header-btn__wrap_active");
            $('.header__nav-wrap .header__wishlist .header-btn__wrap').addClass("header-btn__wrap_active");
            $('.header__main .header__wishlist .header__count').addClass("header__count_active");
            $('.header__nav-wrap .header__wishlist .header__count').addClass("header__count_active");
        }
        } );
    } );
});
};

//plntAjaxGetWishlist(); //функция используется в плагнах Load More и BeRocket filters


