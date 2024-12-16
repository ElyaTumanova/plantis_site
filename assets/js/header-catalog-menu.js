let majorCats = document.querySelectorAll('.menu--main .menu-item-has-children.menu-node_lvl_1');

function showSubmenu(event) {
    console.log(event.target);
    let menu = event.target;
    let subMenues = menu.querySelectorAll('.sub-menu');

}

majorCats.forEach((el) => {
	console.log(el);
    el.addEventListener('mouseenter',showSubmenu);
})