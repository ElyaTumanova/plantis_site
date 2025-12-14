<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Enqueue all scripts
 */
if ( ! function_exists( 'ast_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'ast_scripts' );
	function ast_scripts() {

    global $delivery_inMKAD, $delivery_outMKAD;
    global $local_pickup, $delivery_free, $delivery_pochta, $delivery_courier, $delivery_long_dist;

    $late_markup_delivery   = carbon_get_theme_option('late_markup_delivery');
    $urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');
    $shipping_costs         = plnt_get_shiping_costs();

    $in_mkad  = $shipping_costs[$delivery_inMKAD];
    $out_mkad = $shipping_costs[$delivery_outMKAD];

    $isbackorders      = plnt_is_backorder();
    $isTreezBackorders = plnt_is_treez_backorder();

    $delivery_murkup = get_delivery_markup();

    $isUrgentCourierTariff = true;
		// wp_enqueue_script( 'magnific-popup', get_template_directory_uri() .
		//                                      '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), null, true );
		// wp_enqueue_script( 'owl-script', get_template_directory_uri() .
		//                                  '/assets/js/owl.carousel.min.js', array( 'jquery' ), null, true );
		
		wp_enqueue_script( 'ajax-update-cart', get_template_directory_uri() .
											 '/assets/js/ajax-update-cart.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/ajax-update-cart.js'), true );									 
		
    wp_enqueue_script( 'filter-show-more', get_template_directory_uri() .
		                                     '/assets/js/filter-show-more.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/filter-show-more.js'), true );

		wp_enqueue_script( 'ajax-urgent-delivery', get_template_directory_uri() .
											 '/assets/js/ajax-urgent-delivery.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/ajax-urgent-delivery.js'), true );	
		
		wp_localize_script(
        'ajax-urgent-delivery',
        'PLNT_Delivery_Data',
        [
            'deliveryInMKAD'        => (string) $delivery_inMKAD,
            'deliveryOutMKAD'       => (string) $delivery_outMKAD,

            'localPickupId'         => (string) $local_pickup,
            'deliveryFreeId'        => (string) $delivery_free,
            'deliveryPochtaId'      => (string) $delivery_pochta,
            'deliveryCourierId'     => (string) $delivery_courier,
            'deliveryLongId'        => (string) $delivery_long_dist,

            'deliveryCostInMkad'    => (float) $in_mkad,
            'deliveryCostOutMkad'   => (float) $out_mkad,

            'deliveryUrgMarkup'     => (float) $delivery_murkup['urg'],
            'deliveryLateMarkup'    => (float) $late_markup_delivery,
            'deliveryMarkupInMkad'  => (float) $delivery_murkup['in_mkad'],
            'deliveryMarkupOutMkad' => (float) $delivery_murkup['out_mkad'],

            'isBackorder'           => (bool) $isbackorders,
            'isTreezBackorders'     => (bool) $isTreezBackorders,
            'isUrgentCourierTariff'     => (bool) $isUrgentCourierTariff,
        ]
    );
    
    wp_enqueue_script( 'chekout-fields', get_template_directory_uri() .
											 '/assets/js/chekout-fields.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/chekout-fields.js'), true );	
		
		wp_enqueue_script( 'ajax-search', get_template_directory_uri() .
		                                 '/assets/js/ajax-search.js', array(), filemtime(get_stylesheet_directory() .'/assets/js/ajax-search.js'), true );
		wp_localize_script ('ajax-search', 'search_form', array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('search-nonce')
		));
		
		wp_enqueue_script( 'buttons', get_template_directory_uri() .
											 '/assets/js/buttons.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/buttons.js'), true );	
		wp_enqueue_script( 'slider-init', get_template_directory_uri() .
											 '/assets/js/slider-init.js', array( 'jquery', 'swiper' ), filemtime(get_stylesheet_directory() .'/assets/js/slider-init.js'), true );
		wp_enqueue_script( 'main-cats-sliders', get_template_directory_uri() .
											 '/assets/js/main-cats-sliders.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/main-cats-sliders.js'), true );
		wp_enqueue_script( 'header-catalog-menu', get_template_directory_uri() .
		                                     '/assets/js/header-catalog-menu.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/header-catalog-menu.js'), true );
		wp_enqueue_script( 'search-popup', get_template_directory_uri() .
		                                     '/assets/js/search-popup.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/search-popup.js'), true );
		wp_enqueue_script( 'page-popup', get_template_directory_uri() .
											 '/assets/js/page-popup.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/page-popup.js'), true );
		wp_enqueue_script( 'side-cart', get_template_directory_uri() .
											 '/assets/js/side-cart.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/side-cart.js'), true );
		wp_enqueue_script( 'swiper', get_template_directory_uri() .
		                                     '/assets/js/swiper.js', array( 'jquery' ), null, true ); //swiper	

		// wp_enqueue_script( 'account', get_template_directory_uri() .
		// 									 '/assets/js/account.js', array( 'jquery' ), null, true );	

		wp_enqueue_script( 'login-popup', get_template_directory_uri() .
											 '/assets/js/login-popup.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/login-popup.js'), true );

		// wp_enqueue_script( 'lazy-load', get_template_directory_uri() .
		//                                      '/assets/js/lazy-load.js', array( 'jquery' ), null, true ); // for lazy load

		// wp_enqueue_script( 'progressive-image', get_template_directory_uri() .
		                                    //  '/assets/js/progressive-image.js', array( 'jquery' ), null, true ); // for lazy load

		wp_enqueue_script( 'quantity-buttons', get_template_directory_uri() .
		                                     '/assets/js/quantity-buttons.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/quantity-buttons.js'), true );

		wp_enqueue_script( 'menu-mob', get_template_directory_uri() .
		                                     '/assets/js/menu-mob.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/menu-mob.js'), true );
											 
		// wp_enqueue_script( 'jquery.flexisel', get_template_directory_uri() .
		//                                      '/assets/js/jquery.flexisel.js', array( 'jquery' ), null, true );

		// wp_enqueue_script( 'jquery.nivo.slider', get_template_directory_uri() .
		//                                      '/assets/js/jquery.nivo.slider.js', array( 'jquery' ), null, true);

		wp_enqueue_script( 'catalog-menu', get_template_directory_uri() .
		                                     '/assets/js/catalog-menu.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/catalog-menu.js'), true );
		wp_enqueue_script( 'gift-card', get_template_directory_uri() .
		                                     '/assets/js/gift-card.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/gift-card.js'), true );

		wp_enqueue_script( 'delivery-dropdown', get_template_directory_uri() .
		                                     '/assets/js/delivery-dropdown.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/delivery-dropdown.js'), true );
		
		wp_enqueue_script( 'cart-backorder-crossell', get_template_directory_uri() .
		                                     '/assets/js/cart-backorder-crossell.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/cart-backorder-crossell.js'), true );

		
		if(is_page('test-kakoe-ty-rastenie')) {
			wp_enqueue_script( 'test', get_template_directory_uri() .
												 '/assets/js/test.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/test.js'), true );
            
            $plant_types = require get_theme_file_path('assets/data/plant-types.php');
            $inline = 'const plantTypes = ' . wp_json_encode($plant_types, JSON_UNESCAPED_UNICODE) . ';';
            wp_add_inline_script('test', $inline, 'before');
            wp_localize_script('test','vars', array('theme_url' => get_template_directory_uri(), 'site_url' => get_site_url()));
    }

		wp_enqueue_script( 'cart-upsells', get_template_directory_uri() .
		                                     '/assets/js/cart-upsells.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/cart-upsells.js'), true );
		wp_enqueue_script( 'contact-form-validation', get_template_directory_uri() .
		                                     '/assets/js/contact-form-validation.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/contact-form-validation.js'), true );


		wp_enqueue_script( 'metrikaGoal', get_template_directory_uri() .
		                                     '/assets/js/metrikaGoal.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/metrikaGoal.js'), true );  //metrikaGoal Яндекс Метрика Yandex Metrika

		// wp_enqueue_script( 'datepicker', get_template_directory_uri() .
		//                                      '/assets/js/datepicker.js', array( 'jquery' ), null, true );  // datepicker
		// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		// 	wp_enqueue_script( 'comment-reply' );
		// }
	}
}

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
        'siteKey' => '6LcP2rIrAAAAAGxrNXEe4AP0rC_fXZ7v7vKVr4wF',
        'debug'   => false // <-- ставь false, чтобы выключить логи
    ));
    wp_enqueue_script('recaptcha-woocommerce');
}
add_action('wp_enqueue_scripts', 'my_enqueue_recaptcha_woo_js');

/**
 * Enqueue all styles
 */
if ( ! function_exists( 'ast_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'ast_styles' );
	function ast_styles() {
		wp_enqueue_style( 'ast-style', get_stylesheet_uri() );
		// wp_enqueue_style( 'magnific-css', get_template_directory_uri() .
		//                              '/assets/css/magnific-popup.css', array(), null, 'all' );
		// wp_enqueue_style( 'owl-css', get_template_directory_uri() .
		//                              '/assets/css/owl.carousel.min.css', array(), null, 'all' );

		wp_enqueue_style( 'swiper', get_template_directory_uri() .
									'/assets/css/swiper.css', array(), null, 'all' ); //swiper


    wp_enqueue_style( 'general', get_template_directory_uri() .
		                             '/assets/css/general.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/general.css'), 'all' );
		
    // main
    wp_enqueue_style( 'main', 
      get_template_directory_uri().'/assets/css/main.css', 
      array(), 
      filemtime(get_stylesheet_directory() .'/assets/css/main.css'), 
      'all' 
    );
		
    wp_enqueue_style( 'header', get_template_directory_uri() .
		                             '/assets/css/header.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/header.css'), 'all' );
		wp_enqueue_style( 'menu', get_template_directory_uri() .
		                             '/assets/css/menu.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/menu.css'), 'all' );
		wp_enqueue_style( 'card', get_template_directory_uri() .
		                             '/assets/css/card.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/card.css'), 'all' );
		wp_enqueue_style( 'catalog', get_template_directory_uri() .
		                             '/assets/css/catalog.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/catalog.css'), 'all' );
		wp_enqueue_style( 'cart', get_template_directory_uri() .
		                             '/assets/css/cart.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/cart.css'), 'all' );
		wp_enqueue_style( 'checkout', get_template_directory_uri() .
		                             '/assets/css/checkout.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/checkout.css'), 'all' );
		wp_enqueue_style( 'pages', get_template_directory_uri() .
		                             '/assets/css/pages.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/pages.css'), 'all' );
		wp_enqueue_style( 'wishlist', get_template_directory_uri() .
		                             '/assets/css/wishlist.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/wishlist.css'), 'all' );
		wp_enqueue_style( 'account', get_template_directory_uri() .
		                             '/assets/css/account.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/account.css'), 'all' );
		wp_enqueue_style( 'popup', get_template_directory_uri() .
		                             '/assets/css/popup.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/popup.css'), 'all' );
		
		wp_enqueue_style( 'gift-card', get_template_directory_uri() .
		                             '/assets/css/gift-card.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/gift-card.css'), 'all' );
		
    
		wp_enqueue_style( 'test', get_template_directory_uri() .
		                             '/assets/css/test.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/test.css'), 'all' );
  
    wp_enqueue_style( 'progressive-image', get_template_directory_uri() .
		                             '/assets/css/progressive-image.css', array(), null, 'all' ); // for lazy load
		wp_enqueue_style( 'FlexSlider', get_template_directory_uri() .
		                             '/assets/css/FlexSlider.css', array(), null, 'all' );
		// wp_enqueue_style( 'datepicker', get_template_directory_uri() .
		//                              '/assets/css/datepicker.material.css', array(), null, 'all' ); //datepicker
		// wp_enqueue_style( 'flexisel', get_template_directory_uri() .
		//                              '/assets/css/flexisel.css', array(), null, 'all' );
		// wp_enqueue_style( 'nivo-slider', get_template_directory_uri() .
		//                              '/assets/css/nivo-slider.css', array(), null, 'all' );
		wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap', array(), null, 'all' );
	};
}


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
        
        //wp_dequeue_style('woocommerce-product-filter-removable-chips-style');
        //wp_dequeue_style('woocommerce-product-gallery-style');
    }
  // if (is_product()) {
        
  //       //wp_dequeue_script('flexslider');
        
  //       //wp_dequeue_script('photoswipe-ui-default');

  //   }

}
add_action( 'init', 'remove_my_style_stylesheet', 99 );

function remove_my_style_stylesheet() {

	wp_deregister_style( 'wc-blocks-style' ); //отключаем стили WC так как font-awesome на критическом пути
	wp_deregister_style( 'wp-block-library' ); 
	wp_deregister_style( 'woocommerce-product-filter-removable-chips-style' ); 
	wp_deregister_style( 'woocommerce-product-gallery-style' ); 
}