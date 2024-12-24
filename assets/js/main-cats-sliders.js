let navItems = document.querySelectorAll('.main__cats-nav-title');
let catsSliders = document.querySelectorAll('.main__cats-slider');

console.log(navItems);
console.log(catsSliders);

function closeSlider() {
    navItems.forEach((el,index) => {
        if(index === 0) {
            el.classList.add('main__cats-nav-title_active');
        } else {
            el.classList.add('main__cats-nav-title');
        }
    });
    catsSliders.forEach((el,index) => {
        if(index === 0) {
            el.classList.add('main__cats-slider_open');
        } else {
            el.classList.add('main__cats-slider');
        }
    });
}


function showSlider(event,index) {
    console.log(event.target);
    console.log(index);
    catsSliders[index].classList.add('main__cats-slider_open');
}

closeSlider();
navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(event,index));
});