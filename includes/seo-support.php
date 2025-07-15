<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'wpseo_schema_organization', 'fix_yoast_schema_image', 10, 2 );
function fix_yoast_schema_image( $data, $context ) {
	if ( empty( $data['image'] ) || ! is_array( $data['image'] ) || ! isset( $data['image']['url'] ) ) {
        $image_id = attachment_url_to_postid( 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp' );
		if ( $image_id ) {
			$image = wp_get_attachment_image_src( $image_id );
		}
		$data['image'] = [
			'url'    => $image[0],
			'width'  => $image[1],
			'height' => $image[2],
		];
	}
	return $data;
}

add_filter( 'wpseo_schema_graph_pieces', 'fix_yoast_schema_images_globally', 10, 2 );
function fix_yoast_schema_images_globally( $pieces, $context ) {

	foreach ( $pieces as &$piece ) {

         // Обрабатываем только массивы
		if ( ! is_array( $piece ) ) {
			continue;
		}
        
		if ( isset( $piece['@type'] ) && isset( $piece['image'] ) && ! is_array( $piece['image'] ) ) {
            $image_id = attachment_url_to_postid( 'https://plantis-shop.ru/wp-content/uploads/2025/07/mainbannermob.webp' );
            if ( $image_id ) {
                $image = wp_get_attachment_image_src( $image_id );
            }
			$piece['image'] = [
				'url'    => $image[0],
                'width'  => $image[1],
                'height' => $image[2],
			];
		}
	}
	return $pieces;
}

add_filter( 'wpseo_opengraph_image', 'plnt_default_og_image');
add_filter( 'wpseo_opengraph_image_width', 'plnt_change_opengraph_image_width');
add_filter( 'wpseo_opengraph_image_height', 'plnt_change_opengraph_image_height');
add_filter( 'wpseo_twitter_image', 'plantis_default_twitter_image',10,1 );
add_filter( 'wpseo_schema_graph_pieces', 'plantis_schema_default_image', 11, 2 );

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