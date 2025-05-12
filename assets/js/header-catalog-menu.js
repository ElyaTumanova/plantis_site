let catalogBtn = document.querySelector('.header__catalog');
let headerCatalogWrap = document.querySelector('.header__menu');
let majorCats = document.querySelectorAll('.menu--main .menu-node_lvl_1');
let subMenues = document.querySelectorAll('.menu--main .sub-menu');
let firstSubMenues = majorCats[0].querySelectorAll('.menu--onside_lvl_1');

let treezCollectionsCats = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3');
let treezSubMenues = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3 .sub-menu');

let plantsCats = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2');
let plantsSubMenues = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2 .sub-menu');

let lechuzaCat = document.querySelectorAll('.menu_item_lechuza');
let lechuzaSubMenues = document.querySelectorAll('.menu_item_lechuza .sub-menu');

let gorshkiCats = document.querySelectorAll('.menu_item_gorshki .menu-node_lvl_2 > a');

function openHeaderCatalog () {
    headerCatalogWrap.classList.add('header__menu_open');
    catalogBtn.classList.add('header__catalog_open');

    closeAllSubmenu();

    firstSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    });
    majorCats[0].classList.add('menu_active');

    catalogBtn.addEventListener('click',closeHeaderCatalog,{once:true})
}

function closeHeaderCatalog () {
    headerCatalogWrap.classList.remove('header__menu_open');
    catalogBtn.classList.remove('header__catalog_open');
    closeAllSubmenu();
    catalogBtn.addEventListener('click',openHeaderCatalog,{once:true});
}

function showSubmenu(event) {
    let menu = event.target;
    menu.classList.add('menu_active');
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    })
    closeTreezSubMenues ();
    closePlantsSubMenues();
    closeLechuzaSubMenues();
}

function closeAllSubmenu() {
    closePlantsSubMenues();
    closeTreezSubMenues ();
    closeLechuzaSubMenues();
    majorCats.forEach((el) => {
        el.classList.remove('menu_active');
    })
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

catalogBtn.addEventListener('click',openHeaderCatalog,{once:true});

majorCats.forEach((el) => {
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
    console.log(classList)
    if(!classList.contains('menu_item_lechuza')) {
        el.addEventListener('mouseenter',closeLechuzaSubMenues);
    }
})