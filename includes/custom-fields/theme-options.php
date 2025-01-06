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
			 Field::make( 'textarea', 'account_icon', 'Personal Account' )
				->set_rows( 6 ),
			 Field::make( 'textarea', 'account_logged_icon', 'Personal Account LoggedIn' )
				->set_rows( 6 ),
			 Field::make( 'textarea', 'whishlist_icon', 'Wishlist' )
				->set_rows( 6 ),
			 Field::make( 'textarea', 'cart_icon', 'Cart' )
			 	->set_rows( 6 ),
			Field::make( 'textarea', 'search_icon', 'Search' )
				->set_rows( 6 ),
			 Field::make( 'textarea', 'catalog_icon', 'Catalog' )
				->set_rows( 6 ),
			 Field::make( 'image', 'search_icon_mob', 'Search for mob' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'menu_icon_mob', 'Menu for mob' )
			 ->set_value_type( 'url' ),
			 Field::make( 'textarea', 'phone_icon', 'Phone button for mob' )
				->set_rows( 6 ),
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
			Field::make( 'text', 'min_small_delivery', 'Сумма заказа для более дорогой доставки - самая минимальная'),
			Field::make( 'text', 'min_medium_delivery', 'Сумма заказа для более дорогой доставки - средняя (не обязательно)'),
			Field::make( 'text', 'min_treez_delivery', 'Сумма заказа для доставки кашпо Treez'),
			Field::make( 'text', 'late_markup_delivery', 'Надбавка к стоимости поздней доставки'),
	   ))
		->add_tab( __('Header notice'), array(
			Field::make( 'text', 'notice', 'Уведомление' ),
			Field::make( 'checkbox', 'show_notice', __( 'Показать уведомление' ) )
    				->set_option_value( 'yes' ),
			Field::make( 'text', 'weekend', 'Выходной (формат ДД.ММ, разделитель - запятая без пробелов)' ),
	   ));