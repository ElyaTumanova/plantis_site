<?php
defined( 'ABSPATH' ) || exit;

/**
 * Синхронизация метки "Скидки" с акционной ценой.
 */
add_action( 'woocommerce_after_product_object_save', function ( $product ) {

    $product_id = $product->get_id();

    if ( $product->get_sale_price( 'edit' ) !== '' ) {
        wp_set_object_terms( $product_id, 'skidki', 'product_tag', true );
    } else {
        wp_remove_object_terms( $product_id, 'skidki', 'product_tag' );
    }

} );