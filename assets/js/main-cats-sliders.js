let navItems = document.querySelectorAll('.main__cats-nav-title');
//let catsSliders = document.querySelectorAll('.main__cats-slider');

function showSlider(sliderNmber) {
    // console.log(event.target);
    // console.log(sliderNmber);
    navItems.forEach((el,index) => {
        if(index === sliderNmber) {
            el.classList.add('main__cats-nav-title_active');
        } else {
            el.classList.remove('main__cats-nav-title_active');
        }
        console.log(el);
    });
    // catsSliders.forEach((el,index) => {
    //     if(index === sliderNmber) {
    //         el.classList.add('main__cats-slider_open');
    //     } else {
    //         el.classList.remove('main__cats-slider_open');
    //     }
    // });

}

showSlider(0);
navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(index));
});