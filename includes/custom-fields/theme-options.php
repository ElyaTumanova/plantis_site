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
			 Field::make( 'image', 'whishlist_icon', 'Wishlist' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'cart_icon', 'Cart' )
			 ->set_value_type( 'url' ),
			 Field::make( 'image', 'search_icon', 'Search' )
			 ->set_value_type( 'url' ),
			 Field::make( 'text', 'site_title', 'Site title in header' )
         ))
		 ->add_tab( __('Main Page Banners'), array(
			Field::make( 'image', 'main_banner_1', 'Banner 1' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_1_name', 'Banner 1 Name' ),
			Field::make( 'image', 'main_banner_2', 'Banner 2' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_2_name', 'Banner 2 Name' ),
			Field::make( 'image', 'main_banner_3', 'Banner 3' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_3_name', 'Banner 3 Name' ),
			Field::make( 'image', 'main_banner_4', 'Banner 4' )
			 ->set_value_type( 'url' ),
			Field::make( 'text', 'main_banner_4_name', 'Banner 4 Name' ),
			Field::make( 'media_gallery', 'main_banners', 'Баннеры' )
			->set_value_type( 'image' )
			->set_duplicates_allowed( false ),
		));

// Add second options page under 'Basic Options'
Container::make('theme_options', 'Social Links')
         ->set_page_parent('Настройки темы')  // title of a top level Theme Options page
         ->add_fields(array(
		Field::make('text', 'crb_facebook_link', 'попр'),
		Field::make('text', 'crb_twitter_link')
	));

// Add third options page under "Appearance"
Container::make('theme_options', 'Customize Background')
			->set_page_parent('themes.php')
            ->add_fields(array(
		Field::make('color', 'crb_background_color'),
		Field::make('image', 'crb_background_image')
	));