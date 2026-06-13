<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$mode       = $args['mode'] ?? '';
$product_id = $args['product_id'] ?? 0;
$product    = wc_get_product( $product_id );

if ( ! $product || ! $mode ) {
    return;
}

$query_args = array();
$title      = '';
$root_class = '';

if ( $mode === 'backorder_crosssell' ) {

    $ids = $product->get_cross_sell_ids();

    if ( empty( $ids ) ) {
        return;
    }

    $cart_product_ids = array();

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $cart_product_ids[] = (int) apply_filters(
            'woocommerce_cart_item_product_id',
            $cart_item['product_id'],
            $cart_item,
            $cart_item_key
        );
    }

    $ids = array_diff( $ids, $cart_product_ids );

    if ( empty( $ids ) ) {
        return;
    }

    $title      = 'Похожие растения в наличии';
    $root_class = 'backorder-crossells';

    $query_args = array(
        'posts_per_page' => 6,
        'orderby'        => 'rand',
        'post__in'       => $ids,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'komnatnye-rasteniya',
                'operator' => 'IN',
            ),
        ),
    );
}

if ( $mode === 'cart_upsells' ) {
    $ids = $product->get_upsell_ids();

    if ( empty( $ids ) ) {
        return;
    }

    $title      = 'Этому растению подойдет';
    $root_class = 'cart-upsells';

    $query_args = array(
        'posts_per_page' => 8,
        'orderby'        => 'rand',
        'post__in'       => $ids,
    );
}

if ( empty( $query_args ) || empty( $root_class ) ) {
    return;
}
?>

<div class="<?php echo esc_attr( $root_class ); ?> cart__product-recs-wrap">
    <div class="cart__product-recs-header">
        <div class="cart__product-recs-title">
            <?php echo esc_html( $title ); ?>
        </div>

        <div class="cart__product-recs-toggle">
          <?php echo plnt_icon('chevron-left');?>
        </div>
    </div>

    <?php

    global $plnt_cart_product_slider_mode;
    global $plnt_replace_cart_item_key;

    $plnt_cart_product_slider_mode   = $mode === 'backorder_crosssell' ? 'backorder' : '';
    $plnt_replace_cart_item_key = $args['cart_item'] ?? '';

    get_template_part(
        'template-parts/products/product-slider',
        null,
        array(
            'queryArgs'      => $query_args,
            'slidesMobile'  => 1.8,
            'slidesTablet'  => 2.8,
            'slidesDesktop' => 5,
            'spaceMobile'     => 8,
            'spaceTablet'     => 8,
            'spaceDesktop'    => 8,
        )
    );

    $plnt_cart_product_slider_mode   = '';
    $plnt_replace_cart_item_key = '';
    ?>
</div>