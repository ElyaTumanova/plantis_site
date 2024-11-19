<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
?>

<div class="card_banner" id="card_banner_photo">
    <p>Напишите нам, и мы пришлём вам “живые” фотографии этого растения</p>
    <?php get_template_part('template-parts/social-media-btns');?>
</div>
<div class="card_banner" id="card_banner_peresadka">
    <p>При покупке <a href="<?php echo get_site_url()?>/product-category/gorshki_i_kashpo/" target="_blank" rel="noopener">горшка </a>— пересадка в подарок!</p>
</div>
<div class="card_banner" id="card_banner_treez">
    <p> Минимальная сумма заказа для кашпо Treez и/или искусственных растений Treez<span><?php echo $min_treez_delivery?></span> рублей (без учета стоимости других товаров)</p>
    <p> Оплатить заказ с кашпо Treez можно будет после подтверждения наличия кашпо. Наш менеджер свяжется с Вами после оформления заказа.</p>
</div>
