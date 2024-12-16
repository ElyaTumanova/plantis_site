let majorCats = document.querySelectorAll('.menu--main .menu-item-has-children.menu-node_lvl_1');
let subMenues = document.querySelectorAll('.menu--main .sub-menu');
let firstSubMenues = majorCats[0].querySelectorAll('.sub-menu');
console.log(firstSubMenues)
console.log(subMenues)

function showSubmenu(event) {
    console.log(event.target);
    let menu = event.target;
    let menuSubMenues = menu.querySelectorAll('.sub-menu');
    menuSubMenues.forEach((el) => {
       //console.log(el);
        el.classList.add('menu--onside_show');
    })
}

function closeAllSubmenu() {
    subMenues.forEach((el) => {
       // console.log(el);
        el.classList.remove('menu--onside_show');
    })
}

closeAllSubmenu();

firstSubMenues.forEach((el) => {
    el.classList.add('menu--onside_show');
});

majorCats.forEach((el) => {
	//console.log(el);
    el.addEventListener('mouseenter',closeAllSubmenu);
    el.addEventListener('mouseenter',showSubmenu);
})