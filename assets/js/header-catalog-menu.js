let headerCatalogWrap = document.querySelector('.header__menu');
let headerMenuWrap = document.querySelector('.header__main-menu-wrap');
let headerMenuItems = document.querySelectorAll('.header__main-menu-item');
let subMenues = document.querySelectorAll('.header__main-submenu');
let menuLinksWithImage = headerCatalogWrap.querySelectorAll('.header__main-submenu-item_image');
console.log(menuLinksWithImage);
function getCatImageAjax(catId) {
    alert(catId)
}

function openHeaderCatalog () {
    headerCatalogWrap.classList.add('header__menu_open');
}

function closeHeaderCatalog () {
    headerCatalogWrap.classList.remove('header__menu_open');
}

function showSubmenu(event) {
    let menu = event.target.getAttribute('data-menu');
    let menuSubMenu = document.querySelector(`.header__main-submenu[data-menu='${menu}']`);
    menuSubMenu.classList.add('header__main-submenu_show');
}

function closeAllSubmenu() {
    subMenues.forEach((el) => {
        el.classList.remove('header__main-submenu_show');
    })
}

headerMenuItems.forEach((el) => {
    if(el.getAttribute('data-menu')) {
        el.addEventListener('mouseenter', openHeaderCatalog);
        el.addEventListener('mouseenter',closeAllSubmenu);
        el.addEventListener('mouseenter',showSubmenu);
    }
  })

headerMenuItems.forEach(menu => {
    if(!menu.getAttribute('data-menu')) {
        menu.addEventListener('mouseenter', closeHeaderCatalog);
    }
});

headerMenuWrap.addEventListener('mouseleave', closeHeaderCatalog);

menuLinksWithImage.forEach((el)=>{
    let catId = el.getAttribute('data-cat_id');
    if(catId) {
        console.log(catId)
        el.addEventListener('mouseenter',(evt)=>{getCatImageAjax(catId)})
    }
})


