<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
// стоимость доставки

$in_mkad = carbon_get_theme_option('in_mkad');
$out_mkad = carbon_get_theme_option('out_mkad');

$min_free_delivery = carbon_get_theme_option('min_free_delivery');
$min_small_delivery = carbon_get_theme_option('min_small_delivery');

$large_delivery_markup_in_mkad = carbon_get_theme_option('large_delivery_markup_in_mkad');
$urgent_delivery_markup = carbon_get_theme_option('urgent_delivery_markup');
$small_delivery_markup = carbon_get_theme_option('small_delivery_markup');

if($urgent_delivery_markup) {
    $in_mkad_urg = $in_mkad + $urgent_delivery_markup;
    $out_mkad_urg = $out_mkad + $urgent_delivery_markup;
}

if($large_delivery_markup_in_mkad) {
    $in_mkad_large = $in_mkad + $large_delivery_markup_in_mkad;
    $out_mkad_large = $out_mkad + $large_delivery_markup_in_mkad;
    if($urgent_delivery_markup) {
        $in_mkad_urg_large = $in_mkad + $large_delivery_markup_in_mkad + $urgent_delivery_markup;
        $out_mkad_urg_large = $out_mkad + $large_delivery_markup_in_mkad + $urgent_delivery_markup;
    }
}

if($small_delivery_markup) {
    $in_mkad_small = $in_mkad + $small_delivery_markup;
    $out_mkad_small = $out_mkad + $small_delivery_markup;
    if($urgent_delivery_markup) {
        $in_mkad_small_urg = $in_mkad + $small_delivery_markup + $urgent_delivery_markup;
        $out_mkad_small_urg = $out_mkad + $small_delivery_markup + $urgent_delivery_markup;
    }
}
?>

<div class="delivery-info">
    <h4 class="delivery_table__header">Доставка</h4>
    <div class="delivery_table">
        <?php if($min_free_delivery) { 
            echo '
                <div class="delivery_table__row">
                    <div>При заказе от '.$min_free_delivery.'₽</div>
                    <div>бесплатно</div>
                </div>
            ';}?>	
        <div class="delivery_table__row">
            <div>В пределах МКАД</div>
            <div><?php echo $in_mkad?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (до 5км)</div>
            <div><?php echo $out_mkad?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>В пределах МКАД <strong>в день заказа</strong></div>
            <div><?php echo $in_mkad_urg?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (до 5км) <strong>в день заказа</strong></div>
            <div><?php echo $out_mkad_urg?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (от 5км)</div>
            <div>индивидуально</div>
        </div>
        <?php if($large_delivery_markup_in_mkad) { 
            echo '
                <div class="delivery_table__row">
                    <div>Крупногабаритная доставка</div>
                    <div>+ '.$large_delivery_markup_in_mkad.'₽</div>
                </div>
            ';}?>
        <div class="delivery_table__row">
            <div>Самовывоз – <a style="text-decoration: underline;" href="https://yandex.ru/maps/-/CXQ-ErQ" target="_blank" rel="noopener">
            ул. Мещерякова, д. 3</a></div>
            <div>бесплатно</div>
        </div>
    </div>
</div>