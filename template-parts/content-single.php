<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">

		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php 
		    the_content(); 
			if(wp_get_post_categories(get_the_ID())[0] == '1349') {
				?>
				<a class="take-test button" href='<?php echo site_url()?>/test-kakoe-ty-rastenie' target = "_blank">Пройти тест</a>
				<?php
			};
			
			
			?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'plantis-theme' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
	
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
