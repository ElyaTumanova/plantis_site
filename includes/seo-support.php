<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
Contents
# Opengraph
# Schema.org
# Favicon
--------------------------------------------------------------*/

/*--------------------------------------------------------------
# Opengraph
--------------------------------------------------------------*/


add_filter('wpseo_opengraph_title', function ($title) {
    if (is_page('gift-card')) {                // ваша страница
        return 'Ваш подарочный сертификат в Plantis';
    }
    return $title;
});

add_filter('wpseo_opengraph_desc', function ($desc) {
    if (is_page('gift-card')) {
        return 'Интернет-магазин комнатных растений';
    }
    return $desc;
});

add_action('wp_head', function() {
    if (is_page('gift-card')) {
        echo '<meta property="og:image" content="'. get_template_directory_uri() .'/images/gift-card/gc_soc.jpg" />' . "\n";
        echo '<meta property="og:image:width" content="1200" />' . "\n";
        echo '<meta property="og:image:height" content="630" />' . "\n";
    }
});

/*--------------------------------------------------------------
# Schema.org
--------------------------------------------------------------*/

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

add_action('wp_head','plnt_schema_json'); //todo вернуть

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
            "description" => strip_tags($product->get_description()),
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

/*--------------------------------------------------------------
# Favicon
--------------------------------------------------------------*/
//добавить фавикон favicon
add_action( 'wp_head', 'plnt_add_favicons' );
function plnt_add_favicons() {
    $dir = get_template_directory_uri() . '/images/favicons';
    ?>
    <link rel="icon" type="image/svg+xml" href="<?php echo $dir; ?>/favicon.svg" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo $dir; ?>/favicon-32x32.png" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo $dir; ?>/favicon-96x96.png" sizes="96x96"/>
    <link rel="shortcut icon" href="<?php echo $dir; ?>/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $dir; ?>/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Plantis" />
    <link rel="manifest" href="<?php echo $dir; ?>/site.webmanifest" />
    <?php
}
