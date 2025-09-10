<?php get_header(); 
$gcid = 15419;
$product    = wc_get_product( $gcid );
?>

<h1>Hi this is gift card page</h1>




<?php 


wc_get_template(
  'single-product/add-to-cart/gift-card.php',
  '',
  '',
  trailingslashit( YITH_YWGC_TEMPLATES_DIR )
);

?>

<?php get_footer();?>