<?php
get_header(); ?>

<?php 
    // стоимость доставки

    global $delivery_inMKAD;
    global $delivery_outMKAD;
   
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');

    $large_markup_delivery_in_mkad = carbon_get_theme_option('large_markup_delivery_in_mkad');
    $large_markup_delivery_out_mkad = carbon_get_theme_option('large_markup_delivery_out_mkad');

    $small_markup_delivery = carbon_get_theme_option('small_markup_delivery');
    $medium_markup_delivery = carbon_get_theme_option('medium_markup_delivery');

    $urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');
    $urgent_markup_delivery_large = carbon_get_theme_option('urgent_markup_delivery_large');

    $late_markup_delivery = carbon_get_theme_option('late_markup_delivery');
    $late_interval_delivery = carbon_get_theme_option('late_interval_delivery');
    echo($late_interval_delivery);

    $isUrgentCourierTariff = carbon_get_theme_option('is_urgent_courier_tariff') == '1';

    $shipping_costs = plnt_get_shiping_costs();

    $in_mkad = $shipping_costs[$delivery_inMKAD];
    $out_mkad = $shipping_costs[$delivery_outMKAD];
    $min_small_delivery_minus_1 =  floatval(str_replace(' ', '',  $min_small_delivery)) - 1;
    $min_medium_delivery_minus_1 =  floatval(str_replace(' ', '',  $min_medium_delivery)) - 1;


    $intervals = [
      '11:00 - 21:00',
      '11:00 - 16:00',
      '14:00 - 18:00',
      '18:00 - 21:00',
    ];


    //print_r($shipping_costs);
    
?>

<div class="content-area content-area_sidebar">
    <aside class='info-menu-sidebar'>  
        <?php get_template_part('template-parts/info-pages-list');?> 
    </aside> 
	<main id="main" class="site-main" role="main">
        <div class="delivery info__list">
            <div class="delivery__block">
                <!-- <div class="delivery__header"> -->
                    <h1 class="entry-header">Доставка</h1>
                    <!-- <span class="delivery__dropdown-arrow">next</span> -->
                <!-- </div> -->
                <!-- <div class="delivery__dropdown"> -->
                    <div>
                        <!-- <h3 class="delivery__heading heading-2">Если ваш заказ <b>от <?php //echo $min_small_delivery ?></b> рублей:</h3> -->
                        <p><strong>Доставка на следующий день или позже:</strong></p>
                            <ul>
                                <li>в пределах МКАД — от <?php echo $in_mkad ?> рублей;</li>
                                <li>за пределы МКАД (до 5 км) — от <?php echo $out_mkad ?> рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                        <p><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                        <?php if($isUrgentCourierTariff):?>
                            <p>осуществляется по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</p>
                        <?php else:?>
                          <ul>
                              <li>в пределах МКАД — от <?php echo floatval(str_replace(' ', '', $in_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery)) ?> рублей;</li>
                              <li>за пределы МКАД (до 5 км) — от <?php echo floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery)) ?> рублей;</li>
                              <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                          </ul>	
                        <?php endif; ?>
                        <?php if($min_small_delivery || $min_medium_delivery) {
                            if(array_key_exists($delivery_courier,$shipping_costs)) :?>
                                <p>Если ваш заказ <b>до 
                                <?php  if($min_medium_delivery) {echo $min_medium_delivery;} else {echo $min_small_delivery;}?></b> рублей 
                                доставка осуществляется по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</p>
                            <?php else :?>    
                                <p><strong>Цена доставки для заказов стоимостью до 
                                    <?php 
                                    if($min_small_delivery) {
                                        echo $min_small_delivery_minus_1.' рублей увеличена на '.$small_markup_delivery.' рублей.';   
                                    } 
                                    
                                    if($min_medium_delivery) {
                                        echo '<br>Цена доставки для заказов стоимостью от '.$min_small_delivery.' до '.$min_medium_delivery_minus_1.' рублей увеличена на '.$medium_markup_delivery.' рублей.'; 
                                    } ?> 
                                </strong></p>  
                            <?php endif; ?>			
                        <?php

                        } ?>
                        <p class="info__note">В итоговой стоимости заказа не учитывается цена доставки!</p>
                        
                    </div>
                
                    <!-- <div>
                        <h3 class="delivery__heading heading-2">Если ваш заказ <b>до <?php //echo $min_small_delivery ?></b> рублей:</h3>
                        <?php if(array_key_exists($delivery_courier,$shipping_costs)) :?>
                            <p>Доставка осуществляется по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</p>
                        <?php else :?>
                            <p><strong>Доставка на следующий день или позже:</strong></p>
                            <ul>
                                <li>в пределах МКАД — от <?php //echo $in_mkad_small ?> рублей;</li>
                                <li>за пределы МКАД (до 5 км) — от <?php //echo $out_mkad_small ?> рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                            <p><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                            <ul>
                                <li>в пределах МКАД — от <?php //echo $in_mkad_small_urg ?> рублей;</li>
                                <li>за пределы МКАД (до 5 км) — от <?php //echo $out_mkad_small_urg ?> рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                            <p class="info__note">В итоговой стоимости заказа не учитывается цена доставки!</p>
                        <?php endif; ?>
                    </div> -->
                <!-- </div> -->
            </div>
                <?php if($large_markup_delivery_in_mkad):?>
                    <div class="delivery__block">
                        <!-- <div class="delivery__header"> -->
                            <h2 class="entry-header">Крупногабаритная доставка</h2>
                            <!-- <span class="delivery__dropdown-arrow">next</span>
                        </div> -->
                        <!-- <div class="delivery__dropdown"> -->
                            <p>Доставка крупномерных растений (от 100см), больших заказов, высоких или тяжелых кашпо осуществляется грузовым автомобилем.</p>
                            <p><strong>Крупногабаритная доставка на следующий день или позже:</strong></p>
                            <ul>
                                <li>в пределах МКАД — от <?php echo(floatval(str_replace(' ', '', $in_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_in_mkad)))?> рублей;</li>
                                <li>за пределы МКАД (до 5 км) — от <?php echo(floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_out_mkad)))?> рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                            <p><strong>Крупногабаритная срочная “день в день”.</strong> Можно оформить до 18:00:</p>
                            <?php if($isUrgentCourierTariff):?>
                              <p>осуществляется по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</p>
                            <?php else:?>
                              <ul>
                                  <li>в пределах МКАД — от <?php echo(floatval(str_replace(' ', '', $in_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_in_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery_large)))?> рублей;</li>
                                  <li>за пределы МКАД (до 5 км) — от <?php echo(floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_out_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery_large)))?> рублей;</li>
                                  <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                              </ul>
                            <?php endif;?>
                        <!-- </div> -->
                    </div>
                <?php endif;?>				
                    
                <?php if($min_free_delivery) {'<p>При заказе товаров на сумму <strong>свыше '.$min_free_delivery.' рублей</strong> доставка осуществляется бесплатно.</p>';}?>				
                
                <div class="delivery__block">
                    <!-- <div class="delivery__header"> -->
                        <h2 class="entry-header">Самовывоз</h2>
                        <!-- <span class="delivery__dropdown-arrow">next</span>
                    </div> -->
                    <!-- <div class="delivery__dropdown"> -->
                        <h3 class="delivery__heading heading-2">Где</h3>
                        <p>Вы можете бесплатно забрать товары из нашего шоурума по адресу г. Москва, ул. Мещерякова, д.3 (от м. Тушинская или м. Сокол).</p>
                        <div class="info__map">
                            <iframe src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=237252555639" width="560" height="400" frameborder="0"></iframe>
                        </div>

                        <h3 class="delivery__heading heading-2">Когда</h3>
                            <p>Мы ждем Вас ежедневно в рабочие часы.</p>
                            <p>Необходимо предварительно связаться с нами и договорится о времени Вашего прибытия.</p>
                    <!-- </div> -->
                </div>
                
                
                <div class="delivery__block">
                    <!-- <div class="delivery__header"> -->
                        <h3 class="entry-header">Интервалы доставки</h3>
                        <!-- <span class="delivery__dropdown-arrow">next</span>
                    </div> -->
                    <!-- <div class="delivery__dropdown"> -->
                        <ul>
                          <?php foreach ($intervals as $interval): ?>
                            <li>
                              с <?= str_replace(' - ', ' до ', $interval); ?>
                              <?= ($interval === '18:00 - 21:00' && $late_interval_delivery === '18:00 - 21:00')
                                  ? ' + ' . (int)$late_markup_delivery . ' рублей к стоимости доставки'
                                  : '' ?>;
                            </li>
                          <?php endforeach; ?>
                        </ul>
                        <p>Мы работаем без выходных, поэтому <strong>доставка осуществляется каждый день.</strong></p>
                        <p>При оформлении срочной доставки “день в день” менеджер согласует с вами удобный интервал доставки.</p>					
                    <!-- </div> -->
                </div>

                <div class="delivery__block">
                    <!-- <div class="delivery__header"> -->
                        <h3 class="entry-header">Связаться с нами</h3>
                        <!-- <span class="delivery__dropdown-arrow">next</span>
                    </div> -->
                    <!-- <div class="delivery__dropdown"> -->
                        <p>Ничего страшного, если вы не можете принять заказ в согласованные дату и время. В таком случае просим связаться с нами удобным для вас способом.</p>

                        <?php get_template_part('template-parts/contacts-part');?>
                    <!-- </div> -->
                </div>


           

		</div>
	</main><!-- #main -->  
</div><!-- #primary -->



<?php get_footer(); ?>