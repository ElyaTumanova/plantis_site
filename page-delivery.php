<?php
get_header(); ?>

<?php 
    // стоимость доставки
   
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
    
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

    $in_mkad_medium = $shipping_costs[$delivery_inMKAD_medium];
	$out_mkad_medium = $shipping_costs[$delivery_outMKAD_medium];

	$in_mkad_medium_urg = $shipping_costs[$urgent_delivery_inMKAD_medium];
	$out_mkad_medium_urg = $shipping_costs[$urgent_delivery_outMKAD_medium];
?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
        <div class="delivery info__list">

            <div class="delivery__block">
                <div class="delivery__header">
                    <h1 class="entry-header">Доставка</h1>
                    <span class="delivery__dropdown-arrow">next</span>
                </div>
                <div class="delivery__dropdown">
                    <div>
                        <!-- <h3 class="delivery__heading heading-2">Если ваш заказ <b>от <?php //echo $min_small_delivery ?></b> рублей:</h3> -->
                        <p><strong>Доставка на следующий день или позже<sup>1</sup>:</strong></p>
                            <ul>
                                <li>в пределах МКАД — от <?php echo $in_mkad ?> рублей;</li>
                                <li>за пределы МКАД (до 5 км) — от <?php echo $out_mkad ?> рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                        <p><strong>Срочная “день в день”<sup>2</sup></strong>. Можно оформить до 18:00:</p>
                        <ul>
                            <li>в пределах МКАД — от <?php echo $in_mkad_urg ?> рублей;</li>
                            <li>за пределы МКАД (до 5 км) — от <?php echo $out_mkad_urg ?> рублей;</li>
                            <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                        </ul>	
                        <?php if($min_small_delivery || $min_medium_delivery) {
                            if(array_key_exists($delivery_courier,$shipping_costs)) :?>
                                <p>Если ваш заказ <b>до 
                                <?php  if($min_medium_delivery) {echo $min_medium_delivery} else{echo $min_small_delivery}?></b> рублей 
                                доставка осуществляется по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</p>
                            <?php else :?>    
                                <small>1 - если стоимость вашего заказа меньше 
                                    <?php if($min_small_delivery){echo $min_small_delivery.'стоимость доставки будет увеличена на '. echo ($in_mkad_small - $in_mkad_) } ?>
                                    <?php if($min_medium_delivery){echo $min_medium_delivery} ?>
                            <?php endif; ?>			
                        <?php

                        } ?>
                        
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
                </div>
            </div>
                <?php if(array_key_exists($delivery_inMKAD_large,$shipping_costs)) { 
                    echo '<div class="delivery__block">
                        <div class="delivery__header">
                            <h2 class="entry-header">Крупногабаритная доставка</h2>
                            <span class="delivery__dropdown-arrow">next</span>
                        </div>
                        <div class="delivery__dropdown">
                            <p>Доставка крупномерных растений (от 100см), больших заказов, высоких или тяжелых кашпо осуществляется грузовым автомобилем.</p>
                            <p><strong>Крупногабаритная доставка на следующий день или позже:</strong></p>
                            <ul>
                                <li>в пределах МКАД — от '.$in_mkad_large.' рублей;</li>
                                <li>за пределы МКАД (до 5 км) — от '.$out_mkad_large.' рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                            <p><strong>Крупногабаритная срочная “день в день”. Можно оформить до 18:00:</strong></p>
                            <ul>
                                <li>в пределах МКАД — от '.$in_mkad_urg_large.' рублей;</li>
                                <li>за пределы МКАД (до 5 км) — от '.$out_mkad_urg_large.' рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                        </div>
                    </div>';
                }?>				
                    
                <?php if($min_free_delivery) { echo '<p>При заказе товаров на сумму <strong>свыше '.$min_free_delivery.' рублей</strong> доставка осуществляется бесплатно.</p>';}?>				
                
                <div class="delivery__block">
                    <div class="delivery__header">
                        <h2 class="entry-header">Самовывоз</h2>
                        <span class="delivery__dropdown-arrow">next</span>
                    </div>
                    <div class="delivery__dropdown">
                        <h3 class="delivery__heading heading-2">Где</h3>
                        <p>Вы можете бесплатно забрать товары из нашего шоурума по адресу г. Москва, ул. Мещерякова, д.3 (от м. Тушинская или м. Сокол).</p>
                        <div class="info__map">
                            <iframe src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=237252555639" width="560" height="400" frameborder="0"></iframe>
                        </div>

                        <h3 class="delivery__heading heading-2">Когда</h3>
                            <p>Мы ждем Вас ежедневно в рабочие часы.</p>
                            <p>Необходимо предварительно связаться с нами и договорится о времени Вашего прибытия.</p>
                    </div>
                </div>
                
                
                <div class="delivery__block">
                    <div class="delivery__header">
                        <h3 class="entry-header">Интервалы доставки</h3>
                        <span class="delivery__dropdown-arrow">next</span>
                    </div>
                    <div class="delivery__dropdown">
                        <ul>
                            <li>с 11:00 до 21:00;</li>
                            <li>с 11:00 до 16:00;</li>
                            <li>с 14:00 до 18:00;</li>
                            <li>с 18:00 до 21:00.</li>
                        </ul>
                        <p>Мы работаем без выходных, поэтому <strong>доставка осуществляется каждый день.</strong></p>
                        <p>При оформлении срочной доставки “день в день” менеджер согласует с вами удобный интервал доставки.</p>					
                    </div>
                </div>

                <div class="delivery__block">
                    <div class="delivery__header">
                        <h3 class="entry-header">Связаться с нами</h3>
                        <span class="delivery__dropdown-arrow">next</span>
                    </div>
                    <div class="delivery__dropdown">
                        <p>Ничего страшного, если вы не можете принять заказ в согласованные дату и время. В таком случае просим связаться с нами удобным для вас способом.</p>

                        <?php get_template_part('template-parts/contacts-part');?>
                    </div>
                </div>


           

		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>