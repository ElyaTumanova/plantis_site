/*--------------------------------------------------------------
# Высота и ширина экрана мобильного устройства
--------------------------------------------------------------*/

let vh = window.innerHeight;
document.documentElement.style.setProperty('--vh', `${vh}px`);
// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let vh = window.innerHeight;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
});

/*--------------------------------------------------------------
# Ширина скролл бара окна браузера
--------------------------------------------------------------*/

let scrollWidth= window.innerWidth - document.body.clientWidth;
document.documentElement.style.setProperty('--scrollWidth', `${scrollWidth}px`);
// console.log(scrollWidth);
// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let scrollWidth= window.innerWidth - document.body.clientWidth;
    document.documentElement.style.setProperty('--scrollWidth', `${scrollWidth}px`);
    // console.log(scrollWidth);
});



/*--------------------------------------------------------------
# Menu in header for mobile
--------------------------------------------------------------*/
const menuMob = document.querySelector('.burger-menu');
const menuMobOpen = document.querySelector('.header__mob-menu');
const menuMobClose = document.querySelector('.burger-menu__close');
const body = document.querySelector('body');

menuMobOpen.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
});
menuMobClose.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
});

function toggle_mob_menu () {
    menuMob.classList.toggle ('modal-mob_active');
    body.classList.toggle ('fix-body');
};

/*--------------------------------------------------------------
# Catalog for mobile
--------------------------------------------------------------*/
const catalogMob = document.querySelector('.catalog-menu__wrap');
const catalogMobOpen = document.querySelector('.header__catalog');
const catalogMobClose = document.querySelector('.catalog-menu__close');

// для разворачивая пункта меню с растениями
const dropdownPlants = catalogMob.querySelector('.catalog__dropdown');
const menuPlants = dropdownPlants.querySelector('.catalog__dropdown-menu');

catalogMobOpen.addEventListener ("click", (evt)=>{
    toggle_mob_catalog ();
});
catalogMobClose.addEventListener ("click", (evt)=>{
    toggle_mob_catalog ();
});

function toggle_mob_catalog () {
    catalogMob.classList.toggle ('modal-mob_active');
    body.classList.toggle ('fix-body');
    // для разворачивая пункта меню с растениями
    menuPlants.classList.add('catalog__dropdown-menu_show');
	dropdownPlants.classList.add('catalog__dropdown_open');
};



/*--------------------------------------------------------------
# Filters for mobile in catalog
--------------------------------------------------------------*/
const filtersMob = document.querySelector('.catalog__sidebar');
const filtersMobOpen = document.querySelector('.catalog__mob-filter-btn ');
const filtersMobClose = document.querySelector('.catalog-sidebar__close');
const contentArea = document.querySelector('.content-area');

if (filtersMob) {
    filtersMobOpen.addEventListener ("click", (evt)=>{
        toggle_mob_filters ();
    });
    filtersMobClose.addEventListener ("click", (evt)=>{
        toggle_mob_filters ();
    });
    
    function toggle_mob_filters () {
        filtersMob.classList.toggle ('modal-mob_active');
        body.classList.toggle ('fix-body');
        contentArea.classList.toggle ('no-padding');
    };
}


/*--------------------------------------------------------------
# Buttons to change grid columns in catalog
--------------------------------------------------------------*/

const gridButton2 = document.getElementById('catalog__grid-button-2');
const gridButton3 = document.getElementById('catalog__grid-button-3');
const catalogWrap = document.querySelector('.catalog__products-wrap');
if(catalogWrap) {
    const catalogGrid = catalogWrap.querySelector('.products');
    if (gridButton2) {
        gridButton2.addEventListener ("click", (evt)=>{
            make_2_grid_columns();
        });
    }
    if (gridButton3) {
        gridButton3.addEventListener ("click", (evt)=>{
            make_3_grid_columns();
        });
    }
    
    function make_2_grid_columns () {
        catalogGrid.classList.add ('columns-2');
        catalogGrid.classList.remove ('columns-3');
        gridButton2.disabled = true;
        gridButton3.disabled = false;
    };
    
    function make_3_grid_columns () {
        catalogGrid.classList.remove ('columns-2');
        catalogGrid.classList.add ('columns-3');
        gridButton2.disabled = false;
        gridButton3.disabled = true;
    };
};




( function ( $ ) {
 "use strict";
 console.log('hihihi');
// Define the PHP function to call from here
 var data = {
   'action': 'mode_theme_update_mini_cart'
 };
 $.post(
   woocommerce_params.ajax_url, // The AJAX URL
   data, // Send our PHP function
   function(response){
     $('.mini-cart').html(response); // Repopulate the specific element with the new content
   }
 );
// Close anon function.
}( jQuery ) );


jQuery( function( $ ) {
    $( document ).on( 'click', function() {
      $.get( yith_wcwl_l10n.ajax_url, {
        action: 'yith_wcwl_update_wishlist_count'
      }, function( data ) {
        $('.yith-wcwl-items-count').children('i').html( data.count );
      } );
    } );
  })