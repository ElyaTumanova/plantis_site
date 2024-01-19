<?php

get_header(); ?>



<div class="content-area">
	<main id="main" class="site-main" role="main">
	<div class="main__wrap-colored-bg">
		<div class="main__wrap container">
			<div class="main__banners-wrap">
				<?php get_template_part('template-parts/main-banners-gallery');?>
			</div>
	
			<div class="main__sale-gallery-wrap">
				<h2 class="main__sale-gallery-title heading-2">Спецпредложения</h2>
				<div class="main__sale-gallery">
					<?php echo do_shortcode('[products on_sale="true" class="main-sale-slider" limit="8" columns="1" orderby="rand" category="komnatnye-rasteniya"]') ?>
			
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
			</div>
		</div>
	</div>
	<div class="main__wrap container">
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
			<p>Не знаете, какое комнатное растение подойдёт именно вам?<br>Спросите нас об этом!</p>
		</div>
	</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
