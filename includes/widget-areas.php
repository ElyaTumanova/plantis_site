<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//not used

/**
 * Register widget areas
 */
if ( ! function_exists( 'ast_sidebar_widgets' ) ) {
	add_action( 'widgets_init', 'ast_sidebar_widgets' );
	function ast_sidebar_widgets() {
		register_sidebar( array(
			'id'            => 'sidebar-widgets',
			'name'          => __( 'Sidebar widgets', 'plantis-theme' ),
			'description'   => __( 'Drag widgets to this sidebar container.', 'plantis-theme' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div>',
			'after_title'   => '</div>',
		) );
		register_sidebar( array(
			'id'            => 'footer-widgets',
			'name'          => __( 'Footer widgets', 'plantis-theme' ),
			'description'   => __( 'Drag widgets to this footer container', 'plantis-theme' ),
			'before_widget' => '<aside id="%1$s" class="large-4 columns widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div>',
			'after_title'   => '</div>',
		) );
	}
}


