<?php get_header();

function my_get_logged_in_user_email() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        return ! empty( $user->user_email ) ? $user->user_email : '';
    }
    return '';
}

function my_logged_in_email_focus_class() {
    $email = my_get_logged_in_user_email();
    return $email !== '' ? ' focus' : '';
}

function my_get_logged_in_user_name() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();

        // приоритет: first_name → display_name → user_login
        if ( ! empty( $user->first_name ) ) {
            return $user->first_name;
        }

        if ( ! empty( $user->display_name ) ) {
            return $user->display_name;
        }

        return $user->user_login;
    }

    return '';
}

function my_logged_in_name_focus_class() {
    $name = my_get_logged_in_user_name();
    return $name !== '' ? ' focus' : '';
}

$gcid = 15419;?>
<div class="content-area">
  <div class="gift-content-area">
    <h1 class="gift-card__title">Электронный подарочный сертификат</h1>
    <button class="gift-card__example-btn page-popup-open-btn">Посмотреть пример</button>
    <a class="gift-card__example-btn" href="<?php echo get_site_url()?>/gift-card" target="_blank">Проверить баланс</a>
    <div class="gift-image-wrap">
      <img src="<?php echo get_template_directory_uri()?>/images/gift-card/gc_cover.webp" class="gift-image" alt="Подарочная карта">
      <p class="gift-image-amount">1500<span>₽</span></p>
    </div>


    <form method="post" action="/wp-admin/admin-post.php" class="gift-cards_form" novalidate>

      <input type="hidden" name="action" value="giftcard_pay">
      <input type="hidden" name="giftcard_product_id" value="15419"> <!-- ID товара gift-card -->
      <input type="number" name="giftcard_amount" id="giftcard_amount" value="" min="1" required style="display:none;">
      <h3 class="gift_select_amount_title">Выберите желаемую сумму подарка</h3>
      <div class="gift-input-wrap">
        <input id="gift-manual-amount" 
        name="gift-manual-amount" 
        class="gift-manual-amount" 
        type="number" placeholder="" 
        value="10" 
        required
        min=10
        max=30000
        >
        <span class="field__errors"></span>
      </div>

      <p class="gift__note">Можно ввести любую сумму от 1500 до 30&nbsp;000&nbsp;₽</p>
      <div class="gift__amounts gift-swiper-wrap">
          <div class="swiper-wrapper">
            <p class="swiper-slide" data-amount="1500">1500<span>₽</span></p>
            <p class="swiper-slide" data-amount="2000">2000<span>₽</span></p>
            <p class="swiper-slide" data-amount="3000">3000<span>₽</span></p>
            <p class="swiper-slide" data-amount="4000">4000<span>₽</span></p>
            <p class="swiper-slide" data-amount="5000">5000<span>₽</span></p>
            <p class="swiper-slide" data-amount="10000">10000<span>₽</span></p>
            <p class="swiper-slide" data-amount="15000">15000<span>₽</span></p>
            <p class="swiper-slide" data-amount="20000">20000<span>₽</span></p>
            <p class="swiper-slide" data-amount="25000">25000<span>₽</span></p>
            <p class="swiper-slide" data-amount="30000">30000<span>₽</span></p>
          </div>
      </div>

      <div class="gift-card-content">

        <h3>Куда отправить сертификат</h3>
        <div class="gift-buyer-name gift-input-wrap gift-input-wrap_labeled">
          <label for="gift-buyer-name">Как вас зовут*</label>
          <input type="text"
                id="gift-buyer-name"
                name="gift-buyer-name[]"
                value="<?php echo esc_attr( my_get_logged_in_user_name() ); ?>"
                required
                class="gift-recipient yith_wc_gift_card_input_recipient_details <?php echo esc_attr( my_logged_in_name_focus_class() ); ?>">
          <span class="field__errors"></span>
        </div>
        <div class="gift-recipient-email gift-input-wrap gift-input-wrap_labeled">
          <label for="gift-recipient-email">Ваша почта*</label>
          <input type="email"
                id="gift-recipient-email"
                name="gift-recipient-email[]"
                value="<?php echo esc_attr( my_get_logged_in_user_email() ); ?>"
                required
                pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
                title="Введите корректный email в формате name@example.com"
                class="gift-recipient yith_wc_gift_card_input_recipient_details <?php echo esc_attr( my_logged_in_email_focus_class() ); ?>">
          <span class="field__errors"></span>
          <p class="gift-recipient-note">Ссылка на подарочный сертификат будет направлена на указанную почту автоматически после оплаты</p>
        </div>
        <div class="gift-recipient-phone gift-input-wrap gift-input-wrap_labeled">
          <label for="gift-recipient-phone">Ваш номер телефона*</label>
          <input type="tel"
                id="gift-recipient-phone"
                name="gift-recipient-phone[]"
                required
                pattern="^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$"
                title="Введите номер в формате: +7 (XXX) XXX-XX-XX"
                class="gift-recipient yith_wc_gift_card_input_recipient_details">
          <span class="field__errors"></span>
          <p class="gift-recipient-note">Ваш номер телефона для связи</p>
        </div>

        <h3>Кому дарим</h3>
        <div class="gift-recipient-name gift-input-wrap gift-input-wrap_labeled">
          <label for="gift-recipient-name">Имя получателя*</label>
          <input type="text"
                id="gift-recipient-name"
                name="gift-recipient-name[]"
                required
                class="yith_wc_gift_card_input_recipient_details">
          <span class="field__errors"></span>
        </div>

        <div class="gift-message gift-input-wrap gift-input-wrap_labeled">
          <label for="gift-edit-message">Добавьте теплых слов</label>
          <textarea id="gift-edit-message" name="gift-edit-message" rows="5"></textarea>
        </div>

        <div class="gift-buyer-name gift-input-wrap gift-input-wrap_labeled">
          <label for="gift-buyer-name">Имя отправителя</label>
          <input type="text" name="gift-buyer-name" id="gift-buyer-name" value="">
        </div>

      </div>

      <button type="submit" class = "gift-card__submit-btn button">К оплате</button>
    </form>

    <div class="gift-card__info">
      <h2 class="giftcard-advantages__title">Преимущества подарочного сертификата</h2>
      <div class="giftcard-advantages">
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">01</span>
          <div class="giftcard-advantages__descr">Подходит для физлиц и корпоративных клиентов</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">02</span>
          <div class="giftcard-advantages__descr">Гибкий номинал сертификата от 1500 до 30 000 рублей</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">03</span>
          <div class="giftcard-advantages__descr">Можно использовать сертификат онлайн или в шоурумах</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">04</span>
          <div class="giftcard-advantages__descr">Можно оплачивать товары и услуги со скидкой</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">05</span>
          <div class="giftcard-advantages__descr">Можно использовать многократно в рамках номинала и срока действия</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">06</span>
          <div class="giftcard-advantages__descr">Удобно, если вы не знаете, какое именно растение подойдет </div>
        </div>
      </div>
    </div>
    <h2 class="giftcard-advantages__title">Часто задаваемые вопросы</h2>
    <?php get_template_part( 'template-parts/gift-card-faq' );?>
  </div>
</div>
<?php get_template_part('template-parts/popups/gift-card-popup');?>


<?php get_footer();?>