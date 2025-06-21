let catalogBtn = document.querySelector('.header__catalog');
let headerCatalogWrap = document.querySelector('.header__menu');
let primaryMenuDiv = document.querySelector('.main-navigation');
let headerMenuWrap = document.querySelector('.header__main-menu-wrap');
let headerMenuItems = document.querySelectorAll('.header__main-menu-item');
let majorCats;
let subMenues;
// let firstSubMenues;

let treezCollectionsCats;
let treezSubMenues;

let plantsCats;
let plantsSubMenues;

let lechuzaCat;
let lechuzaSubMenues;

let gorshkiCats;

function plntAjaxShowPrimaryMenu(event) {
  ( function ( $ ) {
      "use strict";
    // Define the PHP function to call from here
      var data = {
        'action': 'plnt_show_primary_menu'
      };
      $.get(
        woocommerce_params.ajax_url, // The AJAX URL
        data, // Send our PHP function
        function(response){
          //console.log(response);
          primaryMenuDiv.innerHTML = response;
          primaryMenuInit();
          openHeaderCatalog();
          showSubmenu(event);
        }
      );
  // Close anon function.
  }( jQuery ) );
}


function primaryMenuInit() {

headerMenuItems.forEach(menu => {
    menu.addEventListener('mouseenter', openHeaderCatalog);
});

  subMenues = document.querySelectorAll('.menu--main .sub-menu');

  treezCollectionsCats = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3');
  treezSubMenues = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3 .sub-menu');

  plantsCats = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2');
  plantsSubMenues = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2 .sub-menu');

  plantsTreezCats = document.querySelectorAll('.menu_item_treez_plants .menu-node_lvl_2');
  plantsTreezSubMenues = document.querySelectorAll('.menu_item_treez_plants .menu-node_lvl_2 .sub-menu');

  lechuzaCat = document.querySelectorAll('.menu_item_lechuza');
  lechuzaSubMenues = document.querySelectorAll('.menu_item_lechuza .sub-menu');

  gorshkiCats = document.querySelectorAll('.menu_item_gorshki .menu-node_lvl_2 > a');

  headerMenuItems.forEach((el) => {
      el.addEventListener('mouseenter',closeAllSubmenu);
      el.addEventListener('mouseenter',showSubmenu);
  })

  treezCollectionsCats.forEach((el) => {
      el.addEventListener('mouseenter',openTreezSubMenues);
  })

  lechuzaCat.forEach((el) => {
      el.addEventListener('mouseenter',openLechuzaSubMenues);
  })

  plantsCats.forEach((el) => {
      el.addEventListener('mouseenter',openPlantsSubMenues);
  })

  gorshkiCats.forEach((el) => {
    el.addEventListener('mouseenter',closeTreezSubMenues);
    let classList = el.parentElement.classList;
    if(!classList.contains('menu_item_lechuza')) {
        el.addEventListener('mouseenter',closeLechuzaSubMenues);
    }
  })
}


function openHeaderCatalog () {
    closeAllSubmenu();
    headerCatalogWrap.classList.add('header__menu_open');
}

function closeHeaderCatalog () {
    headerCatalogWrap.classList.remove('header__menu_open');
    closeAllSubmenu();
}

function showSubmenu(event) {
    let menu = event.target.getAttribute('data-menu');
    //event.target.classList.add('menu_active');
    let menuSubMenues = document.querySelectorAll(`.${menu} .menu--onside_lvl_1`);
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    })
}

function closeAllSubmenu() {
    closePlantsSubMenues();
    //closePlantsTreezSubMenues();
    closeTreezSubMenues ();
    closeLechuzaSubMenues();
    subMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
}

function openTreezSubMenues(event) {
    closeLechuzaSubMenues();
    closeTreezSubMenues();    
    let menu = event.target;
    menu.classList.add('menu_active_lvl_2');
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    })
}

function closeTreezSubMenues () {
    treezCollectionsCats.forEach((el) => {
        el.classList.remove('menu_active_lvl_2');
    })
    
    treezSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
}

function openLechuzaSubMenues(event) {
    closeTreezSubMenues();     
    let menu = event.target;
    menu.classList.add('menu_active_lvl_2');
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    })
}

function closeLechuzaSubMenues () {
    lechuzaCat.forEach((el) => {
        el.classList.remove('menu_active_lvl_2');
    })
    
    lechuzaSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
}

function openPlantsSubMenues(event) {
    closePlantsSubMenues();
    let menu = event.target;
    menu.classList.add('menu_active_lvl_2');
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    })
}

function closePlantsSubMenues() {
    plantsCats.forEach((el) => {
        el.classList.remove('menu_active_lvl_2');
    })
    plantsSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })

    plantsTreezCats.forEach((el) => {
        el.classList.remove('menu_active_lvl_2');
    })
    plantsTreezSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
}

// function closePlantsTreezSubMenues() {
//     plantsTreezCats.forEach((el) => {
//         el.classList.remove('menu_active_lvl_2');
//     })
//     plantsTreezSubMenues.forEach((el) => {
//         el.classList.remove('menu--onside_show');
//     })
// }


headerMenuItems.forEach(menu => {
    menu.addEventListener('mouseenter', (event) => {plntAjaxShowPrimaryMenu(event)},{once:true});
});
headerMenuWrap.addEventListener('mouseleave', closeHeaderCatalog);
