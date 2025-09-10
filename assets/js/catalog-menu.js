//раскрывает пункты меню в категориях на страницах каталога

const dropdownCatalog = document.querySelectorAll('.catalog__dropdown');

dropdownCatalog.forEach((el) => {
    const menu = el.querySelector('.catalog__dropdown-menu');
    const btn = el.querySelector('.menu__dropdown-arrow');
    if(menu && btn) {
        btn.addEventListener('click', function (event) {
            menu.classList.toggle('catalog__dropdown-menu_show');
            el.classList.toggle('catalog__dropdown_open');
            event.stopPropagation();
        })
    }
})


