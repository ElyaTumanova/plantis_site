<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'init', 'true_register_post_type_init' );
 
function true_register_post_type_init() {
 
	$labels = array(
		'name' => 'Результаты теста',
		'singular_name' => 'Результат теста',
		'add_new' => 'Добавить результат теста',
		'add_new_item' => 'Добавить результат теста',
		'edit_item' => 'Редактировать результат теста',
		'new_item' => 'Новый результат теста',
		'all_items' => 'Все результаты теста',
		'search_items' => 'Искать результаты теста',
		'not_found' =>  'результатов теста по заданным критериям не найдено.',
		'not_found_in_trash' => 'В корзине нет результатов теста.',
		'menu_name' => 'Результаты теста'
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => false,
		'has_archive' => false,
		'menu_icon' => 'dashicons-email-alt2',
		'menu_position' => 2,
		'supports' => array( 'title', 'editor' )
	);
 
	register_post_type( 'lead', $args );
}