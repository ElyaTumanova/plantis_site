<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//определяем переменные
// add_action('woocommerce_before_single_product','plnt_set_constants',5);
function plnt_set_constants() {
  global $product;
  global $parentCatId;
  global $isTreez;
  global $isLechuza;
  if($product) {
    $parentCatId = check_category ($product);
    $isTreez = check_is_treez($product);
    $isLechuza = check_is_lechuza($product);
  }
}

/* структура карточки */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products', 20);
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action('woocommerce_before_single_product','woocommerce_output_all_notices', 10); /* убрать уведомления woocommerce*/



add_action( 'woocommerce_before_main_content', 'plnt_product_artikul', 30 );
add_action( 'woocommerce_before_single_product_summary', 'plnt_output_actions_wrap', 5 );
add_action('woocommerce_before_single_product_summary','plnt_card_grid_start',6);
add_action('woocommerce_after_single_product_summary','plnt_card_grid_end',10);

plnt_add_wrapper('card__image-wrap','woocommerce_before_single_product_summary', 10, 'woocommerce_before_single_product_summary',20);
add_action('woocommerce_before_single_product_summary','plnt_get_product_image',15);

plnt_add_wrapper('card__summary-wrap','woocommerce_before_single_product_summary', 30, 'woocommerce_after_single_product_summary',5);

plnt_add_wrapper('card__title-wrap','woocommerce_single_product_summary', 1, 'woocommerce_single_product_summary',35);
add_action('woocommerce_single_product_summary', 'plnt_check_stock_status', 30);
add_action('woocommerce_single_product_summary', 'plnt_price_wrap', 40);
add_action('woocommerce_single_product_summary', 'plnt_banners_wrap', 50);
add_action('woocommerce_single_product_summary', 'plnt_characteristics_wrap', 60);
add_action('woocommerce_single_product_summary', 'plnt_peresadka_banner', 70);


// plnt_add_section('card__bottom section','woocommerce_after_single_product_summary', 20, 'woocommerce_after_single_product_summary',999);

add_action('woocommerce_after_single_product_summary','plnt_get_upsells', 30);

add_action('woocommerce_after_single_product_summary','plnt_get_product_data_tabs', 40);

add_action('woocommerce_after_single_product_summary','plnt_get_cross_sells', 50);

add_action('woocommerce_after_single_product_summary','plnt_card_ukhod_loop',60);

//обертки для card grid + schema.org

function plnt_card_grid_start () {
    global $product;
    global $plants_cat_id;

    $parentCatId = plnt_get_parent_cat_id();
    $isTreez     = plnt_is_treez_product();
    $isLechuza   = plnt_is_lechuza_product();
    
    $schemaOrgAttr = 'itemscope itemtype="https://schema.org/Product"';

    $classes = array( 'card__grid', 'section' );

    if ( $parentCatId === $plants_cat_id ) {

      if ( $product->get_stock_status() === 'onbackorder' && $product->backorders_allowed() ) {
        $classes[] = 'card__grid_backorder';
      }

      if ( $product->get_stock_status() === 'outofstock' ) {
        $classes[] = 'card__grid_outofstock';
      }

    } else {

      $classes[] = 'card__grid_not-plant';

      if ( $isTreez || $isLechuza ) {
        $classes[] = 'card__grid_treez';
      }
    }

    ?>
    <section
      class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
      <?php echo $schemaOrgAttr; ?>
    >
    <?php
};

function plnt_card_grid_end() {
    global $product;

    if ( ! $product ) {
        return;
    }

    $product_id  = $product->get_id();
    $idCats      = $product->get_category_ids();
    $description = wp_strip_all_tags( $product->get_description() );
    $brand       = plnt_get_brand_text( $idCats );
    ?>

    <link itemprop="url" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">

    <?php if ( $description ) : ?>
        <meta itemprop="description" content="<?php echo esc_attr( $description ); ?>">
    <?php endif; ?>

    <?php if ( $brand ) : ?>
        <div itemprop="brand" itemscope itemtype="https://schema.org/Brand">
            <meta itemprop="name" content="<?php echo esc_attr( $brand ); ?>">
        </div>
    <?php endif; ?>

    </section>
    <?php
}


function plnt_output_actions_wrap() {
  global $product;
  $image_id = $product->get_image_id();
	?>
	<div class="card__actions-wrap">
		<div class="card__actions-wrap-inner">
      <div class="card__actions-wrap-thumb" aria-hidden="true">
        <?php   
          if ( $image_id ) {
            echo wp_get_attachment_image(
              $image_id,
              'thumbnail',
              false,
              [
                'class' => 'card__actions-image',
                'alt'   => esc_attr( $product->get_name() ),
              ]
            );
          }
        ?>
      </div>
      <div class="card__actions-info">
        <p class="card__actions-name">
          <?php echo esc_html( $product->get_name() ); ?>
        </p>
        <div class="card__actions-price">
          <?php 
            woocommerce_template_single_price();
            plnt_sale_badge();
          ?>
        </div>
      </div>
      <div class="card__actions-buttons" data-js-actions-buttons></div>
    </div>
	</div>
	<?php
}
/* Фото товара, бейдж распродажа */

  //выводим фото товара
  function plnt_get_product_image () {
    // woocommerce_show_product_images();

    global $product;

    if ( ! $product ) {
      return;
    }

    $main_image_id = $product->get_image_id();
    $gallery_ids   = $product->get_gallery_image_ids();

    $image_ids = array_filter( array_merge( array( $main_image_id ), $gallery_ids ) );

    if ( empty( $image_ids ) ) {
      return;
    }
    plnt_product_artikul_schema();
    plnt_card_wishlist_btn();
    ?>

    <div class="product-gallery" data-js-product-gallery>
      <div class="swiper product-gallery__thumbs">
        <div class="swiper-wrapper">
          <?php foreach ( $image_ids as $image_id ) : ?>
            <div class="swiper-slide product-gallery__thumb">
              <?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="swiper product-gallery__main">
        <div class="swiper-wrapper">
          <?php foreach ( $image_ids as $index => $image_id ) : ?>
            <div class="swiper-slide product-gallery__slide">
              <?php
                echo wp_get_attachment_image(
                  $image_id,
                  'large',
                  false,
                  [
                    'loading' => $index === 0 ? false : 'lazy',
                    'fetchpriority' => $index === 0 ? 'high' : false,
                    'decoding' => 'async',
                  ]
                );
              ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <?php
  };
  
  // добавляем к изображению товара разметку schema.org
  add_filter( 'woocommerce_gallery_image_html_attachment_image_params', 'plnt_image_params', 10, 4 );

  function plnt_image_params( $image_attributes, $attachment_id, $image_size, $main_image ){
      $image_attributes['itemprop'] = 'image';
    return $image_attributes;
  }


  //слайдер фото товара
  add_filter( 'woocommerce_single_product_carousel_options', 'plnt_product_gallery' );
  
  function plnt_product_gallery( $options ) {
  
    $options[ 'directionNav' ] = true;
    $options[ 'controlNav' ] = true;
    $options[ 'animationLoop' ] = false;
    return $options;
  };

  //размер изображения
  add_filter( 'woocommerce_gallery_image_size', function( $size ) {
    return 'large'; // или 'full', или свой кастомный size
  } );
/*  
*/

/* Цена и кнопка в корзину, кнопка в избранное + schema.org */
  add_filter( 'woocommerce_cart_redirect_after_error', '__return_false' );  //остановка перезагрузки страницы (перадресации) при ошибке добаления товара в корзину
  add_filter( 'wc_add_to_cart_message_html', '__return_false' ); //Удалить сообщение «Товар добавлен в корзину..»



  function for_dev() {
      global $product;

      $parentCatId = plnt_get_parent_cat_id();
      $isTreez     = plnt_is_treez_product();

      $idCats = $product->get_category_ids();
      //print_r($idCats);
      echo 'stock qty '.$product->get_stock_quantity();
      echo '<br>';
      echo 'stock status '.$product->get_stock_status();
      echo '<br>';
      echo 'get_manage_stock '.$product->get_manage_stock();
      echo '<br>';
      echo 'backorders_allowed '.$product->backorders_allowed();
      echo '<br>';
      echo 'price '.$product->get_price();
      echo '<br>';
      echo 'parent cat '.$parentCatId;
      echo '<br>';
      $term = get_term_by( 'id', $parentCatId, 'product_cat' );
      echo $term->name;    
      echo '<br>';
      //print_r($product);
      //$isTreez = check_is_treez($product);
      echo 'is Treez '.$isTreez;
      echo '<br>';
  }

  function plnt_price_wrap() {
    global $product;
    $price = number_format($product->get_price(), 2, '.', ''); 
    $availability = plnt_get_availability_text($product);
    ?>
    <div class="card__price-wrap">
        <div class = "card__add-to-cart-wrap" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <?php
            //echo for_dev();
            echo ('<div class="product__price-wrap">');
              woocommerce_template_single_price();
              plnt_sale_badge();
            echo('</div>');
            echo ('<div class="card__btns-wrap">');
              plnt_get_add_to_card();
              plnt_card_wishlist_btn();
            echo('</div>');
           ?>
            <link itemprop="availability" href="http://schema.org/<?php echo $availability?>">
            <meta itemprop="price" content="<?php echo $price?>">
            <meta itemprop="priceCurrency" content="RUB">
            <meta itemprop="seller" content="Plantis">
        </div>
        <?php
        // peresadka_init
        //plnt_get_peresadka_add_to_cart();
        ?>
    </div>
    <?php
  };

  function plnt_banners_wrap() {
    get_template_part('template-parts/card-banners'); // info cards for card
  }
 
  // peresadka
  function plnt_get_peresadka_add_to_cart() {
      global $product; 
      $product_id = $product->get_id();

      get_template_part('template-parts/products/products-peresadka',null, 
          array( // массив с параметрами
              'product_id' => $product_id
          ));

  };

  function plnt_get_add_to_card() {
    global $product;
    if(is_product()) {
      $quantity =  $product->get_stock_quantity();
      $backorders_allowed = $product->backorders_allowed()
      ?><div class="add-to-cart-wrap"> <?php
      /* quantity */
      if ($quantity > 1 && !$backorders_allowed) {
        woocommerce_quantity_input(array(
            'min_value' => 1,
            'max_value'    => $quantity,    // почему-то пришлось передавать заново, проверить на PLANTIS #TODO
        ),);           // добавили поля ввода. чтобы кнопка "в корзину" работала я полем ввода и кнопками +- см скрипт quantity-buttons.js
      }
      if ($backorders_allowed || !$product->get_manage_stock()) {
        woocommerce_quantity_input(array(
            'min_value' => 1,
        ),);           // добавили поля ввода. чтобы кнопка "в корзину" работала я полем ввода и кнопками +- см скрипт quantity-buttons.js
      }
      /* buttons */
      if ($product->get_stock_status() ==='instock' || $backorders_allowed) {
        plnt_buy_one_click_btn();
        woocommerce_template_loop_add_to_cart(); //заменили обычную не яакс кнопку на аякс кнопку из каталога
      } else {
        plnt_outofstock_btn();
      }
      
      ?></div>
      <?php
    } 
  };

  function plnt_outofstock_btn() {
    ?>
    <div class="card__outofstock-btn-wrap">
        <button class="button card__preorder-btn button--green page-popup-open-btn">Оформить прездаказ</button>
    </div>
    <?php
  }
/*  
*/
/* Табы */

  function plnt_get_product_data_tabs() {
    echo ('<section class="section">');
    woocommerce_output_product_data_tabs();
    echo('</section>');
  }

  add_filter( 'woocommerce_product_tabs', 'plnt_edit_product_tabs', 25 );

  function plnt_edit_product_tabs( $tabs ) {

      unset( $tabs['additional_information'] );

      if ( empty( get_post()->post_content ) ) {
          unset( $tabs['description'] );
      }

      if ( isset( $tabs['description'] ) ) {
          $tabs['description']['priority'] = 10;
      }

      $tabs['delivery'] = [
          'title'    => 'Доставка и самовывоз',
          'priority' => 20,
          'callback' => 'plnt_delivery_tab_content',
      ];

      return $tabs;
  }

  function plnt_delivery_tab_content() {
    get_template_part('template-parts/delivery-info'); // delivery info for card
  }

/*  
*/
/* Характиристики */
  function plnt_characteristics_wrap() {
    global $plants_cat_id;

    $parentCatId = plnt_get_parent_cat_id();

    if( $parentCatId === $plants_cat_id ) {
        $title = 'Уход и характеристики';
    } else {
      $title = 'Xарактеристики';
    }

    echo ('<div class="card__characteristics-wrap">');
      echo ('<h2 class="h5">'.$title.'</h2>');
      plnt_get_product_tags();
      plnt_product_attributes( 'card__product-attributes' );
    echo ('</div>');
  }
/*  
*/
/* Баннер пересадка */
  function plnt_peresadka_banner() {
    $isTreez   = plnt_is_treez_product();
    $isLechuza = plnt_is_lechuza_product();

    if ( ! $isTreez && ! $isLechuza ) : ?>
      <div class="card__banner card__banner--peresadka" style="background-image:linear-gradient(270deg, rgba(2, 21, 14, 0.24) 0%, rgba(2, 21, 14, 0.48) 100%), url(<?php echo esc_url( get_template_directory_uri() . '/images/frontend/peresadka-bg.jpg' ); ?>)">
        <p>
          При покупке
          <a href="<?php echo esc_url( get_site_url() . '/product-category/gorshki_i_kashpo/' ); ?>" target="_blank" rel="noopener">
            горшка 
          </a> —
          <br/>пересадка в подарок!*
        </p>

        <cite>*кроме кашпо Treez и Lechuza диаметром больше 26 см</cite>
      </div>
    <?php endif;
  }
/*  
*/
/* Артикул */
  function plnt_product_artikul_schema() {
    if ( is_product() ) {
      global $product;
      $sku = $product->get_sku();
        
      if( $sku ) { // если заполнен, то выводим
        echo '<span class="product__artikul">Арт. <span itemprop="sku">' . $sku . '</span> </span>';
      }
    }

  };
  function plnt_product_artikul() {
    if ( is_product() ) {
      global $product;
      $sku = $product->get_sku();
        
      if( $sku ) { // если заполнен, то выводим
        echo '<span class="product__artikul">Арт.' . $sku . '</span>';
      }
    }

  };
/* 
 */
/* Products loops */
  //upsells & cross sells

  function plnt_get_upsells(){
    echo ('<section class="section">');
    $heading = plnt_upsells_heading();
    global $product;

    $upsells_ids = $product->get_upsell_ids();
    if (!empty ($upsells_ids) && count($upsells_ids)>0) {

      echo('<h2 class="h2">'.$heading.' </h2>');

      get_template_part( 'template-parts/products/product-slider', null, [
        'queryArgs' => [
          'posts_per_page' => 8,
          'post__in' => $upsells_ids,
        ],
        'isSwiperOver' => true,
      ]);
    }
    echo('</section>');
  }

  function plnt_upsells_heading() {

    global $plants_cat_id;
    global $gorshki_cat_id;
    global $treez_cat_id;
    global $lechuza_cat_id;

    $parentCatId = plnt_get_parent_cat_id();

    switch ( $parentCatId ) {

      case $plants_cat_id:
          return 'Этому растению подойдет';

      case $gorshki_cat_id:
      case $lechuza_cat_id:
          return 'Другие цвета';

      case $treez_cat_id:
          return 'Другие цвета и сопутствующие';

      default:
          return 'Вас также заинтересует';
    }
  }
          

 function plnt_get_cross_sells() {
  global $product;

  if ( ! $product ) {
    return;
  }

  $crosssell_ids = $product->get_cross_sell_ids();

  if ( empty( $crosssell_ids ) || ! is_array( $crosssell_ids ) ) {
    return;
  }

  $query_args = [
    'posts_per_page' => 8,
    'post__in'       => $crosssell_ids,
    'tax_query'      => [
      [
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => 'peresadka',
        'operator' => 'NOT IN',
      ],
    ],
  ];

  $check_query = new WP_Query( wp_parse_args( $query_args, [
    'post_type' => 'product',
  ] ) );

  if ( ! $check_query->have_posts() ) {
    wp_reset_postdata();
    return;
  }

  wp_reset_postdata();

  echo '<section class="section">';
  echo '<h2 class="h2">Похожие растения</h2>';

  get_template_part( 'template-parts/products/product-slider', null, [
    'queryArgs'    => $query_args,
    'isSwiperOver' => true,
  ] );

  echo '</section>';
}


  // товары для ухода

  function plnt_card_ukhod_loop() {
    global $product;
    echo('<h2 class="h2">Товары для ухода за растениями</h2>');

    get_template_part( 'template-parts/products/product-slider', null, [
      'queryArgs' => [
        'posts_per_page' => 8,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'ukhod'
            )
        ),
      ],
      'isSwiperOver' => true,
    ]);
    echo('</section>');
  }
/*  
*/