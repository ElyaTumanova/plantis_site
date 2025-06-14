<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

    /* all.php */

    header( 'Content-Type: text/css' );

    $css=array( '/css/card.css', '/css/catalog.css' );
    print_r($css);
    $output=array();

    foreach( $css as $file ){
        $output[]=file_exists( $file ) ? file_get_contents( $file ) : '/* error: '.$file.' cannot be found */';
    }
    echo implode( PHP_EOL, $output );


?>