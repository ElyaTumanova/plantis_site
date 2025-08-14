<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'ast_theme_support' ) ) :
	add_action( 'after_setup_theme', 'ast_theme_support' );
	function ast_theme_support() {
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'menus' );
		// Let WordPress manage the document title
		add_theme_support( 'title-tag' );
		// Add post thumbnail support: http://codex.wordpress.org/Post_Thumbnails
		add_theme_support( 'post-thumbnails' );
		// RSS thingy
		add_theme_support( 'automatic-feed-links' );
		// Woocommerce support
		add_theme_support( 'woocommerce' );
		//add_theme_support( 'wc-product-gallery-zoom' ); // отключаем зум для фото товара
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		// Add foundation.css as editor style https://codex.wordpress.org/Editor_Style
		add_editor_style( 'assets/stylesheets/editor.css' );
	}
endif;

// Разрешаем загрузку svg
add_filter( 'upload_mimes', 'svg_upload_allow' );

# Добавляет SVG в список разрешенных для загрузки файлов.
function svg_upload_allow( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}

add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

# Исправление MIME типа для SVG файлов.
function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){

	// WP 5.1 +
	if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ){
		$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
	}
	else {
		$dosvg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );
	}

	// mime тип был обнулен, поправим его
	// а также проверим право пользователя
	if( $dosvg ){

		// разрешим
		if( current_user_can('manage_options') ){

			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		// запретим
		else {
			$data['ext']  = false;
			$data['type'] = false;
		}

	}

	return $data;
}

add_filter( 'wp_prepare_attachment_for_js', 'show_svg_in_media_library' );

# Формирует данные для отображения SVG как изображения в медиабиблиотеке.
function show_svg_in_media_library( $response ) {

	if ( $response['mime'] === 'image/svg+xml' ) {

		// С выводом названия файла
		$response['image'] = [
			'src' => $response['url'],
		];
	}

	return $response;
}

// отключаем srcset

add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );

//убираем ненужны размеры изображений
// add_filter( 'intermediate_image_sizes', function( $sizes ) {
//     error_log( 'Фильтр сработал, удаляем размеры: ' . implode(', ', array_keys($sizes)) );
//     unset( $sizes['1536x1536'] ); // с WP 5.3+
//     unset( $sizes['2048x2048'] ); // с WP 5.3+
//     error_log( 'Фильтр сработал, остались размеры: ' . implode(', ', array_keys($sizes)) );
//     return $sizes;
// });


// убираем дубли URL для категорий товаров
add_action( 'template_redirect', 'check_301redirect_tax_url', 9 );
function check_301redirect_tax_url(){

	// not taxonomy
	if( ! ( is_category() || is_tag() || is_tax() ) )
		return;

	$qo = get_queried_object();

	$term_url = get_term_link( $qo );
	$parsed_url = parse_url( $_SERVER['REQUEST_URI'] );

	if( strpos( $parsed_url['path'], wp_make_link_relative( $term_url ) ) === false ){
		$redirect_to = isset( $parsed_url['query'] ) ? "$term_url?{$parsed_url['query']}" : $term_url;
		wp_redirect( $redirect_to, 301 );
		exit;
	}
}

//добавить фавикон favicon
add_action( 'wp_head', 'plnt_add_favicons' );
function plnt_add_favicons() {
    $dir = get_template_directory_uri() . '/images/favicons';
    ?>
    <link rel="icon" type="image/svg+xml" href="<?php echo $dir; ?>/favicon.svg" />
    <link rel="icon" type="image/png" href="<?php echo $dir; ?>/favicon-96x96.png" sizes="96x96" />
    <link rel="shortcut icon" href="<?php echo $dir; ?>/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $dir; ?>/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Plantis" />
    <link rel="manifest" href="<?php echo $dir; ?>/site.webmanifest" />
    <?php
}
