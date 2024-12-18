let navItems = document.querySelectorAll('.main__cats-nav');
let catsSliders = document.querySelectorAll('.main__cats-nav');

console.log(navItems)
console.log(catsSliders)

navItems.forEach(el => {
    el.addEventListener('mousenter',showSlider);
});

function showSlider(event) {
    console.log(event.target);
}