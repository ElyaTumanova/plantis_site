<?php
get_header(); ?>

<div class="content-area content-area_sidebar">
    <aside class='info-menu-sidebar'>  
        <?php get_template_part('template-parts/info-pages-list');?> 
    </aside> 
	<main id="main" class="site-main" role="main">
		<header class="entry-header">
			<h1 class="entry-title">Поставщикам и партнерам</h1>                
		</header>
		<div class="info__content">
			<p>Уважаемые поставщики и партнёры!</p>
            <p>Мы всегда готовы рассмотреть ваши предложения по сотрудничеству с нашей компанией.</p>
            <p>Для этого свяжитесь с нами удобным способом.<br>Мы не оставим вас без ответа.</p>					

            <?php get_template_part('template-parts/contacts-part');?>
        
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>