<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
    $in_mkad = carbon_get_theme_option('in_mkad');
    $out_mkad = carbon_get_theme_option('out_mkad');
    $in_mkad_urg = carbon_get_theme_option('in_mkad_urg');
    $out_mkad_urg = carbon_get_theme_option('out_mkad_urg');
    $in_mkad_small = carbon_get_theme_option('in_mkad_small');
    $out_mkad_small = carbon_get_theme_option('out_mkad_small');
    $in_mkad_small_urg = carbon_get_theme_option('in_mkad_small_urg');
    $out_mkad_small_urg = carbon_get_theme_option('out_mkad_small_urg');
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
?>

<div class="delivery-info">
    <h4 class="delivery_table__header">Доставка</h4>
    <div class="delivery_table">
        <div class="delivery_table__row">
            <div>Самовывоз – <a style="text-decoration: underline;" href="https://yandex.ru/maps/-/CXQ-ErQ" target="_blank" rel="noopener">
            ул. Мещерякова, д. 3</a></div>
            <div>бесплатно</div>
        </div>
        <div class="delivery_table__row">
            <div>При заказе от <?php echo $min_free_delivery?>₽</div>
            <div>бесплатно</div>
        </div>
        <div class="delivery_table__row">
            <div>В пределах МКАД</div>
            <div><?php echo $in_mkad?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (до 5км)</div>
            <div><?php echo $out_mkad?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>В пределах МКАД в день заказа</div>
            <div><?php echo $in_mkad_urg?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (до 5км) в день заказа</div>
            <div><?php echo $out_mkad_urg?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (от 5км)</div>
            <div>индивидуально</div>
        </div>
    </div>
</div>