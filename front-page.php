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
					<?php echo do_shortcode('[products on_sale="true" class="main-sale-slider" limit="10" columns="1" orderby="rand" category="komnatnye-rasteniya"]') ?>

					<?php if( !wp_is_mobile() ) {?>
					
						<script type="text/javascript">
							console.log('not mob');
							jQuery(window).load(function() {
								jQuery(".main-sale-slider > ul").flexisel({
									visibleItems:1,
									animationSpeed: 1000,
									autoPlay: false,
									autoPlaySpeed: 3000,
									pauseOnHover: false,
									enableResponsiveBreakpoints: true,
									responsiveBreakpoints: {
										portrait: {
											changePoint:767,
											visibleItems: 2,
											columnGaps: 10,
											// animationSpeed: 2000,
										},
										landscape: {
											changePoint:1095,
											visibleItems:2,
											columnGaps: 0,
											// animationSpeed: 1000,
										},
										tablet: {
											changePoint:1279,
											visibleItems: 3,
											columnGaps: 0.
											// animationSpeed: 1000,
										}
									}
								});
							});
						</script> <?php
					} ?>
			
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
			<?php get_template_part('template-parts/social-media-btns');?>
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
