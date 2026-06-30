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

  add_action('woocommerce_archive_description','plnt_catalog_header_image', 5);

  plnt_add_section('catalog__grid section', 'woocommerce_before_shop_loop', 15, 'woocommerce_after_shop_loop', 21);
  plnt_add_wrapper('catalog__sidebar catalog-filters', 'woocommerce_before_shop_loop', 20, 'woocommerce_before_shop_loop', 22);
  add_action('woocommerce_before_shop_loop','plnt_catalog_sidebar', 21);
  plnt_add_wrapper('catalog__filter-metki catalog__filter-metki-ontop', 'woocommerce_before_shop_loop', 22, 'woocommerce_before_shop_loop', 23);
  add_action('woocommerce_before_shop_loop', 'plnt_metki_mob', 22);
  plnt_add_wrapper('catalog__top', 'woocommerce_before_shop_loop', 25, 'woocommerce_before_shop_loop', 36);
  add_action('woocommerce_before_shop_loop','plnt_woocommerce_total_count', 32);
  add_action('woocommerce_before_shop_loop','plnt_woocommerce_clear_filters', 33);
  add_action('woocommerce_before_shop_loop','plnt_catalog_grid_columns', 35);
  // // вывод фильтров над каталогом  #filters #berocket
  add_action('woocommerce_before_shop_loop','plnt_catalog_filters_main_area', 34);
  plnt_add_wrapper('catalog__products-wrap', 'woocommerce_before_shop_loop', 40, 'woocommerce_after_shop_loop', 20);
  
  add_action('woocommerce_after_shop_loop','plnt_get_advantages',24);
  // plnt_add_section('section container catalog__term-description expandable-content', 'woocommerce_after_shop_loop', 24, 'woocommerce_after_shop_loop', 28);
  // plnt_add_wrapper('expandable-content-area', 'woocommerce_after_shop_loop', 24, 'woocommerce_after_shop_loop', 26);
  add_action('woocommerce_after_shop_loop','plnt_get_expandable_archive_description',25);
  // add_action('woocommerce_after_shop_loop','plnt_expandable_content_button',27);

  //header catalog

  function plnt_catalog_header_image() {

      if ( is_page( 'search-results' ) || ! is_tax() ) {
          return;
      }

      $term_id = get_queried_object_id();

      if ( ! $term_id ) {
          return;
      }

      $image_id = get_term_meta( $term_id, 'catalog_image', true );

      if ( ! $image_id ) {
          return;
      }

      $image_url = wp_get_attachment_image_url( $image_id, 'full' );

      if ( ! $image_url ) {
          return;
      }
      ?>

      <div class="catalog__header-image-wrap darken">
          <img
              class="catalog__header-image"
              src="<?php echo esc_url( $image_url ); ?>"
              alt=""
              width="800"
              height="800">
      </div>

      <?php
  }

  // // переключатель сетки
  function plnt_catalog_grid_columns () {
    ?>
      <div class="catalog__grid-buttons">
      <button class="catalog__grid-button button" type="button" id="catalog__btn-grid" disabled>
        <?php echo plnt_icon('btn-grid');?>
      </button>
      <button class="catalog__grid-button button" type="button" id="catalog__btn-rows" >
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

    $total =  wc_get_loop_prop( 'total' );
    ?>
      <div class="catalog__sidebar-inner modal-mob">
        <div class="modal-mob__header">
          <span class="h5">Фильтр</span>
          <button type="button" class="modal-mob__close popup__close button"><?php echo plnt_icon('close') ?></button>
        </div>
        <aside class="catalog__sidebar-filters modal-mob__body">
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
            if ( plnt_get_catalog_type() !== 'plants-treez' ) {
              echo do_shortcode('[br_filter_single filter_id='.$filter_materilal_id.']');
            }
            //echo do_shortcode('[br_filter_single filter_id='.$filter_razmer_id.']'); // размер для растений Treez
            //echo do_shortcode('[br_filter_single filter_id='.$filter_volume_id.']'); // Объем //56544 //12016
            echo do_shortcode('[br_filter_single filter_id='.$filter_gift_id.']'); // в подарок //56535 //10988
          }
          ?>
        </aside>
        <div class="modal-mob__footer">
          <?php plnt_woocommerce_clear_filters_mob();?>
          <button class="button button--green catalog__sidebar-filters-show-all popup__close" type="button">Показать <span class="woocommerce-result-count">(<?php echo $total;?>)</span></button>
        </div>
      </div>
      <div class="popup-overlay"></div>
    <?php 
  };

//метки над каталогом 

function plnt_metki_mob() {
  if ( is_page( 'search-results' ) ) {
    return;
  }
  global $filter_podborki_slider_id;
  echo do_shortcode('[br_filter_single filter_id='.$filter_podborki_slider_id.']'); //Подборки дубль

};

// описание категории и преимущества в каталоге

function plnt_get_advantages() {
  echo ('<section class="catalog__advantages section container">');
	get_template_part( 'template-parts/advantages' );			
  echo ('</section>');
};



function plnt_catalog_filters_main_area() {
	?>
    <div class = "catalog__mob-filter-wrap"> 
      <button class="button button--green catalog__mob-filter-btn catalog-filters-open-btn">
        <span class="icon icon--pre icon--filter">Фильтры</span>
      </button>
    </div>
  <?php 	
};

function plnt_woocommerce_clear_filters() {
	?>
	<div class="bapf_sfilter bapf_rst_nofltr bapf_rst_sel">
		<div class="bapf_body">
			<button type="button" class="button bapf_button bapf_reset">
				<?php echo plnt_icon( 'close' ); ?>
				Очистить фильтр
				<span class="plnt-reset-filters__count"></span>
			</button>
		</div>
	</div>
	<?php
}
function plnt_woocommerce_clear_filters_mob() {
	?>
	<div class="bapf_sfilter bapf_rst_nofltr bapf_rst_sel">
		<div class="bapf_body">
			<button type="button" class="button bapf_button bapf_reset button--green-l">
				Очистить
			</button>
		</div>
	</div>
	<?php
}

function plnt_get_expandable_archive_description() {
  $description = term_description();

  if ( ! $description ) {
    return;
  }

  echo '<section class="section container catalog__term-description expandable-content">';
    echo '<div class="expandable-content-area">';
      echo '<div class="term-description">';
        echo wp_kses_post( $description );
      echo '</div>';
    echo '</div>';
    ?>

    <button
      class="catalog__term-description-read-full-button button button--clean expandable-content__button"
      type="button"
      data-js-expandable-content-button
    >
      <span class="icon icon--chevron-down">Показать полностью</span>
    </button>

    <?php
  echo '</section>';
}