<?php get_header(); 

$gcnum = get_query_var('gcnum');
$gift_card_id = mytheme_get_giftcard_by_code( $gcnum );
$gift_card = get_post_meta( $gift_card_id );
echo('<pre>');
if ( $gift_card ) {
    echo 'Карта найдена.';
    // echo 'Номер карты: ' . esc_html( $gift_card->gift_card_number );
    // echo '<br>Баланс: ' . wc_price( $gift_card->get_balance() );
    // echo '<br>Действует до: ' . date_i18n( get_option( 'date_format' ), $gift_card->get_expiration() );
} else {
    echo 'Карта с таким номером не найдена.';
}
print_r($gcnum);
print_r($gift_card);
echo('</pre>');
?>


<div class="gift-card-content-area">
  <h1 class="gift-card__title">Подарочный сертификат</h1>
  <p class="gift-card__descr">Интернет магазин комнатных растений Plantis</p>
  <div class="gift-card__main">
    <div class="gift-card__wrap">
      <div class="gift-image-wrap">
        <img src="https://plantis-shop.ru/wp-content/uploads/2025/07/decor-n.webp" class="gift-image" alt="Подарочная карта" loading="lazy">
        <p class="gift-image-amount"><?php echo $gift_card['_ywgc_balance_total'][0] ?><span>₽</span></p>
      </div>
      <div class="gift-card__row">
        <p>Номер сертификата:</p>
        <p class="copy-wrap">
          <span id="gift-code"><?php echo $gcnum?></span>
          <button class="copy-btn" type="button" data-copy-target="#gift-code">Скопировать</button>
        </p>
      </div>
      <div class="gift-card__row">
        <p>Срок действия сертификата:</p>
        <p><?php echo $gift_card['_ywgc_expiration_date_formatted'][0] ?></p>
      </div>
      <a class="button gift-card__btn" href="<?php echo get_site_url()?>/shop">К покупкам</a>
    </div>

    <div class="gift-card__greeting">
      <p class="gift-card__greeting-to"><?php echo $gift_card['_ywgc_recipient_name'][0] ?></p>
      <p class="gift-card__greeting-text"><?php echo $gift_card['_ywgc_message'][0] ?></p>
      <p class="gift-card__greeting-from"><?php echo $gift_card['_ywgc_sender_name'][0] ?></p>
    </div>
  </div>

  <div  class="faq-items">
    <div class="faq-item">
      <div class="faq-question">
        <h3>Как использовать подарочный сертификат?</h3>
      </div>
      <div class="faq-answer">
        <ol>
          <li>Зайдите на наш сайт — выбирайте всё, что нравится.</li>
          <li>Добавьте выбранные товары в корзину.</li>
          <li>Перейдите к оформлению заказа</li>
          <li>В поле <strong>«Подарочный сертификат»</strong> введите номер из вашего сертификата.</li>
          <li>Подтвердите заказ — готово!</li>
        </ol>
        <p>Приятных покупок!</p>
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
        <h3>Не получается применить — что делать?</h3>
      </div>
      <div class="faq-answer">
        <p>
          Проверьте баланс сертификата
          <a target="_blank" rel="noopener noreferrer" href="#">по ссылке</a>
          или свяжитесь с нами удобным вам способом. Нам можно позвонить:
          <a href="tel:+78002015790">8 800 201 57 90</a>
          (каждый день с 10:00 до 20:00 по Москве) или написать:
          <a target="_blank" rel="noopener noreferrer" href="mailto:INFO@PLANTIS.SHOP">INFO@PLANTIS.SHOP</a>.
        </p>
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
        <h3>Сколько сертификатов можно применить в одном заказе?</h3>
      </div>
      <div class="faq-answer">
        <p>
          На сайте — только один. Если хотите использовать ещё,
          скажите об этом менеджеру при подтверждении заказа — он точно поможет.
        </p>
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
        <h3>Сколько действует?</h3>
      </div>
      <div class="faq-answer">
        <p>Сертификат действует три месяца со дня покупки. Срок действия сертификата указан на странице сертификата.</p>
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
        <h3>Можно использовать частично?</h3>
      </div>
      <div class="faq-answer">
        <p>
          Конечно! Используйте столько раз, сколько позволяет баланс и срок действия.
          Если стоимость заказа выше баланса, можно оплатить разницу банковской картой.
          Если стоимость ниже, сертификат можно использовать повторно — остаток средств
          останется доступным на балансе сертификата.
        </p>
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
        <h3>Как проверить баланс?</h3>
      </div>
      <div class="faq-answer">
        <p>
          <a target="_blank" rel="noopener noreferrer" href="#">По ссылке</a>
          или свяжитесь с нами удобным вам способом. Нам можно позвонить:
          <a href="tel:+78002015790">8 800 201 57 90</a>
          (каждый день с 10:00 до 20:00 по Москве) или написать:
          <a target="_blank" rel="noopener noreferrer" href="mailto:INFO@PLANTIS.SHOP">INFO@PLANTIS.SHOP</a>.
        </p>
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
        <h3>На что можно потратить?</h3>
      </div>
      <div class="faq-answer">
        <p>
          На любые товары и услуги plantis-shop.ru. Сертификат нельзя применить,
          для покупки другого подарочного сертификата.
        </p>
      </div>
    </div>
  </div>
  
  <div class="gift-card__info">
    <h2 class="giftcard-advantages__title">Преимущества подарочного сертификата</h2>
    <div class="giftcard-advantages">
      <div class="giftcard-advantages__wrap">
        <img width="111" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/фото_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">													
        <div class="giftcard-advantages__descr">Подходит для физлиц и корпоративных клиентов</div>
      </div>
      <div class="giftcard-advantages__wrap">
        <img width="110" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/пересадка_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">

        <div class="giftcard-advantages__descr">Гибкий номинал сертификата от 1500 до 30 000 рублей</div>		
      </div>
      <div class="giftcard-advantages__wrap">									
        <img width="115" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/доставка_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">														

        <div class="giftcard-advantages__descr">Можно использовать сертификат онлайн или в шоурумах</div>	
      </div>
      <div class="giftcard-advantages__wrap">		
        <img width="112" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/уход_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">												
 
        <div class="giftcard-advantages__descr">Можно оплачивать товары и услуги со скидкой</div>
      </div>	
      <div class="giftcard-advantages__wrap">		
        <img width="112" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/уход_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">												

        <div class="giftcard-advantages__descr">Можно использовать многократно в рамках номинала и срока действия</div>
      </div>	
      <div class="giftcard-advantages__wrap">		
        <img width="112" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/уход_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">												

        <div class="giftcard-advantages__descr">Удобно, если вы не знаете, какое именно растение подойдет </div>
      </div>	
    </div>
  </div>



</div>





<?php get_footer();?>