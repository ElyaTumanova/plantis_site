let navItems = document.querySelectorAll('.main__cats-nav-title');
let catsSliders = document.querySelectorAll('.main__cats-slider');

console.log(navItems);
console.log(catsSliders);

navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(event,index));
});

function showSlider(event,index) {
    console.log(event.target);
    console.log(index);
}