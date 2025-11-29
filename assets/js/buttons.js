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

// let scrollWidth= window.innerWidth - document.body.clientWidth;
// document.documentElement.style.setProperty('--scrollWidth', `${scrollWidth}px`);
// // console.log(scrollWidth);
// // слушаем событие resize
// window.addEventListener('resize', () => {
//     // получаем текущее значение высоты
//     let scrollWidth= window.innerWidth - document.body.clientWidth;
//     document.documentElement.style.setProperty('--scrollWidth', `${scrollWidth}px`);
//     // console.log(scrollWidth);
// });


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
let searchDiv = document.querySelector('.search__wrap');
let searchResultDiv = document.querySelector('.search-result');
let headerMainHeight= headerMainDiv.offsetHeight;

// слушаем событие resize
window.addEventListener('resize', () => {
    // получаем текущее значение высоты
    let headerMainHeight= headerMainDiv.offsetHeight;
});

//скрываем меню при скролле
let marginTopOffset = headerMainHeight - headerHeight;
window.addEventListener('scroll', function() {
    let scrollTop = window.pageYOffset;
    if(scrollTop >0){
        // headerMainDiv.setAttribute('style', `margin-top:${marginTopOffset}px`);
        document.documentElement.style.setProperty('--marginTopOffset', `${marginTopOffset}px`);
        headerMainDiv.classList.add('scrollhidden');
        searchDiv.classList.add('scrollhidden');
        searchResultDiv.classList.add('scrollhidden');
    } else{
        // headerMainDiv.removeAttribute('style');
        document.documentElement.style.setProperty('--marginTopOffset', 0);
        headerMainDiv.classList.remove('scrollhidden');
        searchDiv.classList.remove('scrollhidden');
        searchResultDiv.classList.remove('scrollhidden');
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

if(catalogFilterWrap) {
    window.addEventListener('scroll', function() {
        let scrollTopPosition = window.pageYOffset;
    
        if (scrollTopPosition > lastScrollTop) {
            // console.log('scrolling down');
            catalogFilterWrap.classList.add('catalog__filter-wrap_up');
        } else if (scrollTopPosition < lastScrollTop) {
            // console.log('scrolling up');
            catalogFilterWrap.classList.remove('catalog__filter-wrap_up');
        }
        lastScrollTop = scrollTopPosition <= 0 ? 0 : scrollTopPosition;
    });
}

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
    console.log(catalogGrid.classList)
    if(catalogGrid.classList.contains('columns-2')) {
      gridButton3.disabled = false;
      gridButton2.disabled = true;
    } 
    if(catalogGrid.classList.contains('columns-3')) {
      gridButton2.disabled = false;
      gridButton3.disabled = true;
    } 
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

/*--------------------------------------------------------------
# Buttons to copy link
--------------------------------------------------------------*/

copyShareBtn = document.querySelector('#copyShareBtn');
if(copyShareBtn) {
  copyShareBtn.addEventListener('click', async () => {
      const url = copyShareBtn.dataset.url || window.location.href;
  
      try {
      if (navigator.clipboard?.writeText) {
          await navigator.clipboard.writeText(url);
      } else {
          const ta = document.createElement('textarea');
          ta.value = url;
          ta.setAttribute('readonly', '');
          ta.style.position = 'fixed';
          ta.style.top = '-9999px';
          document.body.appendChild(ta);
          ta.select();
          document.execCommand('copy');
          document.body.removeChild(ta);
      }
      const old = copyShareBtn.textContent;
      copyShareBtn.textContent = 'Скопировано!';
      setTimeout(() => copyShareBtn.textContent = old, 1500);
      } catch {
      alert('Не удалось скопировать:\n' + url);
      }
  });
}

/*--------------------------------------------------------------
# Убираем клик по кнопке вопроса
--------------------------------------------------------------*/
document.addEventListener('click', function(e) {
  console.log(e.target);
  if (e.target.closest('.catalog__help-icon')) {
    e.preventDefault();
    e.stopPropagation();
  }
});