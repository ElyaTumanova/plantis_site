<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// not used

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', 'SEO Data' )
	->show_on_post_type('product')
	->set_context( 'advanced' )
	// ->show_on_template( 'templates/main-page.php' )
	->add_fields( array(
		Field::make( 'text', 'seo_title_prod', __( 'seo-title' ) ),
	) );


