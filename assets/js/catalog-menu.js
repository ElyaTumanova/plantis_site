const dropdown = document.querySelectorAll('.catalog__dropdown');

dropdown.forEach((el) => {
	const menu = el.querySelector('.catalog__dropdown-menu');
	const btn = el.querySelector('.catalog__dropdown-menu:after');
	console.log(btn);
	btn.addEventListener('click', function (event) {
		menu.classList.toggle('catalog__dropdown-menu_show');
		el.classList.toggle('catalog__dropdown_open');
		event.stopPropagation();
	})
})