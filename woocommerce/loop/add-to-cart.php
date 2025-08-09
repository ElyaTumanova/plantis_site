<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 * 
 * MODIFIED FOR PLANTIS THEME (for remove from cart function)
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
global $peresadka_cat_id;
$parentCat = check_category ($product);

// added
if( $product->is_type( 'simple' )
&& $product->is_purchasable()
&& $product->is_in_stock()
&& WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) )
&& $parentCat != $peresadka_cat_id) 
{ 
    $cart_item_key = WC()->cart->generate_cart_id( $product->get_id() );
    $url = wc_get_cart_remove_url( $cart_item_key );
    // if ($parentCat === $peresadka_cat_id) {
    //     $text = 'Пересадка добавлена';
    // } else {
    //     $text = 'Добавлен';
    // }
    $text = 'Добавлено';
    $class = 'button product_type_simple remove_from_cart_button added';
}
else {
	if ($parentCat == $peresadka_cat_id) 
	{
        $url = $product->add_to_cart_url();
		$text = 'Добавить пересадку за ' .$product->get_price(). ' руб.';
		$class = isset( $args['class'] ) ? $args['class'] : 'button';
	} 
	else {
        $url = $product->add_to_cart_url();
		$text = $product->add_to_cart_text();
		$class = isset( $args['class'] ) ? $args['class'] : 'button';
	}
}
//

if ($parentCat !== $peresadka_cat_id) {
    echo apply_filters(
        'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
        sprintf(
            '<form action="%s" method="post" class="add_to_cart_from">
            <button type="submit" data-quantity="%s" class="%s" %s>%s</button>
            </form>',
            // esc_url( $product->add_to_cart_url() ),
            esc_url( $url),                                                                         //added
            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            // esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
            esc_attr( $class ),                                                                     //added
            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
            // esc_html( $product->add_to_cart_text() )
            esc_html( $text )                                                                       //added
        ),
        $product,
        $args
    );
} else {
    echo apply_filters(
    	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
    	sprintf(
    		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            //esc_url( $product->add_to_cart_url() ),
            esc_url( $url),                                                                         //added
    		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
    		// esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
    		esc_attr( $class ),                                                                     //added
    		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
    		// esc_html( $product->add_to_cart_text() )
    		esc_html( $text )                                                                       //added
    	),
    	$product,
    	$args
    );
}


// echo apply_filters(
// 	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
// 	sprintf(
// 		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
//         // esc_url( $product->add_to_cart_url() ),
//         esc_url( $url),                                                                         //added
// 		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
// 		// esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
// 		esc_attr( $class ),                                                                     //added
// 		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
// 		// esc_html( $product->add_to_cart_text() )
// 		esc_html( $text )                                                                       //added
// 	),
// 	$product,
// 	$args
// );