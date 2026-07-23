<?php

if (!defined('ABSPATH')) {
	exit;
}

/*--------------------------------------------------------------
# Schema.org
--------------------------------------------------------------*/

// Полностью отключаем JSON-LD Yoast.
add_filter('wpseo_json_ld_output', '__return_false');

// Отключаем стандартный JSON-LD WooCommerce на страницах сайта.
// Разметку заказов в письмах не трогаем.
add_action('wp', 'plnt_remove_wc_structured_data', 99);

function plnt_remove_wc_structured_data() {
	if (!function_exists('WC')) {
		return;
	}

	$woocommerce = WC();

	if (!$woocommerce || !$woocommerce->structured_data) {
		return;
	}

	remove_action(
		'wp_footer',
		[$woocommerce->structured_data, 'output_structured_data'],
		10
	);
}

// Основной JSON-LD выводим в <head>.
add_action('wp_head', 'plnt_schema_json', 20);

function plnt_schema_json() {
	if (is_front_page()) {
		plnt_schema_print(
			plnt_schema_get_front_page_data(),
			'plnt-schema-main'
		);

		return;
	}

  if (is_page('delivery')) {
    plnt_schema_print(
      plnt_schema_get_delivery_page_data(),
      'plnt-schema-main'
    );

    return;
  }

	if (function_exists('is_product') && is_product()) {
		plnt_schema_print(
			plnt_schema_get_product_data(),
			'plnt-schema-main'
		);
	}
}

/** Главная страница: Organization + WebSite + WebPage */
function plnt_schema_get_front_page_data() {
	$home_url   = trailingslashit(home_url('/'));
	$org_id     = $home_url . '#organization';
	$website_id = $home_url . '#website';
	$webpage_id = $home_url . '#webpage';

	$description = 'Магазин комнатных растений, горшков, кашпо и товаров для ухода с доставкой по Москве и Московской области и офлайн-магазином в Москве.';

	$organization = plnt_schema_get_organization_data();
	$website      = plnt_schema_get_website_data();

	$webpage = [
		'@type'       => 'WebPage',
		'@id'         => $webpage_id,
		'url'         => $home_url,
		'name' => plnt_schema_clean_text(wp_get_document_title()),
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
		$date_published = get_post_time('c', true, $front_page_id);
		$date_modified  = get_post_modified_time('c', true, $front_page_id);

		if ($date_published) {
			$webpage['datePublished'] = $date_published;
		}

		if ($date_modified) {
			$webpage['dateModified'] = $date_modified;
		}
	}

	return [
		'@context' => 'https://schema.org',
		'@graph'   => [
			$organization,
			$website,
			$webpage,
		],
	];
}

/** Страница доставки: Organization + WebSite + WebPage + ShippingService. */
function plnt_schema_get_delivery_page_data() {
	$page_id      = get_queried_object_id();
	$delivery_url = $page_id ? get_permalink($page_id) : '';

	if (!$delivery_url) {
		$delivery_url = trailingslashit(home_url('/delivery/'));
	}

	$delivery_url = esc_url_raw($delivery_url);
	$home_url     = trailingslashit(home_url('/'));
	$website_id   = $home_url . '#website';
	$shipping     = plnt_schema_get_standard_shipping_service();

	$webpage = [
		'@type'       => 'WebPage',
		'@id'         => $delivery_url . '#webpage',
		'url'         => $delivery_url,
		'name'        => plnt_schema_clean_text(wp_get_document_title()),
		'description' => 'Доставка комнатных растений, горшков и кашпо по Москве и Московской области.',
		'inLanguage'  => 'ru-RU',
		'isPartOf'    => [
			'@id' => $website_id,
		],
		'about' => [
			'@id' => $home_url . '#organization',
		],
		'mainEntity' => [
			'@id' => $delivery_url . '#standard-moscow',
		],
	];

	if ($page_id) {
		$date_published = get_post_time('c', true, $page_id);
		$date_modified  = get_post_modified_time('c', true, $page_id);

		if ($date_published) {
			$webpage['datePublished'] = $date_published;
		}

		if ($date_modified) {
			$webpage['dateModified'] = $date_modified;
		}
	}

	return [
		'@context' => 'https://schema.org',
		'@graph'   => [
			plnt_schema_get_organization_data(),
			plnt_schema_get_website_data(),
			$webpage,
			$shipping,
		],
	];
}

/** Стандартная доставка в пределах МКАД. */
function plnt_schema_get_standard_shipping_service() {
	global $delivery_inMKAD;

	$delivery_url = trailingslashit(home_url('/delivery/'));
	$base_rate    = null;

	if (function_exists('plnt_get_shiping_costs')) {
		$shipping_costs = plnt_get_shiping_costs();

		if (
			is_array($shipping_costs)
			&& $delivery_inMKAD !== null
			&& array_key_exists($delivery_inMKAD, $shipping_costs)
		) {
			$base_rate = plnt_schema_to_float(
				$shipping_costs[$delivery_inMKAD]
			);
		}
	}

	$conditions = plnt_schema_get_standard_shipping_conditions($base_rate);

	$service = [
		'@type'           => 'ShippingService',
		'@id'             => $delivery_url . '#standard-moscow',
		'url'             => $delivery_url,
		'name'            => 'Стандартная доставка в пределах МКАД',
		'description' => 'Доставка на следующий день или позже в пределах МКАД. Базовая стоимость зависит от суммы заказа; дополнительный интервал оплачивается отдельно.',
		'fulfillmentType' => 'https://schema.org/FulfillmentTypeDelivery',
	];

	if ($conditions) {
		$service['shippingConditions'] = $conditions;
	}

	return $service;
}

function plnt_schema_get_standard_shipping_conditions($base_rate) {
	if ($base_rate === null || !function_exists('carbon_get_theme_option')) {
		return [];
	}

	$min_small    = plnt_schema_to_float(carbon_get_theme_option('min_small_delivery'));
	$min_medium   = plnt_schema_to_float(carbon_get_theme_option('min_medium_delivery'));
	$small_markup = plnt_schema_to_float(carbon_get_theme_option('small_markup_delivery')) ?? 0;
	$medium_markup = plnt_schema_to_float(carbon_get_theme_option('medium_markup_delivery')) ?? 0;

	$destination = [
		'@type'          => 'DefinedRegion',
		'name'           => 'Москва в пределах МКАД',
		'addressCountry' => 'RU',
		'addressRegion'  => 'Москва',
	];

	$conditions = [];

	if ($min_small !== null && $min_small > 0) {
		$conditions[] = [
			'@type' => 'ShippingConditions',
			'shippingDestination' => $destination,
			'orderValue' => [
				'@type'    => 'MonetaryAmount',
				'minValue' => 0,
				'maxValue' => $min_small - 1,
				'currency' => 'RUB',
			],
			'shippingRate' => [
				'@type'    => 'MonetaryAmount',
				'value'    => $base_rate + $small_markup,
				'currency' => 'RUB',
			],
		];
	}

	if ($min_small !== null && $min_medium !== null && $min_medium > $min_small) {
		$conditions[] = [
			'@type' => 'ShippingConditions',
			'shippingDestination' => $destination,
			'orderValue' => [
				'@type'    => 'MonetaryAmount',
				'minValue' => $min_small,
				'maxValue' => $min_medium - 1,
				'currency' => 'RUB',
			],
			'shippingRate' => [
				'@type'    => 'MonetaryAmount',
				'value'    => $base_rate + $medium_markup,
				'currency' => 'RUB',
			],
		];
	}

	$conditions[] = [
		'@type' => 'ShippingConditions',
		'shippingDestination' => $destination,
		'orderValue' => [
			'@type'    => 'MonetaryAmount',
			'minValue' => $min_medium ?? $min_small ?? 0,
			'currency' => 'RUB',
		],
		'shippingRate' => [
			'@type'    => 'MonetaryAmount',
			'value'    => $base_rate,
			'currency' => 'RUB',
		],
	];

	return $conditions;
}

function plnt_schema_to_float($value) {
	if ($value === null || $value === '') {
		return null;
	}

	$value = str_replace(
		["\xC2\xA0", ' ', ','],
		['', '', '.'],
		(string) $value
	);

	return is_numeric($value) ? (float) $value : null;
}

/** Товарная страница: Organization + WebSite + WebPage + Product */
function plnt_schema_get_product_data() {
  if (!function_exists('wc_get_product')) {
    return [];
  }
	$product = wc_get_product(get_queried_object_id());

	if (!$product || $product->is_type('gift-card')) {
		return [];
	}

	$product_url = get_permalink($product->get_id());

  if (!$product_url) {
    return [];
  }

  $product_url = esc_url_raw($product_url);

	$home_url     = trailingslashit(home_url('/'));
	$org_id       = $home_url . '#organization';
	$website_id   = $home_url . '#website';
	$product_id   = $product_url . '#product';
	$webpage_id   = $product_url . '#webpage';
	$breadcrumb_id = $product_url . '#breadcrumb';

	$images                = plnt_schema_get_product_images($product);
	$description           = plnt_schema_get_product_description($product);
  $alternate_name        = plnt_schema_get_product_alternate_name($product);
	$brand = function_exists('plnt_get_brand_text')
    ? plnt_schema_clean_text(
      plnt_get_brand_text($product->get_category_ids())
    )
    : '';
	$additional_properties = plnt_schema_get_product_properties($product);
	$offer                 = plnt_schema_get_product_offer($product, $product_url, $org_id);

	$product_data = [
		'@type'            => 'Product',
		'@id'              => $product_id,
		'url'              => $product_url,
		'name' => plnt_schema_clean_text($product->get_name()),
		'mainEntityOfPage' => [
			'@id' => $webpage_id,
		],
	];

	if ($images) {
		$product_data['image'] = $images;
	}

	if ($product->get_sku()) {
		$product_data['sku'] = $product->get_sku();
	}

	if ($description) {
		$product_data['description'] = $description;
	}

  if ($alternate_name && $alternate_name !== $product_data['name']) {
    $product_data['alternateName'] = $alternate_name;
  }

	if ($brand) {
		$product_data['brand'] = [
			'@type' => 'Brand',
			'name'  => $brand,
		];
	}

	if ($additional_properties) {
		$product_data['additionalProperty'] = $additional_properties;
	}

	if ($offer) {
		$product_data['offers'] = $offer;
	}

	$webpage = [
		'@type'      => 'WebPage',
		'@id'        => $webpage_id,
		'url'        => $product_url,
		'name' => plnt_schema_clean_text(wp_get_document_title()),
		'inLanguage' => 'ru-RU',
		'isPartOf'   => [
			'@id' => $website_id,
		],
		'breadcrumb' => [
			'@id' => $breadcrumb_id,
		],
		'mainEntity' => [
			'@id' => $product_id,
		],
	];

	if ($description) {
		$webpage['description'] = $description;
	}

	if ($images) {
		$webpage['primaryImageOfPage'] = [
			'@type' => 'ImageObject',
			'url'   => $images[0],
		];
	}

	$date_published = get_post_time('c', true, $product->get_id());
	$date_modified  = get_post_modified_time('c', true, $product->get_id());

	if ($date_published) {
		$webpage['datePublished'] = $date_published;
	}

	if ($date_modified) {
		$webpage['dateModified'] = $date_modified;
	}

	return [
		'@context' => 'https://schema.org',
		'@graph'   => [
			plnt_schema_get_organization_data(),
			plnt_schema_get_website_data(),
			$webpage,
			$product_data,
		],
	];
}

/** Организация/магазин. Одинаковый @id используется на всех страницах.*/
function plnt_schema_get_organization_data() {
	$home_url = trailingslashit(home_url('/'));
	$org_id   = $home_url . '#organization';
	$logo_id  = $home_url . '#logo';
  $shipping_id      = trailingslashit(home_url('/delivery/')) . '#standard-moscow';
  $return_policy_id = trailingslashit(home_url('/refund_returns/')) . '#policy';

	$logo_value = function_exists('carbon_get_theme_option')
		? carbon_get_theme_option('logo')
		: '';

	if (is_array($logo_value)) {
		$logo_value = $logo_value['url'] ?? '';
	}

	$logo_url = is_numeric($logo_value)
		? wp_get_attachment_url((int) $logo_value)
		: esc_url_raw((string) $logo_value);

	if (!$logo_url) {
		$logo_url = content_url('/uploads/2025/07/Logo.svg');
	}

	$description = 'Магазин комнатных растений, горшков, кашпо и товаров для ухода с доставкой по Москве и Московской области и офлайн-магазином в Москве.';

	$organization = [
		'@type' => [
			'GardenStore',
			'OnlineStore',
		],
		'@id'           => $org_id,
		'name'          => 'Plantis',
		'alternateName' => 'Интернет-магазин комнатных растений Plantis',
		'legalName'     => 'ИП Туманов Вячеслав Витальевич',
		'taxID'         => '645313252670',
		'identifier'    => [
			'@type'      => 'PropertyValue',
			'propertyID' => 'ОГРНИП',
			'value'      => '321774600774479',
		],
		'url'                  => $home_url,
		'description'          => $description,
		'telephone'          => '+78002015790',
		'email'                => 'info@plantis.shop',
		'priceRange'           => '100–40 000 ₽',
		'currenciesAccepted'   => 'RUB',
    'openingHours'        => 'Mo-Su 10:00-20:00',
		'address'              => [
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'ул. Мещерякова, д. 3',
			'addressLocality' => 'Москва',
      'addressRegion'   => 'Москва',
			'postalCode'      => '125362',
			'addressCountry'  => 'RU',
		],
    'legalAddress' => [
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Б. Почтовая, д. 1/33, стр. 1',
			'addressLocality' => 'Москва',
			'addressRegion'   => 'Москва',
			'postalCode'      => '105082',
			'addressCountry'  => 'RU',
		],
		'geo' => [
			'@type'     => 'GeoCoordinates',
			'latitude'  => 55.83226,
			'longitude' => 37.45181,
		],
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
    'hasShippingService' => [
      [
        '@id' => $shipping_id,
      ],
    ],
    'hasMerchantReturnPolicy' => [
      '@id' => $return_policy_id,
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

	return $organization;
}

function plnt_schema_get_website_data() {
	$home_url = trailingslashit(home_url('/'));

	return [
		'@type'         => 'WebSite',
		'@id'           => $home_url . '#website',
		'url'           => $home_url,
		'name'          => 'Plantis',
		'alternateName' => [
			'Интернет-магазин Plantis',
			'plantis-shop.ru',
		],
		'inLanguage' => 'ru-RU',
		'publisher'  => [
			'@id' => $home_url . '#organization',
		],
	];
}

function plnt_schema_get_product_images($product) {
	if (!$product instanceof WC_Product) {
		return [];
	}

	$image_ids = array_filter(array_merge(
		[$product->get_image_id()],
		$product->get_gallery_image_ids()
	));

	$images = [];

	foreach ($image_ids as $attachment_id) {
		$image_url = wp_get_attachment_image_url($attachment_id, 'full');

		if ($image_url) {
			$images[] = $image_url;
		}
	}

	return array_values(array_unique($images));
}

function plnt_schema_get_product_description($product) {
	if (!$product instanceof WC_Product) {
		return '';
	}

	$description = $product->get_description();

	if (!$description) {
		$description = $product->get_short_description();
	}

	return plnt_schema_clean_text($description);
}

function plnt_schema_get_product_alternate_name($product) {
	if (!$product instanceof WC_Product) {
		return '';
	}

	return plnt_schema_clean_text($product->get_short_description());
}

function plnt_schema_get_product_offer($product, $product_url, $org_id) {
	if (!$product instanceof WC_Product) {
		return [];
	}

	$availability = plnt_schema_get_product_availability($product);

	$price = $product->get_price();

	if ($price === '') {
		return [];
	}

	$offer = [
		'@type'         => 'Offer',
		'@id'           => $product_url . '#offer',
		'url'           => $product_url,
		'priceCurrency' => 'RUB',
		'price'         => (float) wc_format_decimal($price, wc_get_price_decimals()),
		'availability'  => $availability,
		'itemCondition' => 'https://schema.org/NewCondition',
		'seller'        => [
			'@id' => $org_id,
		],
	];

	if ($product->is_on_sale() && $product->get_regular_price() !== '') {
		$offer['priceSpecification'] = [
			'@type'         => 'UnitPriceSpecification',
			'priceType'     => 'https://schema.org/StrikethroughPrice',
			'price'         => (float) wc_format_decimal(
				$product->get_regular_price(),
				wc_get_price_decimals()
			),
			'priceCurrency' => 'RUB',
		];
	}

	return $offer;
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

		$name = plnt_schema_clean_text(
      wc_attribute_label($attribute->get_name(), $product)
    );

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

		$values = array_map('plnt_schema_clean_text', $values);

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

	return apply_filters(
		'plnt_schema_product_properties',
		$properties,
		$product
	);
}

function plnt_schema_get_product_availability($product) {
	if (!$product instanceof WC_Product) {
		return 'https://schema.org/OutOfStock';
	}

	if (function_exists('plnt_get_availability_text')) {
		$value = plnt_get_availability_text($product);

		if (in_array($value, ['InStock', 'BackOrder', 'PreOrder', 'OutOfStock'], true)) {
			return 'https://schema.org/' . $value;
		}
	}

	if (!$product->is_in_stock()) {
		return 'https://schema.org/OutOfStock';
	}

	if ($product->is_on_backorder(1)) {
		return 'https://schema.org/BackOrder';
	}

	return 'https://schema.org/InStock';
}

function plnt_schema_clean_text($value) {
	$value = wp_strip_all_tags((string) $value);

	$value = html_entity_decode(
		$value,
		ENT_QUOTES | ENT_HTML5,
		get_bloginfo('charset') ?: 'UTF-8'
	);

	$value = preg_replace('/\s+/u', ' ', $value);

	return trim((string) $value);
}

function plnt_schema_print($data, $class = '') {
	if (!$data || !is_array($data)) {
		return;
	}

	$json = wp_json_encode(
		$data,
		JSON_UNESCAPED_UNICODE
		| JSON_UNESCAPED_SLASHES
		| JSON_HEX_TAG
		| JSON_HEX_AMP
		| JSON_HEX_APOS
		| JSON_HEX_QUOT
	);

	if (!$json) {
		return;
	}

	$class_attr = $class
		? ' class="' . esc_attr($class) . '"'
		: '';

	echo "\n<script type=\"application/ld+json\"{$class_attr}>{$json}</script>\n";
}

/*--------------------------------------------------------------
# BreadcrumbList
--------------------------------------------------------------*/

/**
 * Сохраняем итоговую цепочку Yoast после всех пользовательских фильтров.
 * Видимые крошки продолжают выводиться стандартной yoast_breadcrumb().
 */
add_filter('wpseo_breadcrumb_links', 'plnt_capture_breadcrumb_links', 999);
add_action('wp_footer', 'plnt_output_breadcrumb_schema', 20);

function plnt_capture_breadcrumb_links($links) {
	if (plnt_schema_has_breadcrumbs()) {
		$GLOBALS['plnt_breadcrumb_links'] = is_array($links) ? $links : [];
	}

	return $links;
}

function plnt_output_breadcrumb_schema() {
	if (!plnt_schema_has_breadcrumbs()) {
		return;
	}

	$links = $GLOBALS['plnt_breadcrumb_links'] ?? [];

	if (count($links) < 2) {
		return;
	}

	$current_url = plnt_get_current_breadcrumb_url();

	if (!$current_url) {
		return;
	}

	$items    = [];
	$last_key = array_key_last($links);

	foreach ($links as $key => $link) {
		$name = isset($link['text'])
      ? plnt_schema_clean_text($link['text'])
      : '';

		if (!$name) {
			continue;
		}

		$url = !empty($link['url'])
			? esc_url_raw($link['url'])
			: '';

		if (!$url && $key === $last_key) {
			$url = $current_url;
		}

		$item = [
			'@type'    => 'ListItem',
			'position' => count($items) + 1,
			'name'     => $name,
		];

		if ($url) {
			$item['item'] = $url;
		}

		$items[] = $item;
	}

	if (count($items) < 2) {
		return;
	}

	plnt_schema_print(
		[
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'@id'             => $current_url . '#breadcrumb',
			'itemListElement' => $items,
		],
		'plnt-schema-breadcrumb'
	);

	unset($GLOBALS['plnt_breadcrumb_links']);
}

function plnt_schema_has_breadcrumbs() {
	return is_product()
		|| is_shop()
		|| is_product_taxonomy()
		|| is_page('wishlist');
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
		: trailingslashit(home_url('/'));
}
