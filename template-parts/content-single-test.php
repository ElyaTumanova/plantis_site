<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <p>hello</p>

		<div class="entry-meta">

		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php 
		    the_content(); 

            ?>
            <a class="take-test button" href='<?php echo site_url()?>/test-kakoe-ty-rastenie' target = "_blank">Пройти тест</a>
            <?php
	
			
			
			?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
	
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
