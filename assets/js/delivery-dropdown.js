const dropdown = document.querySelectorAll('.delivery__block');

dropdown.forEach((el) => {
	const menu = el.querySelector('.delivery__dropdown');
	const btn = el.querySelector('.delivery__dropdown-arrow');
	btn.addEventListener('click', function (event) {
		menu.classList.toggle('delivery__dropdown_show');
		// el.classList.toggle('catalog__dropdown_open');
		event.stopPropagation();
	})
})