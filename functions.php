<?php
/** Add Carbon Fields */
add_action( 'carbon_fields_register_fields', 'ast_register_custom_fields' );
function ast_register_custom_fields() {
	require get_template_directory() . '/includes/custom-fields/post-meta.php';
	require get_template_directory() . '/includes/custom-fields/theme-options.php';
}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	load_template( get_template_directory() . '/includes/carbon-fields/vendor/autoload.php' );
	\Carbon_Fields\Carbon_Fields::boot();
}

/** Add theme support */
require get_template_directory() . '/includes/theme-support.php';
/** Enqueue scripts */
require get_template_directory() . '/includes/enqueue-scripts-style.php';
/** Various clean up functions */
require get_template_directory() . '/includes/cleanup.php';
/** Return entry meta information for posts */
// require get_template_directory() . '/includes/meta-data.php';
/** Create widget areas in sidebar and footer */
// require get_template_directory() . '/includes/widget-areas.php';
/** Add register nav menu */
require get_template_directory() . '/includes/navigation.php';
/** Add ajax */
require get_template_directory() . '/includes/ajax.php';
/** Add constants */
require get_template_directory() . '/includes/constants.php';
/** Add Yandex metrika */
require get_template_directory() . '/includes/metrika.php';
/** Create Yandex XML */
//require get_template_directory() . '/includes/xml/create_yandex_xml.php';
//require get_template_directory() . '/includes/xml/create_google_xml.php';

/** Add Woocommerce files */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/woocommerce/includes/wc-helpers-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-cart-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-shipping-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-checkout-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-custom-fields.php';
	require get_template_directory() . '/woocommerce/includes/wc-function.php';
	require get_template_directory() . '/woocommerce/includes/wc-remove-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-card-function.php';
	require get_template_directory() . '/woocommerce/includes/wc-catalog-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-yith-wishlist-finctions.php';
	require get_template_directory() . '/woocommerce/includes/wc-account-functions.php';
}


//ЗАДАЕМ КОНСТАНТЫ ДЛЯ JS
add_action( 'wp_footer', 'plnt_set_constants_script' );
function plnt_set_constants_script() {
	global $delivery_inMKAD;
	global $delivery_outMKAD;
	// global $delivery_inMKAD_small;
	// global $delivery_outMKAD_small;
	// global $delivery_inMKAD_large;
	// global $delivery_outMKAD_large;
	// global $delivery_inMKAD_medium;
	// global $delivery_outMKAD_medium;

	// global $urgent_delivery_inMKAD; 
	// global $urgent_delivery_outMKAD; 
	// global $urgent_delivery_inMKAD_small; 
	// global $urgent_delivery_outMKAD_small;
	// global $urgent_delivery_inMKAD_large; 
	// global $urgent_delivery_outMKAD_large;
	// global $urgent_delivery_inMKAD_medium;
	// global $urgent_delivery_outMKAD_medium;

	global $local_pickup;
	global $delivery_free;
	global $delivery_pochta;
	global $delivery_courier;
	global $delivery_long_dist;

	$late_markup_delivery = carbon_get_theme_option('late_markup_delivery');
	$large_markup_delivery = carbon_get_theme_option('large_markup_delivery');
    $small_markup_delivery = carbon_get_theme_option('small_markup_delivery');
    $medium_markup_delivery = carbon_get_theme_option('medium_markup_delivery');
    $urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');

	$shipping_costs = plnt_get_shiping_costs();

    $in_mkad = $shipping_costs[$delivery_inMKAD];
    $out_mkad = $shipping_costs[$delivery_outMKAD];

	// $in_mkad_urg = $shipping_costs[$urgent_delivery_inMKAD];
	// $out_mkad_urg = $shipping_costs[$urgent_delivery_outMKAD];

	// $in_mkad_large = $shipping_costs[$delivery_inMKAD_large];
	// $out_mkad_large = $shipping_costs[$delivery_outMKAD_large];

	// $in_mkad_urg_large = $shipping_costs[$urgent_delivery_inMKAD_large];
	// $out_mkad_urg_large = $shipping_costs[$urgent_delivery_outMKAD_large];
 
	// $in_mkad_small = $shipping_costs[$delivery_inMKAD_small];
	// $out_mkad_small = $shipping_costs[$delivery_outMKAD_small];

	// $in_mkad_small_urg = $shipping_costs[$urgent_delivery_inMKAD_small];
	// $out_mkad_small_urg = $shipping_costs[$urgent_delivery_outMKAD_small];

	// $in_mkad_medium = $shipping_costs[$delivery_inMKAD_medium];
	// $out_mkad_medium = $shipping_costs[$delivery_outMKAD_medium];

	// $in_mkad_medium_urg = $shipping_costs[$urgent_delivery_inMKAD_medium];
	// $out_mkad_medium_urg = $shipping_costs[$urgent_delivery_outMKAD_medium];

	$isbackorders = plnt_is_backorder();
	$isTreezBackorders = plnt_is_treez_backorder();

	 // define markup
	$delivery_murkup = 0;

    $cart_weight = WC()->cart->cart_contents_weight; // вес товаров в корзине

    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');

    // проверяем крупногабаритную доставку
    if ($cart_weight >= 11) {
        $delivery_murkup = $large_markup_delivery;
    } 
    // проверяем маленькие суммы заказов
    else {
        if ( WC()->cart->subtotal < $min_small_delivery ) {
                $delivery_murkup = $small_markup_delivery;
        } else if (WC()->cart->subtotal < $min_medium_delivery) {
            $delivery_murkup = $medium_markup_delivery;
        }
    }
   
	?>
	<script>
		// shipping methods IDs
		let deliveryInMKAD = '<?php echo $delivery_inMKAD; ?>';
		let deliveryOutMKAD = '<?php echo $delivery_outMKAD; ?>';
	
		let localPickupId = '<?php echo $local_pickup; ?>';
		let deliveryFreeId = '<?php echo $delivery_free; ?>';
		let deliveryPochtaId = '<?php echo $delivery_pochta; ?>';
		let deliveryCourierId = '<?php echo $delivery_courier; ?>';
		let deliveryLongId = '<?php echo $delivery_long_dist; ?>';

		// shipping methods costs
		let deliveryCostInMkad = '<?php echo $in_mkad; ?>';
		let deliveryCostOutMkad = '<?php echo $out_mkad; ?>';


		let deliveryUrgMarkup = '<?php echo $urgent_markup_delivery; ?>';
		let deliveryLateMarkup = '<?php echo $late_markup_delivery; ?>';
		let deliveryMarkup = '<?php echo $delivery_murkup; ?>';

		let isBackorder = '<?php echo $isbackorders; ?>';
		let isTreezBackorders = '<?php echo $isTreezBackorders; ?>';

	</script>
	<?php
}

// add_filter('wpseo_opengraph_image', 'plnt_opengraph_image');
// function plnt_opengraph_image($image) {
//    $image = 'https://plantis.shop/wp-content/uploads/2025/01/пересадка_мобnorm-копия.webp';
//    return $image;
// }

// FOR DEV

function plnt_dev_functions() {

	global $plants_treez_cat_id;
	global $plants_cat_id;
	global $gorshki_cat_id;
	global $ukhod_cat_id;
	global $treez_cat_id;
	global $treez_poliv_cat_id;
	global $lechuza_cat_id;
	global $misc_cat_id;
	global $peresadka_cat_id;

	$cats_for_check = [$plants_cat_id, $gorshki_cat_id, $ukhod_cat_id,$treez_cat_id, $treez_poliv_cat_id, $plants_treez_cat_id, $lechuza_cat_id, $peresadka_cat_id, $misc_cat_id];
	$cats_for_include = [];
	$cats_for_include_clean = [];
	foreach($cats_for_check as $item){
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query' => array( 
				array(
					'key' => '_stock',
					'type'    => 'numeric',
					'value' => '0',
					'compare' => '>'
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => [$item],
					'operator' => 'IN',
					'include_children' => 1,
				)
			)
		);
		$query = new WP_Query;
		$checkproducts = $query->query($args);

		$checkproductscount = count($checkproducts);
		// echo $item.' '.$checkproductscount;
		// echo '<br>';
		if($checkproductscount != 0) {
			//print_r($checkproducts);
			foreach ($checkproducts as $item) {
				// print_r($item);
				// echo '<br>';
				$product = wc_get_product($item);
				$prod_cats = $product->get_category_ids();
				foreach ($prod_cats as $cat) {
					array_push($cats_for_include, $cat);
				}
				// echo ('cat ids ');
				// print_r($product->get_category_ids());
				// echo '<br>';
			}
		};
	}
	echo 'cats_for_include ';
	$cats_for_include_clean = array_unique($cats_for_include);
	// print_r($cats_for_include);
	// echo '<br>';
	print_r($cats_for_include_clean);
	echo '<br>';
	echo 'cats_for_exclude ';
	print_r($cats_for_exclude); 
	

	$args_cats=array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'include' => $cats_for_include_clean,
	);

	$terms=get_terms($args_cats);

	foreach($terms as $item){
		echo $item->name;
		echo '<br>';
	}

	//print_r($terms);
}

function plnt_check_page() {
	// global $gorshki_cat_id;
	// if ( term_is_ancestor_of( $gorshki_cat_id, get_queried_object_id(), 'product_cat' ) ) {
	// 	echo 'подкатегория';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	// echo '<pre>';
	// print_r( get_queried_object_id() );
	// print_r( $gorshki_cat_id );
	// echo '</pre>';
	if ( is_page( 'wishlist' ) ) {
		echo 'Это wishlist!';
	}
	else {
		echo 'Это какая-то другая страница.';
	}

  echo yith_wcwl_count_all_products();
	// if(is_page_template('themes/plantis_site/woocommerce/loop/no-products-found.php')) {
	// 	echo 'Это то что нужно';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	

}

function plnt_get_cats_data() {
	global $plants_treez_cat_id;
	global $plants_cat_id;
	global $gorshki_cat_id;
	global $ukhod_cat_id;
	global $treez_cat_id;
	global $treez_poliv_cat_id;
	global $lechuza_cat_id;
	global $misc_cat_id;
	global $peresadka_cat_id;

	$term = get_term( $plants_cat_id, 'product_cat');
	$term_name = $term->name; // получаем название конкретной категории товаров (в данном случае)
	//print_r($term_name);

	$terms = get_terms( [
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
	] );

	//print_r($terms);
	$my_terms_array = [];

	// foreach ($terms as $key => $term) {
	// 	//print_r($term);
	// 	//print_r($term->term_id);
	// 	echo ("['name' => '");
	// 	print_r($term->name);
	// 	echo ("','slug' => '");
	// 	print_r($term->slug);
	// 	echo ("'],");
	// 	//echo (';');
	// 	//print_r($term->description);
	// 	//echo (';');
	// 	//echo ('<br>');
	// 	//array_push($my_terms_array, ['name'=>$term->name, 'slug'=>$term->slug]);
	// }

	//print_r($my_terms_array);

	$cats_array = array(['name' => 'Treez Effectory Anthra','slug' => 'treez-effectory-anthra'],['name' => 'Treez Effectory Aura','slug' => 'treez-effectory-aura'],['name' => 'Treez Effectory Black Stone','slug' => 'treez-effectory-black-stone-treez-effectory'],['name' => 'Treez Effectory Corten Steel','slug' => 'treez-effectory-corten-steel'],['name' => 'Treez Effectory Crystal','slug' => 'treez-effectory-crystal'],['name' => 'Treez Effectory Dune','slug' => 'treez-effectory-dune'],['name' => 'Treez Effectory Gloss','slug' => 'treez-effectory-gloss'],['name' => 'Treez Effectory Metal','slug' => 'treez-effectory-metal'],['name' => 'Treez Effectory Moho','slug' => 'treez-effectory-moho'],['name' => 'Treez Effectory Organic','slug' => 'treez-effectory-organic'],['name' => 'Treez Effectory Quartz','slug' => 'treez-effectory-quartz'],['name' => 'Treez Effectory Ron','slug' => 'treez-effectory-ron'],['name' => 'Treez Effectory Savage Garden','slug' => 'treez-effectory-savage-garden'],['name' => 'Treez Effectory Stone','slug' => 'treez-effectory-stone'],['name' => 'Treez Effectory Terra','slug' => 'treez-effectory-terra'],['name' => 'Treez Effectory Timberline','slug' => 'treez-effectory-timberline'],['name' => 'Treez Effectory Volcano','slug' => 'treez-effectory-volcano'],['name' => 'Treez Effectory Wood','slug' => 'treez-effectory-wood'],['name' => 'Treez Effectory Wow','slug' => 'treez-effectory-wow'],['name' => 'Treez Ergo Comb','slug' => 'treez-ergo-comb'],['name' => 'Treez Ergo Concrete','slug' => 'treez-ergo-concrete'],['name' => 'Treez Ergo Diamond','slug' => 'treez-ergo-diamond'],['name' => 'Treez Ergo Fine Rock','slug' => 'treez-ergo-fine-rock'],['name' => 'Treez Ergo Graphics','slug' => 'treez-ergo-graphics'],['name' => 'Treez Ergo Hard Rock','slug' => 'treez-ergo-hard-rock'],['name' => 'Treez Ergo Italica','slug' => 'treez-ergp-italica'],['name' => 'Treez Ergo Jet','slug' => 'treez-ergo-jet'],['name' => 'Treez Ergo Just','slug' => 'treez-ergo-just'],['name' => 'Treez Ergo Nature','slug' => 'treez-ergo-nature'],['name' => 'Treez Ergo Nero','slug' => 'treez-ergo-nero'],['name' => 'Treez Ergo Orien Metal','slug' => 'treez-ergo-orien-metal'],['name' => 'Treez Ergo Rombo','slug' => 'treez-ergo-rombo'],['name' => 'Treez Ergo TreeLine','slug' => 'treez-ergo-treeline'],['name' => 'Агава','slug' => '%d0%b0%d0%b3%d0%b0%d0%b2%d0%b0'],['name' => 'Адениум','slug' => '%d0%b0%d0%b4%d0%b5%d0%bd%d0%b8%d1%83%d0%bc'],['name' => 'Аспидистра','slug' => 'aspidistra'],['name' => 'Гибискус','slug' => 'gibiskus'],['name' => 'Грунт','slug' => 'grunt'],['name' => 'Жасмин','slug' => 'zhasmin'],['name' => 'Защита растений','slug' => 'zashchita-rastenij'],['name' => 'Искусственная Агава Treez','slug' => 'iskusstvennaya-agava-treez'],['name' => 'Искусственная Алоказия Treez','slug' => 'iskusstvennaya-alokaziya-treez'],['name' => 'Искусственная Бамбуковая Пальма Treez','slug' => 'iskusstvennaya-palma-bambukovaya-treez'],['name' => 'Искусственная Банановая пальма Treez','slug' => 'iskusstvennaya-bananovaya-palma-treez'],['name' => 'Искусственная Берёза Treez','slug' => 'iskusstvennaya-beryoza-treez'],['name' => 'Искусственная Бутылочная пальма Treez','slug' => 'iskusstvennaya-palma-butylochnaya-treez'],['name' => 'Искусственная Вистерия Treez','slug' => 'iskusstvennaya-visteriya-treez'],['name' => 'Искусственная Драцена Treez','slug' => 'iskusstvennaya-dracena-treez'],['name' => 'Искусственная Ива Treez','slug' => 'iskusstvennaya-iva-treez'],['name' => 'Искусственная Камелия Treez','slug' => 'iskusstvennaya-kameliya-treez'],['name' => 'Искусственная Капенсия Treez','slug' => 'iskusstvennaya-kapensiya-treez'],['name' => 'Искусственная Клузия Treez','slug' => 'iskusstvennaya-kluziya-treez'],['name' => 'Искусственная Кордилина Treez','slug' => 'iskusstvennaya-kordilina-treez'],['name' => 'Искусственная Лонгифолия Treez','slug' => 'iskusstvennaya-longifoliya-treez'],['name' => 'Искусственная Монстера Treez','slug' => 'iskusstvennaya-monstera-treez'],['name' => 'Искусственная Олива Treez','slug' => 'iskusstvennaya-oliva-treez'],['name' => 'Искусственная пальма Treez','slug' => 'iskusstvennaya-palma-treez'],['name' => 'Искусственная Пальма Арека Treez','slug' => 'iskusstvennaya-palma-areka-treez'],['name' => 'Искусственная Пальма Вашингтония Treez','slug' => 'iskusstvennaya-palma-vashingtoniya-treez'],['name' => 'Искусственная Пальма Рапис Treez','slug' => 'iskusstvennaya-palma-rapis-treez'],['name' => 'Искусственная Пальма Хамедорея Treez','slug' => 'iskusstvennaya-palma-hamedoreya-treez'],['name' => 'Искусственная Пальма Ховея Treez','slug' => 'iskusstvennaya-palma-hoveya-treez'],['name' => 'Искусственная Пальма Цикас Treez','slug' => 'iskusstvennaya-palma-cikas-treez'],['name' => 'Искусственная Пахира Treez','slug' => 'iskusstvennaya-pahira-treez'],['name' => 'Искусственная сансевиерия Treez','slug' => 'iskusstvennaya-sansevieriya-treez'],['name' => 'Искусственная Сосна Treez','slug' => 'iskusstvennaya-sosna-treez'],['name' => 'Искусственная Стрелиция Treez','slug' => 'iskusstvennaya-streliciya-treez'],['name' => 'Искусственная Туя Treez','slug' => 'iskusstvennaya-tuya-treez'],['name' => 'Искусственная Финиковая пальма Treez','slug' => 'iskusstvennaya-finikovaya-palma-treez'],['name' => 'Искусственная фуксия Treez','slug' => 'iskusstvennaya-fuksiya-treez'],['name' => 'Искусственная Шеффлера Treez','slug' => 'iskusstvennaya-shefflera-treez'],['name' => 'Искусственная Юкка Treez','slug' => 'iskusstvennaya-yukka-treez'],['name' => 'Искусственная Яблоня Treez','slug' => 'iskusstvennaya-yablonya-treez'],['name' => 'Искусственное Алоэ Treez','slug' => 'iskusstvennoe-aloeh-treez'],['name' => 'Искусственное Апельсиновое дерево Treez','slug' => 'iskusstvennoe-apelsinovoe-derevo-treez'],['name' => 'Искусственное дерево Гинкго Treez','slug' => '%d0%b8%d1%81%d0%ba%d1%83%d1%81%d1%81%d1%82%d0%b2%d0%b5%d0%bd%d0%bd%d0%be%d0%b5-%d0%b4%d0%b5%d1%80%d0%b5%d0%b2%d0%be-%d0%b3%d0%b8%d0%bd%d0%ba%d0%b3%d0%be-treez'],['name' => 'Искусственное Кофейное дерево Treez','slug' => 'iskusstvennoe-kofejnoe-derevo-treez'],['name' => 'Искусственное Лимонное дерево Treez','slug' => 'iskusstvennoe-limonnoe-derevo-treez'],['name' => 'Искусственные декоративно-лиственные растения Treez','slug' => 'iskusstvennye-dekorativno-listvennye-rasteniya-treez'],['name' => 'Искусственные деревья Treez','slug' => 'iskusstvennye-derevya-treez'],['name' => 'Искусственные растения Treez','slug' => 'iskusstvennye-rasteniya-treez'],['name' => 'Искусственные суккуленты Treez','slug' => 'iskusstvennye-sukkulenty-treez'],['name' => 'Искусственные цитрусовые деревья','slug' => 'iskusstvennye-citrusovye-derevya'],['name' => 'Искусственный Бамбук Treez','slug' => 'iskusstvennyj-bambuk-treez'],['name' => 'Искусственный Бересклет Treez','slug' => 'iskusstvennyj-beresklet-treez'],['name' => 'Искусственный Кактус Баррель Treez','slug' => 'iskusstvennyj-kaktus-barrel-treez'],['name' => 'Искусственный Кактус Цереус Treez','slug' => 'iskusstvennyj-kaktus-cereus-treez'],['name' => 'Искусственный Кипарис Treez','slug' => 'iskusstvennyj-kiparis-treez'],['name' => 'Искусственный Клематис Treez','slug' => 'iskusstvennyj-klematis-treez'],['name' => 'Искусственный Кроссостефиум Treez','slug' => 'iskusstvennyj-krossostefium-treez'],['name' => 'Искусственный Кротон Treez','slug' => 'iskusstvennyj-kroton-treez'],['name' => 'Искусственный Лавр Treez','slug' => 'iskusstvennyj-lavr-treez'],['name' => 'Искусственный Мандарин Treez','slug' => 'iskusstvennyj-mandarin-treez'],['name' => 'Искусственный папоротник Treez','slug' => 'iskusstvennyj-paporotnik-treez'],['name' => 'Искусственный Питтоспорум Treez','slug' => 'iskusstvennyj-pittosporum-treez'],['name' => 'Искусственный Подокарпус Treez','slug' => 'iskusstvennyj-podokarpus-treez'],['name' => 'Искусственный Самшит Treez','slug' => 'iskusstvennyj-samshit-treez'],['name' => 'Искусственный Фикус Treez','slug' => 'iskusstvennyj-fikus-treez'],['name' => 'Искусственный Эвкалипт Treez','slug' => 'iskusstvennyj-ehvkalipt-treez'],['name' => 'Каладиум','slug' => 'kaladium'],['name' => 'Каламондин (Цитрофортунелла)','slug' => 'kalamondin-citrofortunella'],['name' => 'Кашпо Lechuza','slug' => 'kashpo-lechuza'],['name' => 'Кашпо Lechuza BACINO Cottage','slug' => 'kashpo-lechuza-bacino-cottage'],['name' => 'Кашпо Lechuza BALCONERA Color','slug' => 'kashpo-lechuza-balconera-color'],['name' => 'Кашпо Lechuza BALCONERA Cottage','slug' => 'kashpo-lechuza-balconera-cottage'],['name' => 'Кашпо Lechuza BALCONERA Stone','slug' => 'kashpo-lechuza-balconera-stone'],['name' => 'Кашпо Lechuza BOLA Color','slug' => 'kashpo-lechuza-bola-color'],['name' => 'Кашпо Lechuza CANTO Premium','slug' => 'kashpo-lechuza-canto-premium'],['name' => 'Кашпо Lechuza CANTO Stone','slug' => 'kashpo-lechuza-canto-stone'],['name' => 'Кашпо Lechuza CARARO Premium','slug' => 'kashpo-lechuza-cararo-premium'],['name' => 'Кашпо Lechuza CILINDRO Color','slug' => 'kashpo-lechuza-cilindro-color'],['name' => 'Кашпо Lechuza Cilindro Cottage','slug' => 'kashpo-lechuza-cilindro-cottage'],['name' => 'Кашпо Lechuza CLASSICO Color','slug' => 'kashpo-lechuza-classico-color'],['name' => 'Кашпо Lechuza CLASSICO Color LS','slug' => 'kashpo-lechuza-classico-color-ls'],['name' => 'Кашпо Lechuza CLASSICO Premium','slug' => 'kashpo-lechuza-classico-premium'],['name' => 'Кашпо Lechuza CLASSICO Premium LS','slug' => 'kashpo-lechuza-classico-premium-ls'],['name' => 'Кашпо Lechuza CUBE Color','slug' => 'kashpo-lechuza-cube-color'],['name' => 'Кашпо Lechuza CUBE Color Triple','slug' => 'kashpo-lechuza-cube-color-triple'],['name' => 'Кашпо Lechuza CUBE Cottage','slug' => 'kashpo-lechuza-cube-cottage'],['name' => 'Кашпо Lechuza CUBE Glossy','slug' => 'kashpo-lechuza-cube-glossy'],['name' => 'Кашпо Lechuza CUBE Glossy Triple','slug' => 'kashpo-lechuza-cube-glossy-triple'],['name' => 'Кашпо Lechuza CUBE Premium','slug' => 'kashpo-lechuza-cube-premium'],['name' => 'Кашпо Lechuza CUBETO Stone','slug' => 'kashpo-lechuza-cubeto-stone'],['name' => 'Кашпо Lechuza CUBICO ALTO Premium','slug' => 'kashpo-lechuza-cubico-alto-premium'],['name' => 'Кашпо Lechuza CUBICO Color','slug' => 'kashpo-lechuza-cubico-color'],['name' => 'Кашпо Lechuza CUBICO Cottage','slug' => 'kashpo-lechuza-cubico-cottage'],['name' => 'Кашпо Lechuza CUBICO Premium','slug' => 'kashpo-lechuza-cubico-premium'],['name' => 'Кашпо Lechuza CURSIVO Premium','slug' => 'kashpo-lechuza-cursivo-premium'],['name' => 'Кашпо Lechuza DELTA Premium','slug' => 'kashpo-lechuza-delta-premium'],['name' => 'Кашпо Lechuza DELTINI Premium','slug' => 'kashpo-lechuza-deltini-premium'],['name' => 'Кашпо Lechuza GREEN WALL HOME KIT Glossy','slug' => 'kashpo-lechuza-green-wall-home-kit-glossy'],['name' => 'Кашпо Lechuza MAXI CUBI Premium','slug' => 'kashpo-lechuza-maxi-cubi-premium'],['name' => 'Кашпо Lechuza MINI CUBI Premium','slug' => 'kashpo-lechuza-mini-cubi-premium'],['name' => 'Кашпо Lechuza MINI DELTINI Premium','slug' => 'kashpo-lechuza-mini-deltini-premium'],['name' => 'Кашпо Lechuza NIDO Cottage','slug' => 'kashpo-lechuza-nido-cottage'],['name' => 'Кашпо Lechuza ORCHIDEA Color','slug' => 'kashpo-lechuza-orchidea-color'],['name' => 'Кашпо Lechuza PILA Color','slug' => 'kashpo-lechuza-pila-color'],['name' => 'Кашпо Lechuza PURO Color','slug' => 'kashpo-lechuza-puro-color'],['name' => 'Кашпо Lechuza RONDO Premium','slug' => 'kashpo-lechuza-rondo-premium'],['name' => 'Кипарисовик','slug' => 'kiparisovik'],['name' => 'Комнатные растения','slug' => 'komnatnye-rasteniya'],['name' => 'Кумкват','slug' => 'kumkvat'],['name' => 'Маммиллярия','slug' => 'mammillyariya'],['name' => 'Мирсина','slug' => 'mirsina'],['name' => 'Ореоцереус','slug' => '%d0%be%d1%80%d0%b5%d0%be%d1%86%d0%b5%d1%80%d0%b5%d1%83%d1%81'],['name' => 'Пилозоцереус','slug' => '%d0%bf%d0%b8%d0%bb%d0%be%d0%b7%d0%be%d1%86%d0%b5%d1%80%d0%b5%d1%83%d1%81'],['name' => 'Селагинелла','slug' => 'selaginella'],['name' => 'Система Автополива Treez','slug' => 'sistema-avtopoliva-treez'],['name' => 'Строманта','slug' => 'stromanta'],['name' => 'Удобрения','slug' => 'udobreniya'],['name' => 'Фикус Циатистипула','slug' => 'fikus-ciatistipula'],['name' => 'Хатиора','slug' => 'hatiora'],['name' => 'Хойя','slug' => 'hojya'],['name' => 'Хомаломена','slug' => '%d1%85%d0%be%d0%bc%d0%b0%d0%bb%d0%be%d0%bc%d0%b5%d0%bd%d0%b0'],['name' => 'Хомаломена','slug' => '%d1%85%d0%be%d0%bc%d0%b0%d0%bb%d0%be%d0%bc%d0%b5%d0%bd%d0%b0-dekorativno-listvennye'],['name' => 'Цереус','slug' => 'cereus'],['name' => 'Скидки','slug' => 'product_of_week'],['name' => 'Орхидеи','slug' => 'orhidei'],['name' => 'Фаленопсис','slug' => 'falenopsis'],['name' => 'Цитрусовые','slug' => 'citrusovye'],['name' => 'Декоративно-лиственные','slug' => 'dekorativno-listvennye'],['name' => 'Аукуба','slug' => 'aukuba'],['name' => 'Оливковое дерево','slug' => 'olivkovoe-derevo'],['name' => 'Радермахера','slug' => 'radermahera'],['name' => 'Хлорофитум','slug' => 'hlorofitum'],['name' => 'Эвкалипт','slug' => 'ehvkalipt'],['name' => 'Аглаонема','slug' => 'aglaonema'],['name' => 'Алоказия','slug' => 'alokaziya'],['name' => 'Араукария','slug' => 'araukariya'],['name' => 'Ардизия','slug' => 'ardiziya'],['name' => 'Бересклет','slug' => 'beresklet'],['name' => 'Диффенбахия','slug' => 'diffenbahiya'],['name' => 'Замиокулькас','slug' => 'zamiokulkas'],['name' => 'Ель','slug' => 'el'],['name' => 'Калатея','slug' => 'kalateya'],['name' => 'Кипарис (Купрессус)','slug' => 'kupressus-kiparis'],['name' => 'Кротон (Кодиеум)','slug' => 'kroton-kodieum'],['name' => 'Кофе','slug' => 'kofe'],['name' => 'Ктенанта','slug' => 'ktenanta'],['name' => 'Лавр','slug' => 'lavr'],['name' => 'Мирт','slug' => 'mirt'],['name' => 'Пеперомия','slug' => 'peperomiya'],['name' => 'Пилея','slug' => 'pileya'],['name' => 'Полисциас','slug' => 'poliscias'],['name' => 'Самшит (Буксус)','slug' => 'samshit-buksus'],['name' => 'Сансевиерия','slug' => 'sansevieriya'],['name' => 'Стрелиция','slug' => 'streliciya'],['name' => 'Фатсия','slug' => 'fatsiya'],['name' => 'Шеффлера','slug' => 'shefflera'],['name' => 'Монстера','slug' => 'monstera'],['name' => 'Кактусы','slug' => 'kaktusy'],['name' => 'Опунция','slug' => 'opunciya'],['name' => 'Пародия (Эриокактус)','slug' => 'parodiya-ehriokaktus'],['name' => 'Рипсалис','slug' => 'ripsalis'],['name' => 'Лианы','slug' => 'lianas'],['name' => 'Сциндапсус','slug' => 'scindapsus'],['name' => 'Маранта','slug' => 'maranta'],['name' => 'Сингониум','slug' => 'singonium'],['name' => 'Филодендрон','slug' => 'filodendron'],['name' => 'Хедера','slug' => 'hedera'],['name' => 'Эпипремнум','slug' => 'ehpipremnum'],['name' => 'Пальмы','slug' => 'palms'],['name' => 'Хамеропс','slug' => 'hamerops'],['name' => 'Банановая пальма','slug' => 'bananovaya-palma'],['name' => 'Вашингтония','slug' => 'vashingtoniya'],['name' => 'Дипсис','slug' => 'dipsis'],['name' => 'Драцена','slug' => 'dracena'],['name' => 'Кариота','slug' => 'kariota'],['name' => 'Кордилина','slug' => 'kordilina'],['name' => 'Ливистона','slug' => 'livistona'],['name' => 'Нолина','slug' => 'nolina'],['name' => 'Пахира','slug' => 'pahira'],['name' => 'Финиковая пальма','slug' => 'finikovaya-palma'],['name' => 'Хамедорея','slug' => 'hamedoreya'],['name' => 'Ховея (Кентия)','slug' => 'hovei'],['name' => 'Цикас','slug' => 'cikas'],['name' => 'Юкка','slug' => 'yukka'],['name' => 'Папоротники','slug' => 'paporotniki'],['name' => 'Адиантум','slug' => 'adiantum'],['name' => 'Аспарагус','slug' => 'asparagus'],['name' => 'Асплениум','slug' => 'asplenium'],['name' => 'Нефролепис','slug' => 'nefrolepis'],['name' => 'Румора','slug' => 'rumora'],['name' => 'Флебодиум','slug' => 'flebodium'],['name' => 'Суккуленты','slug' => 'succulent'],['name' => 'Очиток (Седум)','slug' => 'ochitok-sedum'],['name' => 'Эуфорбия','slug' => 'ehuforbiya'],['name' => 'Алоэ','slug' => 'aloeh'],['name' => 'Гастерия','slug' => 'gasteriya'],['name' => 'Каланхоэ','slug' => 'kalanhoeh'],['name' => 'Крассула','slug' => 'krassula'],['name' => 'Сенецио (Крестовник)','slug' => 'senecio-krestovnik'],['name' => 'Хавортия','slug' => 'havortiya'],['name' => 'Эхеверия','slug' => 'ehkheveriya'],['name' => 'Фикусы','slug' => 'fikusy'],['name' => 'Фикус Пумила','slug' => 'fikus-pumila'],['name' => 'Фикус Бенгальский Одри','slug' => 'fikus-bengalskij-odri'],['name' => 'Фикус Бенджамина','slug' => 'fikus-bendzhamina'],['name' => 'Фикус Биннендийка','slug' => 'fikus-binnendijka'],['name' => 'Фикус Лирата','slug' => 'fikus-lirata'],['name' => 'Фикус Микрокарпа Гинсенг','slug' => 'fikus-mikrokarpa-ginseng'],['name' => 'Фикус Мокламе','slug' => 'fikus-mikrokarpa-moklame'],['name' => 'Фикус Эластика','slug' => 'fikus-ehlastika'],['name' => 'Цветущие','slug' => 'dekorativno-cvetushchie'],['name' => 'Ананас','slug' => 'ananas'],['name' => 'Антуриум','slug' => 'anturium'],['name' => 'Бромелиевые','slug' => 'bromelievye'],['name' => 'Калла','slug' => 'kalla'],['name' => 'Кливия','slug' => 'kliviya'],['name' => 'Спатифиллум','slug' => 'spatifillum'],['name' => 'Горшки и кашпо','slug' => 'gorshki_i_kashpo'],['name' => 'Большие (От 26см)','slug' => 'bolshie-ot-26sm'],['name' => 'Кашпо Treez','slug' => 'kashpo-treez'],['name' => 'Treez Effectory','slug' => 'treez-effectory'],['name' => 'Treez Effectory Beton','slug' => 'treez-effectory-beton'],['name' => 'Treez Ergo','slug' => 'treez-ergo'],['name' => 'Маленькие (До 17см)','slug' => 'malenkie-do-17sm'],['name' => 'Средние (От 18 до 25см)','slug' => 'srednie-ot-18-do-25sm'],['name' => 'Все для ухода','slug' => 'ukhod'],['name' => 'Uncategorized','slug' => 'uncategorized'],);

	print_r ($cats_array[0]['name']);
}

//add_action( 'wp_footer', 'plnt_check_page' );
//add_action( 'wp_footer', 'plnt_dev_functions' );
//add_action( 'wp_footer', 'plnt_get_cats_data' );


