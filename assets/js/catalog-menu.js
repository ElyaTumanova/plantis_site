const dropdown = document.querySelectorAll('.catalog__dropdown');
console.log(dropdown);

dropdown.forEach((el) => {
	console.log(el);
	const menu = el.querySelector('.catalog__dropdown-menu');
	// const button = el.querySelector('.icon-arrow');
	// console.log(menu);
	el.addEventListener('click', function (event) {
        // event.preventDefault();
		console.log('Произошло событие', event.target);
		menu.classList.toggle('catalog__dropdown-menu_show');
		// button.classList.toggle('open');
		// event.stopPropagation();
	})
})