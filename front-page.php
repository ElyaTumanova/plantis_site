<?php

get_header(); 

global $skidki_cat_id;
?>

<div class="content-area-full-width">
	<main id="main" class="site-main" role="main">
	<div class="main__wrap-colored-bg main__row">
		<div class="container">
			<div class="main__banners-wrap">
				<?php get_template_part('template-parts/main-banners-gallery');?>
			</div>

			<div class="main__catalog-buttons-wrap">
				<a class="main__plants-button button" href="<?php echo get_term_link( $plants_cat_id, 'product_cat' );?>">Комнатные растения</a>
				<a class="main__gorshki-button button" href="<?php echo get_term_link( $gorshki_cat_id, 'product_cat' );?>">Горшки и кашпо</a>
			</div>
	
			<!-- <div class="main__sale-gallery-wrap">
				<h2 class="main__sale-gallery-title heading-2">Спецпредложения</h2>
				<div class="main__sale-gallery">
					<?php //get_template_part('template-parts/products/products-on-sale');
					?>
				</div>
				<a class="main__sale-button button" href="<?php //echo get_term_link( $skidki_cat_id, 'product_cat' );?>">Все комнатные растения со скидкой</a>			
			</div> -->
		</div>
	</div>
	<div class="main__cats-wrap main__row container">
		<div class="main__cats-nav">
			<span class = "main__cats-nav-title" data-type="product_tag" data-term="skidki">Скидки</span>
			<span class = "main__cats-nav-title" data-type="product_cat" data-term="dekorativno-cvetushchie">Цветущие</span>
			<span class = "main__cats-nav-title" data-type="product_cat" data-term="fikusy">Фикусы</span>
			<span class = "main__cats-nav-title" data-type="product_tag" data-term="napolnye">Напольные</span>
			<span class = "main__cats-nav-title" data-type="product_cat" data-term="palms">Пальмы</span>
			<span class = "main__cats-nav-title" data-type="product_tag" data-term="novichkam">Неприхотливые</span>
			<span class = "main__cats-nav-title" data-type="product_cat" data-term="dekorativno-listvennye">Декоративно-лиственные</span>
			<span class = "main__cats-nav-title" data-type="product_cat" data-term="succulent">Суккуленты</span>
			<span class = "main__cats-nav-title" data-type="product_tag" data-term="pet-friendly">Pet friendly</span>
			<span class = "main__cats-nav-title" data-type="product_cat" data-term="lianas">Лианы</span>
		</div>
		<div class=main__cats-slider>
		</div>
	</div>
	<div class="cats-grid-wrap main__row container">
		<?php get_template_part('template-parts/plants-cats-grid');?>
	</div>
	<div class="main__wrap container main__row">
		<?php
			get_template_part( 'template-parts/advantages' );
		?>
		<!-- <div class="main__contacts">
			<p class="main__contacts-text">Не знаете, какое комнатное растение подойдёт именно вам?<br>Спросите нас об этом!</p>
			<?php //get_template_part('template-parts/social-media-btns');?>
		</div> -->
		<a class="main__telegram" href="https://t.me/plantis" target="blank"></a>
	</div>
	<div class="main__about container main__row">
		<h2>Plantis.shop — интернет-магазин комнатных растений с доставкой</h2>
		<p>Добро пожаловать в Plantis — интернет-магазин комнатных растений с быстрой доставкой по Москве и Московской области. У нас можно заказать комнатные растения онлайн или выбрать их  в нашем шоуруме оффлайн. Мы предлагаем эксклюзивный ассортимент горшечных растений и высокий уровень сервиса, чтобы ваша покупка была приятной.</p>
		<h3>Комнатные растения для дома, офиса и бизнеса</h3>
		<p>В нашем каталоге представлены <strong>горшечные растения</strong>, которые подойдут как для уютной квартиры, так и для стильного офиса, ресторана, салона красоты или бизнес-центра. Мы работаем как с физическими, так и с юридическими лицами, предлагая выгодные условия для корпоративных клиентов.</p>
		<h4>Ассортимент Plantis.shop включает:</h4>
		<ul>
			<li><strong>Декоративно-лиственные растения:</strong> фикусы, алоказии, аглаонемы, кодиеумы, а также другие цветы, которые могут похвалиться эффектным внешним видом;</li>
			<li><strong>Цветущие растения:</strong> антуриумы, спатифиллумы, гибискусы;</li>
			<li><strong>Комнатные пальмы и крупномеры:</strong> идеальны для зонирования и создания живых акцентов;</li>
			<li><strong>Лианы и ампельные растения:</strong> сциндапсусы, филодендроны, эпипремнумы — для вертикального озеленения;</li>
			<li><strong>Бонсаи, редкие и экзотические виды:</strong> для ценителей и коллекционеров;</li>
			<li><strong>Неприхотливые растения для начинающих:</strong> замиокулькас, сансевиерия, хлорофитум, пеперомия и другие;</li>
			<li><strong>Растения безопасные для животных:</strong> для тех, у кого есть четвероногий друг.</li>
		</ul>
		<h2>Plantis.shop — ваш эксперт по комнатным растениям</h2>
		<p>Plantis.shop — это не просто магазин, это команда экспертов, которые помогут выбрать идеальное растение под ваши цели, условия, образ жизни.</p>
		<p>Станьте частью зелёного сообщества Plantis. Мы — место, где легко и приятно заказать комнатные растения с доставкой, получая удовольствие от сервиса. Сервиса, к которому хочется возвращаться.</p>
	</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
