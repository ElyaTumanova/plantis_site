<?php

get_header(); ?>

<div class="content-area">
	<div>может быть это главная?</div>
	<main id="main" class="site-main" role="main">

	<div class="catalog__sidebar">
		<?php echo do_shortcode('[products on_sale="true" class="main-sale-slider" limit="8" columns="3" orderby="rand" category="komnatnye-rasteniya"]') ?>
    </div>
			
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
