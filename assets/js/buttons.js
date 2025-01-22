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
    let headerHeight= headerDiv.offsetHeight;
    document.documentElement.style.setProperty('--headerHeight', `${headerHeight}px`);
});

/*--------------------------------------------------------------
# Высота woocommerce-tabs wc-tabs-wrapper
--------------------------------------------------------------*/
let tabsWrap = document.querySelector('.wc-tabs-wrapper');
let tabsWrapHeight= tabsWrap.offsetHeight;

document.documentElement.style.setProperty('--tabsWrapHeight', `${tabsWrapHeight}px`);
// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let tabsWrapHeight= tabsWrap.offsetHeight;
    document.documentElement.style.setProperty('--tabsWrapHeight', `${tabsWrapHeight}px`);
});


let tabs = document.querySelector('.wc-tabs');
let tabsHeight= tabs.offsetHeight;

document.documentElement.style.setProperty('--tabsHeight', `${tabsHeight}px`);
// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let tabsHeight= tabs.offsetHeight;
    document.documentElement.style.setProperty('--tabsHeight', `${tabsHeight}px`);
});


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

console.log('hello master');