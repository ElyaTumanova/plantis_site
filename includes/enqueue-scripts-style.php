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
		// wp_enqueue_script( 'magnific-popup', get_template_directory_uri() .
		//                                      '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), null, true );
		// wp_enqueue_script( 'owl-script', get_template_directory_uri() .
		//                                  '/assets/js/owl.carousel.min.js', array( 'jquery' ), null, true );
		
		wp_enqueue_script( 'ajax-update-cart', get_template_directory_uri() .
											 '/assets/js/ajax-update-cart.js', array( 'jquery' ), null, true );									 
		
		wp_enqueue_script( 'ajax-update-wish', get_template_directory_uri() .
											 '/assets/js/ajax-update-wish.js', array( 'jquery' ), null, true );	
		
		wp_enqueue_script( 'ajax-urgent-delivery', get_template_directory_uri() .
											 '/assets/js/ajax-urgent-delivery.js', array( 'jquery' ), null, true );	
		
		wp_enqueue_script( 'hide-chekout-fields', get_template_directory_uri() .
											 '/assets/js/hide-chekout-fields.js', array( 'jquery' ), null, true );	
		
		wp_enqueue_script( 'ajax-search', get_template_directory_uri() .
		                                 '/assets/js/ajax-search.js', array( 'jquery' ), null, true );
		wp_localize_script ('ajax-search', 'search_form', array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('search-nonce')
		));
		
		wp_enqueue_script( 'buttons', get_template_directory_uri() .
											 '/assets/js/buttons.js', array( 'jquery' ), null, true );	
		wp_enqueue_script( 'slider-init', get_template_directory_uri() .
											 '/assets/js/slider-init.js', array( 'jquery', 'swiper' ), null, true );
		wp_enqueue_script( 'main-cats-sliders', get_template_directory_uri() .
											 '/assets/js/main-cats-sliders.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'header-catalog-menu', get_template_directory_uri() .
		                                     '/assets/js/header-catalog-menu.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'search-popup', get_template_directory_uri() .
		                                     '/assets/js/search-popup.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'page-popup', get_template_directory_uri() .
											 '/assets/js/page-popup.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'side-cart', get_template_directory_uri() .
											 '/assets/js/side-cart.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'swiper', get_template_directory_uri() .
		                                     '/assets/js/swiper.js', array( 'jquery' ), null, false ); //swiper	

		// wp_enqueue_script( 'account', get_template_directory_uri() .
		// 									 '/assets/js/account.js', array( 'jquery' ), null, true );	

		wp_enqueue_script( 'login-popup', get_template_directory_uri() .
											 '/assets/js/login-popup.js', array( 'jquery' ), null, true );

		// wp_enqueue_script( 'lazy-load', get_template_directory_uri() .
		//                                      '/assets/js/lazy-load.js', array( 'jquery' ), null, true ); // for lazy load

		// wp_enqueue_script( 'progressive-image', get_template_directory_uri() .
		                                    //  '/assets/js/progressive-image.js', array( 'jquery' ), null, true ); // for lazy load

		wp_enqueue_script( 'quantity-buttons', get_template_directory_uri() .
		                                     '/assets/js/quantity-buttons.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'menu-mob', get_template_directory_uri() .
		                                     '/assets/js/menu-mob.js', array( 'jquery' ), null, true );
											 
		// wp_enqueue_script( 'jquery.flexisel', get_template_directory_uri() .
		//                                      '/assets/js/jquery.flexisel.js', array( 'jquery' ), null, true );

		// wp_enqueue_script( 'jquery.nivo.slider', get_template_directory_uri() .
		//                                      '/assets/js/jquery.nivo.slider.js', array( 'jquery' ), null, true);

		wp_enqueue_script( 'catalog-menu', get_template_directory_uri() .
		                                     '/assets/js/catalog-menu.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'delivery-dropdown', get_template_directory_uri() .
		                                     '/assets/js/delivery-dropdown.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'cart-backorder-crossell', get_template_directory_uri() .
		                                     '/assets/js/cart-backorder-crossell.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'metrikaGoal', get_template_directory_uri() .
		                                     '/assets/js/metrikaGoal.js', array( 'jquery' ), null, true );  //metrikaGoal Яндекс Метрика Yandex Metrika

		wp_enqueue_script( 'datepicker', get_template_directory_uri() .
		                                     '/assets/js/datepicker.js', array( 'jquery' ), null, true );  // datepicker
		wp_enqueue_script( 'datepicker-init', get_template_directory_uri() .
		                                     '/assets/js/datepicker-init.js', array( 'jquery' ), null, true );  // datepicker

		// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		// 	wp_enqueue_script( 'comment-reply' );
		// }
	}
}
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
		                             '/assets/css/general.css', array(), null, 'all' );
		wp_enqueue_style( 'main', get_template_directory_uri() .
		                             '/assets/css/main.css', array(), null, 'all' );
		wp_enqueue_style( 'header', get_template_directory_uri() .
		                             '/assets/css/header.css', array(), null, 'all' );
		wp_enqueue_style( 'menu', get_template_directory_uri() .
		                             '/assets/css/menu.css', array(), null, 'all' );
		wp_enqueue_style( 'card', get_template_directory_uri() .
		                             '/assets/css/card.css', array(), null, 'all' );
		wp_enqueue_style( 'catalog', get_template_directory_uri() .
		                             '/assets/css/catalog.css', array(), null, 'all' );
		wp_enqueue_style( 'cart', get_template_directory_uri() .
		                             '/assets/css/cart.css', array(), null, 'all' );
		wp_enqueue_style( 'checkout', get_template_directory_uri() .
		                             '/assets/css/checkout.css', array(), null, 'all' );
		wp_enqueue_style( 'pages', get_template_directory_uri() .
		                             '/assets/css/pages.css', array(), null, 'all' );
		wp_enqueue_style( 'wishlist', get_template_directory_uri() .
		                             '/assets/css/wishlist.css', array(), null, 'all' );
		wp_enqueue_style( 'account', get_template_directory_uri() .
		                             '/assets/css/account.css', array(), null, 'all' );
		wp_enqueue_style( 'popup', get_template_directory_uri() .
		                             '/assets/css/popup.css', array(), null, 'all' );
		// wp_enqueue_style( 'progressive-image', get_template_directory_uri() .
		//                              '/assets/css/progressive-image.css', array(), null, 'all' ); // for lazy load
		wp_enqueue_style( 'FlexSlider', get_template_directory_uri() .
		                             '/assets/css/FlexSlider.css', array(), null, 'all' );
		wp_enqueue_style( 'datepicker', get_template_directory_uri() .
		                             '/assets/css/datepicker.material.css', array(), null, 'all' ); //datepicker
		// wp_enqueue_style( 'flexisel', get_template_directory_uri() .
		//                              '/assets/css/flexisel.css', array(), null, 'all' );
		// wp_enqueue_style( 'nivo-slider', get_template_directory_uri() .
		//                              '/assets/css/nivo-slider.css', array(), null, 'all' );
		wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap', array(), null, 'all' );
	};
}


// add_action( 'wp_enqueue_scripts', 'plnt_no_filter_css', 999 );
 
// function plnt_no_filter_css() {
 
// 	// находимся на странице каталога сразу выходим из функции
// 	// if( is_shop() || is_product_category() || is_product_tag() ) {
// 	// 	return;
// 	// }
 
// 	wp_dequeue_style( 'berocket_aapf_widget-style' );
// 	// wp_dequeue_script( 'contact-form-7' );
 
// }
