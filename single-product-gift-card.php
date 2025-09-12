<?php get_header(); 
$gcid = 15419;

global $product;
$target_id = 15419; // <-- ID нужного товара

//wc_get_template( 'single-product/product-image.php' );
?>


<h1>Hi this is gift card page</h1>

<?php 
echo do_shortcode('[yith_ywgc_display_gift_card_form]');
?>

<?php get_footer();?>