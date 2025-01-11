/*--------------------------------------------------------------
# Menu in header for mobile
--------------------------------------------------------------*/
const menuMobWrap = document.querySelector('.burger-menu');
const menuMob = document.querySelector('.burger-menu .modal-mob');
const menuMobOverlay = document.querySelector('.burger-menu .modal-mob__overlay');
const menuMobOpen = document.querySelector('.header__mob-menu');
const menuMobClose = document.querySelector('.burger-menu__close');
const body = document.querySelector('body');

const menuMobMenuBtn = document.querySelector('.burger-menu__nav_menu');
const menuMobCatalogBtn = document.querySelector('.burger-menu__nav_catalog');
const menuMobMenu = document.querySelector('.burger-menu__wrap');
const menuMobCatalog = document.querySelector('.catalog-menu__wrap');

const catalogMob = document.querySelector('.catalog-menu__wrap');
const catalogMobOpen = document.querySelector('.header__catalog_mob');


// для разворачивая пункта меню с растениями
const dropdownPlants = catalogMob.querySelector('.catalog__dropdown');
const menuPlants = dropdownPlants.querySelector('.catalog__dropdown-menu');

menuMobOpen.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
    open_menu ();
});
menuMobClose.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
});

menuMobMenuBtn.addEventListener ("click", (evt)=>{
    open_menu ();
});

menuMobCatalogBtn.addEventListener ("click", (evt)=>{
    open_catalog ();
});

menuMobOverlay.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
});

function toggle_mob_menu () {
    menuMob.classList.toggle ('modal-mob_active');
    body.classList.toggle ('fix-body');
    menuMobWrap.classList.toggle('popup_active');
    menuMob.scrollTo(0, 0)
};

function open_menu () {
    menuMobMenu.classList.add('burger-menu__wrap_open');
    menuMobMenuBtn.classList.add('burger-menu__nav-btn_active');
    
    menuMobCatalog.classList.remove('catalog-menu__wrap_open');
    menuMobCatalogBtn.classList.remove('burger-menu__nav-btn_active');

}

function open_catalog () {
    menuMobMenu.classList.remove('burger-menu__wrap_open');
    menuMobMenuBtn.classList.remove('burger-menu__nav-btn_active');
    
    menuMobCatalog.classList.add('catalog-menu__wrap_open');
    menuMobCatalogBtn.classList.add('burger-menu__nav-btn_active');

     // для разворачивая пункта меню с растениями
     menuPlants.classList.add('catalog__dropdown-menu_show');
     dropdownPlants.classList.add('catalog__dropdown_open');

}

/*--------------------------------------------------------------
# Catalog for mobile
--------------------------------------------------------------*/

catalogMobOpen.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
    open_catalog ();
});

