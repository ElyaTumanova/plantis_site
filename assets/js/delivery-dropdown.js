const del_dropdown = document.querySelectorAll('.delivery__block');

del_dropdown.forEach((el) => {
	const dropdown = el.querySelector('.delivery__dropdown');
	const btn = el.querySelector('.delivery__dropdown-arrow');
	btn.addEventListener('click', function (event) {
		dropdown.classList.toggle('delivery__dropdown_show');
		el.classList.toggle('delivery__dropdown_open');
		event.stopPropagation();
	})
})