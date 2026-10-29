<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Default options page
Container::make('theme_options', 'Настройки темы')
		 ->add_tab('Icons', array(
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
			 Field::make( 'textarea', 'close_icon', 'Close icon' )
				->set_rows( 6 ),
            Field::make( 'textarea', 'arrow_icon', 'Arrow icon' )
                ->set_rows( 6 ),
			 Field::make( 'text', 'site_title', 'Site title in header' )
         ))
		 ->add_tab('Main Page Banners', array(
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
		->add_tab('General', array(
			Field::make( 'text', 'notice', 'Уведомление' ),
			Field::make( 'checkbox', 'show_notice', 'Показать уведомление' )
    				->set_option_value( 'yes' ),
			Field::make( 'text', 'weekend', 'Выходной (формат ДД.ММ, разделитель - запятая без пробелов)' ),
			Field::make( 'text', 'pricelist_link', 'Ссылка на скачивание оптового прйс-листа' ),
	   ))
     ->add_tab('Plants cats', array(
			  Field::make( 'image', 'cat_palms', 'Пальмы' )
                ->set_value_type( 'id' ),
                
        Field::make( 'image', 'cat_fikusy', 'Фикусы' )
            ->set_value_type( 'id' ),
            
        Field::make( 'image', 'cat_listvennye', 'Декоративно-лиственные' )
            ->set_value_type( 'id' ),
            
        Field::make( 'image', 'cat_napolnye', 'Напольные' )
            ->set_value_type( 'id' ),
            
        Field::make( 'image', 'cat_cvetushchie', 'Цветущие' )
            ->set_value_type( 'id' ),
            
        Field::make( 'image', 'cat_lianas', 'Лианы' )
            ->set_value_type( 'id' ),
            
        Field::make( 'image', 'cat_neprikhotlivye', 'Неприхотливые' )
            ->set_value_type( 'id' ),
            
        Field::make( 'image', 'cat_succulent', 'Суккуленты' )
            ->set_value_type( 'id' ),
            
        Field::make( 'image', 'cat_pet_friendly', 'Pet Friendly' )
            ->set_value_type( 'id' ),
			 
     ));