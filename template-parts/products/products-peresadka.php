<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $plants_cat_id;
global $gorshki_cat_id;
global $treez_cat_id;
global $lechuza_cat_id;

$is_plants_in_cart = true;

// Если нужно учитывать наличие растений в корзине, раскомментируй
/*
$is_plants_in_cart = false;

foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$_product = apply_filters(
		'woocommerce_cart_item_product',
		$cart_item['data'],
		$cart_item,
		$cart_item_key
	);

	$parentCat = check_category( $_product );

	if ( $parentCat == $plants_cat_id ) {
		$is_plants_in_cart = true;
		break;
	}
}
*/

$img = sprintf(
	'<img src="%s" alt="" aria-hidden="true" width="50" height="48">',
	esc_url( get_template_directory_uri() . '/images/frontend/peresadka-decor.png' )
);

if ( ! $is_plants_in_cart ) {
	return;
}

$product_id = $args['product_id'] ?? 0;

if ( ! $product_id ) {
	return;
}

$cart_product = wc_get_product( $product_id );

if ( ! $cart_product ) {
	return;
}

$parentCat = check_category( $cart_product );

$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids', true );

if ( ! empty( $crosssell_ids ) && is_array( $crosssell_ids ) ) {

	$query_args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'posts_per_page'      => 1,
		'orderby'             => 'rand',
		'post__in'            => $crosssell_ids,

		'meta_query' => array(
			array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => '!=',
			),
		),

		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => 'peresadka',
			),
		),
	);

	$products = new WP_Query( $query_args );

	if ( $products->have_posts() ) {

		$products->the_post();

		$peresadka_product = wc_get_product( get_the_ID() );

		if ( $peresadka_product ) {

			$text = sprintf(
				'Пересадка %s',
				wc_price( $peresadka_product->get_price() )
			);
      echo ('<div class="cart__peresadka-wrap">');
			echo $img;
			?>

			<div class="peresadka_inner">
				<?php echo esc_html( wp_strip_all_tags( $text ) ); ?>

				<?php 
          $parent_cart_item_key = $args['cart_item_key'] ?? '';

          $filter_args = function( $args, $product ) use ( $parent_cart_item_key ) {

            if ( ! $parent_cart_item_key ) {
              return $args;
            }

            $args['attributes']['data-parent_cart_item_key'] = $parent_cart_item_key;

            $args['attributes']['data-plnt_parent_cart_item_key'] = $parent_cart_item_key;

            $args['class'] .= ' plnt-add-peresadka';

            return $args;
          };

          add_filter( 'woocommerce_loop_add_to_cart_args', $filter_args, 10, 2 );

          woocommerce_template_loop_add_to_cart();

          remove_filter( 'woocommerce_loop_add_to_cart_args', $filter_args, 10 );

      ?>


				<div class="cart__peresadka-added_mini"></div>
			</div>
      </div>

			<?php
		}

		wp_reset_postdata();

	} elseif (
		$parentCat == $gorshki_cat_id
		|| $parentCat == $treez_cat_id
		|| $parentCat == $lechuza_cat_id
	) {
    echo '<div class="cart__peresadka-wrap">';
		echo $img;
		echo '<div class="peresadka_free">Пересадка бесплатно</div>';
    echo '</div>';

	}

} elseif (
	$parentCat == $gorshki_cat_id
	|| $parentCat == $treez_cat_id
	|| $parentCat == $lechuza_cat_id
) {
  echo '<div class="cart__peresadka-wrap">';
	echo $img;
	echo '<div class="peresadka_free">Пересадка бесплатно</div>';
  echo '</div>';
}