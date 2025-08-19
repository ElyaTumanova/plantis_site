<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//add_filter( 'wpseo_schema_organization', 'fix_yoast_schema_image', 10, 2 );
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

//add_filter( 'wpseo_schema_graph_pieces', 'fix_yoast_schema_images_globally', 10, 2 );
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

// add_filter( 'wpseo_opengraph_image', 'plnt_default_og_image');
// add_filter( 'wpseo_opengraph_image_width', 'plnt_change_opengraph_image_width');
// add_filter( 'wpseo_opengraph_image_height', 'plnt_change_opengraph_image_height');
// add_filter( 'wpseo_twitter_image', 'plantis_default_twitter_image',10,1 );
// add_filter( 'wpseo_schema_graph_pieces', 'plantis_schema_default_image', 11, 2 );

function plnt_default_og_image( $image ) {
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

// отключаем schema.org в Yoast
add_filter( 'wpseo_json_ld_output', '__return_false' );

/** * Удалить вывод структурированных данных на всех страницах и в письмах WooCommerce */  
function wc_remove_output_structured_data() {  
    // Удалить вывод структурированных данных из footer всех страниц  
    remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 );  
    // Удалить вывод структурированных данных из раздела деталей заказа в письмах WooCommerce  
    // remove_action( 'woocommerce_email_order_details', array( WC()->structured_data, 'output_email_structured_data' ), 30 );  
}  
add_action( 'init', 'wc_remove_output_structured_data' );  

add_action('wp_head','plnt_schema_json');

function plnt_schema_json() {
    if (is_product()) { 
        global $product;
        $idCats = $product->get_category_ids();
        $brand = plnt_get_brand_text($idCats);
        $price = number_format($product->get_price(), 2, '.', '');
        $availability = plnt_get_availability_text($product);

        $images = [];

        $main_img = wp_get_attachment_url( $product->get_image_id() );
        if ( $main_img ) {
            $images[] = $main_img;
        }

        // Дополнительные из галереи
        $attachment_ids = $product->get_gallery_image_ids();
        foreach ( $attachment_ids as $attachment_id ) {
            $img_url = wp_get_attachment_url( $attachment_id );
            if ( $img_url ) {
                $images[] = $img_url;
            }
        }

        $offers = [
            "@type"         => "Offer",
            "priceCurrency" => "RUB",
            "price"         => $price,
            "availability"  => $availability,
        ];

        if($product->get_sale_price()) {
            $offers["price"] = number_format($product->get_sale_price(), 2, '.', '');
            $offers["priceSpecification"] = [
                "@type"         => "UnitPriceSpecification",
                "priceType"     => "https://schema.org/StrikethroughPrice",
                "price"         => number_format($product->get_regular_price(), 2, '.', ''),
                "priceCurrency" => "RUB"
            ];
        }

        $data = [
            "@context" => "https://schema.org/",
            "@type"    => "Product",
            "name"     => $product->get_name(),
            "image"    => $images,
            "sku"      => $product->get_sku(),
            "description" => $product->get_description(),
            "brand"    => [
                "@type"  => "Brand",
                "name"   => $brand,
            ],
            "url"      => get_permalink( $product->get_id() ),
            "offers"   => $offers,
        ];
        ?>
        <script type="application/ld+json">
            <?php echo wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?>
        </script>
        <?php
     

     
 
    }
}
