<?php
/**
 * Share template
 * 
 * MODIFIED FOR PLANTIS THEME
 *
 * @author YITH
 * @package YITH\Wishlist\Templates
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $wishlist                YITH_WCWL_Wishlist Wishlist object
 * @var $share_title             string Title for share section
 * @var $share_facebook_enabled  bool Whether to enable FB sharing button
 * @var $share_twitter_enabled   bool Whether to enable Twitter sharing button
 * @var $share_pinterest_enabled bool Whether to enable Pintereset sharing button
 * @var $share_email_enabled     bool Whether to enable Email sharing button
 * @var $share_whatsapp_enabled  bool Whether to enable WhatsApp sharing button (mobile online)
 * @var $share_url_enabled       bool Whether to enable share via url
 * @var $share_link_title        string Title to use for post (where applicable)
 * @var $share_link_url          string Url to share
 * @var $share_summary           string Summary to use for sharing on social media
 * @var $share_image_url         string Image to use for sharing on social media
 * @var $share_twitter_summary   string Summary to use for sharing on Twitter
 * @var $share_facebook_icon     string Icon for facebook sharing button
 * @var $share_twitter_icon      string Icon for twitter sharing button
 * @var $share_pinterest_icon    string Icon for pinterest sharing button
 * @var $share_email_icon        string Icon for email sharing button
 * @var $share_whatsapp_icon     string Icon for whatsapp sharing button
 * @var $share_whatsapp_url      string Sharing url on whatsapp
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php
// we want spaces to be encoded as + instead of %20, so we use urlencode instead of rawurlencode.
// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode

/**
 * DO_ACTION: yith_wcwl_before_wishlist_share
 *
 * Allows to render some content or fire some action before the share wishlist section.
 */
do_action( 'yith_wcwl_before_wishlist_share', $wishlist );
?>

<div class="yith-wcwl-share">
	<h4 class="yith-wcwl-share-title">Поделиться<?php //echo esc_html( $share_title ); ?></h4>
	<ul>
		<?php if ( $share_facebook_enabled ) : ?>
			<li class="share-button">
				<a target="_blank" rel="noopener" class="facebook" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode( $share_link_url ); ?>&p[title]=<?php echo esc_attr( $share_link_title ); ?>" title="<?php esc_html_e( 'Facebook', 'yith-woocommerce-wishlist' ); ?>">
					<?php echo $share_facebook_icon ? yith_wcwl_kses_icon( $share_facebook_icon ) : esc_html__( 'Facebook', 'yith-woocommerce-wishlist' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $share_twitter_enabled ) : ?>
			<li class="share-button">
				<a target="_blank" rel="noopener" class="twitter" href="https://twitter.com/share?url=<?php echo urlencode( $share_link_url ); ?>&amp;text=<?php echo esc_attr( $share_twitter_summary ); ?>" title="<?php esc_html_e( 'Twitter', 'yith-woocommerce-wishlist' ); ?>">
					<?php echo $share_twitter_icon ? yith_wcwl_kses_icon( $share_twitter_icon ) : esc_html__( 'Twitter', 'yith-woocommerce-wishlist' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $share_pinterest_enabled ) : ?>
			<li class="share-button">
				<a target="_blank" rel="noopener" class="pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( $share_link_url ); ?>&amp;description=<?php echo esc_attr( $share_summary ); ?>&amp;media=<?php echo esc_attr( $share_image_url ); ?>" title="<?php esc_html_e( 'Pinterest', 'yith-woocommerce-wishlist' ); ?>" onclick="window.open(this.href); return false;">
					<?php echo $share_pinterest_icon ? yith_wcwl_kses_icon( $share_pinterest_icon ) : esc_html__( 'Pinterest', 'yith-woocommerce-wishlist' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $share_email_enabled ) : ?>
			<li class="share-button">
				<?php
				/**
				 * APPLY_FILTERS: yith_wcwl_email_share_subject
				 *
				 * Filter the subject for the share email.
				 *
				 * @param string $subject Email subject
				 *
				 * @return string
				 */

				/**
				 * APPLY_FILTERS: yith_wcwl_email_share_body
				 *
				 * Filter the body for the share email.
				 *
				 * @param string $body Email body
				 *
				 * @return string
				 */

				?>
				<a class="email" href="mailto:?subject=<?php echo esc_attr( apply_filters( 'yith_wcwl_email_share_subject', $share_link_title ) ); ?>&amp;body=<?php echo esc_attr( apply_filters( 'yith_wcwl_email_share_body', urlencode( $share_link_url ) ) ); ?>&amp;title=<?php echo esc_attr( $share_link_title ); ?>" title="<?php esc_html_e( 'Email', 'yith-woocommerce-wishlist' ); ?>">
					<?php echo $share_email_icon ? yith_wcwl_kses_icon( $share_email_icon ) : __( 'Email', 'yith-woocommerce-wishlist' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $share_whatsapp_enabled ) : ?>
			<li class="share-button">
				<a class="whatsapp" href="<?php echo esc_attr( $share_whatsapp_url ); ?>" data-action="share/whatsapp/share" target="_blank" rel="noopener" title="<?php esc_html_e( 'WhatsApp', 'yith-woocommerce-wishlist' ); ?>">
					<?php echo $share_whatsapp_icon ? yith_wcwl_kses_icon( $share_whatsapp_icon ) : esc_html__( 'Whatsapp', 'yith-woocommerce-wishlist' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endif; ?>
        <?php 
        if ( wp_is_mobile() ) {
            $share_telegram_url = 'whatsapp://send?text=' . $share_link_title . ' - ' . urlencode( $share_link_url );
        } else {
            $share_telegram_url = 'https://telegram.me/share/url?url=' . urlencode( $share_link_url ) . '&text=' . $share_link_title;
        }
        ?>
        <li class="share-button">
            <a class="telegram" href="<?php echo esc_attr( $share_telegram_url ); ?>" data-action="share/telegram/share" target="_blank" rel="noopener" title="<?php esc_html_e( 'Telegram', 'yith-woocommerce-wishlist' ); ?>">
                <span class="header__telegram-icon">
                    <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.4223 1.02293C13.4223 1.02293 14.7544 0.503503 14.6434 1.76497C14.6064 2.28441 14.2734 4.10241 14.0144 6.06883L13.1263 11.8939C13.1263 11.8939 13.0523 12.7472 12.3862 12.8956C11.7202 13.044 10.7211 12.3762 10.5361 12.2278C10.3881 12.1165 7.7609 10.4469 6.8358 9.63063C6.57677 9.40801 6.28075 8.96278 6.8728 8.44335L10.7581 4.73316C11.2022 4.28793 11.6462 3.24907 9.79603 4.51054L4.61563 8.03525C4.61563 8.03525 4.02359 8.40626 2.91352 8.07235L0.508316 7.3303C0.508316 7.3303 -0.379756 6.77378 1.13737 6.21722C4.83767 4.47341 9.38902 2.69251 13.4223 1.02293Z" fill="white"></path>
                    </svg>
                </span>
            </a>
        </li>
        <div><?php echo $share_link_title?></div>
        <div><?php echo $share_link_url?></div>
        
        

	</ul>

	<?php if ( $share_url_enabled ) : ?>
		<div class="yith-wcwl-after-share-section">
			<input class="copy-target" readonly="readonly" type="url" name="yith_wcwl_share_url" id="yith_wcwl_share_url" value="<?php echo esc_attr( $share_link_url ); ?>"/>
			<?php echo ( ! empty( $share_link_url ) ) ? sprintf( '<small>%s <span class="copy-trigger">%s</span> %s</small>', esc_html__( '(Now', 'yith-woocommerce-wishlist' ), esc_html__( 'copy', 'yith-woocommerce-wishlist' ), esc_html__( 'this wishlist link and share it anywhere)', 'yith-woocommerce-wishlist' ) ) : ''; ?>
		</div>
	<?php endif; ?>

	<?php
	/**
	 * DO_ACTION: yith_wcwl_after_share_buttons
	 *
	 * Allows to render some content or fire some action after the share buttons in the Wishlist page.
	 *
	 * @param string $share_link_url   Share link URL
	 * @param string $share_title      Share title
	 * @param string $share_link_title Share link title
	 */
	do_action( 'yith_wcwl_after_share_buttons', $share_link_url, $share_title, $share_link_title );
	?>
</div>

<?php
/**
 * DO_ACTION: yith_wcwl_after_wishlist_share
 *
 * Allows to render some content or fire some action after the share wishlist section.
 */
do_action( 'yith_wcwl_after_wishlist_share', $wishlist );

// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode
?>
