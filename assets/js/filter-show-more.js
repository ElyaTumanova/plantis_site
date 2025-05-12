let filterValuesDefault = ['d22', 'd15', 'd18', 'd26', 'd30', 'd34', 'd37', '20x20', '30x30'];
let diametrFilter = document.querySelector('.filter_diametr_gorshka');
let diametrFilterItems;

function hideFilterItems() {
    diametrFilterItems.forEach((item, index, arr) => {
        let filterValue = item.querySelector('input').value;
        if (!filterValuesDefault.includes(filterValue)) {
            item.classList.add('d-none');
        }
    });
    showMoreBtn.addEventListener('click', showAllFilterItems, {once:true});
    showMoreBtn.textContent = 'Показать все';
}

function showAllFilterItems() {
    console.log('hi showAllFilterItems')
    diametrFilterItems.forEach((item, index, arr) => {
        item.classList.remove('d-none');
    })
    showMoreBtn.addEventListener('click', hideFilterItems, {once:true});
    showMoreBtn.addEventListener('click', (event) => {
        let sidebar = document.querySelector('.catalog__sidebar-filters');
        sidebar.scrollIntoView(true);
    }, {once:true});
    showMoreBtn.textContent = 'Свернуть';
}

if(diametrFilter) {
    console.log(diametrFilter);
    diametrFilterItems = Array.from(diametrFilter.querySelectorAll('li'));

    let showMoreBtn = document.createElement("button");
    showMoreBtn.classList.add('filter-show-more-btn');
    diametrFilter.appendChild(showMoreBtn);

    hideFilterItems();
}