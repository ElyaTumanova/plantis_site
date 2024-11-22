<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="modal-mob burger-menu">
    <div class="modal-mob__close burger-menu__close button">&#10006;</div>

    <div class="menu__item_accent ">Контакты</div>
    <div class="menu__item menu__item_geo"><a href="https://yandex.ru/maps/-/CXQ-ErQ" target="blank">Москва, ул. Мещерякова, д.3 </a></div>
    <div class="menu__item"><a href="tel:+78002015790">8 800 201 57 90</a> | <a href="tel:+79647687944">8 964 768 79 44</a></div>
    <div class="menu__item">
        <?php get_template_part('template-parts/social-media-btns');?>
    </div>
    <div class="menu__item">Прием заказов круглосуточно</div>
    <div class="menu__item">Шоурум работает ежедневно с 10 до 20</div>
    <div class="menu__item"><a href="mailto:INFO@PLANTIS.SHOP" target="blank">INFO@PLANTIS.SHOP </a></div>
    <div class="menu__item">
        <a href="https://t.me/plantis" class="header__telegram button" role="button" target="blank">
            <span class="header__telegram-icon">
                <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.4223 1.02293C13.4223 1.02293 14.7544 0.503503 14.6434 1.76497C14.6064 2.28441 14.2734 4.10241 14.0144 6.06883L13.1263 11.8939C13.1263 11.8939 13.0523 12.7472 12.3862 12.8956C11.7202 13.044 10.7211 12.3762 10.5361 12.2278C10.3881 12.1165 7.7609 10.4469 6.8358 9.63063C6.57677 9.40801 6.28075 8.96278 6.8728 8.44335L10.7581 4.73316C11.2022 4.28793 11.6462 3.24907 9.79603 4.51054L4.61563 8.03525C4.61563 8.03525 4.02359 8.40626 2.91352 8.07235L0.508316 7.3303C0.508316 7.3303 -0.379756 6.77378 1.13737 6.21722C4.83767 4.47341 9.38902 2.69251 13.4223 1.02293Z" fill="white"></path>
                </svg>
            </span>
            <span class="header__telegram-text">Telegram канал</span>
        </a>
    </div>

    <div class="menu__item_accent ">Услуги</div>
    <div class="menu__item"> <a href="<?php echo site_url()?>/usluga-peresadki-komnatnyh-rastenij/">Пересадка</a></div>
    <div class="menu__item"> <a href="<?php echo site_url()?>/landscaping/">Озеленение</a></div>
    <div class="menu__item"> <a href="<?php echo site_url()?>/pokupka-komnatnyh-rastenij-optom/">Оптовая покупка растений</a></div>
    
    <?php get_template_part('template-parts/info-pages-list');?>
    

    <?php if (!is_user_logged_in()) : ?> 
        <div class="menu__item_accent menu__item_enter burger-menu__account">Личный кабинет</div>
    <?php else :?>
        <div class="menu__item_accent">
            <a href ="<?php echo esc_url( home_url( '/my-account/orders' ) ); ?>" >Личный кабинет</a>
        </div>
    <?php endif; ?>
</div>