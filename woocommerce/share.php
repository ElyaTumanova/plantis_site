<?php
/**
 * Share template
 * MODIFIED FOR PLANTIS THEME
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
}

$share_link_url   = $share_link_url ?? '';
$share_link_title = $share_link_title ?? '';

$email_subject = apply_filters( 'yith_wcwl_email_share_subject', $share_link_title );
$email_body    = apply_filters( 'yith_wcwl_email_share_body', urlencode( $share_link_url ) );

$share_email_url = add_query_arg(
	array(
		'subject' => $email_subject,
		'body'    => $email_body,
		'title'   => $share_link_title,
	),
	'mailto:'
);

$share_whatsapp_url = $share_whatsapp_url ?? 'whatsapp://send?text=' . urlencode( $share_link_title . ' - ' . $share_link_url );

$share_telegram_url = 'https://telegram.me/share/url?url=' . urlencode( $share_link_url ) . '&text=' . urlencode( $share_link_title );
?>

<div class="yith-wcwl-share">
	<h4 class="yith-wcwl-share-title">Поделиться</h4>

	<div class="yith-wcwl-share-actions">
    
    <ul>
      <li class="yith-wcwl-after-share-section">
        <input
          class="copy-target"
          readonly="readonly"
          type="url"
          name="yith_wcwl_share_url"
          id="yith_wcwl_share_url"
          value="<?php echo esc_attr( $share_link_url ); ?>"
        />
      </li>
      <li class="share-button">
        <a
          class="email"
          href="<?php echo esc_url( $share_email_url ); ?>"
          title="<?php esc_attr_e( 'Email', 'yith-woocommerce-wishlist' ); ?>"
        >
          <?php
          echo plnt_icon('email');
          ?>
        </a>
      </li>
      <li class="share-button">
        <a
          class="whatsapp"
          href="<?php echo esc_url( $share_whatsapp_url ); ?>"
          data-action="share/whatsapp/share"
          target="_blank"
          rel="noopener"
          title="<?php esc_attr_e( 'WhatsApp', 'yith-woocommerce-wishlist' ); ?>"
        >
          <?php
          echo plnt_icon('whatsapp');
          ?>
        </a>
      </li>
      <li class="share-button">
        <a
          class="telegram"
          href="<?php echo esc_url( $share_telegram_url ); ?>"
          data-action="share/telegram/share"
          target="_blank"
          rel="noopener"
          title="<?php esc_attr_e( 'Telegram', 'yith-woocommerce-wishlist' ); ?>"
        >
          <?php
          echo plnt_icon('telegram');
          ?>
        </a>
      </li>
    </ul>
  </div>
</div>