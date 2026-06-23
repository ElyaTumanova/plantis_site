<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if(is_page('wishlist')):?>
    <?php plnt_breadrumbs_yoast();?>
    <section class="catalog__header section">
      <div class="catalog__header-inner">
        <header class="woocommerce-products-header">
          <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        </header><!-- .entry-header -->
        <?php do_action( 'plnt_wish_after_header' );?>
      </div>
      <?php plnt_wishlist_share();?>
    </section>
  <?php else:?>
    <header class="entry-header">
      <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header><!-- .entry-header -->
  <?php endif;?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'plantis-theme' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'plantis-theme' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
