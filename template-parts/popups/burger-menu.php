<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="modal-mob burger-menu">
    <div class="modal-mob__close burger-menu__close button">&#10006;</div>
    <div class="menu__item_accent header__catalog_mob">Каталог</div>
    <!-- контакты -->
    <div class="menu__item_accent ">Контакты</div>
    <div class="menu__item menu__item_geo"><a href="https://yandex.ru/maps/-/CXQ-ErQ" target="blank">Москва, ул. Мещерякова, д.3 </a> 
    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="21" viewBox="0 0 17 21" fill="none">
        <path d="M8.49991 11.9085C7.82566 11.9085 7.16654 11.7086 6.60592 11.334C6.0453 10.9594 5.60835 10.427 5.35032 9.80402C5.0923 9.18109 5.02479 8.49564 5.15633 7.83434C5.28787 7.17304 5.61255 6.5656 6.08932 6.08883C6.56609 5.61206 7.17353 5.28738 7.83483 5.15584C8.49613 5.0243 9.18158 5.09181 9.80451 5.34983C10.4274 5.60786 10.9599 6.04481 11.3345 6.60543C11.7091 7.16606 11.909 7.82517 11.909 8.49942C11.9079 9.40324 11.5484 10.2697 10.9093 10.9088C10.2702 11.5479 9.40372 11.9074 8.49991 11.9085V11.9085ZM8.49991 6.45397C8.09536 6.45397 7.69989 6.57393 7.36352 6.79869C7.02714 7.02345 6.76497 7.3429 6.61016 7.71666C6.45534 8.09042 6.41484 8.50169 6.49376 8.89847C6.57268 9.29525 6.76749 9.65971 7.05356 9.94577C7.33962 10.2318 7.70408 10.4266 8.10086 10.5056C8.49764 10.5845 8.90891 10.544 9.28267 10.3892C9.65643 10.2344 9.97588 9.97219 10.2006 9.63581C10.4254 9.29944 10.5454 8.90397 10.5454 8.49942C10.5448 7.9571 10.3291 7.43715 9.94566 7.05367C9.56219 6.67019 9.04223 6.45451 8.49991 6.45397V6.45397Z" fill="black" stroke="black" stroke-width="0.4"/>
        <path d="M8.5 20.0909L2.74819 13.3075C2.66827 13.2056 2.58917 13.1031 2.51092 13C1.5284 11.7057 0.997626 10.1249 1.00001 8.49999C1.00001 6.51087 1.79018 4.60322 3.1967 3.1967C4.60323 1.79018 6.51088 1 8.5 1C10.4891 1 12.3968 1.79018 13.8033 3.1967C15.2098 4.60322 16 6.51087 16 8.49999C16.0024 10.1242 15.4718 11.7043 14.4898 12.9979L14.4891 13C14.4891 13 14.2845 13.2686 14.2539 13.3048L8.5 20.0909ZM3.5991 12.1784C3.5991 12.1784 3.75864 12.3884 3.79478 12.4334L8.5 17.9827L13.2114 12.4259C13.2414 12.3884 13.4016 12.1764 13.4016 12.1764C14.2042 11.1189 14.6379 9.82751 14.6364 8.49999C14.6364 6.87253 13.9898 5.31172 12.8391 4.16093C11.6883 3.01014 10.1275 2.36363 8.5 2.36363C6.87254 2.36363 5.31173 3.01014 4.16094 4.16093C3.01015 5.31172 2.36364 6.87253 2.36364 8.49999C2.36221 9.82834 2.79576 11.1205 3.5991 12.1784Z" fill="black" stroke="black" stroke-width="0.4"/>
    </svg>
    </div>
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

    <!-- услуги -->
    <div class="menu__item_accent ">Услуги</div>
    <div class="menu__item"> <a href="<?php echo site_url()?>/usluga-peresadki-komnatnyh-rastenij/">Пересадка</a></div>
    <div class="menu__item"> <a href="<?php echo site_url()?>/landscaping/">Озеленение</a></div>
    <div class="menu__item"> <a href="<?php echo site_url()?>/pokupka-komnatnyh-rastenij-optom/">Оптовая покупка растений</a></div>

    <!-- информация -->
    <?php get_template_part('template-parts/info-pages-list');?>
    
    <!-- личный кабинет -->
    <?php if (!is_user_logged_in()) : ?> 
        <div class="menu__item_accent menu__item_enter burger-menu__account">Личный кабинет 
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" viewBox="0 0 14 12" fill="none">
                <path d="M4.5 3.5V2.25C4.5 1.91848 4.6317 1.60054 4.86612 1.36612C5.10054 1.1317 5.41848 1 5.75 1H12.25C12.5815 1 12.8995 1.1317 13.1339 1.36612C13.3683 1.60054 13.5 1.91848 13.5 2.25V9.75C13.5 10.0815 13.3683 10.3995 13.1339 10.6339C12.8995 10.8683 12.5815 11 12.25 11H5.75C5.41848 11 5.10054 10.8683 4.86612 10.6339C4.6317 10.3995 4.5 10.0815 4.5 9.75V8.5M7.5 8.5L10 6L7.5 3.5M0.5 6H9.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    <?php else :?>
        <div class="menu__item_accent">
            <a href ="<?php echo esc_url( home_url( '/my-account/orders' ) ); ?>" >Личный кабинет</a>
        </div>
    <?php endif; ?>
</div>