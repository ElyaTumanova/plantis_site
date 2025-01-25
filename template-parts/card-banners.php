<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
$min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
?>

<div class="card_banner text-ani-show " id="card_banner_photo">
    <p>Напишите нам, и мы пришлём вам “живые” фотографии этого растения</p>
    <?php get_template_part('template-parts/social-media-btns');?>
    <span class = "backorder-info">В наличии <?php echo $product->get_stock_quantity();?> шт. Если вы хотите заказать большее количество, то ориентировочная дата доставки из Европы <?php echo plnt_set_backorders_date();?>. После оформления заказа наш менеджер свяжется с вами для уточнения деталей заказа.</span>
</div>
<div class="card_banner" id="card_banner_backorder">
    <p>Растение под заказ из Европы, ориентировочная дата доставки <?php echo plnt_set_backorders_date();?>. После оформления заказа наш менеджер свяжется с вами для уточнения деталей заказа.</p>
</div>
<div class="card_banner" id="card_banner_peresadka">
    <p>При покупке <a href="<?php echo get_site_url()?>/product-category/gorshki_i_kashpo/" target="_blank" rel="noopener">горшка </a>— пересадка в подарок!</p>
</div>
<div class="card_banner" id="card_banner_treez">
    <p> Минимальная сумма заказа для кашпо и искусственных растений Treez <span><?php echo $min_treez_delivery?></span> рублей (без учета стоимости других товаров)</p>
    <p> Оплатить заказ с кашпо и/или искусственными растениями Treez можно будет после подтверждения их наличия. Наш менеджер свяжется с Вами после оформления заказа.</p>
</div>
