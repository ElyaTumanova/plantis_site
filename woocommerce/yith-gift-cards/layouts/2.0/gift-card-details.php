<?php
/**
 * Gift Card product add to cart
 *
 * @author  YITH <plugins@yithemes.com>
 * @package yith-woocommerce-gift-cards\templates\yith-gift-cards\
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


global $product;

?>


<div class="gift-card-content-editor step-content clearfix">


  <h3>Куда отправить сертификат</h3>
  <div class="ywgc-recipient-email clearfix">
    <label for="ywgc-recipient-email">Ваша почта</label>
    <input type="email" id="ywgc-recipient-email" name="ywgc-recipient-email[]" <?php echo ( $mandatory_recipient ) ? 'required' : ''; ?>
    class="ywgc-recipient yith_wc_gift_card_input_recipient_details"/>
  </div>
  <h3>Кому дарим</h3>
  <div class="ywgc-recipient-name clearfix">
    <label for="ywgc-recipient-name">Имя получателя</label>
    <input type="text" id="ywgc-recipient-name" name="ywgc-recipient-name[]" <?php echo ( $mandatory_recipient ) ? 'required' : ''; ?> 
    class="yith_wc_gift_card_input_recipient_details">
  </div>
  <div class="ywgc-message clearfix">
    <label for="ywgc-edit-message">Добавьте теплых слов</label>
		<textarea id="ywgc-edit-message" name="ywgc-edit-message" rows="5"></textarea>
	</div>

	<?php if ( 'yes' === get_option( 'ywgc_ask_sender_name', 'yes' ) ) : ?>
		<div class="ywgc-sender-name clearfix">
      <label for="ywgc-sender-name">Имя отправителя</label>
			<input type="text" name="ywgc-sender-name" id="ywgc-sender-name" 
      value="<?php echo wp_kses( apply_filters( 'ywgc_sender_name_value', '' ), 'post' ); ?>">
		</div>
	<?php endif; ?>
	
</div>