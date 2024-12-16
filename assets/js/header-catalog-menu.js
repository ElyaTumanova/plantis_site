let majorCats = document.querySelectorAll('.menu--main .menu-item-has-children.menu-node_lvl_1');

function showSubmenu(event) {
    console.log(event.target);
    let menu = event.target;
    let subMenues = menu.querySelectorAll('.sub-menu');
    subMenues.forEach((el) => {
        console.log(el);
        el.classList.add('menu--onside_show');
    })
}

function closeSubmenu() {
    let subMenues = document.querySelectorAll('.sub-menu');
    subMenues.forEach((el) => {
       // console.log(el);
        el.classList.remove('menu--onside_show');
    })
}

majorCats.forEach((el) => {
	console.log(el);
    el.addEventListener('mouseenter',closeSubmenu);
    el.addEventListener('mouseenter',showSubmenu);
})