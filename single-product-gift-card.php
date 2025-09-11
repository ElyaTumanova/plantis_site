<?php get_header(); 
$gcid = 15419;

global $product;
$target_id = 15419; // <-- ID нужного товара

?>


<h1>Hi this is gift card page</h1>

<?php 

ywgc_append_design_presets( $product );
echo do_shortcode('[yith_ywgc_display_gift_card_form]');

if ( $product && (int) $product->get_id() === $target_id ) : ?>
    <a class="button alt buy-now"
       href="<?php echo esc_url( add_query_arg(
           array(
               'add-to-cart' => $product->get_id(),
               'quantity'    => 1,
               'buy_now'     => 1, // флажок для редиректа (см. шаг 4)
           ),
           wc_get_page_permalink( 'checkout' ) // сразу на страницу Оформления
       ) ); ?>">
        Купить сейчас
    </a>
<?php endif; 
?>

<?php get_footer();?>