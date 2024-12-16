let majorCats = document.querySelectorAll('.menu--main .menu-node_lvl_1');

function showSubmenu(event) {
    console.log(event.target);

}

majorCats.forEach((el) => {
	//console.log(el);
    el.addEventListener('mouseenter',showSubmenu);
})