let catalogBtn = document.querySelector('.header__catalog');
let headerCatalogWrap = document.querySelector('.header__menu');
let majorCats = document.querySelectorAll('.menu--main .menu-node_lvl_1');
let subMenues = document.querySelectorAll('.menu--main .sub-menu');
let firstSubMenues = majorCats[0].querySelectorAll('.menu--onside_lvl_1');

let treezCollectionsCats = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3');
let treezSubMenues = document.querySelectorAll('.menu_item_treez .menu-node_lvl_3 .sub-menu');

let plantsCats = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2');
let plantsSubMenues = document.querySelectorAll('.menu_item_plants .menu-node_lvl_2 .sub-menu');

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
    treezSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
    plantsSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
}

function closeAllSubmenu() {
    majorCats.forEach((el) => {
        el.classList.remove('menu_active');
    })
    plantsCats.forEach((el) => {
        el.classList.remove('menu_active_lvl_2');
    })
    subMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
}

function openTreezSubMenues(event) {
    let menu = event.target;
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    treezSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    })
}

function openPlantsSubMenues(event) {
    let menu = event.target;
    menu.classList.add('menu_active_lvl_2');
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    plantsSubMenues.forEach((el) => {
        el.classList.remove('menu--onside_show');
    })
    menuSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
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

plantsCats.forEach((el) => {
    el.addEventListener('mouseenter',openPlantsSubMenues);
})