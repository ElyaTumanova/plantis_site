<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Register Menus
 */
register_nav_menus(array(
	'top-bar-r'  => 'Right Top Bar',
	'mobile-nav' => 'Mobile',
));
register_nav_menus( array(
	'primary'   => esc_html__( 'Primary Menu', 'art-starter-theme' ),
	'secondary' => esc_html__( 'Secondary Menu', 'art-starter-theme' ),
	'mobile'    => esc_html__( 'Mobile Menu', 'art-starter-theme' ),
) );


if ( ! function_exists( 'ast_primary_menu' ) ) {
	function ast_primary_menu() {
		wp_nav_menu( array(
			'container'      => 'nav',
			'menu_class'     => 'primary-menu',
			// 'walker' => new CSS_Menu_Walker(),
			'theme_location' => 'primary',
			'depth'          => 3,
			'fallback_cb'     => '__return_empty_string',
		));
	}
}

// class CSS_Menu_Walker extends Walker {
// 	var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');
	
// 	function start_lvl(&$output, $depth = 0, $args = array()) {
// 		$indent = str_repeat("t", $depth);
// 		$output .= "n$indent<ul>n";
// 	}
	
// 	function end_lvl(&$output, $depth = 0, $args = array()) {
// 		$indent = str_repeat("t", $depth);
// 		$output .= "$indent</ul>n";
// 	}
	
// 	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	
// 		global $wp_query;
// 		$indent = ($depth) ? str_repeat("t", $depth) : '';
// 		$class_names = $value = '';
// 		$classes = empty($item->classes) ? array() : (array) $item->classes;
		
// 		/* Добавление активного класса */
// 		if (in_array('current-menu-item', $classes)) {
// 			$classes[] = 'active';
// 			unset($classes['current-menu-item']);
// 		}
		
// 		/* Проверка наличия дочерних элементов */
// 		$children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));
// 		if (!empty($children)) {
// 			$classes[] = 'has-sub';
// 		}
		
// 		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
// 		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
		
// 		$id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
// 		$id = $id ? ' id="' . esc_attr($id) . '"' : '';
		
// 		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		
// 		$attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
// 		$attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
// 		$attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
// 		$attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';
		
// 		$item_output = $args->before;
// 		$item_output .= '<a'. $attributes .'><span>';
// 		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
// 		$item_output .= '</span></a>';
// 		$item_output .= $args->after;
		
// 		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
// 	}
	
// 	function end_el(&$output, $item, $depth = 0, $args = array()) {
// 		$output .= "</li>n";
// 	}
// }