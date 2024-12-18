let navItems = document.querySelectorAll('.main__cats-nav-title');
let catsSliders = document.querySelectorAll('.main__cats-slider');

console.log(navItems);
console.log(catsSliders);

navItems.forEach(el => {
    el.addEventListener('click',showSlider);
});

function showSlider(event) {
    console.log(event.target);
}