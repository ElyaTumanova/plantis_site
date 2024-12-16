let catalogBtn = document.querySelector('.header__catalog');
let headerCatalogWrap = document.querySelector('.header__menu');
let majorCats = document.querySelectorAll('.menu--main .menu-node_lvl_1');
let subMenues = document.querySelectorAll('.menu--main .sub-menu');
let firstSubMenues = majorCats[0].querySelectorAll('.sub-menu');
console.log(firstSubMenues)
console.log(subMenues)

function openHeaderCatalog () {
    headerCatalogWrap.classList.add('header__menu_open');
    closeAllSubmenu();

    firstSubMenues.forEach((el) => {
        el.classList.add('menu--onside_show');
    });

    catalogBtn.addEventListener('click',closeHeaderCatalog,{once:true})
}

function closeHeaderCatalog () {
    headerCatalogWrap.classList.remove('header__menu_open');
    closeAllSubmenu();
    catalogBtn.addEventListener('click',openHeaderCatalog,{once:true});
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

catalogBtn.addEventListener('click',openHeaderCatalog,{once:true});



majorCats.forEach((el) => {
	//console.log(el);
    el.addEventListener('mouseenter',closeAllSubmenu);
    el.addEventListener('mouseenter',showSubmenu);
})