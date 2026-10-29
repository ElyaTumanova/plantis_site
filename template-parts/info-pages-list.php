<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function menu_item_class( $slug ) {
    return is_page( $slug ) ? 'is-active' : '';
}
?>
<!-- <div class="menu__item_accent ">Информация</div> -->

<div class="menu__item <?php echo menu_item_class('delivery'); ?>"> 
    <?php echo plnt_icon('car-transparent');?>
    <a href="<?php echo site_url()?>/delivery/">Доставка и самовывоз (Москва и МО)</a>
</div>

<div class="menu__item <?php echo menu_item_class('dostavka-komnatnyh-rastenij-po-rossii'); ?>"> 
    <?php echo plnt_icon('box-transparent');?>
    <a href="<?php echo site_url()?>/dostavka-komnatnyh-rastenij-po-rossii/">Доставка комнатных растений по России</a>
</div>

<div class="menu__item <?php echo menu_item_class('payment'); ?>"> 
    <?php echo plnt_icon('payment');?>
    <a href="<?php echo site_url()?>/payment/">Оплата</a>
</div>

<div class="menu__item <?php echo menu_item_class('about-us'); ?>"> 
    <?php echo plnt_icon('about');?>
    <a href="<?php echo site_url()?>/about-us/">О нас</a>
</div>

<div class="menu__item <?php echo menu_item_class('refund_returns'); ?>"> 
    <?php echo plnt_icon('returns');?>
    <a href="<?php echo site_url()?>/refund_returns/">Возврат и обмен</a>
</div>

<div class="menu__item <?php echo menu_item_class('faq'); ?>"> 
    <?php echo plnt_icon('faq');?>
    <a href="<?php echo site_url()?>/faq/">Вопросы и ответы</a>
</div>

<div class="menu__item <?php echo menu_item_class('partners'); ?>"> 
    <?php echo plnt_icon('partners');?>
    <a href="<?php echo site_url()?>/partners/">Поставщикам и партнерам</a>
</div>

<div class="menu__item <?php echo menu_item_class('vakansii'); ?>"> 
     <?php echo plnt_icon('vacancies');?>
    <a href="<?php echo site_url()?>/vakansii/">Вакансии</a>
</div>