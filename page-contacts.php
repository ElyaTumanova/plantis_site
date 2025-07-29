<?php
get_header(); ?>

<div class="content-area content-area_sidebar">
    <aside class='info-menu-sidebar'>  
        <?php get_template_part('template-parts/info-pages-list');?> 
    </aside> 
	<main id="main" class="site-main" role="main">
		<header class="entry-header">
			<h1 class="entry-title">Контакты</h1>                
		</header>
		<div class="contacts__wrap info__content">
			<div class="contacts__phones">
				<!-- <h2 class="heading-2">Телефон</h2> -->
				<p><a class="contacts__phone" href="tel:+78002015790">8 800 201 57 90</a></p>
				<p><a class="contacts__phone" href="tel:+79647687944">8 964 768 79 44</a></p>
			</div>
            
			<div class="contacts__mail">
                <!-- <h2 class="heading-2">Почта</h2> -->
				<a class="contacts__mail-link" href="mailto:INFO@PLANTIS.SHOP">INFO@PLANTIS.SHOP</a>
                <div class="contacts__social-media">
                    <?php get_template_part('template-parts/social-media-btns');?>
                </div>
			</div>
		
			<div class="contacts__adress">
				<h2 class="heading-2">Адрес</h2>
				<p>г. Москва, ул. Мещерякова, д.3. м. Тушинская</p>
                <div class="contacts__work-hours">
                    <h2 class="heading-2">Часы работы</h2>
                    <p>Ежедневно с 10 до 20</p>
                </div>
				<div class="contacts__map">
					<iframe src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=237252555639" width="560" height="400" frameborder="0"></iframe>
				</div>		
			</div>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
