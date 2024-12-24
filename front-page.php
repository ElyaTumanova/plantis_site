<?php

get_header(); 

global $skidki_cat_id;
?>

<div class="content-area-full-width">
	<main id="main" class="site-main" role="main">
	<div class="main__wrap-colored-bg main__row">
		<div class="main__wrap container">
			<div class="main__banners-wrap">
				<?php get_template_part('template-parts/main-banners-gallery');?>
			</div>

			<div class="main__catalog-buttons-wrap">
				<a class="main__plants-button button" href="<?php echo get_term_link( $plants_cat_id, 'product_cat' );?>">Комнатные растения</a>
				<a class="main__gorshki-button button" href="<?php echo get_term_link( $gorshki_cat_id, 'product_cat' );?>">Горшки и кашпо</a>
			</div>
	
			<div class="main__sale-gallery-wrap">
				<h2 class="main__sale-gallery-title heading-2">Спецпредложения</h2>
				<div class="main__sale-gallery">
					<?php get_template_part('template-parts/products-on-sale');
					?>
				</div>
				<a class="main__sale-button button" href="<?php echo get_term_link( $skidki_cat_id, 'product_cat' );?>">Все комнатные растения со скидкой</a>			
			</div>
		</div>
	</div>
	<div class="main__cats-wrap main__row container">
		<div class="main__cats-nav">
			<span class = "main__cats-nav-title">Скидки</span>
			<span class = "main__cats-nav-title">Популярные</span>
			<span class = "main__cats-nav-title">Декоративно-лиственные</span>
			<span class = "main__cats-nav-title">Пальмы</span>
			<span class = "main__cats-nav-title">Фикусы</span>
			<span class = "main__cats-nav-title">Лианы</span>
			<span class = "main__cats-nav-title">Цветущие</span>
		</div>
		<div class=main__cats-slider>
			<?php get_template_part('template-parts/products-on-sale');?>
			<a class="button main__cats-all" href="<?php echo get_term_link( $skidki_cat_id, 'product_cat' );?>">Все товары категории</a>
		</div>
		<div class=main__cats-slider>
			<?php get_template_part('template-parts/products-popular');?>
			<a class="button main__cats-all" href="<?php echo get_term_link( $skidki_cat_id, 'product_cat' );?>">Все товары категории</a>
		</div>
		<div class=main__cats-slider>
			<?php get_template_part('template-parts/products/products-listvennye');?>
			<a class="button main__cats-all" href="https://plantis.shop/product-category/komnatnye-rasteniya/dekorativno-listvennye/">Все товары категории</a>
		</div>
		<?php //get_template_part('template-parts/plants-cats-grid');?>
	</div>
	<div class="main__wrap container main__row">
		<?php
			get_template_part( 'template-parts/advantages' );
		?>
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
