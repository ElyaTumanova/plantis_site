<?php
/**
 * Wishlist page template - Standard Layout
 * 
 * MODIFIED FOR PLANTIS THEME
 *
 * @author YITH
 * @package YITH\Wishlist\Templates\Wishlist\View
 * @version 3.0.11
 */

/**
 * Template variables:
 *
 * @var $wishlist                      \YITH_WCWL_Wishlist Current wishlist
 * @var $wishlist_items                array Array of items to show for current page
 * @var $wishlist_token                string Current wishlist token
 * @var $wishlist_id                   int Current wishlist id
 * @var $users_wishlists               array Array of current user wishlists
 * @var $pagination                    string yes/no
 * @var $per_page                      int Items per page
 * @var $current_page                  int Current page
 * @var $page_links                    array Array of page links
 * @var $is_user_owner                 bool Whether current user is wishlist owner
 * @var $show_price                    bool Whether to show price column
 * @var $show_dateadded                bool Whether to show item date of addition
 * @var $show_stock_status             bool Whether to show product stock status
 * @var $show_add_to_cart              bool Whether to show Add to Cart button
 * @var $show_remove_product           bool Whether to show Remove button
 * @var $show_price_variations         bool Whether to show price variation over time
 * @var $show_variation                bool Whether to show variation attributes when possible
 * @var $show_cb                       bool Whether to show checkbox column
 * @var $show_quantity                 bool Whether to show input quantity or not
 * @var $show_ask_estimate_button      bool Whether to show Ask an Estimate form
 * @var $show_last_column              bool Whether to show last column (calculated basing on previous flags)
 * @var $move_to_another_wishlist      bool Whether to show Move to another wishlist select
 * @var $move_to_another_wishlist_type string Whether to show a select or a popup for wishlist change
 * @var $additional_info               bool Whether to show Additional info textarea in Ask an estimate form
 * @var $price_excl_tax                bool Whether to show price excluding taxes
 * @var $enable_drag_n_drop            bool Whether to enable drag n drop feature
 * @var $repeat_remove_button          bool Whether to repeat remove button in last column
 * @var $available_multi_wishlist      bool Whether multi wishlist is enabled and available
 * @var $no_interactions               bool
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<!-- WISHLIST MOBILE -->
<section class="catalog__grid section">
  <?php do_action( 'plnt_wish_before_shop_loop', $wishlist );?>
  <ul
    class="products columns-3 shop_table cart wishlist_view responsive mobile <?php echo $show_cb ? 'with-checkbox' : ''; ?> <?php echo $no_interactions ? 'no-interactions' : ''; ?>"
    data-pagination="<?php echo esc_attr( $pagination ); ?>" data-per-page="<?php echo esc_attr( $per_page ); ?>" data-page="<?php echo esc_attr( $current_page ); ?>"
    data-id="<?php echo esc_attr( $wishlist_id ); ?>" data-token="<?php echo esc_attr( $wishlist_token ); ?>">
  
    <?php
    if ( $wishlist && $wishlist->has_items() ) :
      foreach ( $wishlist_items as $item ) :
        /**
         * Each of wishlist items
         *
         * @var $item \YITH_WCWL_Wishlist_Item
         */
        global $product;
        global $post;
  
        $product = $item->get_product();
  
        if ( $product && $product->exists() ) :
          $post = get_post( $product->get_id() );
          setup_postdata( $post );
          ?>
           <?php wc_get_template_part( 'content', 'product' ); ?>
          <?php
        endif;
      endforeach;
      wp_reset_postdata();
    else :
      ?>
      <p class="wishlist-empty">
        <?php
        /**
         * APPLY_FILTERS: yith_wcwl_no_product_to_remove_message
         *
         * Filter the message shown when there are no products in the wishlist.
         *
         * @param string $message Message
         *
         * @return string
         */
        echo esc_html( apply_filters( 'yith_wcwl_no_product_to_remove_message', __( 'No products added to the wishlist', 'yith-woocommerce-wishlist' ) ) );
        ?>
      </p>
    <?php endif; ?>
  
  </ul>
</section>

<?php if ( ! empty( $page_links ) ) : ?>
	<nav class="wishlist-pagination">
		<?php echo wp_kses_post( $page_links ); ?>
	</nav>
<?php endif; ?>
