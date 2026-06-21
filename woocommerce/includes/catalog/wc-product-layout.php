<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
#Card in Catalog design
--------------------------------------------------------------*/


remove_action( 'woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price', 10 );
remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close', 5);

/* фото */
remove_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title','plnt_catalog_gallery', 10);
add_action('woocommerce_before_product_loop_end','plnt_img_gallery_swiper_init', 10);
add_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_link_close', 20);

/* content */
plnt_add_wrapper('product__content-wrap','woocommerce_before_shop_loop_item_title', 24, 'woocommerce_after_shop_loop_item_title', 40 );

/* цена */
plnt_add_wrapper('product__price-wrap','woocommerce_before_shop_loop_item_title', 25, 'woocommerce_before_shop_loop_item_title', 29 );
add_action( 'woocommerce_before_shop_loop_item_title','woocommerce_template_loop_price', 26 );
add_action('woocommerce_before_shop_loop_item_title','plnt_sale_badge', 27);

/* заголовок */
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_open', 10);
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_close', 15);

/* метки */
add_action('woocommerce_after_shop_loop_item_title', 'plnt_get_product_tags', 30);

/* атрибуты */
add_action( 'woocommerce_after_shop_loop_item_title', function () {
    plnt_product_attributes( 'catalog__product-attributes' );
}, 31 );


/* кнопки в корзину */
plnt_add_wrapper('product__add-to-cart-wrap','woocommerce_after_shop_loop_item',5, 'woocommerce_after_shop_loop_item', 15 );
add_action('woocommerce_after_shop_loop_item', 'plnt_get_buy_one_click_btn', 6);



//название товара - меняем тег h2 на h3 + schema.org

remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'soChangeProductsTitle', 10 );
function soChangeProductsTitle() {
  if ( is_shop() || is_product_category() || is_product_tag() || is_tax() ) {
  $schema_data = 'itemprop="name"';
  } else {
      $schema_data = '';
  }
  echo '<h3 ' . $schema_data . ' class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h3>';
}

//оформление карточки товара в каталоге

function plnt_catalog_gallery() {
	if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_page('search-results')) {
		global $product;
		$image = $product->get_image('large', array('itemprop'=>'image'));	//schema.org
		$attachment_ids = $product->get_gallery_image_ids();
		echo '
		<div class="product__image-slider-wrap swiper">
			<div class="swiper-wrapper" >';
				echo $image;
				foreach( $attachment_ids as $attachment_id ) {
					// $params = [ 'class' => "attachment-woocommerce_thumbnail" ];
					echo wp_get_attachment_image( $attachment_id, 'large');
				};
			echo '
			</div>
			<div class="swiper-pagination"></div>
		</div>';
	} else {
		woocommerce_template_loop_product_thumbnail();
	}
};

add_filter( 'single_product_archive_thumbnail_size', 'plnt_loop_product_thumbnail_size' );

function plnt_loop_product_thumbnail_size() {
	return 'large';
}

function plnt_img_gallery_swiper_init() {
	?>
	<script>
		jQuery(function($){
			swiper_catalog_card_imgs_init();
		})
	</script>
	<?php	
}

// добавляем класс для swiper к изображениям товара 

add_filter( 'wp_get_attachment_image_attributes', 'AddThumbnailClass', 20, 2 );
function AddThumbnailClass( $atts, $attachment ) {
	if (!is_product()) {
		$atts['class'] .= " swiper-slide"; 
	}
	return $atts;
}


// // меняем текст кнопки в корзину, если товар не в наличии

//add_filter('woocommerce_product_add_to_cart_text','plnt_change_add_to_cart_text',10, 2);

function plnt_change_add_to_cart_text($text,$product) {
	if ($product->is_in_stock()) {
		return $text;
	} else {
		$text = __( 'Заказать', 'woocommerce' );
		return $text;
	}
}

// // добавляем класс для кнопки в корзину, если товар не в наличии
add_filter( 'woocommerce_loop_add_to_cart_args', 'plnt_woocommerce_loop_add_to_cart_args', 10, 2 );

function plnt_woocommerce_loop_add_to_cart_args( $args, $product ) {

    // Дополнительные классы для товаров не в наличии
    if ( ! $product->is_in_stock() ) {
        $args['class'] .= ' product_out_of_stock button--white';
    }

    return $args;
}



// // короткое описание
// add_action('woocommerce_shop_loop_item_title','woocommerce_template_single_excerpt', 20);




function plnt_get_product_tags() {
  global $product;
  global $pet_tag_id;
  global $neprihotliv_tag_id;

  if ( ! $product ) {
    return;
  }

  $allowed_tags = [
      $pet_tag_id => [
          'class' => 'petfriendly',
      ],
      $neprihotliv_tag_id => [
          'class' => 'neprikhotliv',
      ],
  ];
  $tags = wc_get_product_term_ids( $product->get_id(), 'product_tag' );
  if ( empty( $tags ) ) {
    return;
  }

  $visible_tags = array_intersect( $tags, array_keys( $allowed_tags ) );

  if ( empty( $visible_tags ) ) {
    return;
  }

  echo '<div class=catalog__tags>';
  foreach($tags as $tag) {
    if (!isset($allowed_tags[$tag])) {
        continue;
    }

    echo '<a class=catalog__tag-link href="'.get_tag_link(get_term($tag)->term_taxonomy_id).'">
      <span class= "catalog__tag icon icon--pre icon--tags icon--'.esc_attr($allowed_tags[$tag]['class']).'">'.esc_html(get_term($tag)->name).'</span>
    </a>';
  
  }
  echo '</div>';
}

// вывод статуса в наличии

add_action('woocommerce_after_shop_loop_item', 'plnt_check_stock_status', 30);

// // добавляем класс для swiper для каталог гридов

add_filter('post_class', 'plnt_add_class_loop_item_swiper', 10, 3);
function plnt_add_class_loop_item_swiper($clasess){
	if(is_product() || is_front_page() || is_cart() || is_page('wishlist') || is_search() || is_page('search-results')) {
		$clasess[] = 'swiper-slide';
	}
	//get_pr($clasess, false);
	return $clasess;
}


//вывод данных для Schema.org 
add_action('woocommerce_after_shop_loop_item', 'plnt_get_catalog_schema_data', 40);

function plnt_get_catalog_schema_data() {
    if ( is_shop() || is_product_category() || is_product_tag() || is_tax() ) {
        global $product;
        if ($product->is_virtual()) {
          return;
        }
        global $plants_cat_id;
        $parentCatId = check_category ($product);
        $product_id = $product->get_id();
        $price = number_format($product->get_price(), 2, '.', '');
        ?>
            <meta itemprop="description" content="<?php echo strip_tags($product->get_description())?>">
            <link itemprop="url" href="<?php echo get_permalink( $product_id )?>">
            <meta itemprop="price" content="<?php echo $price?>">
            <meta itemprop="priceCurrency" content="RUB">
        <?php
        $availability = plnt_get_availability_text($product);
        ?><link itemprop="availability" href="http://schema.org/<?php echo $availability?>"><?php
    }
}
