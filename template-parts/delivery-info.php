<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
// стоимость доставки

        
global $delivery_inMKAD;
global $delivery_outMKAD;

$shipping_costs = plnt_get_shiping_costs();
   
$in_mkad = $shipping_costs[$delivery_inMKAD];
$out_mkad = $shipping_costs[$delivery_outMKAD];

$large_markup_delivery_in_mkad = carbon_get_theme_option('large_markup_delivery_in_mkad');
$large_markup_delivery_out_mkad = carbon_get_theme_option('large_markup_delivery_out_mkad');

$urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');
$urgent_markup_delivery_large = carbon_get_theme_option('urgent_markup_delivery_large');

$late_markup_delivery = carbon_get_theme_option('late_markup_delivery');

$min_free_delivery = carbon_get_theme_option('min_free_delivery');
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
            <div>от <?php echo $in_mkad?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (до 5км)</div>
            <div>от <?php echo $out_mkad?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>В пределах МКАД <strong>в день заказа</strong></div>
            <div>от <?php echo floatval(str_replace(' ', '', $in_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery))?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (до 5км) <strong>в день заказа</strong></div>
            <div>от <?php echo floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery))?>₽</div>
        </div>
        <div class="delivery_table__row">
            <div>За пределы МКАД (от 5км)</div>
            <div>индивидуально</div>
        </div>
        <?php if($large_markup_delivery_in_mkad) { 
            echo '
                <div class="delivery_table__row">
                    <div>Крупногабаритная доставка</div>
                    <div>+ '.$large_markup_delivery_in_mkad.'₽ / '.$large_markup_delivery_out_mkad.'₽</div>
                </div>
            ';}?>
        <div class="delivery_table__row">
            <div>Самовывоз – <a style="text-decoration: underline;" href="https://yandex.ru/maps/-/CXQ-ErQ" target="_blank" rel="noopener">
            ул. Мещерякова, д. 3</a></div>
            <div>бесплатно</div>
        </div>
    </div>
</div>