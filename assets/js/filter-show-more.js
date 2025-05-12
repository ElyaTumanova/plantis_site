let diametrFilter = document.querySelector('.filter_diametr_gorshka');

let diametrFilterItems = Array.from(diametrFilter.querySelectorAll('li'));

console.log(diametrFilterItems);

const newBtn = document.createElement("button");

newBtn.textContent = 'Показать все';

diametrFilter.appendChild(newBtn);

diametrFilterItems.forEach((item, index, arr) => {
if (index > 5) {
   item.classList.add('.d-none');
  }
})