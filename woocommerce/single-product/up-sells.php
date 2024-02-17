<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 * 
 * MODIFIED FOR PLANTIS_THEME
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>

	<div class="up-sells upsells">
		<?php
		$heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) );

		if ( $heading ) :
			?>
			<h2 class="heading-2"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

        <!-- <ul id="flexisel-upsells" class="products columns-<?php //echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>"> -->
        <!-- <ul id="flexisel-upsells" class="products columns-3"> -->
        <?php 
            if (wp_is_mobile()) {
                    ?> <ul class="card__slider_mob products columns-3"> <?php
            } else {
                ?> <ul id="flexisel-upsells" class="card__slider products columns-3"> <?php
            }
        ?>

			<?php foreach ( $upsells as $upsell ) : ?>

                <?php if ( ! $upsell->is_in_stock() && ! $upsell->backorders_allowed() ) : continue; endif; ?>

				<?php
				$post_object = get_post( $upsell->get_id() );

				setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

				wc_get_template_part( 'content', 'product' );
				?>

			<?php endforeach; ?>

        </ul>
        
        <?php if (count($upsells) >3) {?>  
            <script type="text/javascript">
                jQuery(window).load(function() {
                    jQuery("#flexisel-upsells").flexisel({
                        visibleItems: 3,
                        columnGaps: 30,  //кол-во колонок * column-gap для .card__sliders-wrap .products
                        animationSpeed: 1000,
                        autoPlay: false,
                        autoPlaySpeed: 3000,
                        pauseOnHover: true,
                        enableResponsiveBreakpoints: true,
                        responsiveBreakpoints: {
                            portrait: {
                                changePoint:480,
                                visibleItems: 2,
                                columnGaps: 20
                            },
                            landscape: {
                                changePoint:640,
                                visibleItems:2,
                                columnGaps: 20
                            },
                            tablet: {
                                changePoint:768,
                                visibleItems: 3,
                                columnGaps: 30
                            }
                        }
                    });
                });
            </script>
        <?php }?>  
        </div>
	<?php
endif;

wp_reset_postdata();
