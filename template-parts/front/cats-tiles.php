<?php
/**
 * Category tiles (WooCommerce product categories + first tile "Все товары")
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wc_get_page_permalink' ) ) {
	return;
}

/*
|--------------------------------------------------------------------------
| Параметры плиток
|--------------------------------------------------------------------------
*/
global $plants_cat_id;
$parent     = $plants_cat_id;
$hide_empty = true;
// $number     = 0;

$show_count = false;

$placeholder_image = wc_placeholder_img_src();


$shop_url = wc_get_page_permalink( 'shop' );

$args_terms = array(
	'taxonomy'   => 'product_cat',
	'parent'     => $parent,
	'hide_empty' => $hide_empty,
	'orderby'    => 'menu_order',
	'order'      => 'ASC',
);

// if ( $number > 0 ) {
// 	$args_terms['number'] = $number;
// }

$terms = get_terms( $args_terms );
if ( is_wp_error( $terms ) ) {
	return;
}
?>

<div class="cats-tiles swiper swiper--over">
	<div class="cats-tiles__wrapper swiper-wrapper">

		<?php foreach ( $terms as $term ) : ?>
			<?php
			// if ( ! reazy_category_has_available_products( $term->term_id ) ) {
			// 	continue;
			// }

			$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
			$img_url  = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_thumbnail' ) : '';

			if ( ! $img_url && function_exists( 'wc_placeholder_img_src' ) ) {
				$img_url = wc_placeholder_img_src( 'woocommerce_thumbnail' );
			}

			$link = get_term_link( $term );

			if ( is_wp_error( $link ) ) {
				continue;
			}
			?>

			<a class="cats-tiles__link swiper-slide" href="<?php echo esc_url( $link ); ?>">
				<span class="cats-tiles__media">
					<?php if ( $img_url ) : ?>
						<img loading="lazy" src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
					<?php endif; ?>
				</span>

				<span class="cats-tiles__title">
					<?php echo esc_html( $term->name ); ?>

					<?php if ( $show_count ) : ?>
						<span class="cats-tiles__count">(<?php echo (int) $term->count; ?>)</span>
					<?php endif; ?>
				</span>
			</a>
		<?php endforeach; ?>

	</div>

	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
	<div class="swiper-scrollbar"></div>
</div>