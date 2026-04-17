<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*  Style Config */
function plnt_get_style_files() {
	return array(
		'swiper',
		'general',
		'main',
		'header',
		'menu',
		'card',
		'catalog',
		'cart',
		'checkout',
		'pages',
		'wishlist',
		'account',
		'popup',
		'gift-card',
    'gift-card/gc-vars',
    'gift-card/gc-single-product',
    'gift-card/gc-result-page',
    'gift-card/gc-account-section',
		'test',
		'faq',
		'FlexSlider',
	);
}

/* Script Config */
function plnt_get_script_files() {
	return array(
		array(
			'handle' => 'ajax-update-cart',
			'file'   => 'ajax-update-cart',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'filter-show-more',
			'file'   => 'filter-show-more',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'ajax-urgent-delivery',
			'file'   => 'ajax-urgent-delivery',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'chekout-fields',
			'file'   => 'chekout-fields',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'ajax-search',
			'file'   => 'ajax-search',
			'deps'   => array(),
		),
		array(
			'handle' => 'buttons',
			'file'   => 'buttons',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'slider-init',
			'file'   => 'slider-init',
			'deps'   => array( 'jquery', 'swiper' ),
		),
		array(
			'handle' => 'main-cats-sliders',
			'file'   => 'main-cats-sliders',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'header-catalog-menu',
			'file'   => 'header-catalog-menu',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'search-popup',
			'file'   => 'search-popup',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'page-popup',
			'file'   => 'popup',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'swiper',
			'file'   => 'swiper',
			'deps'   => array( 'jquery' ),
			'ver'    => null,
		),
		array(
			'handle' => 'account',
			'file'   => 'account',
			'deps'   => array(),
			'ver'    => null,
		),
		array(
			'handle' => 'quantity-buttons',
			'file'   => 'quantity-buttons',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'catalog-menu',
			'file'   => 'catalog-menu',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'gift-card',
			'file'   => 'gift-card',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'cart-backorder-crossell',
			'file'   => 'cart-backorder-crossell',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'cart-upsells',
			'file'   => 'cart-upsells',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'contact-form-validation',
			'file'   => 'contact-form-validation',
			'deps'   => array( 'jquery' ),
		),
		array(
			'handle' => 'form-validation',
			'file'   => 'form-validation',
			'deps'   => array(),
		),
		array(
			'handle' => 'metrikaGoal',
			'file'   => 'metrikaGoal',
			'deps'   => array( 'jquery' ),
		),
	);
}

/* Script Localize */
function plnt_get_ajax_search_localize_data() {
	return array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'search-nonce' ),
	);
}

function plnt_get_account_localize_data() {
	return array(
		'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
		'nonce'         => wp_create_nonce( 'plantis_ajax_login' ),
		'registerNonce' => wp_create_nonce( 'plantis_ajax_register' ),
	);
}

function plnt_get_gift_card_localize_data() {
	$config = plnt_get_giftcard_designs_config();

	return array(
		'gradients'   => $config['gradients'] ?? array(),
		'images'      => $config['images'] ?? array(),
		'backgrounds' => $config['backgrounds'] ?? array(),
		'defaults'    => array(
			'gradient' => plnt_get_giftcard_default_gradient(),
			'image'    => plnt_get_giftcard_default_image(),
		),
	);
}

function plnt_get_delivery_localize_data() {
	global $delivery_inMKAD, $delivery_outMKAD;
	global $local_pickup, $delivery_free, $delivery_pochta, $delivery_courier, $delivery_long_dist;

	$late_markup_delivery   = carbon_get_theme_option( 'late_markup_delivery' );
	$late_interval_delivery = carbon_get_theme_option( 'late_interval_delivery' );
	$isUrgentCourierTariff  = carbon_get_theme_option( 'is_urgent_courier_tariff' );
	$isHolidayCourierTariff = carbon_get_theme_option( 'is_holiday_courier_tariff' ) == '1';

	$shipping_costs = plnt_get_shiping_costs();
	$in_mkad        = $shipping_costs[ $delivery_inMKAD ] ?? 0;
	$out_mkad       = $shipping_costs[ $delivery_outMKAD ] ?? 0;

	$isbackorders           = plnt_is_backorder();
	$isTreezBackorders      = plnt_is_treez_backorder();
	$delivery_murkup        = get_delivery_markup();
	$cart_subtotal          = ( WC()->cart ) ? WC()->cart->subtotal : 0;
	$isSmallHolidayCart     = $cart_subtotal < 5000;
	$isSmallHolidayTariffOn = $isHolidayCourierTariff && $isSmallHolidayCart;

	return array(
		'deliveryInMKAD'         => (string) $delivery_inMKAD,
		'deliveryOutMKAD'        => (string) $delivery_outMKAD,
		'localPickupId'          => (string) $local_pickup,
		'deliveryFreeId'         => (string) $delivery_free,
		'deliveryPochtaId'       => (string) $delivery_pochta,
		'deliveryCourierId'      => (string) $delivery_courier,
		'deliveryLongId'         => (string) $delivery_long_dist,
		'deliveryCostInMkad'     => (float) $in_mkad,
		'deliveryCostOutMkad'    => (float) $out_mkad,
		'deliveryUrgMarkup'      => (float) ( $delivery_murkup['urg'] ?? 0 ),
		'deliveryLateMarkup'     => (float) $late_markup_delivery,
		'deliveryLateInterval'   => (string) $late_interval_delivery,
		'deliveryMarkupInMkad'   => (float) ( $delivery_murkup['in_mkad'] ?? 0 ),
		'deliveryMarkupOutMkad'  => (float) ( $delivery_murkup['out_mkad'] ?? 0 ),
		'isBackorder'            => (bool) $isbackorders,
		'isTreezBackorders'      => (bool) $isTreezBackorders,
		'isUrgentCourierTariff'  => $isUrgentCourierTariff,
		'isSmallHolidayTariffOn' => $isSmallHolidayTariffOn,
	);
}


/* Enqueue helpers */
function plnt_localize_script( $handle, $object_name, $data ) {
	if ( wp_script_is( $handle, 'enqueued' ) ) {
		wp_localize_script( $handle, $object_name, $data );
	}
}

function plnt_scripts() {
	$template_uri  = get_template_directory_uri();
	$template_path = get_template_directory();

	foreach ( plnt_get_script_files() as $script ) {
		$file_rel_path = '/assets/js/' . $script['file'] . '.js';
		$file_path     = $template_path . $file_rel_path;
		$version       = array_key_exists( 'ver', $script )
			? $script['ver']
			: ( file_exists( $file_path ) ? filemtime( $file_path ) : null );

		wp_enqueue_script(
			$script['handle'],
			$template_uri . $file_rel_path,
			$script['deps'] ?? array(),
			$version,
			$script['in_footer'] ?? true
		);
	}

	plnt_localize_script( 'ajax-urgent-delivery', 'PLNT_Delivery_Data', plnt_get_delivery_localize_data() );
	plnt_localize_script( 'ajax-search', 'search_form', plnt_get_ajax_search_localize_data() );
	plnt_localize_script( 'account', 'PLANTIS_LOGIN', plnt_get_account_localize_data() );
	plnt_localize_script( 'gift-card', 'plntGiftCardData', plnt_get_gift_card_localize_data() );

	if ( is_page( 'test-kakoe-ty-rastenie' ) ) {
		$file_rel_path = '/assets/js/test.js';
		$file_path     = $template_path . $file_rel_path;

		wp_enqueue_script(
			'test',
			$template_uri . $file_rel_path,
			array( 'jquery' ),
			file_exists( $file_path ) ? filemtime( $file_path ) : null,
			true
		);

		$plant_types = require get_theme_file_path( 'assets/data/plant-types.php' );
		$inline      = 'const plantTypes = ' . wp_json_encode( $plant_types, JSON_UNESCAPED_UNICODE ) . ';';

		wp_add_inline_script( 'test', $inline, 'before' );

		wp_localize_script(
			'test',
			'vars',
			array(
				'theme_url' => get_template_directory_uri(),
				'site_url'  => get_site_url(),
			)
		);
	}
}

function plnt_styles() {
  wp_enqueue_style( 'plnt-style', get_stylesheet_uri() );

  $template_uri  = get_template_directory_uri();
  $template_path = get_template_directory();

  foreach ( plnt_get_style_files() as $style ) {
    $file_rel_path = '/assets/css/' . $style . '.css';
    $file_path     = $template_path . $file_rel_path;
    $handle        = 'plnt-' . str_replace( '/', '-', $style );

    wp_enqueue_style(
      $handle,
      $template_uri . $file_rel_path,
      array(),
      filemtime( $file_path ),
      'all'
    );
  }

	wp_enqueue_style(
		'fonts',
		'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap',
		array(),
		null,
		'all'
	);
}

/* Enqueue */
add_action( 'wp_enqueue_scripts', 'plnt_scripts' );
add_action( 'wp_enqueue_scripts', 'plnt_styles' );

/* Other */
function my_enqueue_recaptcha_woo_js() {
    // Передаём site key из PHP, чтобы не хардкодить
    wp_register_script(
        'recaptcha-woocommerce',
        get_template_directory_uri() . '/assets/js/recaptcha-woocommerce.js',
        array(), // grecaptcha уже подключен глобально
        filemtime(get_stylesheet_directory() .'/assets/js/recaptcha-woocommerce.js'),
        true
    );
    wp_localize_script('recaptcha-woocommerce', 'recaptchaWoo', array(
        'siteKey' => '6LezYTQsAAAAAEzapFcvWQ9w9vAP1uCYtNKXKfXy',
        'debug'   => false // <-- ставь false, чтобы выключить логи
    ));
    wp_enqueue_script('recaptcha-woocommerce');
}
//add_action('wp_enqueue_scripts', 'my_enqueue_recaptcha_woo_js');

add_action( 'wp_enqueue_scripts', 'plnt_no_filter_css', 9999 );
 
function plnt_no_filter_css() {
 
	wp_dequeue_style( 'yith-wcwl-user-main' ); //отключаем стили YITH wishlist так как font-awesome на критическом пути
	wp_dequeue_style( 'yith-wcwl-main' ); //отключаем стили YITH wishlist так как font-awesome на критическом пути
	
  wp_dequeue_style( 'classic-theme-styles' ); 
  wp_dequeue_style( 'woocommerce-add-to-cart-form-style' ); 
  wp_dequeue_style( 'woocommerce-product-button-style' ); 
  wp_dequeue_style( 'woocommerce-product-collection-style' ); 
  wp_dequeue_style( 'woocommerce-product-filter-checkbox-list-style' ); 
  wp_dequeue_style( 'woocommerce-product-filter-chips-style' ); 
  wp_dequeue_style( 'woocommerce-product-template-style' ); 
  wp_dequeue_style( 'global-styles' ); 
  wp_dequeue_style('woocommerce-product-filters-style');
  wp_dequeue_style('woocommerce-product-filter-price-slider-style');
  wp_dequeue_style('brands-styles');

  if( !is_checkout()) {
    wp_dequeue_style('suggestions');
    wp_dequeue_style('custom-css');
    // wp_dequeue_script('jquery.suggestions.min');
  }

  if (is_product() || is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
        
        wp_dequeue_style('woocommerce_prettyPhoto_css');
        wp_dequeue_script('photoswipe');
    }

}
add_action( 'init', 'remove_my_style_stylesheet', 99 );

function remove_my_style_stylesheet() {

	wp_deregister_style( 'wc-blocks-style' ); //отключаем стили WC так как font-awesome на критическом пути
	wp_deregister_style( 'wp-block-library' ); 
	wp_deregister_style( 'woocommerce-product-filter-removable-chips-style' ); 
	wp_deregister_style( 'woocommerce-product-gallery-style' ); 
}