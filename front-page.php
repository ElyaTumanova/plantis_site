<?php

get_header(); ?>

<div class="content-area-main">
	<main id="main" class="site-main" role="main">
	<div class="main__wrap-colored-bg main__row">
		<div class="main__wrap container">
			<div class="main__banners-wrap">
				<?php get_template_part('template-parts/main-banners-gallery');?>
			</div>
	
			<div class="main__sale-gallery-wrap">
				<h2 class="main__sale-gallery-title heading-2">Спецпредложения</h2>
				<div class="main__sale-gallery">
					<?php echo do_shortcode('[products on_sale="true" class="main-sale-slider" limit="20" columns="1" orderby="rand" category="komnatnye-rasteniya"]') ?>
			
					<script type="text/javascript">
						jQuery(window).load(function() {
							jQuery(".main-sale-slider > ul").flexisel({
								visibleItems:1,
								animationSpeed: 1000,
								autoPlay: false,
								autoPlaySpeed: 3000,
								pauseOnHover: true,
								enableResponsiveBreakpoints: true,
								responsiveBreakpoints: {
									portrait: {
										changePoint:480,
										visibleItems: 1
									},
									landscape: {
										changePoint:640,
										visibleItems:1
									},
									tablet: {
										changePoint:768,
										visibleItems: 1
									}
								}
							});
						});
					</script>
				</div>
				<a class="main__sale-button button" href="http://new.plantis.shop/product-category/%d1%81%d0%ba%d0%b8%d0%b4%d0%ba%d0%b8/"> Все комнатные растения со скидкой</a>			
			</div>
		</div>
	</div>
	<div class="main__wrap container main__row">
		<div class="advantages">
			<div class="advantages__wrap">
				<img width="111" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/фото_зел.svg" class="advantages__image" alt="" loading="lazy">														
				<div class="advantages__title">“Живые” фотографии</div>	
				<div class="advantages__descr">Перед оплатой мы пришлём вам фотографии именно того растения, которое поедет к вам домой</div>
			</div>
			<div class="advantages__wrap">
				<img width="110" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/пересадка_зел.svg" class="advantages__image" alt="" loading="lazy">
				<div class="advantages__title">Бесплатная пересадка</div>
				<div class="advantages__descr">Абсолютно бесплатно пересадим комнатное растение, если вы приобретаете его вместе с горшком из нашего каталога</div>		
			</div>
			<div class="advantages__wrap">									
				<img width="115" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/доставка_зел.svg" class="advantages__image" alt="" loading="lazy">														
				<div class="advantages__title">Бережная доставка</div>
				<div class="advantages__descr">Осуществляем доставку комнатных растений до двери. Если сумма покупки будет выше 15000 рублей, то делаем это бесплатно</div>	
			</div>
			<div class="advantages__wrap">		
				<img width="112" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/уход_зел.svg" class="advantages__image" alt="" loading="lazy">												
				<div class="advantages__title">Бесплатная консультация</div>
				<div class="advantages__descr">Поможем подобрать нового друга, ответим на вопросы по уходу и останемся на связи с вами даже после покупки</div>
			</div>	
		</div>
		<div class="main__contacts">
			<p class="main__contacts-text">Не знаете, какое комнатное растение подойдёт именно вам?<br>Спросите нас об этом!</p>
			<div class="main__contacts-buttons">
				<a class="main__contacts-button button main__contacts-button-whatsapp" href="https://wa.me/79647687944" target="_blank">
					<svg class="main__contacts-button-icon" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>					
				</a>
				<a class="main__contacts-button button main__contacts-button-telegram" href="https://t.me/+79647687944" target="_blank">
					<svg class="main__contacts-button-icon" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z"></path></svg>					
				</a>
			</div>
		</div>
	</div>

	<div class="main__about container main__row">
		<h2 class="main__about-heading">ИНТЕРНЕТ МАГАЗИН КОМНАТНЫХ РАСТЕНИЙ и цветов PLANTIS.SHOP</h2>		
		<p class="main__about-text">Мы сделали ставку на сервис и не прогадали.</p>
		<p class="main__about-text">Покупка в интернет-магазине горшечного растения может быть интересной и увлекательной.</p>
		<ul class="main__about-list">
			<li>Все горшечные растения поставляются из лучших питомников Голландии, Дании, Италии, Турции и России. Мы лично отбираем те экземпляры, которыми хотим поделиться с вами.</li>
			<li>В нашем каталоге комнатных растений практически каждый найдёт то, что ищет. А если не найдёт, то у нас можно заказать растение для вас.</li>
			<li>У нас большой выбор декоративно-лиственных растений, цветущих, вьющихся лиан, карликовых деревьев и, конечно же, комнатных пальм. 
				В ассортименте также представлены редкие и экзотические растения, бонсаи, а не только фикусы, спатифиллумы, сансевиерии и замиокулькасы.</li>
		</ul>
		<p class="main__about-text">Мы знаем, что многие наши клиенты хотят купить комнатное растение в горшке первый раз, поэтому предлагаем большой выбор неприхотливых комнатных растений.</p>	
		<p class="main__about-text">Благодаря такому отношению к делу более 30% наших клиентов возвращаются к нам за покупкой вновь.</p>
		<p class="main__about-text">Для нас это важно, ведь так приятно делать сервис, после которого вы будете вспоминать нас с теплотой и смотреть на своего нового друга.</p>					
	</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
