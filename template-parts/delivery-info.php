<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
// стоимость доставки

$shipping_costs = plnt_get_shiping_costs();
    
$in_mkad = $shipping_costs[$delivery_inMKAD];
$out_mkad = $shipping_costs[$delivery_outMKAD];

$in_mkad_urg = $shipping_costs[$urgent_delivery_inMKAD];
$out_mkad_urg = $shipping_costs[$urgent_delivery_outMKAD];

$in_mkad_large = $shipping_costs[$delivery_inMKAD_large];
$out_mkad_large = $shipping_costs[$delivery_outMKAD_large];

$in_mkad_urg_large = $shipping_costs[$urgent_delivery_inMKAD_large];
$out_mkad_urg_large = $shipping_costs[$urgent_delivery_outMKAD_large];

$in_mkad_small = $shipping_costs[$delivery_inMKAD_small];
$out_mkad_small = $shipping_costs[$delivery_outMKAD_small];

$in_mkad_small_urg = $shipping_costs[$urgent_delivery_inMKAD_small];
$out_mkad_small_urg = $shipping_costs[$urgent_delivery_outMKAD_small];

$large_delivery_markup_in_mkad = $in_mkad_large - $in_mkad;
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