let catalogBtn = document.querySelector('.header__catalog');
let headerCatalogWrap = document.querySelector('.header__menu');
let primaryMenuDiv = document.querySelector('.main-navigation');
let primaryMenu;
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
    console.log('plntAjaxShowPrimaryMenu init');
    console.log(event);
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
          console.log('plntAjaxShowPrimaryMenu success')
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

  primaryMenu = document.querySelector('.menu--main');

//   majorCats = document.querySelectorAll('.menu--main .menu-node_lvl_1');
  subMenues = document.querySelectorAll('.menu--main .sub-menu');
//   firstSubMenues = majorCats[0].querySelectorAll('.menu--onside_lvl_1');

  treezCollectionsCats = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3');
  treezSubMenues = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3 .sub-menu');

  plantsCats = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2');
  plantsSubMenues = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2 .sub-menu');

  lechuzaCat = document.querySelectorAll('.menu_item_lechuza');
  lechuzaSubMenues = document.querySelectorAll('.menu_item_lechuza .sub-menu');

  gorshkiCats = document.querySelectorAll('.menu_item_gorshki .menu-node_lvl_2 > a');

//   majorCats.forEach((el) => {
//       el.addEventListener('mouseenter',closeAllSubmenu);
//       el.addEventListener('mouseenter',showSubmenu);
//   })
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
    // console.log(classList)
    if(!classList.contains('menu_item_lechuza')) {
        el.addEventListener('mouseenter',closeLechuzaSubMenues);
    }
  })
}


function openHeaderCatalog () {
    closeAllSubmenu();
    headerCatalogWrap.classList.add('header__menu_open');
    // catalogBtn.classList.add('header__catalog_open');

        
 

    // firstSubMenues.forEach((el) => {
    //     el.classList.add('menu--onside_show');
    // });
    // majorCats[0].classList.add('menu_active');
}

function closeHeaderCatalog () {
    headerCatalogWrap.classList.remove('header__menu_open');
    // catalogBtn.classList.remove('header__catalog_open');
    if(primaryMenu) {
        closeAllSubmenu();
    }
    // catalogBtn.addEventListener('click',openHeaderCatalog,{once:true});
}

function showSubmenu(event) {
    console.log(event);
    let menu = event.target.getAttribute('data-menu');
    console.log(menu);
    //menu.classList.add('menu_active');
    let menuSubMenues = document.querySelectorAll(`.${menu} .sub-menu`);
    //console.log(menuSubMenues);
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    })
    // closeTreezSubMenues ();
    // closePlantsSubMenues();
    // closeLechuzaSubMenues();
}

function closeAllSubmenu() {
    closePlantsSubMenues();
    closeTreezSubMenues ();
    closeLechuzaSubMenues();
    // majorCats.forEach((el) => {
    //     el.classList.remove('menu_active');
    // })
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
}

//catalogBtn.addEventListener('click',plntAjaxShowPrimaryMenu,{once:true});
//catalogBtn.addEventListener('click',openHeaderCatalog,{once:true});

headerMenuItems.forEach(menu => {
    menu.addEventListener('mouseenter', (event) => {plntAjaxShowPrimaryMenu(event)},{once:true});
});
headerMenuWrap.addEventListener('mouseleave', closeHeaderCatalog);
