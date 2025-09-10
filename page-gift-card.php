<?php get_header(); 
$gcid = 15419;
$product    = wc_get_product( $gcid );
?>

<h1>Hi this is gift card page</h1>




<?php 


echo do_shortcode('[yith_ywgc_display_gift_card_form]');


?>

<?php get_footer();?>