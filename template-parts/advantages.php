<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="advantages">
  <div class="advantages__wrap">
    <img width="111" height="100" src="<?php echo get_template_directory_uri()?>/images/advantages/фото_зел.svg" class="advantages__image" alt="" loading="lazy">														
    <div class="advantages__title">“Живые” фотографии</div>	
    <div class="advantages__descr">Перед оплатой мы пришлём вам фотографии именно того растения, которое поедет к вам домой</div>
  </div>
  <div class="advantages__wrap">
    <img width="110" height="100" src="<?php echo get_template_directory_uri()?>/images/advantages/пересадка_зел.svg" class="advantages__image" alt="" loading="lazy">
    <div class="advantages__title">Бесплатная пересадка</div>
    <div class="advantages__descr">Абсолютно бесплатно пересадим комнатное растение, если вы приобретаете его вместе с горшком из нашего каталога</div>		
  </div>
  <div class="advantages__wrap">									
    <img width="115" height="100" src="<?php echo get_template_directory_uri()?>/images/advantages/доставка_зел.svg" class="advantages__image" alt="" loading="lazy">														
    <div class="advantages__title">Бережная доставка</div>
    <?php $min_free_delivery = carbon_get_theme_option('min_free_delivery');?>
    <div class="advantages__descr">Осуществляем доставку комнатных растений до двери. <?php if($min_free_delivery) { echo 'Если сумма покупки будет выше '.$min_free_delivery.' рублей, то делаем это бесплатно';}?></div>	
  </div>
  <div class="advantages__wrap">		
    <img width="112" height="100" src="<?php echo get_template_directory_uri()?>/images/advantages/уход_зел.svg" class="advantages__image" alt="" loading="lazy">												
    <div class="advantages__title">Бесплатная консультация</div>
    <div class="advantages__descr">Поможем подобрать нового друга, ответим на вопросы по уходу и останемся на связи с вами даже после покупки</div>
  </div>	
</div>