/*--------------------------------------------------------------
# Menu in header for mobile
--------------------------------------------------------------*/
const menuMob = document.querySelector('.menu-mob');
const menuMobOpen = document.querySelector('.header__mob-menu');
const menuMobClose = document.querySelector('.menu-mob__close');
const body = document.querySelector('body');

menuMobOpen.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
});
menuMobClose.addEventListener ("click", (evt)=>{
    toggle_mob_menu ();
});

function toggle_mob_menu () {
    menuMob.classList.toggle ('menu-mob_active');
    body.classList.toggle ('fix-body');
};

// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let vh = window.innerHeight;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
  });