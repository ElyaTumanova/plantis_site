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
# Ширина скролл бара окна браузера
--------------------------------------------------------------*/

let scrollWidth= window.innerWidth - document.body.clientWidth;
document.documentElement.style.setProperty('--scrollWidth', `${scrollWidth}px`);
// console.log(scrollWidth);
// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let scrollWidth= window.innerWidth - document.body.clientWidth;
    document.documentElement.style.setProperty('--scrollWidth', `${scrollWidth}px`);
    // console.log(scrollWidth);
});


/*--------------------------------------------------------------
# Высота хедера в десктопе
--------------------------------------------------------------*/
let headerDiv = document.querySelector('.header__desktop');
let headerHeight= headerDiv.offsetHeight;
document.documentElement.style.setProperty('--headerHeight', `${headerHeight}px`);
// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let headerHeight= headerDiv.offsetHeight;;
    document.documentElement.style.setProperty('--headerHeight', `${headerHeight}px`);
});



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
// const catalogMobClose = document.querySelector('.catalog-menu__close');

//console.log(catalogMobOpen);

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


// catalogMobClose.addEventListener ("click", (evt)=>{
//     toggle_mob_catalog ();
// });

// function toggle_mob_catalog () {
//     catalogMob.classList.toggle ('modal-mob_active');
//     body.classList.toggle ('fix-body');
//     // для разворачивая пункта меню с растениями
//     menuPlants.classList.add('catalog__dropdown-menu_show');
// 	dropdownPlants.classList.add('catalog__dropdown_open');
// };



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


/*--------------------------------------------------------------
# Buttons to change grid columns in catalog
--------------------------------------------------------------*/

const gridButton2 = document.getElementById('catalog__grid-button-2');
const gridButton3 = document.getElementById('catalog__grid-button-3');
const catalogWrap = document.querySelector('.catalog__products-wrap');
if(catalogWrap) {
    const catalogGrid = catalogWrap.querySelector('.products');
    if (gridButton2) {
        gridButton2.addEventListener ("click", (evt)=>{
            make_2_grid_columns();
        });
    }
    if (gridButton3) {
        gridButton3.addEventListener ("click", (evt)=>{
            make_3_grid_columns();
        });
    }
    
    function make_2_grid_columns () {
        catalogGrid.classList.add ('columns-2');
        catalogGrid.classList.remove ('columns-3');
        gridButton2.disabled = true;
        gridButton3.disabled = false;
    };
    
    function make_3_grid_columns () {
        catalogGrid.classList.remove ('columns-2');
        catalogGrid.classList.add ('columns-3');
        gridButton2.disabled = false;
        gridButton3.disabled = true;
    };
};

//функция прокрутки до начала страницы - добавлена в checkout.min.js
function scrollToTop () {
    console.log('top');
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
      })
}

console.log('hello redesign');