function plntAjaxUpdateWishCount() {
    console.log('hi plntAjaxUpdateWishCount');
    jQuery( function( $ ) {
        $(document).ready( function() {
            $.get( yith_wcwl_l10n.ajax_url, {
            action: 'yith_wcwl_update_wishlist_count'
            }, function( data ) {
            $('.yith-wcwl-items-count').children('i').html( data.count );
            } );
        } );
    });
};
  
plntAjaxUpdateWishCount();


function plntAjaxGetWishlist() {
console.log('hi plntAjaxGetWishlist');
jQuery( function( $ ) {
    $(document).ready( function() {
        $.get( yith_wcwl_l10n.ajax_url, {
        action: 'plnt_yith_wcwl_get_wishlist'
        }, function( data ) {
        //console.log(data.wish);
        updateWishBtns(data.wish);
        } );
    } );
});
};

plntAjaxGetWishlist(); //функция используется в плагнах Load More и BeRocket filters


function updateWishBtns(wishListItemsStr) {
    //console.log('hi updateWishBtns');
   // console.log(wishListItemsStr);
    let wishListItems = wishListItemsStr.split(',');
    //console.log(wishListItems);
    let addToWishBtns = document.querySelectorAll('.yith-wcwl-add-button .add_to_wishlist');
    let removeToWishBtns = document.querySelectorAll('.yith-wcwl-add-button .delete_item');

    //console.log(wishBtns);
    addToWishBtns.forEach(button => {
        //console.log(button);
        //console.log(button.dataset.productId);
        if(wishListItems.includes(button.dataset.productId)) {
        //console.log(button);
        button.setAttribute('href', `?remove_from_wishlist=${button.dataset.productId}`);
        button.setAttribute('class', 'delete_item');
        let img = button.querySelector('img');
        img.setAttribute('src','https://plantis.shop/wp-content/uploads/2024/03/heart-red.svg');
        };
    });

    removeToWishBtns.forEach(button => {
        //console.log(button);
        //console.log(button.dataset.productId);
        if(wishListItems.includes(button.dataset.productId)) {
            return;
        } else {
            console.log(button);
            button.setAttribute('href', `?add_to_wishlist=${button.dataset.productId}`);
            button.setAttribute('class', 'add_to_wishlist single_add_to_wishlist');
            let img = button.querySelector('img');
            img.setAttribute('src','https://plantis.shop/wp-content/uploads/2024/03/heart_green.svg');
        };
    });
}