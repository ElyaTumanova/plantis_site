<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Default options page
Container::make('theme_options', 'Настройки темы')
		 ->add_tab( __('Icons'), array(
			 Field::make( 'image', 'logo', 'Logo' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'account_icon', 'Personal Account' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'account_logged_icon', 'Personal Account LoggedIn' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'whishlist_icon', 'Wishlist' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'cart_icon', 'Cart' )
			 ->set_value_type( 'url' ),
			//  Field::make( 'image', 'search_icon', 'Search' )
			//  ->set_value_type( 'url' ),
			 Field::make( 'html', 'search_icon', __( 'Search' ) )
			->set_html( sprintf( '<p>$1</p>', __( 'Here, you can add some useful description for the fields below / above this text.' ) ) ),
			 Field::make( 'image', 'catalog_icon', 'Catalog' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'search_icon_mob', 'Search for mob' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'menu_icon_mob', 'Menu for mob' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'phone_icon', 'Phone button for mob' )
			 ->set_value_type( 'url' ),
			//  Field::make( 'image', 'filter_icon', 'Filter button for mob in catalog' )
			//  ->set_value_type( 'url' ),
			 Field::make( 'text', 'site_title', 'Site title in header' )
         ))
		 ->add_tab( __('Main Page Banners'), array(
			Field::make( 'complex', 'banners', 'Баннеры для главной страницы' )
				->add_fields( array(
					Field::make( 'image', 'banner_desktop', 'Баннер для десктопа' )
							->set_width( 33 )
							->set_value_type( 'url' ),
					Field::make( 'image', 'banner_mob', 'Баннер для мобильного' )
							->set_width( 33 )
							->set_value_type( 'url' ),
					Field::make( 'text', 'banner_name', 'Описание баннера для alt' )
							->set_width( 33 ),
					Field::make( 'text', 'banner_link', 'Ссылка при клике не баннер (не обязательно)' )
							->set_width( 33 )
					)
				)
		))
		 ->add_tab( __('Main Page Categories'), array(
			Field::make( 'image', 'cats_palms', 'Пальмы' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_fikus', 'Фикусы' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_lisv', 'Декор-листв' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_napol', 'Напольные' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_cvetush', 'Цветущие' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_lianas', 'Лианы' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_neprikhotliv', 'Неприхотливые' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_succulent', 'Суккуленты' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'cats_petfriendly', 'Pet Friendly' )
			 ->set_value_type( 'url' ),
		))
		->add_tab( __('Delivery'), array(
			Field::make( 'text', 'min_free_delivery', 'Минимальная сумма заказа для бесплатной доставки (текст с пробелом)'),
			Field::make( 'text', 'min_small_delivery', 'Сумма заказа для более дорогой доставки'),
			Field::make( 'text', 'min_treez_delivery', 'Сумма заказа для доставки кашпо Treez'),
	   ))
		->add_tab( __('Header notice'), array(
			Field::make( 'text', 'notice', 'Уведомление' ),
			Field::make( 'checkbox', 'show_notice', __( 'Показать уведомление' ) )
    				->set_option_value( 'yes' ),
			Field::make( 'text', 'weekend', 'Выходной (формат ДД.ММ, разделитель - запятая без пробелов)' ),
	   ));