<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
global $plants_cat_id;
global $plants_treez_cat_id;
global $delivery_inMKAD;

$parentCatId  = plnt_get_parent_cat_id();
$isTreez      = plnt_is_treez_product();
$isLechuza    = plnt_is_lechuza_product();
$stock_qty   = $product->get_stock_quantity();
$stock_status = $product->get_stock_status();

$backorders_date = plnt_set_backorders_date();


$shipping_costs = plnt_get_shiping_costs();
$in_mkad = $shipping_costs[$delivery_inMKAD];
$current_hour = (int) current_datetime()->format('G');
?>

<div class="card__banners-wrap">

	<?php if ( $parentCatId === $plants_cat_id ) : ?>

		<?php if ($stock_status === 'instock' ) : ?>
			<div class="card__banner card__banner--photo">
				<div class="card__banner-photo-wrap">
					<p>Хотите увидеть как выглядит растение перед покупкой?</p>
					<span class="divider"></span>
					<p>Просто напишите нам</p>

					<?php get_template_part( 'template-parts/social-media-btns' ); ?>
				</div>

				<img
					class="card__banner-image"
					src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/card-banner-photo-mob.png' ); ?>"
					alt=""
					width="152"
					height="160"
				>
			</div>
		<?php endif; ?>

		<?php if ($stock_status === 'onbackorder' ) : ?>
			<div class="card__banner card__banner--backorder">
				<span class="card__banner-icon">
          <?php echo plnt_icon('tag');?>
        </span>

				<p class="card__banner-title">Растение под заказ из Европы</p>

				<p class="card__banner-text">
					После оформления заказа наш менеджер свяжется с вами для уточнения деталей.
				</p>

				<div class="card__banner-pill card__banner-pill--grid card__banner-backorder-date-wrap">
					<span>дата доставки</span>

					<span class="card__banner-backorder-date" data-backorder-date>
						<?php echo $backorders_date; ?>
					</span>
				</div>
			</div>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( $isTreez ) : ?>
		<div class="card__banner card__banner--warehouse">
			<?php if ( $parentCatId === $plants_treez_cat_id ) : ?>
				<p>Доставка искусственных растений Treez осуществляется со склада в течение 3–7 дней.</p>
			<?php else : ?>
				<p>Доставка этого кашпо Treez осуществляется со склада в течение 3–7 дней.</p>
			<?php endif; ?>

			<p>
				Оплатить заказ с кашпо и/или искусственными растениями Treez можно будет после подтверждения их наличия.
				Наш менеджер свяжется с вами после оформления заказа.
			</p>
		</div>
	<?php endif; ?>

	<?php if ( $isLechuza ) : ?>
		<div class="card__banner card__banner--warehouse">
			<p>Доставка этого кашпо Lechuza осуществляется со склада в течение 3–7 дней.</p>

			<p>
				Оплатить заказ с кашпо Lechuza можно будет после подтверждения их наличия.
				Наш менеджер свяжется с вами после оформления заказа.
			</p>
		</div>
	<?php endif; ?>

	<?php if ($stock_status === 'outofstock' ) : ?>
		<div class="card__banner card__banner--outofstock">
			<img
				class="card__banner-image"
				src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/card-banner-outofstock.png' ); ?>"
				alt=""
				width="135"
				height="171"
			>

			<div class="card__banner-outofstock-wrap">
				<p class="card__banner-title">К сожалению, данный товар закончился</p>

				<p class="card__banner-text">
					Вы можете оставить предзаказ на сайте или связаться с нами удобным способом, и мы привезём его под заказ.
				</p>

				<?php get_template_part( 'template-parts/social-media-btns' ); ?>
			</div>
		</div>
	<?php endif; ?>

  <?php if ($stock_status === 'instock' ) : ?>
    <div class="card__banner card__banner--backorder-info">
      <span class="card__banner-icon card__banner-icon--stock">
        <?php echo plnt_icon('warning');?>
      </span>

      <span class="card__banner-title">
        В наличии <?php echo esc_html( $stock_qty ); ?> шт.
      </span>

      <p class="card__banner-text">
        Если вам нужно большее количество, после оформления заказа наш менеджер свяжется с вами для уточнения деталей.
      </p>

      <span class="card__banner-pill card__banner-pill--row">
        <span>Дата доставки</span>
        <span class="card__banner-backorder-date" data-backorder-date>
          <?php echo $backorders_date; ?>
        </span>
      </span>
    </div>
  <?php endif; ?>

</div>

<?php
$text1 = $current_hour < 18
  ? 'Сегодня, от 2 часов'
  : 'Завтра, с 11:00 до 22:00';

$text2 = $current_hour < 20
  ? 'Сегодня, с 10:00 до 20:00'
  : 'Завтра, с 10:00 до 20:00';

$show_delivery = false;

if ( $stock_status === 'instock' && ! ( $isTreez || $isLechuza ) ) :
  $show_delivery = true;

elseif ( $stock_status === 'onbackorder' && $parentCatId === $plants_cat_id ) :
  $text1 = "с {$backorders_date}, с 11:00 до 22:00";
  $text2 = "с {$backorders_date}, с 10:00 до 20:00";
  $show_delivery = true;
endif;
?>

<?php if ( $show_delivery ) : ?>
  <div class="card__delivery-wrap">
    <div class="card__delivery-card">
      <div class="card__delivery-title">Доставка</div>
      <div class="card__delivery-date"><?php echo esc_html( $text1 ); ?></div>
      <div class="card__delivery-price">от <?php echo esc_html( $in_mkad ); ?>₽</div>
    </div>

    <div class="card__delivery-card">
      <div class="card__delivery-title">Самовывоз</div>
      <div class="card__delivery-date"><?php echo esc_html( $text2 ); ?></div>
      <div class="card__delivery-price">Бесплатно</div>
    </div>
  </div>
<?php endif; ?>