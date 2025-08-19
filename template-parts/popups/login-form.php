<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="login-popup popup">
    <div class="plnt-customer-login page-popup__container">
        <?php //wc_get_template('myaccount/form-login.php'); ?>

        <!-- CODE BASED ON plugins/woocommerce/templates/myaccount/form-login.php -->
        <?php do_action( 'woocommerce_before_customer_login_form' ); ?>

        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

        <div class="u-columns col2-set" id="customer_login">

            <div class="u-column1 col-1">

        <?php endif; ?>

                <!-- <h4><?php //esc_html_e( 'Login', 'woocommerce' ); ?></h4> -->

                <form class="woocommerce-form woocommerce-form-login login" method="post">

                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
                        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
                    </p>

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <p class="form-row">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                        </label>
                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
                    </p>
                    <p class="woocommerce-LostPassword lost_password">
                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
                    </p>

                    <div class=login-form__registration-btn>Регистрация</div>

                    <?php do_action( 'woocommerce_login_form_end' ); ?>

                </form>

        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

            </div>


        </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_after_customer_login_form' ); ?>

        <div class="login__close">✖</div>
    </div>
    <div class="login__popup-overlay popup-overlay"></div>
</div>