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

catalogMobOpen.addEventListener ("click", (evt)=>{
    toggle_mob_catalog ();
});
catalogMobClose.addEventListener ("click", (evt)=>{
    toggle_mob_catalog ();
});

function toggle_mob_catalog () {
    catalogMob.classList.toggle ('modal-mob_active');
    body.classList.toggle ('fix-body');
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


$( document ).ready(function() {
    console.log( "ready!" );
});