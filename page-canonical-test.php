
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// require wordpress
define('DOING_AJAX', false);
define('WP_USE_THEMES', true);
define('QM_DISABLED', true);
define('DONOTCACHEPAGE', true);

define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('WP_DEBUG_LOG', false);

$_SERVER['REQUEST_URI']    = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Load the WordPress library.
require_once( dirname(__FILE__) . '/wp-load.php' );                
// Set up the WordPress query.
wp();
// basic init for templates
do_action( 'template_redirect' );
echo '<pre>';

global $wp_filter;
echo "Debug info for filters\n";
if ( isset( $wp_filter['wpseo_canonical'] ) ) {
  var_dump( $wp_filter['wpseo_canonical'] );
}

echo "Try to init Yoast\n";
$wpseo_front=WPSEO_Frontend::get_instance();
$wpseo_front->front_page_specific_init();

echo "Test Yoast API\n";
echo "Canonical URL\n";
$url = $wpseo_front->canonical( false, true, true );
var_dump( $url );
echo '</pre>';