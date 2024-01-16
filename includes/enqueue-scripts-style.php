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
		wp_enqueue_script( 'magnific-popup', get_template_directory_uri() .
		                                     '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'owl-script', get_template_directory_uri() .
		                                 '/assets/js/owl.carousel.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'ajax-search', get_template_directory_uri() .
		                                 '/assets/js/ajax-search.js', array( 'jquery' ), null, true );
		wp_localize_script ('ajax-search', 'search_form', array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('search-nonce')
		));
		wp_enqueue_script( 'search-popup', get_template_directory_uri() .
		                                     '/assets/js/search-popup.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'lazy-load', get_template_directory_uri() .
		                                     '/assets/js/lazy-load.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'progressive-image', get_template_directory_uri() .
		                                     '/assets/js/progressive-image.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'quantity-buttons', get_template_directory_uri() .
		                                     '/assets/js/quantity-buttons.js', array( 'jquery' ), null, true );
											 
		wp_enqueue_script( 'jquery.flexisel', get_template_directory_uri() .
		                                     '/assets/js/jquery.flexisel.js', array( 'jquery' ), null, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
/**
 * Enqueue all styles
 */
if ( ! function_exists( 'ast_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'ast_styles' );
	function ast_styles() {
		wp_enqueue_style( 'ast-style', get_stylesheet_uri() );
		wp_enqueue_style( 'magnific-css', get_template_directory_uri() .
		                             '/assets/css/magnific-popup.css', array(), null, 'all' );
		wp_enqueue_style( 'owl-css', get_template_directory_uri() .
		                             '/assets/css/owl.carousel.min.css', array(), null, 'all' );
		wp_enqueue_style( 'general', get_template_directory_uri() .
		                             '/assets/css/general.css', array(), null, 'all' );
		wp_enqueue_style( 'header', get_template_directory_uri() .
		                             '/assets/css/header.css', array(), null, 'all' );
		wp_enqueue_style( 'card', get_template_directory_uri() .
		                             '/assets/css/card.css', array(), null, 'all' );
		wp_enqueue_style( 'catalog', get_template_directory_uri() .
		                             '/assets/css/catalog.css', array(), null, 'all' );
		wp_enqueue_style( 'progressive-image', get_template_directory_uri() .
		                             '/assets/css/progressive-image.css', array(), null, 'all' );
		wp_enqueue_style( 'FlexSlider', get_template_directory_uri() .
		                             '/assets/css/FlexSlider.css', array(), null, 'all' );
		wp_enqueue_style( 'flexisel', get_template_directory_uri() .
		                             '/assets/css/flexisel.css', array(), null, 'all' );
		wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap', array(), null, 'all' );
	}
}

/**
 * Dequeue all styles
 */

add_action( 'wp_enqueue_scripts', 'my_dequeue_style', 99 );

function my_dequeue_style(){
	wp_dequeue_style( 'font-awesome' );  //berocket style
	wp_dequeue_style( 'berocket_aapf_widget-style' );  //berocket style
}