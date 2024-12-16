let catalogBtn = document.querySelector('.header__catalog');
let headerCatalogWrap = document.querySelector('.header__menu');
let majorCats = document.querySelectorAll('.menu--main .menu-node_lvl_1');
let subMenues = document.querySelectorAll('.menu--main .sub-menu');
let firstSubMenues = majorCats[0].querySelectorAll('.sub-menu');
console.log(firstSubMenues)
console.log(subMenues)

function toggleHeaderCatalog () {
    headerCatalogWrap.classList.toggle('header__menu_open');
    closeAllSubmenu();

    firstSubMenues.forEach((el) => {
        el.classList.toggle('menu--onside_show');
    });
}

function showSubmenu(event) {
    console.log(event.target);
    let menu = event.target;
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    menuSubMenues.forEach((el) => {
       //console.log(el);
        el.classList.add('menu--onside_show');
    })
}

function closeAllSubmenu() {
    subMenues.forEach((el) => {
       // console.log(el);
        el.classList.remove('menu--onside_show');
    })
}

catalogBtn.addEventListener('click',toggleHeaderCatalog);



majorCats.forEach((el) => {
	//console.log(el);
    el.addEventListener('mouseenter',closeAllSubmenu);
    el.addEventListener('mouseenter',showSubmenu);
})