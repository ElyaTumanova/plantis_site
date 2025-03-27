<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'term_meta', __( 'Category Properties' ) )
    ->where( 'term_taxonomy', '=', 'product_cat' )
    ->add_fields( array(
        Field::make( 'text', 'SEO-title', __( 'SEO-title' ) ),
    ) );