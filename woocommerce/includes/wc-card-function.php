<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}





// баннеры в карточке товара

//add_action('woocommerce_after_single_product_summary', 'plnt_card__banners_wrap', 5);

function plnt_card__banners_wrap() {
    get_template_part('template-parts/card-banners'); // info cards for card
}


//кнопки изменения количества
add_action( 'woocommerce_before_quantity_input_field', 'plnt_quantity_minus', 25 );
add_action( 'woocommerce_after_quantity_input_field', 'plnt_quantity_plus', 25 );
 
function plnt_quantity_plus() {
  global $product;
  if(is_product()) {
    $quantity =  $product->get_stock_quantity();
    if ($quantity > 1 || !$product->get_manage_stock() || $product->backorders_allowed()) {
      echo '<div class="plus">';
      echo plnt_icon('plus');
      echo '</div>';
    }
  } 
};
 
function plnt_quantity_minus() {
  global $product;
  if(is_product()) {
    $quantity =  $product->get_stock_quantity();
    if ($quantity > 1 || !$product->get_manage_stock() || $product->backorders_allowed()) {
      echo '<div class="minus">';
      echo plnt_icon('minus');
      echo '</div>';
    }
  } 
};

// мета данные товара артикул + schema.org



//add_action( 'woocommerce_single_product_summary', 'plnt_get_buy_one_click_btn', 50);

function plnt_get_buy_one_click_btn() {
  global $product;
  if ( $product->get_stock_status() !=='outofstock') {
    plnt_buy_one_click_btn();
  }
}



// ссылка "назад" для карточки товара

//add_action('woocommerce_before_single_product','plnt_category_link',20);

function plnt_category_link () {
    global $parentCatId;
    $term = get_term($parentCatId);
    $link = get_term_link( $parentCatId, 'product_cat' );
    $name = $term->name;

    echo '<div class="card__toback-link">
    <span>prev</span>
    <a href="' . $link . '">Каталог: '.$name.'</a>
      </div>';
}

// поп-ап предзаказ preoprder popup
add_action('woocommerce_after_main_content','plnt_get_preorder_popup',20);

function plnt_get_preorder_popup () {
    global $product;
    if (is_product() && $product->get_stock_status() ==='outofstock') {
        wc_get_template_part('template-parts/popups/preorder-popup');
    }
}