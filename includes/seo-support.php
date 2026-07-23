<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

# Contents
# Opengraph
# Favicon


/*--------------------------------------------------------------
# Opengraph
--------------------------------------------------------------*/


add_filter('wpseo_opengraph_title', function ($title) {
     if (is_page('gift-card')) {                // ваша страница
        return 'Ваш подарочный сертификат в Plantis';
    } else if (is_page('test-kakoe-ty-rastenie')) {                // ваша страница
        return 'Пройди тест — Какое ты растение?';
    } else if (is_page('test-result')) {
        return 'Посмотри какое я растение';
    }
    return $title;
});

add_filter('wpseo_opengraph_desc', function ($desc) {
    if (is_page('gift-card')) {
        return 'Интернет-магазин комнатных растений';
    } else if (is_page('test-kakoe-ty-rastenie')) {
        return 'Узнай, какое растение тебе ближе всего!';
    } else if (is_page('test-result')) {
        $plant_types = require get_theme_file_path('assets/data/plant-types.php');
        $plants_by_slug = [];
        foreach ($plant_types as $it) { $plants_by_slug[$it['slug']] = $it; };
        $plant = get_query_var('plant');
        if ($plant && isset($plants_by_slug[$plant])) {
          $desc = $plants_by_slug[$plant]['result'];
        } else {
          $desc = 'Результат теста - Какое ты растение?';
        }
        return $desc;
    }
    return $desc;
});

add_action('wp_head', function() {
    if (is_page('gift-card')) {
        echo '<meta property="og:image" content="'. get_template_directory_uri() .'/images/gift-card/gc_soc.jpg" />' . "\n";
        echo '<meta property="og:image:width" content="1200" />' . "\n";
        echo '<meta property="og:image:height" content="630" />' . "\n";
    }
    else if (is_page('test-kakoe-ty-rastenie')) {
        echo '<meta property="og:image" content="'. get_template_directory_uri() .'/images/test/test_cover_long.jpg" />' . "\n";
        echo '<meta property="og:image:width" content="1200" />' . "\n";
        echo '<meta property="og:image:height" content="630" />' . "\n";
    } else if (is_page('test-result')) {
        $plant_types = require get_theme_file_path('assets/data/plant-types.php');
        $plants_by_slug = [];
        foreach ($plant_types as $it) { $plants_by_slug[$it['slug']] = $it; };
        $gen = get_query_var('gen');
        $plant = get_query_var('plant');
        if ($gen && $plant && isset($plants_by_slug[$plant]) && isset($plants_by_slug[$plant]['image'][$gen])) {
          $img = $plants_by_slug[$plant]['image'][$gen];
        } else {
          $img = get_template_directory_uri().'/images/test/test_cover.webp';
        }
        echo '<meta property="og:image" content="'. $img .'"/>' . "\n";
        echo '<meta property="og:image:width" content="1024" />' . "\n";
        echo '<meta property="og:image:height" content="1024" />' . "\n";
    }
});


/*--------------------------------------------------------------
# Favicon
--------------------------------------------------------------*/
//добавить фавикон favicon
add_action( 'wp_head', 'plnt_add_favicons' );
function plnt_add_favicons() {
    $dir = get_template_directory_uri() . '/images/favicons';
    ?>
    <link rel="icon" type="image/svg+xml" href="<?php echo $dir; ?>/favicon.svg" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo $dir; ?>/favicon-32x32.png" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo $dir; ?>/favicon-96x96.png" sizes="96x96"/>
    <link rel="shortcut icon" href="<?php echo $dir; ?>/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $dir; ?>/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Plantis" />
    <link rel="manifest" href="<?php echo $dir; ?>/site.webmanifest" />
    <?php
}
