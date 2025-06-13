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
											 '/assets/js/ajax-update-cart.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/ajax-update-cart.js'), true );									 
		
		wp_enqueue_script( 'ajax-update-wish', get_template_directory_uri() .
											 '/assets/js/ajax-update-wish.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/ajax-update-wish.js'), true );	
		
		wp_enqueue_script( 'ajax-urgent-delivery', get_template_directory_uri() .
											 '/assets/js/ajax-urgent-delivery.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/ajax-urgent-delivery.js'), true );	
		
		wp_enqueue_script( 'hide-chekout-fields', get_template_directory_uri() .
											 '/assets/js/hide-chekout-fields.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/hide-chekout-fields.js'), true );	
		
		wp_enqueue_script( 'ajax-search', get_template_directory_uri() .
		                                 '/assets/js/ajax-search.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/ajax-search.js'), true );
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
		                                     '/assets/js/swiper.js', array( 'jquery' ), null, false ); //swiper	

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

		wp_enqueue_script( 'delivery-dropdown', get_template_directory_uri() .
		                                     '/assets/js/delivery-dropdown.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/delivery-dropdown.js'), true );
		
		wp_enqueue_script( 'filter-show-more', get_template_directory_uri() .
		                                     '/assets/js/filter-show-more.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/filter-show-more.js'), true );

		wp_enqueue_script( 'cart-backorder-crossell', get_template_directory_uri() .
		                                     '/assets/js/cart-backorder-crossell.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/cart-backorder-crossell.js'), true );

		wp_enqueue_script( 'metrikaGoal', get_template_directory_uri() .
		                                     '/assets/js/metrikaGoal.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/js/metrikaGoal.js'), true );  //metrikaGoal Яндекс Метрика Yandex Metrika

		wp_enqueue_script( 'datepicker', get_template_directory_uri() .
		                                     '/assets/js/datepicker.js', array( 'jquery' ), null, true );  // datepicker
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
		                             '/assets/css/general.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/general.css'), 'all' );
		wp_enqueue_style( 'main', get_template_directory_uri() .
		                             '/assets/css/main.css', array(), filemtime(get_stylesheet_directory() .'/assets/css/main.css'), 'all' );
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


add_action( 'wp_enqueue_scripts', 'plnt_no_filter_css', 9999 );
 
function plnt_no_filter_css() {
 
	// находимся на странице каталога сразу выходим из функции
	// if( is_shop() || is_product_category() || is_product_tag() ) {
	// 	return;
	// }
 
	wp_dequeue_style( 'yith-wcwl-user-main' ); //отключаем стили YITH wishlist так как font-awesome на критическом пути
	wp_dequeue_style( 'yith-wcwl-main' ); //отключаем стили YITH wishlist так как font-awesome на критическом пути
	//wp_dequeue_style( 'berocket_lmp_style' ); //отключаем стили Berocket Load More так как font-awesome на критическом пути
	//wp_dequeue_style( 'wc-blocks-style' ); //отключаем стили WC так как font-awesome на критическом пути
	// wp_dequeue_script( 'contact-form-7' );
}
add_action( 'init', 'remove_my_style_stylesheet', 99 );

function remove_my_style_stylesheet() {

	wp_deregister_style( 'wc-blocks-style' ); //отключаем стили WC так как font-awesome на критическом пути
	wp_deregister_style( 'berocket_lmp_style' ); //отключаем стили Berocket Load More так как font-awesome на критическом пути

}