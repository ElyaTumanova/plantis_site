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
# Высота и ширина экрана мобильного устройства
--------------------------------------------------------------*/

let vh = window.innerHeight;
let vw = window.innerWidth;
document.documentElement.style.setProperty('--vh', `${vh}px`);
document.documentElement.style.setProperty('--vw', `${vw}px`);
// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let vh = window.innerHeight;
    let vw = window.innerWidth;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
    document.documentElement.style.setProperty('--vw', `${vw}px`);
});