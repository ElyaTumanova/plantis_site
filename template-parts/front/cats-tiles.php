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

$tiles = [
    [
        'taxonomy' => 'product_tag',
        'slug'     => 'napolnye',
    ],
    [
      'taxonomy' => 'product_cat',
      'slug'     => 'dekorativno-listvennye',
    ],
    [
        'taxonomy' => 'product_cat',
        'slug'     => 'fikusy',
    ],
    [
        'taxonomy' => 'product_tag',
        'slug'     => 'novichkam',
    ],
    [
        'taxonomy' => 'product_cat',
        'slug'     => 'dekorativno-cvetushchie',
    ],
    [
        'taxonomy' => 'product_cat',
        'slug'     => 'palms',
    ],
    [
        'taxonomy' => 'product_tag',
        'slug'     => 'pet-friendly',
    ],
    [
        'taxonomy' => 'product_cat',
        'slug'     => 'lianas',
    ],
    [
        'taxonomy' => 'product_cat',
        'slug'     => 'succulent',
    ],
];

$terms = [];

foreach ( $tiles as $tile ) {

    $term = get_term_by(
        'slug',
        $tile['slug'],
        $tile['taxonomy']
    );

    if ( $term && ! is_wp_error( $term ) ) {
        $terms[] = $term;
    }
}
?>

<div class="cats-tiles swiper--over">
    <div class="swiper">
        <div class="cats-tiles__wrapper swiper-wrapper">

            <?php foreach ( $terms as $term ) : ?>

                <?php
                $front_image_id = (int) get_term_meta( $term->term_id, 'front_image', true );
                $thumb_id       = $front_image_id ?: (int) get_term_meta( $term->term_id, 'thumbnail_id', true );

                $img_url = $thumb_id
                    ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_thumbnail' )
                    : wc_placeholder_img_src( 'woocommerce_thumbnail' );

                $link = get_term_link( $term );

                if ( is_wp_error( $link ) ) {
                    continue;
                }
                ?>

                <a class="cats-tiles__link swiper-slide" href="<?php echo esc_url( $link ); ?>">
                    <span class="cats-tiles__media darken">
                        <img
                            loading="lazy"
                            src="<?php echo esc_url( $img_url ); ?>"
                            alt="<?php echo esc_attr( $term->name ); ?>">
                    </span>

                    <span class="cats-tiles__title">
                        <?php echo esc_html( $term->name ); ?>
                    </span>
                </a>

            <?php endforeach; ?>

        </div>

        <div class="swiper-scrollbar"></div>
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>