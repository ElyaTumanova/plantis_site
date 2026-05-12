<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// оформление каталога целиком

// // убираем лишнее
  remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description',10);
  remove_action('woocommerce_before_shop_loop','woocommerce_result_count', 20);
  remove_action('woocommerce_before_shop_loop','woocommerce_output_all_notices', 10);
  remove_action('woocommerce_sidebar','woocommerce_get_sidebar', 10);


// // структура каталога - добавлеям обертки

  plnt_add_wrapper('catalog__header', 'woocommerce_before_main_content', 15, 'woocommerce_before_shop_loop', 5);
  plnt_add_wrapper('catalog__header-inner', 'woocommerce_before_main_content', 21, 'woocommerce_before_shop_loop', 4);
  add_action('woocommerce_before_shop_loop','plnt_catalog_header_image', 3);

  plnt_add_wrapper('catalog__grid', 'woocommerce_before_shop_loop', 15, 'woocommerce_after_shop_loop', 21);
  plnt_add_wrapper('catalog__sidebar modal-mob', 'woocommerce_before_shop_loop', 20, 'woocommerce_before_shop_loop', 22);
  add_action('woocommerce_before_shop_loop','plnt_catalog_sidebar', 21);
  plnt_add_wrapper('catalog__top', 'woocommerce_before_shop_loop', 25, 'woocommerce_before_shop_loop', 35);
  add_action('woocommerce_before_shop_loop','plnt_woocommerce_total_count', 32);
  add_action('woocommerce_before_shop_loop','plnt_catalog_grid_columns', 34);
  plnt_add_wrapper('catalog__products-wrap', 'woocommerce_before_shop_loop', 40, 'woocommerce_after_shop_loop', 20);
  
  add_action('woocommerce_after_shop_loop','woocommerce_taxonomy_archive_description',25);
  add_action('woocommerce_after_shop_loop','plnt_get_advantages',25);

  //header catalog

  function plnt_catalog_header_image() {
    ?>
    <div class="catalog__header-image-wrap darken">
      <img
        class="catalog__header-image"
        src="https://plantis-shop.ru/wp-content/uploads/2025/07/aglaonema-silver-bej-17-50-1-800x800.webp"
        alt=""
        whidth="800"
        height="800">
    </div>
    <?
  }

  // // переключатель сетки
  function plnt_catalog_grid_columns () {
    ?>
      <div class="catalog__grid-buttons">
      <button class="catalog__grid-button button" id="catalog__btn-grid" disabled>
        <?php echo plnt_icon('btn-grid');?>
      </button>
      <button class="catalog__grid-button button" id="catalog__btn-rows" >
        <?php echo plnt_icon('btn-rows');?>
      </button>
      </div>
      <?php 	
  }

  /** общее количество товаров в текущем WooCommerce loop.*/
  function plnt_product_count_text( $count ) {
    $forms = array( 'товар', 'товара', 'товаров' );

    $count_abs = abs( $count ) % 100;
    $count_mod = $count_abs % 10;

    if ( $count_abs > 10 && $count_abs < 20 ) {
      $form = $forms[2];
    } elseif ( $count_mod > 1 && $count_mod < 5 ) {
      $form = $forms[1];
    } elseif ( $count_mod === 1 ) {
      $form = $forms[0];
    } else {
      $form = $forms[2];
    }

    return sprintf(
      '%s %s',
      number_format_i18n( $count ),
      $form
    );
  }
  function plnt_woocommerce_total_count() {
    if ( ! woocommerce_products_will_display() ) {
      return;
    }

    $total = wc_get_loop_prop( 'total' );

    if ( ! $total ) {
      return;
    }

    echo '<span class="woocommerce-result-count" aria-hidden="false">';
    echo esc_html( plnt_product_count_text( $total ) );
    echo '</span>';
  }

// // вывод фильтров в сайд баре  #filters #berocket
  
  function plnt_catalog_sidebar() {
    global $plants_treez_cat_id;
    // // #filters ID's
    global $filter_podborki_id;
    global $filter_plant_type_id;
    global $filter_plant_name_id;
    global $filter_in_stock_id;
    global $filter_price_id;
    global $filter_height_id;
    global $filter_poliv_id;
    global $filter_svet_id;
    global $filter_vlaga_id;
    global $filter_diametr_id;
    global $filter_color_id;
    global $filter_forma_id;
    global $filter_materilal_id;
    global $filter_volume_id;
    global $filter_gift_id; 
    global $filter_razmer_id;
    global $filter_razmer_kashpo_id;
    $close_icon = carbon_get_theme_option('close_icon')
    ?>
      <!-- <div class="catalog__sidebar modal-mob"> -->
        <h2 class="catalog__sidebar-filters-heading">Фильтры</h2>
        <div class="modal-mob__close catalog-sidebar__close button"><?php echo $close_icon ?></div>
        <aside class="catalog__sidebar-filters">
          <div class="catalog__filter-metki">
            <?php echo do_shortcode('[br_filter_single filter_id='.$filter_podborki_id.']') //Подборки //56536 //10989?>  
          </div>
          <?php 
          echo do_shortcode('[br_filter_single filter_id='.$filter_price_id.']'); // цена  \\56529 //6055
          if (!is_shop() && !is_page('search-results')) {
            echo do_shortcode('[br_filter_single filter_id='.$filter_plant_type_id.']');
            echo do_shortcode('[br_filter_single filter_id='.$filter_plant_name_id.']');
            echo do_shortcode('[br_filter_single filter_id='.$filter_height_id.']'); // высота //56530 //6056
            echo do_shortcode('[br_filter_single filter_id='.$filter_poliv_id.']'); //	полив //56533 //6109
            echo do_shortcode('[br_filter_single filter_id='.$filter_svet_id.']'); // освещение //56538 //11115
            echo do_shortcode('[br_filter_single filter_id='.$filter_vlaga_id.']'); // влажность //56539 //11116
            echo do_shortcode('[br_filter_single filter_id='.$filter_diametr_id.']'); // диаметр горшка //56540 //11117
            //echo do_shortcode('[br_filter_single filter_id='.$filter_razmer_kashpo_id.']'); // диаметр кашпо Treez //56545 //12017
            echo do_shortcode('[br_filter_single filter_id='.$filter_color_id.']'); // цвет //56532 //6108
            echo do_shortcode('[br_filter_single filter_id='.$filter_forma_id.']'); // форма //56541 //12013
            if(!(is_product_category($plants_treez_cat_id) || 
              term_is_ancestor_of( $plants_treez_cat_id, get_queried_object_id(), 'product_cat' ))) {
                echo do_shortcode('[br_filter_single filter_id='.$filter_materilal_id.']'); // материал //56543 //12015
            }
            //echo do_shortcode('[br_filter_single filter_id='.$filter_razmer_id.']'); // размер для растений Treez
            //echo do_shortcode('[br_filter_single filter_id='.$filter_volume_id.']'); // Объем //56544 //12016
            echo do_shortcode('[br_filter_single filter_id='.$filter_gift_id.']'); // в подарок //56535 //10988
          }
          ?>
        </aside>
      <!-- </div> -->
      <?php 
  };

// описание категории и преимущества в каталоге

function plnt_get_advantages() {
	get_template_part( 'template-parts/advantages' );			
};
