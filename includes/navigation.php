<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Register Menus
 */
// register_nav_menus(array(
// 	'top-bar-r'  => 'Right Top Bar',
// 	'mobile-nav' => 'Mobile',
// ));
register_nav_menus( array(
	'primary'   => esc_html__( 'Primary Menu', 'art-starter-theme' ),
	'secondary' => esc_html__( 'Secondary Menu', 'art-starter-theme' ),
	'mobile'    => esc_html__( 'Mobile Menu', 'art-starter-theme' ),
	'catalog'    => esc_html__( 'Categories in Catalog', 'art-starter-theme' ),
) );


if ( ! function_exists( 'plnt_primary_menu' ) ) {
	function plnt_primary_menu() {
		wp_nav_menu( array(
			'container'      => 'nav',
			'menu_class'     => 'primary-menu',
			'theme_location' => 'primary',
			'depth'          => 3,
			'fallback_cb'     => '__return_empty_string',
		));
	}
}

if ( ! function_exists( 'plnt_secondary_menu' ) ) {
	function plnt_secondary_menu() {
		wp_nav_menu( array(
			'container'      => 'nav',
			'menu_class'     => 'secondary-menu',
			'theme_location' => 'secondary',
			'depth'          => 3,
			'fallback_cb'     => '__return_empty_string',
		));
	}
}

if ( ! function_exists( 'plnt_catalog_menu' ) ) {
	function plnt_catalog_menu() {
		wp_nav_menu( array(
			'container'      => 'nav',
			'menu_class'     => 'catalog-menu',
			'theme_location' => 'catalog',
			'depth'          => 3,
			'fallback_cb'     => '__return_empty_string',
		));
	}
}


//классы меню
add_filter( 'wp_nav_menu_args', 'filter_wp_menu_args_primary' );
function filter_wp_menu_args_primary( $args ) {
	if ( $args['theme_location'] === 'primary' ) {
		$args['container']  = false;
		$args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
		$args['menu_class'] = 'menu menu--main menu-horizontal';
	}

	return $args;
}

add_filter( 'wp_nav_menu_args', 'filter_wp_menu_args_secondary' );
function filter_wp_menu_args_secondary( $args ) {
	if ( $args['theme_location'] === 'secondary' ) {
		$args['container']  = false;
		$args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
		$args['menu_class'] = 'menu menu--info menu-horizontal';
	}

	return $args;
}

// Изменяем атрибут id у тега li
add_filter( 'nav_menu_item_id', 'filter_menu_item_css_id', 10, 4 );
function filter_menu_item_css_id( $menu_id, $item, $args, $depth ) {
	return $args->theme_location === 'primary' ? '' : $menu_id;
}

// Изменяем атрибут class у тега li
add_filter( 'nav_menu_css_class', 'filter_nav_menu_css_classes', 10, 4 );
function filter_nav_menu_css_classes( $classes, $item, $args, $depth ) {
	if ( $args->theme_location != 'mobile' ) {
		array_push($classes, 'menu-node', 'menu-node_lvl_' . ( $depth + 1 ));

		if ( $item->current ) {
			$classes[] = 'menu-node--active';
		}
	}

	return $classes;
}

// Изменяет класс у вложенного ul
add_filter( 'nav_menu_submenu_css_class', 'filter_nav_menu_submenu_css_class', 10, 3 );
function filter_nav_menu_submenu_css_class( $classes, $args, $depth ) {
	// echo '<pre>';
	// print_r( $args );
	// echo '</pre>';
	if ( $args->theme_location != 'mobile' ) {
		array_push($classes, 'menu', 'menu--dropdown', 'menu--vertical', 'menu--dropdown_lvl_' . ( $depth + 1 ));
	}

	return $classes;
}


// ДОбавляем классы ссылкам
add_filter( 'nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 4 );
function filter_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	if ( $args->theme_location != 'mobile' ) {
		$atts['class'] = 'menu-link';

		if ( $item->current ) {
			$atts['class'] .= ' menu-link--active';
		}
	}

	return $atts;
}