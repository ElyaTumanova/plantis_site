<?php
get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
		<header class="entry-header">
			<h1 class="entry-title">Поставщикам и партнерам</h1>                
		</header>
		<div class="entry-content">
        <div class="elementor-text-editor elementor-clearfix">
			<p>Уважаемые поставщики и партнёры!</p>
            <p>Мы всегда готовы рассмотреть ваши предложения по сотрудничеству с нашей компанией.</p>
            <p>Для этого свяжитесь с нами удобным способом.<br>Мы не оставим вас без ответа.</p>					
        </div>

            <?php get_template_part('template-parts/contacts-part');?>
            
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>