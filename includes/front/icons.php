<?php
if (!defined('ABSPATH')) exit;

function plnt_icon( $icon, $class = '' ) {

    $file = get_template_directory() . '/images/icons/' . $icon . '.svg';

    if ( file_exists( $file ) ) {

        $svg = file_get_contents( $file );

        if ( $class ) {
            $svg = preg_replace(
                '/<svg\b([^>]*)>/',
                '<svg$1 class="' . esc_attr( $class ) . '">',
                $svg,
                1
            );
        }

        return $svg;
    }

    return '';
}
