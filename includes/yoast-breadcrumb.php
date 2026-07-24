<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//убираем стандартные крошки WC
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

add_action( 'woocommerce_before_main_content', 'plnt_breadcrumbs_yoast', 21 );
plnt_add_wrapper('plnt_breadrumbs_wrap','woocommerce_before_main_content', 20, 'woocommerce_before_main_content', 23);

function plnt_breadcrumbs_yoast() {
	if (
		!is_product()
		&& !is_shop()
		&& !is_product_taxonomy()
		&& !is_page('wishlist')
	) {
		return;
	}

	if (!function_exists('yoast_breadcrumb')) {
		return;
	}

	yoast_breadcrumb(
		'<nav class="plnt-breadcrumbs" aria-label="Хлебные крошки"><div class="woocommerce-breadcrumb plnt-woocommerce-breadcrumb" id="breadcrumbs">',
		'</div></nav>'
	);
}

add_filter('wpseo_breadcrumb_separator', 'plnt_breadcrumb_separator');

function plnt_breadcrumb_separator() {
  return ' — ';
}


// //Изменение заголовка в хлебных крошках Yoast SEO #breadcrumb
add_filter( 'wpseo_breadcrumb_links', 'plnt_change_breadcrumb_title', 10, 2 );
function plnt_change_breadcrumb_title( $links ) {
    $new_links = [];
    foreach($links as $link) {
        if(array_key_exists('taxonomy', $link)){
            if ($link['taxonomy'] == 'pa_color') {
                $new_text = plnt_get_color_name_title($link['text']);
                $link['text'] = "Горшки и кашпо ".$new_text." цвета";
            }
        }

        array_push($new_links, $link);
    }
	return $new_links;
}