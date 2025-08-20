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
	'primary'   => 'Primary Menu',
	'secondary' => 'Secondary Menu',
	'catalog'    => 'Categories in Catalog',
	'footer-plants'    => 'Plants Categories in Footer',
	'footer-services'    => 'Services List in Footer',
) );


if ( ! function_exists( 'plnt_primary_menu' ) ) {
	function plnt_primary_menu() {
		wp_nav_menu( array(
			'container'      => 'nav',
			'menu_class'     => 'primary-menu',
			'theme_location' => 'primary',
			'depth'          => 4,
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

if ( ! function_exists( 'plnt_footer_menu_plants' ) ) {
	function plnt_footer_menu_plants() {
		wp_nav_menu( array(
			'container'      => 'nav',
			'menu_class'     => 'footer__menu-palnts',
			'theme_location' => 'footer-plants',
			'depth'          => 1,
			'fallback_cb'     => '__return_empty_string',
		));
	}
}

if ( ! function_exists( 'plnt_footer_menu_services' ) ) {
	function plnt_footer_menu_services() {
		wp_nav_menu( array(
			'container'      => 'nav',
			'menu_class'     => 'footer__menu-services',
			'theme_location' => 'footer-services',
			'depth'          => 1,
			'fallback_cb'     => '__return_empty_string',
		));
	}
}

// for primary & secondary menu

// //классы меню
add_filter( 'wp_nav_menu_args', 'filter_wp_menu_args_primary' );
function filter_wp_menu_args_primary( $args ) {
	if ( $args['theme_location'] === 'primary' ) {
		$args['container']  = false;
		$args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
		$args['menu_class'] = 'menu menu--main';
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

// // Изменяем атрибут id у тега li
add_filter( 'nav_menu_item_id', 'filter_menu_item_css_id', 10, 4 );
function filter_menu_item_css_id( $menu_id, $item, $args, $depth ) {
	return $args->theme_location === 'primary' ? '' : $menu_id;
}

// // Изменяем атрибут class у тега li
add_filter( 'nav_menu_css_class', 'filter_nav_menu_css_classes', 10, 4 );
function filter_nav_menu_css_classes( $classes, $item, $args, $depth ) {
	if ( $args->theme_location === 'primary' || $args->theme_location === 'secondary') {
		array_push($classes, 'menu-node', 'menu-node_lvl_' . ( $depth + 1 ));

		if ( $item->current ) {
			$classes[] = 'menu-node--active';
		}
	}

	return $classes;
}

// // Изменяет класс у вложенного ul
add_filter( 'nav_menu_submenu_css_class', 'filter_nav_menu_submenu_css_class', 10, 3 );
function filter_nav_menu_submenu_css_class( $classes, $args, $depth ) {
	if ( $args->theme_location === 'primary' ) {
		array_push($classes, 'menu', 'menu--onside', 'menu--onside_lvl_' . ( $depth + 1 ));
	}
	if ( $args->theme_location === 'secondary' ) {
		array_push($classes, 'menu', 'menu--dropdown', 'menu--vertical', 'menu--dropdown_lvl_' . ( $depth + 1 ));
	}

	return $classes;
}


// // Добавляем классы ссылкам
add_filter( 'nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 4 );
function filter_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	if ( $args->theme_location === 'primary' || $args->theme_location === 'secondary' ) {
		$atts['class'] = 'menu-link';

		if ( $item->current ) {
			$atts['class'] .= ' menu-link--active';
		}
	}

	return $atts;
}

// for catalog menu

// // Изменяем атрибут class у тега li
add_filter( 'nav_menu_css_class', 'filter_nav_menu_css_classes_catalog', 10, 4 );
function filter_nav_menu_css_classes_catalog( $classes, $item, $args, $depth ) {
	if ( $args->theme_location === 'catalog') {

		array_push($classes, 'catalog__node', 'catalog__node_lvl_' . ( $depth + 1 ));

		if (in_array('menu-item-has-children',$classes )){
			array_push($classes, 'catalog__dropdown');
		}

		if ( $item->current ) {
			$classes[] = 'catalog__node_active';
		}
	}

	return $classes;
}

// // Изменяет класс у вложенного ul
add_filter( 'nav_menu_submenu_css_class', 'filter_nav_menu_submenu_css_class_catalog', 10, 3 );
function filter_nav_menu_submenu_css_class_catalog( $classes, $args, $depth ) {
	if ( $args->theme_location === 'catalog' ) {
		array_push($classes, 'catalog__dropdown-menu', 'catalog__dropdown-menu_lvl_' . ( $depth + 1 ));
	}

	return $classes;
}

// // Добавляем стрелочку для разворачивания меню
add_filter( 'wp_nav_menu_args', 'filter_wp_menu_args_catalog' );
function filter_wp_menu_args_catalog( $args ) {
	if ( $args['theme_location'] === 'catalog' ) {
		$args['after'] = '<span class="menu__dropdown-arrow">next</span>';
	}
	return $args;
}

// доп функции для вывода меню

// // убираем пустые категории из меню
function nav_remove_empty_category_menu_item ( $items, $menu) {

    if ( ! is_admin() ) {
        
        $args = array(
        'hide_empty' => false,
        'hierarchical' => true,
    );

    $product_categories = get_terms( 'product_cat', $args );

    $exclude = array();
    foreach ( $product_categories as $category ) {

        $posts         = get_posts( array( 'post_type' => 'product', 'posts_per_page' => -1, 'product_cat' => $category->slug, 'fields' => 'ids' ) );
        $show_category = false;
        $visible_product = false;

        foreach ( $posts as $post ) {

            $product         = new WC_Product( $post );
			
			if($product->get_stock_status() == 'instock' || $product->backorders_allowed()) {
				$visible_product = true;
			}

            if ( true === $visible_product ) {
                $show_category = true;
                break;
            }

        }

        if ( false === $show_category ) {
            $exclude[] = $category->term_id;
        }

    }

        global $wpdb;

        $nopost = $wpdb->get_col( "SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE count = 0" );

        foreach ( $items as $key => $item ) {

            if ( ( 'taxonomy' == $item->type ) && ( in_array( $item->object_id, $exclude ) ) ) {

                unset( $items[$key] );

            }

        }

    }

    return $items;

}

add_filter( 'wp_get_nav_menu_items', 'nav_remove_empty_category_menu_item', 10, 3 );

// AJAX for menu
function plnt_show_primary_menu() {
	echo plnt_primary_menu();
	die();
}

add_filter( 'wp_ajax_nopriv_plnt_show_primary_menu', 'plnt_show_primary_menu' );
add_filter( 'wp_ajax_plnt_show_primary_menu', 'plnt_show_primary_menu' );

function plnt_show_catalog_menu() {
	echo plnt_catalog_menu();
	die();
}

add_filter( 'wp_ajax_nopriv_plnt_show_catalog_menu', 'plnt_show_catalog_menu' );
add_filter( 'wp_ajax_plnt_show_catalog_menu', 'plnt_show_catalog_menu' );