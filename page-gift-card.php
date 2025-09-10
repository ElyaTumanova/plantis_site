<?php get_header(); 
$gcid = 15419;


global $product, $post;

$post    = get_post( $gcid );
$product = wc_get_product( $gcid );
?>

<h1>Hi this is gift card page</h1>




<?php 
if ( $product && $product->is_type( 'gift-card' ) ) {
    setup_postdata( $post );

    // Шорткод теперь видит "текущий товар"
    echo do_shortcode('[yith_ywgc_display_gift_card_form]');

    wp_reset_postdata();
} else {
    echo '<p>Указанный продукт не является gift-card либо не найден.</p>';
}
?>

<?php get_footer();?>