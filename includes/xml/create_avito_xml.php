<?php
/**
 * PLANTIS — dev XML feed for Avito.
 *
 * Include this file in functions.php:
 * require_once get_theme_file_path( '/includes/xml/create_avito_xml.php' );
 *
 * Open: https://dev.plantis-shop.ru/?plnt_avito_xml=1
 * No cron and no physical XML file: XML is generated on every request.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'template_redirect', 'plnt_avito_output_xml', 0 );

/**
 * Dedicated ACF gallery: IDs of images in the order set by the editor.
 * We intentionally do NOT fall back to WooCommerce featured/gallery images.
 */
function plnt_avito_image_urls( int $product_id ): array {
    $images = get_field( 'plnt_avito_gallery', $product_id, false );

    if ( ! is_array( $images ) || ! $images ) {
        return [];
    }

    $urls = [];

    foreach ( $images as $image ) {
        $image_id = is_array( $image )
            ? absint( $image['ID'] ?? $image['id'] ?? 0 )
            : absint( $image );

        if ( ! $image_id ) {
            continue;
        }

        // Use formats documented for Avito image upload.
        if ( ! in_array( get_post_mime_type( $image_id ), [ 'image/jpeg', 'image/png' ], true ) ) {
            continue;
        }

        $url = wp_get_attachment_image_url( $image_id, 'full' );
        if ( ! $url || ! wp_http_validate_url( $url ) ) {
            continue;
        }

        $urls[ $url ] = $url; // Remove duplicates without changing order.

        if ( count( $urls ) >= 10 ) {
            break;
        }
    }

    return array_values( $urls );
}

/**
 * Description is assembled from reorderable ACF Flexible Content layouts.
 * General texts are stored once on WooCommerce > Авито — описание.
 */
function plnt_avito_description( WC_Product $product ): string {
    return plnt_avito_get_description( $product );
}

/**
 * Read the PlantType selected for the current Avito category.
 * ACF Select restricts the values; here we only choose the correct field.
 */
function plnt_avito_plant_type( int $product_id, string $category ): string {
    $field_names = [
        'decorative'  => 'plnt_avito_plant_type_decorative',
        'flowering'   => 'plnt_avito_plant_type_flowering',
        'palms_ficus' => 'plnt_avito_plant_type_palms_ficus',
        'conifers'    => 'plnt_avito_plant_type_conifers',
    ];

    if ( ! isset( $field_names[ $category ] ) ) {
        return '';
    }

    return trim( (string) get_field( $field_names[ $category ], $product_id ) );
}

/**
 * Prepare category-dependent conifer fields.
 * For the current indoor-only feed this is not called: conifers are excluded.
 * Returns null when a required category-specific field is empty.
 */
function plnt_avito_conifer_fields( int $product_id, string $plant_type ): ?array {
    $required = [
        'Туя'           => [ 'ThujaType' => 'plnt_avito_thuja_type', 'ThujaClass' => 'plnt_avito_thuja_class' ],
        'Сосна'         => [ 'PineType' => 'plnt_avito_pine_type' ],
        'Ель'           => [ 'SpruceType' => 'plnt_avito_spruce_type' ],
        'Пихта'         => [ 'FirType' => 'plnt_avito_fir_type' ],
        'Кедр'          => [ 'CedarType' => 'plnt_avito_cedar_type' ],
        'Можжевельник'  => [ 'JuniperType' => 'plnt_avito_juniper_type', 'JuniperClass' => 'plnt_avito_juniper_class' ],
    ];

    $result = [];
    foreach ( $required[ $plant_type ] ?? [] as $xml_tag => $acf_name ) {
        $value = trim( (string) get_field( $acf_name, $product_id ) );
        if ( '' === $value ) {
            return null;
        }
        $result[ $xml_tag ] = $value;
    }
    return $result;
}

function plnt_avito_output_xml(): void {
    if ( ! isset( $_GET['plnt_avito_xml'] ) || '1' !== (string) wp_unslash( $_GET['plnt_avito_xml'] ) ) {
        return;
    }

    if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'get_field' ) || ! class_exists( 'XMLWriter' ) ) {
        status_header( 503 );
        exit( 'Avito XML: WooCommerce, ACF Gallery and XMLWriter are required.' );
    }

    // Existing Avito XML template / export values. Edit here if needed.
    $common = [
        'ListingFee'       => 'Package',
        'ManagerName'      => 'Plantis',
        'ContactPhone'     => '79995527944',
        'ContactMethod'    => 'По телефону и в сообщениях',
        'Address'          => 'Москва, улица Мещерякова, 3',
        'Category'         => 'Растения',
        'AdType'           => 'Товар приобретен на продажу',
        'Availability'     => 'В наличии',
        'CountryExpedition'=> 'Россия',
    ];

    // Category is selected per product in the ACF field plnt_avito_category.
    // Garden/conifers are intentionally unsupported by today's feed filter.
    // Conifer subtype dependencies are handled by plnt_avito_conifer_fields()
    // for a future separate garden-plants feed.
    $categories = [
        'decorative' => 'Декоративно-лиственные растения',
        'succulents' => 'Кактусы и суккуленты',
        'palms_ficus'=> 'Пальмы и фикусы',
        'flowering'  => 'Цветущие растения',
    ];

    // These subtype fields are conditional, not universal. For example,
    // FicusType is valid only when PlantType is exactly Фикус.
    $subtype_fields = [
        'palms_ficus' => [
            'Фикус'   => [ 'FicusType' => 'plnt_avito_ficus_type' ],
            'Пальма'  => [ 'PalmType'  => 'plnt_avito_palm_type' ],
        ],
        'flowering' => [
            'Орхидея' => [ 'OrchidType' => 'plnt_avito_orchid_type' ],
        ],
    ];

    // Путь к XML-файлу Авито.
    $dir_path  = WP_CONTENT_DIR . '/avito-xml';
    $file_path = $dir_path . '/feed-avito-0.xml';

    // Проверяем доступность папки.
    if ( ! is_dir( $dir_path ) || ! is_writable( $dir_path ) ) {
        wp_die( 'Avito XML: папка не существует или недоступна для записи: ' . esc_html( $dir_path ) );
    }

    // Создаём временный файл в той же папке.
    $temp_file = tempnam( $dir_path, 'avito-' );

    if ( false === $temp_file ) {
        wp_die( 'Avito XML: не удалось создать временный файл.' );
    }

    // Открываем XMLWriter для записи в файл.
    $xml = new XMLWriter();

    if ( ! $xml->openUri( $temp_file ) ) {
        @unlink( $temp_file );
        wp_die( 'Avito XML: не удалось открыть файл для записи.' );
    }

    $xml->setIndent( true );
    $xml->setIndentString( '  ' );
    $xml->startDocument( '1.0', 'UTF-8' );
    $xml->startElement( 'Ads' );
    $xml->writeAttribute( 'formatVersion', '3' );
    $xml->writeAttribute( 'target', 'Avito.ru' );

    // Use the WooCommerce indoor-plants category (and its children).
    // Stock is re-checked using WC methods to avoid preorder products.
    $page     = 1;
    $per_page = 200;

    do {
        $query = new WP_Query( [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'posts_per_page' => $per_page,
            'paged'          => $page,
            'orderby'       => 'ID',
            'order'         => 'ASC',
            'no_found_rows' => true,
            // Only products explicitly marked for Avito (ACF true_false = 1).
            'meta_query'    => [
                [
                    'key'     => 'plnt_avito_publish',
                    'value'   => '1',
                    'compare' => '=',
                ],
            ],
            'tax_query'     => [
                [
                    'taxonomy'         => 'product_cat',
                    'field'            => 'slug',
                    'terms'            => [ 'komnatnye-rasteniya' ],
                    'include_children' => true,
                ],
            ],
        ] );

        foreach ( $query->posts as $product_id ) {
            // Secondary guard: an unchecked or missing ACF field means "do not publish".
            if ( ! (bool) get_field( 'plnt_avito_publish', $product_id ) ) {
                continue;
            }

            $product = wc_get_product( $product_id );
            if ( ! $product || 'instock' !== $product->get_stock_status() ) {
                continue;
            }

            $stock = $product->get_stock_quantity();
            if ( null === $stock || (float) $stock <= 0 ) {
                continue;
            }

            $price = (float) $product->get_price();
            if ( $price <= 0 ) {
                continue;
            }

            $category = (string) get_field( 'plnt_avito_category', $product_id );
            if ( ! isset( $categories[ $category ] ) ) {
                continue;
            }

            $title       = trim( wp_strip_all_tags( $product->get_name() ) );
            // Current Avito product listings use titles no longer than 50 characters.
            $title       = mb_substr( $title, 0, 50, 'UTF-8' );
            $description = plnt_avito_description( $product );
            $images      = plnt_avito_image_urls( (int) $product_id );

            if ( '' === $title || '' === $description || ! $images ) {
                continue;
            }

            // These 3 indoor categories require PlantType from different Avito
            // dictionaries. Succulents have no PlantType in their XML template.
            // The field depends on the Avito category selected in ACF.
            $plant_type = 'succulents' === $category
                ? ''
                : plnt_avito_plant_type( (int) $product_id, $category );

            if ( 'succulents' !== $category && '' === $plant_type ) {
                continue;
            }

            // Берём подтипы из зависимых ACF-полей. Проверяем только обязательность.
            // Допустимые значения уже ограничены в ACF Select.
            $subtypes = [];
            $missing_required_subtype = false;
            foreach ( $subtype_fields[ $category ][ $plant_type ] ?? [] as $xml_tag => $acf_name ) {
                $value = trim( (string) get_field( $acf_name, (int) $product_id ) );

                // ACF exposes these two fields only for the matching PlantType.
                // They are required for Ficus and Palm ads.
                if ( '' === $value && in_array( $xml_tag, [ 'FicusType', 'PalmType' ], true ) ) {
                    $missing_required_subtype = true;
                    break;
                }

                if ( '' !== $value ) {
                    $subtypes[ $xml_tag ] = $value;
                }
            }
            if ( $missing_required_subtype ) {
                continue;
            }

            $xml->startElement( 'Ad' );
            $xml->writeElement( 'Id', (string) $product_id );
            $xml->writeElement( 'ListingFee', $common['ListingFee'] );
            $xml->writeElement( 'ManagerName', $common['ManagerName'] );
            $xml->writeElement( 'ContactPhone', $common['ContactPhone'] );
            $xml->writeElement( 'Title', $title );

            // XMLWriter::writeCdata preserves allowed markup without XML concatenation.
            $xml->startElement( 'Description' );
            $xml->writeCdata( $description );
            $xml->endElement();

            $xml->startElement( 'Images' );
            foreach ( $images as $url ) {
                $xml->startElement( 'Image' );
                $xml->writeAttribute( 'url', $url );
                $xml->endElement();
            }
            $xml->endElement();

            $xml->writeElement( 'ContactMethod', $common['ContactMethod'] );
            $xml->writeElement( 'Category', $common['Category'] );
            $xml->writeElement( 'Address', $common['Address'] );
            $xml->writeElement( 'Price', (string) (int) round( $price ) );
            $xml->writeElement( 'AdType', $common['AdType'] );
            $xml->writeElement( 'Availability', $common['Availability'] );
            $xml->writeElement( 'CountryExpedition', $common['CountryExpedition'] );
            $xml->writeElement( 'GoodsType', 'Комнатные растения' );
            $xml->writeElement( 'HousePlantType', $categories[ $category ] );

            if ( '' !== $plant_type ) {
                $xml->writeElement( 'PlantType', $plant_type );
            }

            foreach ( $subtypes as $xml_tag => $value ) {
                $xml->writeElement( $xml_tag, $value );
            }

            // NDS, delivery/dimensions, certificates/classifiers and wholesale
            // fields were deliberately excluded as agreed.
            $xml->endElement(); // Ad
        }

        $count = count( $query->posts );
        wp_reset_postdata();
        ++$page;
    } while ( $count === $per_page );

    $xml->endElement(); // Ads
    $xml->endDocument();

    // Завершаем запись XML.
    $result = $xml->flush();

    unset( $xml );

    clearstatcache( true, $temp_file );

    // Проверяем, что файл записан.
    if (
        false === $result
        || ! is_file( $temp_file )
        || filesize( $temp_file ) === 0
    ) {
        @unlink( $temp_file );
        wp_die( 'Avito XML: ошибка записи XML-файла.' );
    }

    // Файл должен быть доступен для чтения веб-сервером.
    if ( ! chmod( $temp_file, 0644 ) ) {
        @unlink( $temp_file );
        wp_die( 'Avito XML: не удалось установить права доступа к файлу.' );
    }

    // Заменяем рабочий XML только после успешной генерации.
    if ( ! rename( $temp_file, $file_path ) ) {
        @unlink( $temp_file );
        wp_die( 'Avito XML: не удалось заменить основной файл.' );
    }

    // Показываем готовый XML в браузере.
    nocache_headers();

    header( 'Content-Type: application/xml; charset=UTF-8' );

    readfile( $file_path );
    exit;
}