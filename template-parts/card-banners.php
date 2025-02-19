<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
global $plants_cat_id;
global $treez_cat_id;
global $plants_treez_cat_id;
$parentCatId = check_category ($product);
$min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
?>


<?php if ($parentCatId === $plants_cat_id) :?>
    <?php if ( $product->get_stock_status() ==='instock') :?>
    <div class="card_banner" id="card_banner_photo">
        <p>Напишите нам, и мы пришлём вам “живые” фотографии этого растения</p>
        <?php get_template_part('template-parts/social-media-btns');?>
        <span class = "backorder-info">В наличии <?php echo $product->get_stock_quantity();?> шт. Если вы хотите заказать большее количество, то ориентировочная дата доставки из Европы <?php echo plnt_set_backorders_date();?>. После оформления заказа наш менеджер свяжется с вами для уточнения деталей заказа.</span>
    </div>
    <?php endif; ?>
    <?php if ( $product->get_stock_status() ==='onbackorder') :?>
        <div class="card_banner" id="card_banner_backorder">
            <p>Растение под заказ из Европы, ориентировочная дата доставки <?php echo plnt_set_backorders_date();?>. <br>После оформления заказа наш менеджер свяжется с вами для уточнения деталей заказа.</p>
        </div>
    <?php endif; ?>
<?php endif; ?>
<div class="card_banner" id="card_banner_peresadka">
    <p>При покупке <a href="<?php echo get_site_url()?>/product-category/gorshki_i_kashpo/" target="_blank" rel="noopener">горшка </a>— пересадка в подарок!*</p>
    <cite>*кроме кашпо Treez и Lechuza диаметром больше 26 см</cite>
</div>
<?php if ($parentCatId === $treez_cat_id || $parentCatId === $plants_treez_cat_id) :?>
    <div class="card_banner" id="card_banner_treez">
        <p> Минимальная сумма заказа для кашпо и искусственных растений Treez <span><?php echo $min_treez_delivery?></span> рублей (без учета стоимости других товаров)</p>
        <p> Оплатить заказ с кашпо и/или искусственными растениями Treez можно будет после подтверждения их наличия. Наш менеджер свяжется с Вами после оформления заказа.</p>
    </div>
<?php endif; ?>


