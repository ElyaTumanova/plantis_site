let diametrFilter = document.querySelector('.filter_diametr_gorshka');

let diametrFilterItems = Array.from(diametrFilter.querySelectorAll('li'));

console.log(diametrFilterItems);

let showMoreBtn = document.createElement("button");
showMoreBtn.classList.add('show-more-btn');
showMoreBtn.textContent = 'Показать все';
diametrFilter.appendChild(showMoreBtn);
showMoreBtn.querySelector('.show-more-btn');

function hideFilterItems() {
    diametrFilterItems.forEach((item, index, arr) => {
        if (index > 5) {
            item.classList.add('d-none');
        }
    });
    showMoreBtn.addEventListener('click', showAllFilterItems, {once:true});
    console.log(showMoreBtn)
    //showMoreBtn.textContent('Показать все');
}

function showAllFilterItems() {
    console.log('hi showAllFilterItems')
    diametrFilterItems.forEach((item, index, arr) => {
        item.classList.remove('d-none');
    })
    showMoreBtn.addEventListener('click', hideFilterItems, {once:true});
    showMoreBtn.textContent('Свернуть');
    console.log(showMoreBtn)
}




hideFilterItems();