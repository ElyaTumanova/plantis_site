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

// для разворачивая пункта меню с растениями
const dropdownPlants = catalogMob.querySelector('.catalog__dropdown');
const menuPlants = dropdownPlants.querySelector('.catalog__dropdown-menu');

catalogMobOpen.addEventListener ("click", (evt)=>{
    toggle_mob_catalog ();
});
catalogMobClose.addEventListener ("click", (evt)=>{
    toggle_mob_catalog ();
});

function toggle_mob_catalog () {
    catalogMob.classList.toggle ('modal-mob_active');
    body.classList.toggle ('fix-body');
    // для разворачивая пункта меню с растениями
    menuPlants.classList.add('catalog__dropdown-menu_show');
	dropdownPlants.classList.add('catalog__dropdown_open');
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


/*--------------------------------------------------------------
# Buttons to change grid columns in catalog
--------------------------------------------------------------*/

const gridButton2 = document.querySelector('.catalog__grid-button-2');
const gridButton3 = document.querySelector('.catalog__grid-button-3');
const catalogGrid = document.querySelector('.catalog__products-wrap').querySelector('.products');

if (gridButton2) {
    gridButton2.addEventListener ("click", (evt)=>{
        toggle_grid_columns();
    });
}
if (gridButton3) {
    gridButton3.addEventListener ("click", (evt)=>{
        toggle_grid_columns();
    });
}

function toggle_grid_columns () {
    catalogGrid.classList.toggle ('columns-2');
    catalogGrid.classList.toggle ('columns-3');
    gridButton2.disabled = true;
};
