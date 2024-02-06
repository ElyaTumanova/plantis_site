<?php
get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
        <div class="delivery">
            <div class="delivery__wrap">
                <h1 class="entry-header">Доставка</h1>

                <h3 class="delivery__heading heading-2">Если ваш заказ <b>от 2000</b> рублей:</h3>
                <div class="delivery__text">
                    <p class="delivery__subheading"><strong>Доставка на следующий день или позже:</strong></p>
                        <ul>
                            <li>в пределах МКАД — 590 рублей;</li>
                            <li>за пределы МКАД (до 5 км) — 790 рублей.</li>
                        </ul>
                    <p class="delivery__subheading"><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                    <ul>
                        <li>в пределах МКАД — 790 рублей;</li>
                        <li>за пределы МКАД (до 5 км) — 990 рублей.</li>
                    </ul>				
                </div>
                
                <h3 class="delivery__heading heading-2">Если ваш заказ <b>до 2000</b> рублей:</h3>
                <div class="delivery__text">
				    <p class="delivery__subheading"><strong>Доставка на следующий день или позже:</strong></p>
                    <ul>
                        <li>в пределах МКАД — 790 рублей;</li>
                        <li>за пределы МКАД (до 5 км) — 990 рублей.</li>
                    </ul>
                    <p class="delivery__subheading"><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                    <ul>
                        <li>в пределах МКАД — 990 рублей;</li>
                        <li>за пределы МКАД (до 5 км) — 1190 рублей.</li>
                    </ul>
                    <p class="delivery__note">В итоговой стоимости заказа не учитывается цена доставки!</p>
                    <p>Мы работаем без выходных, поэтому <strong>доставка осуществляется каждый день.</strong></p>
                    <p>При заказе товаров на сумму <strong>свыше 15 000 рублей</strong> доставка осуществляется бесплатно.</p>					
                </div>

                <h3 class="delivery__heading heading-2">Интервалы доставки</h3>
                <div class="delivery__text">
                    <ul>
                        <li>с 11:00 до 21:00;</li>
                        <li>с 11:00 до 16:00;</li>
                        <li>с 14:00 до 18:00;</li>
                        <li>с 16:00 до 21:00.</li>
                    </ul>
                    <p>При оформлении срочной доставки “день в день” менеджер согласует с вами удобный интервал доставки.</p>					
                </div>

                <h3 class="delivery__heading heading-2">Связаться с нами</h3>
                <p>Ничего страшного, если вы не можете принять заказ в согласованные дату и время. В таком случае просим связаться с нами удобным для вас способом.</p>

                <?php get_template_part('template-parts/contacts_part');?>

            </div>
            <div class="delivery__wrap">
                <h2 class="entry-header">Самовывоз</h2>
                
                <h3 class="delivery__heading heading-2">Где</h3>
                <p>Вы можете бесплатно забрать товары из нашего шоурума по адресу г. Москва, ул. Мещерякова, д.3 (от м. Тушинская или м. Сокол).</p>
                <div class="delivery__map">
					<iframe src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=237252555639" width="560" height="400" frameborder="0"></iframe>
				</div>

                <h3 class="delivery__heading heading-2">Когда</h3>
                    <p>Мы ждем Вас ежедневно в рабочие часы.</p>
                    <p>Необходимо предварительно связаться с нами и договорится о времени Вашего прибытия.</p>
            </div>

            					
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>