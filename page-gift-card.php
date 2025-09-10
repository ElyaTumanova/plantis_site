<?php get_header(); 
$gcid = 15419;
$product    = wc_get_product( $gcid );
?>

<h1>Hi this is gift card page</h1>




<?php 


do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );

?>

<?php get_footer();?>