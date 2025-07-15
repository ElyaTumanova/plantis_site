<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'wpseo_opengraph_image', 'plnt_default_og_image');
add_filter( 'wpseo_opengraph_image_width', 'plnt_change_opengraph_image_width');
add_filter( 'wpseo_opengraph_image_height', 'plnt_change_opengraph_image_height');
add_filter( 'wpseo_twitter_image', 'plantis_default_twitter_image',10,1 );
add_filter( 'wpseo_schema_graph_pieces', 'plantis_schema_default_image', 11, 2 );

// function plantis_default_og_image( $image,$presentation ) {
    // 	if ( empty( $image ) ) {
    // 		$image_id = attachment_url_to_postid( 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp' );
    // 		if ( $image_id ) {
    // 			$size = wp_get_attachment_image_src( $image_id, 'full' );
    // 			if ( $size ) {
    // 				return [
    // 					'url' => $size[0],
    // 					'width' => $size[1],
    // 					'height' => $size[2],
    // 				];
    // 			}
    // 		}

    // 		// fallback: без размера
    // 		return [
    // 			'url' => 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp',
    // 		];
    // 	}

    // 	return $image;
    // }

function plnt_default_og_image( $image,$presentation ) {
	if ( empty( $image ) ) {
		$image_id = attachment_url_to_postid( 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp' );
		if ( $image_id ) {
			$image = wp_get_attachment_image_src( $image_id )[0];
		}
	}

	return $image;
}
function plnt_change_opengraph_image_width( $width ) {
    if ( empty( $width ) ) {
		$image_id = attachment_url_to_postid( 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp' );
		if ( $image_id ) {
			$width = wp_get_attachment_image_src( $image_id )[1];
		}
	}
    return $width;
}
function plnt_change_opengraph_image_height( $height  ) {
    if ( empty( $height  ) ) {
		$image_id = attachment_url_to_postid( 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp' );
		if ( $image_id ) {
			$height  = wp_get_attachment_image_src( $image_id )[2];
		}
	}
    return $height ;
}

add_action('wp_footer',function(){
    $image_id = attachment_url_to_postid( 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp' );
    $size = wp_get_attachment_image_src( $image_id, 'full' );
    print_r($size);
});

function plantis_default_twitter_image( $image ) {
	if ( empty( $image ) ) {
		$image = 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp'; // путь к изображению
	}
	return $image;
}

function plantis_schema_default_image( $pieces, $context ) {
	foreach ( $pieces as $piece ) {
		if ( isset( $piece->context ) && $piece->context === 'mainEntity' ) {
			if ( empty( $piece->image ) ) {
				$piece->image = 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp';
			}
		}
	}
	return $pieces;
}