<?php get_header(); 
$gcid = 15419;

global $product;
$target_id = 15419; // <-- ID нужного товара

//wc_get_template( 'single-product/product-image.php' );
?>


<h1>Hi this is gift card page</h1>

<?php 

echo do_shortcode('[yith_ywgc_display_gift_card_form]');

if ( $product && (int) $product->get_id() === $gcid ) : ?>
  <button type="button" class="gift-card-button alt buy-now">
    <?php
      // Базовая подпись на случай, если JS недоступен:
      echo esc_html__( 'Купить сейчас', 'your-textdomain' );
    ?>
  </button>
<?php endif; ?>

<?php get_footer();?>