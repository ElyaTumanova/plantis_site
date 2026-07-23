<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# Schema org
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


add_action('wp_head', 'plnt_schema_json', 20);

function plnt_schema_json() {
	if (is_front_page()) {
		plnt_schema_print(plnt_schema_get_front_page_data());
		return;
	}

	if (is_product()) {
		$data = plnt_schema_get_product_data();

		if ($data) {
			plnt_schema_print($data);
		}
	}
}

function plnt_schema_get_front_page_data() {
	$home_url   = trailingslashit(home_url('/'));
	$org_id     = $home_url . '#organization';
	$website_id = $home_url . '#website';
	$webpage_id = $home_url . '#webpage';
	$logo_id    = $home_url . '#logo';
	$logo_url = content_url('/uploads/2025/07/Logo.svg');

	$description = 'Магазин комнатных растений, горшков, кашпо и товаров для ухода с доставкой по Москве и Московской области и офлайн-магазином в Москве.';

	$organization = [
		'@type' => [
			'GardenStore',
			'OnlineStore',
		],
		'@id'           => $org_id,
		'name'          => 'Plantis',
		'alternateName' => 'Интернет-магазин комнатных растений Plantis',
		'url'           => $home_url,
		'description'   => $description,
		'telephone'     => '+78002015790',
		'email'         => 'info@plantis.shop',
		'priceRange'    => '100–40 000 ₽',
		'currenciesAccepted' => 'RUB',

		'address' => [
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'ул. Мещерякова, д. 3',
			'addressLocality' => 'Москва',
			'addressCountry'  => 'RU',
		],

		'openingHours' => 'Mo-Su 10:00-20:00',

		'openingHoursSpecification' => [
			[
				'@type' => 'OpeningHoursSpecification',
				'dayOfWeek' => [
					'https://schema.org/Monday',
					'https://schema.org/Tuesday',
					'https://schema.org/Wednesday',
					'https://schema.org/Thursday',
					'https://schema.org/Friday',
					'https://schema.org/Saturday',
					'https://schema.org/Sunday',
				],
				'opens'  => '10:00',
				'closes' => '20:00',
			],
		],

		'areaServed' => [
			[
				'@type' => 'City',
				'name'  => 'Москва',
			],
			[
				'@type' => 'AdministrativeArea',
				'name'  => 'Московская область',
			],
		],

		'contactPoint' => [
			[
				'@type'             => 'ContactPoint',
				'contactType'       => 'customer service',
				'telephone'         => '+78002015790',
				'email'             => 'info@plantis.shop',
				'availableLanguage' => 'ru',
			],
			[
				'@type'             => 'ContactPoint',
				'contactType'       => 'customer service',
				'telephone'         => '+79995527944',
				'availableLanguage' => 'ru',
			],
		],

		'hasMap' => 'https://yandex.ru/maps/org/plentis/237252555639/',

		'sameAs' => [
			'https://yandex.ru/maps/org/plentis/237252555639/',
		],
	];

	if ($logo_url) {
		$organization['logo'] = [
			'@type'      => 'ImageObject',
			'@id'        => $logo_id,
			'url'        => $logo_url,
			'contentUrl' => $logo_url,
			'caption'    => 'Plantis',
		];

		$organization['image'] = [
			'@id' => $logo_id,
		];
	}

	$website = [
		'@type'         => 'WebSite',
		'@id'           => $website_id,
		'url'           => $home_url,
		'name'          => 'Plantis',
		'alternateName' => [
			'Интернет-магазин Plantis',
			'plantis-shop.ru',
		],
		'inLanguage' => 'ru-RU',
		'publisher'  => [
			'@id' => $org_id,
		],
	];

	$webpage = [
		'@type'       => 'WebPage',
		'@id'         => $webpage_id,
		'url'         => $home_url,
		'name'        => wp_get_document_title(),
		'description' => $description,
		'inLanguage'  => 'ru-RU',
		'isPartOf'    => [
			'@id' => $website_id,
		],
		'about' => [
			'@id' => $org_id,
		],
		'mainEntity' => [
			'@id' => $org_id,
		],
	];

	$front_page_id = (int) get_option('page_on_front');

	if ($front_page_id) {
		$date_modified = get_post_modified_time('c', true, $front_page_id);

		if ($date_modified) {
			$webpage['dateModified'] = $date_modified;
		}
	}

	return [
		'@context' => 'https://schema.org',
		'@graph'   => [
			$website,
			$webpage,
			$organization,
		],
	];
}

function plnt_schema_get_product_data() {
	$product = wc_get_product(get_queried_object_id());

	if (!$product || $product->is_type('gift-card')) {
		return [];
	}

	$product_url = get_permalink($product->get_id());
	$product_id  = $product_url . '#product';
	$offer_id    = $product_url . '#offer';
	$org_id      = trailingslashit(home_url('/')) . '#organization';

	$images = [];

	$main_image = wp_get_attachment_url($product->get_image_id());

	if ($main_image) {
		$images[] = $main_image;
	}

	foreach ($product->get_gallery_image_ids() as $attachment_id) {
		$image_url = wp_get_attachment_url($attachment_id);

		if ($image_url) {
			$images[] = $image_url;
		}
	}

	$images = array_values(array_unique($images));

	$offers = [
		'@type'         => 'Offer',
		'@id'           => $offer_id,
		'url'           => $product_url,
		'priceCurrency' => 'RUB',
		'price'         => number_format((float) $product->get_price(), 2, '.', ''),
		'availability'  => plnt_schema_get_product_availability($product),
		'itemCondition' => 'https://schema.org/NewCondition',
		'seller'        => [
			'@id' => $org_id,
		],
	];

	if ($product->is_on_sale() && $product->get_regular_price()) {
		$offers['priceSpecification'] = [
			'@type'         => 'UnitPriceSpecification',
			'priceType'     => 'https://schema.org/StrikethroughPrice',
			'price'         => number_format((float) $product->get_regular_price(), 2, '.', ''),
			'priceCurrency' => 'RUB',
		];
	}

	$description = wp_strip_all_tags($product->get_description());
	$brand       = plnt_get_brand_text($product->get_category_ids());
  $additional_properties = plnt_schema_get_product_properties($product);

	$data = [
		'@context'        => 'https://schema.org',
		'@type'           => 'Product',
		'@id'             => $product_id,
		'url'             => $product_url,
		'name'            => $product->get_name(),
		'image'           => $images,
		'sku'             => $product->get_sku(),
		'description'     => $description,
		'mainEntityOfPage' => $product_url,
		'offers'          => $offers,
	];

	if ($brand) {
		$data['brand'] = [
			'@type' => 'Brand',
			'name'  => $brand,
		];
	}

  if ($additional_properties) {
    $data['additionalProperty'] = $additional_properties;
  }

	return $data;
}

function plnt_schema_get_product_properties($product) {
	if (!$product instanceof WC_Product) {
		return [];
	}

	$properties = [];

	foreach ($product->get_attributes() as $attribute) {
		if (!$attribute instanceof WC_Product_Attribute || !$attribute->get_visible()) {
			continue;
		}

		$name   = wc_attribute_label($attribute->get_name(), $product);
		$values = [];

		if ($attribute->is_taxonomy()) {
			$terms = $attribute->get_terms();

			if (is_array($terms)) {
				foreach ($terms as $term) {
					if ($term instanceof WP_Term) {
						$values[] = $term->name;
					}
				}
			}
		} else {
			$values = $attribute->get_options();
		}

		$name = trim(wp_strip_all_tags($name));

		$values = array_map(function($value) {
			return trim(wp_strip_all_tags((string) $value));
		}, $values);

		$values = array_values(array_unique(array_filter($values)));

		if (!$name || !$values) {
			continue;
		}

		$properties[] = [
			'@type' => 'PropertyValue',
			'name'  => $name,
			'value' => implode(', ', $values),
		];
	}

	return $properties;
}

function plnt_schema_get_product_availability($product) {
	if (!$product->is_in_stock()) {
		return 'https://schema.org/OutOfStock';
	}

	if ($product->is_on_backorder(1)) {
		return 'https://schema.org/BackOrder';
	}

	return 'https://schema.org/InStock';
}

function plnt_schema_print($data) {
	if (!$data) {
		return;
	}

	echo '<script type="application/ld+json">';
	echo wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	echo '</script>';
}



/**
 * JSON-LD для хлебных крошек.
 * Видимые крошки продолжают выводиться стандартной yoast_breadcrumb().
 */

add_filter('wpseo_breadcrumb_links', 'plnt_capture_breadcrumb_links', 999);
add_action('wp_footer', 'plnt_output_breadcrumb_schema', 20);

function plnt_capture_breadcrumb_links($links) {
  $GLOBALS['plnt_breadcrumb_links'] = is_array($links) ? $links : [];

  return $links;
}

function plnt_output_breadcrumb_schema() {
  if (!is_product() && !is_shop() && !is_product_taxonomy() && !is_page('wishlist')) {
    return;
  }

  $links = $GLOBALS['plnt_breadcrumb_links'] ?? [];

  if (count($links) < 2) {
    return;
  }

  $items    = [];
  $position = 1;
  $last_key = array_key_last($links);

  foreach ($links as $key => $link) {
    $name = isset($link['text'])
      ? trim(html_entity_decode(wp_strip_all_tags($link['text']), ENT_QUOTES, get_bloginfo('charset')))
      : '';

    if (!$name) {
      continue;
    }

    $url = !empty($link['url'])
      ? esc_url_raw($link['url'])
      : '';

    if (!$url && $key === $last_key) {
      $url = plnt_get_current_breadcrumb_url();
    }

    $item = [
      '@type'    => 'ListItem',
      'position' => $position,
      'name'     => $name,
    ];

    if ($url) {
      $item['item'] = $url;
    }

    $items[] = $item;
    $position++;
  }

  if (count($items) < 2) {
    return;
  }

  $current_url = plnt_get_current_breadcrumb_url();

  $data = [
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    '@id'             => $current_url . '#breadcrumb',
    'itemListElement' => $items,
  ];

  echo '<script type="application/ld+json" class="plnt-schema-breadcrumb">';
  echo wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  echo '</script>';
}

function plnt_get_current_breadcrumb_url() {
  if (is_singular()) {
    return get_permalink();
  }

  if (is_shop()) {
    return wc_get_page_permalink('shop');
  }

  if (is_product_taxonomy()) {
    $url = get_term_link(get_queried_object());

    return !is_wp_error($url) ? $url : '';
  }

  global $wp;

  return isset($wp->request)
    ? home_url(user_trailingslashit($wp->request))
    : home_url('/');
}