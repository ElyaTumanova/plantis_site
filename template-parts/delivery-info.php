<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
    global $in_mkad;
    global $out_mkad;

    global $min_free_delivery;
    global $min_small_delivery;

    global $large_delivery_markup;
    global $urgent_delivery_markup;
    global $small_delivery_markup;
    
    global $in_mkad_large;
    global $out_mkad_large;
    global $in_mkad_urg_large;
    global $out_mkad_urg_large;

    global $in_mkad_small;
    global $out_mkad_small;
    global $in_mkad_small_urg;
    global $out_mkad_small_urg;
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
        <?php if($large_delivery_markup) { 
            echo '
                <div class="delivery_table__row">
                    <div>Крупногабаритная доставка</div>
                    <div>+ '.$large_delivery_markup.'₽</div>
                </div>
            ';}?>
        <div class="delivery_table__row">
            <div>Самовывоз – <a style="text-decoration: underline;" href="https://yandex.ru/maps/-/CXQ-ErQ" target="_blank" rel="noopener">
            ул. Мещерякова, д. 3</a></div>
            <div>бесплатно</div>
        </div>
    </div>
</div>