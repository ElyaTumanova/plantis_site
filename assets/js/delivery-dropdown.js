// for page https://plantis.shop/delivery/ - not used - to be refactored
const del_dropdown = document.querySelectorAll('.delivery__block');

del_dropdown.forEach((el) => {
	const dropdown = el.querySelector('.delivery__dropdown');
	const header = el.querySelector('.delivery__header');
	header.addEventListener('click', function (event) {
		dropdown.classList.toggle('delivery__dropdown_show');
		header.classList.toggle('delivery__header_show');
		el.classList.toggle('delivery__block_show');
		event.stopPropagation();
	})
})