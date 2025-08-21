<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
        <h1 class="entry-title">Поздравляем! <br>
        <?php the_title( '<span>Вы ', '</span>' ); ?>
        </h1>
		<?php 
		    the_content(); 
            if( has_post_thumbnail() ){
                $feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
                echo '<img class="test-image" src="'. $feat_image_url .'" alt = "' . get_the_title() . '">';
            }

            ?>
            <a class="take-test button" href='<?php echo site_url()?>/test-kakoe-ty-rastenie' target = "_blank">Пройти тест</a>
            <?php
	
			
			
			?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
	
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
