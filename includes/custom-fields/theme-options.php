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
			 Field::make( 'image', 'search_icon', 'Search' )
			 ->set_value_type( 'url' ),
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
			Field::make( 'image', 'main_banner_1', 'Banner 1' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'main_banner_1_mob', 'Banner 1 for mob' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_1_name', 'Banner 1 Name' ),
			Field::make( 'image', 'main_banner_2', 'Banner 2' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'main_banner_2_mob', 'Banner 2 for mob' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_2_name', 'Banner 2 Name' ),
			Field::make( 'image', 'main_banner_3', 'Banner 3' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'main_banner_3_mob', 'Banner 3 for mob' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_3_name', 'Banner 3 Name' ),
			Field::make( 'image', 'main_banner_4', 'Banner 4' )
			 ->set_value_type( 'url' ),
			Field::make( 'image', 'main_banner_4_mob', 'Banner 4 for mob' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_4_name', 'Banner 4 Name' ),
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
			// Field::make( 'text', 'in_mkad', 'Доставка в пределах МКАД на след день более мин суммы' ),
			// Field::make( 'text', 'out_mkad', 'Доставка за пределы МКАД на след день более мин суммы'),
			// Field::make( 'text', 'in_mkad_urg', 'Доставка в пределах МКАД срочная более мин суммы'),
			// Field::make( 'text', 'out_mkad_urg', 'Доставка за пределы МКАД срочная более мин суммы'),
			// Field::make( 'text', 'in_mkad_small', 'Доставка в пределах МКАД на след день до мин суммы'),
			// Field::make( 'text', 'out_mkad_small', 'Доставка за пределы МКАД на след день до мин суммы'),
			// Field::make( 'text', 'in_mkad_small_urg', 'Доставка в пределах МКАД срочная до мин суммы'),
			// Field::make( 'text', 'out_mkad_small_urg', 'Доставка за пределы МКАД срочная до мин суммы'),
			Field::make( 'text', 'min_free_delivery', 'Минимальная сумма заказа для бесплатной доставки (текст с пробелом)'),
			Field::make( 'text', 'min_small_delivery', 'Сумма заказа для более дорогой доставки'),
			Field::make( 'text', 'min_treez_delivery', 'Сумма заказа для доставки кашпо Treez'),
			// Field::make( 'text', 'large_delivery_markup_in_mkad', 'Надбавка для крупногабаритной доставки в пределах МКАД'),
			// Field::make( 'text', 'large_delivery_markup_out_mkad', 'Надбавка для крупногабаритной доставки за пределами МКАД'),
			// Field::make( 'text', 'small_delivery_markup', 'Надбавка для доставки ниже минимальной суммы'),
			// Field::make( 'text', 'urgent_delivery_markup', 'Надбавка для срочной доставки день в день'),
	   ))
		->add_tab( __('Header notice'), array(
			Field::make( 'text', 'notice', 'Уведомление' ),
			Field::make( 'checkbox', 'show_notice', __( 'Показать уведомление' ) )
    				->set_option_value( 'yes' ),
			Field::make( 'text', 'weekend', 'Выходной (формат ДД.ММ, разделитель - запятая без пробелов)' ),
	   ));