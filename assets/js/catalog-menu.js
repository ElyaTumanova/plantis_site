// let catalogMenuDiv = document.querySelector('.catalog__sidebar-cats');

// let tempMenu = document.querySelectorAll('.catalog__sidebar-temp');
// let tempMenuItems = document.querySelectorAll('.catalog__sidebar-temp li');

// tempMenuItems.forEach(element => {
//     element.addEventListener('click',plntAjaxShowCatalogMenu,{ once: true })
// });


// function plntAjaxShowCatalogMenu(event) {
//   const data = new URLSearchParams();
//   data.append('action', 'plnt_show_catalog_menu');

//   fetch(woocommerce_params.ajax_url + '?' + data.toString(), {
//     method: 'GET',
//     credentials: 'same-origin'
//   })
//   .then(response => response.text())
//   .then(html => {
//     if (catalogMenuDiv) {
//       catalogMenuDiv.innerHTML = html;
//     }
//     // tempMenu.remove();
//     setCatalogDropdown();
//   })
//   .catch(error => {
//     console.error('AJAX error:', error);
//   });
// }


//раскрывает пункты меню в категориях на страницах каталога

const dropdownCatalog = document.querySelectorAll('.catalog__dropdown');
console.log(dropdownCatalog);

dropdownCatalog.forEach((el) => {
    const menu = el.querySelector('.catalog__dropdown-menu');
    const btn = el.querySelector('.menu__dropdown-arrow');
    console.log(el);
    console.log(menu);
    console.log(btn);
    if(menu && btn) {
        btn.addEventListener('click', function (event) {
            menu.classList.toggle('catalog__dropdown-menu_show');
            el.classList.toggle('catalog__dropdown_open');
            event.stopPropagation();
        })
    }
})


