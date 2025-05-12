let diametrFilter = document.querySelector('.filter_diametr_gorshka');

let diametrFilterItems = diametrFilter.querySelectorAll('li');

console.log(diametrFilterItems);

const newBtn = document.createElement("button");

newBtn.textContent = 'Показать все';

diametrFilter.appendChild(newBtn);