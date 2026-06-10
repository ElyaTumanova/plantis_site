<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function plnt_sale_badge() {

  // получаем объект текущего товара в цикле
  global $product;

  // есле не распродажа, ничего не делаем
  if ( ! $product->is_on_sale() ) {
    return;
  }
    // рассчитываем процент скидки
    $percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;

  if ( $percentage > 0 ) {
    echo '<div class="sale_badge"> - ' . round( $percentage ) . '%</div>';
  }
};

function plnt_buy_one_click_btn() {
  ?> <button class="card__one-click-btn button--white page-popup-open-btn button" type="button"> 
    <span class="desktop-hidden">В</span><span class="mobile-hidden">Купить в</span> 1 клик</button> <?php
}

/* атрибуты */

function plnt_product_attributes($root_class = '') {
	global $product;

	if ( ! $product ) {
		return;
	}

  $attributes = $product->get_attributes();

	if ( empty( $attributes ) ) {
		return;
	}
  echo ('<div class="'. esc_attr( $root_class ) .'">');
	echo '<ul class="product-card-attributes">';

	foreach ( $attributes as $attribute ) {
		if ( ! $attribute->get_visible() ) {
			continue;
		}

		$name = wc_attribute_label( $attribute->get_name() );

		if ( $attribute->is_taxonomy() ) {
			$values = wc_get_product_terms(
				$product->get_id(),
				$attribute->get_name(),
				array( 'fields' => 'names' )
			);
		} else {
			$values = $attribute->get_options();
		}

		if ( empty( $values ) ) {
			continue;
		}

		echo '<li>';
		echo '<span class="product-card-attributes__name">' . esc_html( $name ) . '</span> ';
    echo '<span class="product-card-attributes__line"></span>';
		echo '<span class="product-card-attributes__value">' . esc_html( implode( ', ', $values ) ) . '</span>';
		echo '</li>';
	}
	echo '</ul>';
  echo ('</div>');
}
