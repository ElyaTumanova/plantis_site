<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	</header><!-- .entry-header -->
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<?php 
	$product = wc_get_product( get_the_ID() );
	// print_r($product);
	$short_descr = $product->get_short_description();
	$sale = get_post_meta( get_the_ID(), '_sale_price', true);
	?>
	<div class="search-result__item">
		<a href="<?php echo get_permalink();?>" class="search-result__link" target="blank">
			<img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );?>" class="search-result__image" alt="<?php echo get_the_title();?>">
			<div class="search-result__info">
				<span class="search-result__title"><?php echo get_the_title();?></span>
				<span class="search-result__descr"><?php echo $short_descr?></span>
				<?php if ($sale) {
					?>
					<span class="search-result__reg-price"><?php echo get_post_meta( get_the_ID(), '_regular_price', true);?>&#8381;</span>
					<span class="search-result__price"><?php echo get_post_meta( get_the_ID(), '_sale_price', true);?>&#8381;</span>
					<?php
				} else {
					?>
					<span class="search-result__price"><?php echo get_post_meta( get_the_ID(), '_price', true);?>&#8381;</span>
					<?php 
				}
				?>
			</div>
		</a>  
	</div>

</article><!-- #post-## -->
