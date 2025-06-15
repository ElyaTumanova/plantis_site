//раскрывает пункты меню в категориях на страницах каталога
function setCatalogDropdown() {
  const dropdownCatalog = document.querySelectorAll('.catalog__dropdown');
  console.log(dropdownCatalog);
  dropdownCatalog.forEach((el) => {
    const menu = el.querySelector('.catalog__dropdown-menu');
    const btn = el.querySelector('.menu__dropdown-arrow');
    btn.addEventListener('click', function (event) {
      menu.classList.toggle('catalog__dropdown-menu_show');
      el.classList.toggle('catalog__dropdown_open');
      event.stopPropagation();
    })
  })
}

setCatalogDropdown();

