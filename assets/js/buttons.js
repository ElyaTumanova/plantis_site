/*--------------------------------------------------------------
# Menu in header for mobile
--------------------------------------------------------------*/
const menuMob = document.querySelectorAll('.menu-mob');
const menuMobOpen = document.querySelector('.header__mob-menu');
const menuMobClose = document.querySelector('.menu-mob__close');

menuMobOpen.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});
menuMobClose.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

function toggle_mob_menu () {
    menuMob.classList.toggle ('menu-mob_active');
};