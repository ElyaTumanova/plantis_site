<?php get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
		
		<section class="error-404 not-found">
						
			<div class="page-content">
				<img width="800" height="286" src="<?php get_template_directory_uri()?> '/images/404.png" alt="Такой страницы нет">
				<p>Упс! Похоже что-то пошло не так…</p>
				<p>Давайте начнем сначала!</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button" role="button">На главную</a>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->
	
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
