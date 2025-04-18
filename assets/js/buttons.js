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

let headerMainDiv = document.querySelector('.header__main');
let headerMainHeight= headerMainDiv.offsetHeight;

// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let headerMainHeight= headerMainDiv.offsetHeight;
});

//скрываем меню при скролле
let marginTopOffset = headerMainHeight - headerHeight;
let headerMenu = document.querySelector('.header__menu');
window.addEventListener('scroll', function() {
    let scrollTop = window.pageYOffset;
    if(scrollTop >0){
        headerMainDiv.setAttribute('style', `margin-top:${marginTopOffset}px`);
        headerMenu.setAttribute('style', `margin-top:${marginTopOffset}px`);
    } else{
        headerMainDiv.removeAttribute('style');
        headerMenu.removeAttribute('style');
    }
});
document.documentElement.style.setProperty('--marginTopOffset', `${marginTopOffset}px`);

//это не обязательно
// window.addEventListener('resize', () => {
//     let marginTopOffset = headerMainHeight - headerHeight - 1;
//     document.documentElement.style.setProperty('--marginTopOffset', `${marginTopOffset}px`);
//     console.log(marginTopOffset);
// });

//скрываем панель с фльтрами в моб каталоге при скролле

let catalogFilterWrap = document.querySelector('.catalog__filter-wrap');
let lastScrollTop = window.pageYOffset;

window.addEventListener('scroll', function() {
    let scrollTopPosition = window.pageYOffset;

    if (scrollTopPosition > lastScrollTop) {
        console.log('scrolling down');
      } else if (scrollTopPosition < lastScrollTop) {
        console.log('scrolling up');
      }
      lastScrollTop =
        scrollTopPosition <= 0 ? 0 : scrollTopPosition;

    // if(scrollTop >0){
    //     catalogFilterWrap.classList.add('catalog__filter-wrap_up');
    // } else{
    //     catalogFilterWrap.classList.remove('catalog__filter-wrap_up');
    // }
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
        catalogGrid.classList.add ('columns-2_large');
        catalogGrid.classList.remove ('columns-2');
        gridButton2.disabled = true;
        gridButton3.disabled = false;
    };
    
    function make_3_grid_columns () {
        catalogGrid.classList.remove ('columns-2_large');
        catalogGrid.classList.add ('columns-2');
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

console.log('hello dev4');