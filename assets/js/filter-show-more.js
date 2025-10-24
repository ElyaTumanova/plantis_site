let filterValuesDefault = ['d22', 'd15', 'd18', 'd26', 'd30', 'd34', 'd37', '20x20', '30x30'];
let diametrFilter = document.querySelector('.filter_diametr_gorshka');
let diametrFilterWrap;
let diametrFilterItems;
let showMoreBtn;
let diametrFilterWrapHeight = 150;


function hideFilterItems() {
    let itemsCount = 0;
    diametrFilterItems.forEach((item, index, arr) => {
        let filterValue = item.querySelector('input').value;
        if (!filterValuesDefault.includes(filterValue)) {
            item.classList.add('d-none');
        } else {
            itemsCount++;
        }
    });
    diametrFilterWrapHeight = 16 * itemsCount + 10 * (itemsCount - 1);
    //console.log(itemsCount);
    document.documentElement.style.setProperty('--diametrFilterWrapHeight', `${diametrFilterWrapHeight}px`);

    diametrFilterWrap.classList.add('hidden');
    showMoreBtn.addEventListener('click', showAllFilterItems, {once:true});
    showMoreBtn.textContent = 'Показать все';
}

function showAllFilterItems() {
    let itemsCount = 0;
    diametrFilterItems.forEach((item, index, arr) => {
        item.classList.remove('d-none');
        itemsCount++;
    })
    diametrFilterWrapHeight = 16 * itemsCount + 10 * (itemsCount - 1);
    console.log(itemsCount);
    document.documentElement.style.setProperty('--diametrFilterWrapHeight', `${diametrFilterWrapHeight}px`);
    diametrFilterWrap.classList.remove('hidden');
    showMoreBtn.addEventListener('click', hideFilterItems, {once:true});
    showMoreBtn.addEventListener('click', (event) => {
        let sidebar = document.querySelector('.catalog__sidebar-filters');
        sidebar.scrollIntoView({behavior: "smooth"});
    }, {once:true});
    showMoreBtn.textContent = 'Свернуть';
}

if(diametrFilter) {
    diametrFilterWrap = diametrFilter.querySelector('.bapf_body');
    diametrFilterItems = Array.from(diametrFilter.querySelectorAll('li'));
    console.log(diametrFilterItems.length)

    if(diametrFilterItems.length > 9) {
      showMoreBtn = document.createElement("button");
      showMoreBtn.classList.add('filter-show-more-btn');
      diametrFilter.appendChild(showMoreBtn);
  
      hideFilterItems();
    }

}


//search field for plants names filter
function setSearchFilterField() {
    const searchInput = document.querySelector('.berocket-search-checkbox');
    const checkboxes = document.querySelectorAll('.filter_plant_name li');

    if (searchInput && checkboxes.length) {
        searchInput.addEventListener('input', function () {
        console.log('hi hi');
        const query = this.value.toLowerCase();
        checkboxes.forEach((li) => {
            const label = li.textContent.toLowerCase();
            li.style.display = label.includes(query) ? '' : 'none';
        });
        });
    }
}

document.addEventListener('DOMContentLoaded', setSearchFilterField);