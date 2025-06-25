<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// хук для регистрации
add_action( 'init', 'create_taxonomy' );
function create_taxonomy(){

	// список параметров: wp-kama.ru/function/get_taxonomy_labels
	register_taxonomy( 'taxonomy', [ 'post' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'TestResults',
			'singular_name'     => 'TestResult',
			'search_items'      => 'Search TestResults',
			'all_items'         => 'All TestResults',
			'view_item '        => 'View TestResult',
			'parent_item'       => 'Parent TestResult',
			'parent_item_colon' => 'Parent TestResult:',
			'edit_item'         => 'Edit TestResult',
			'update_item'       => 'Update TestResult',
			'add_new_item'      => 'Add New TestResult',
			'new_item_name'     => 'New TestResult Name',
			'menu_name'         => 'TestResult',
			'back_to_items'     => '← Back to TestResult',
		],
		'description'           => '', // описание таксономии
		'public'                => true,
		// 'publicly_queryable'    => null, // равен аргументу public
		// 'show_in_nav_menus'     => true, // равен аргументу public
		// 'show_ui'               => true, // равен аргументу public
		// 'show_in_menu'          => true, // равен аргументу show_ui
		// 'show_tagcloud'         => true, // равен аргументу show_ui
		// 'show_in_quick_edit'    => null, // равен аргументу show_ui
		'hierarchical'          => false,

		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
}