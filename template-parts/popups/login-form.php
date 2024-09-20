<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="login-popup popup">
    <div class="plnt-customer-login page-popup__containe">
        <?php wc_get_template(
					'auth/form-login.php',
					array(
						'app_name'     => wc_clean( $data['app_name'] ),
						'return_url'   => add_query_arg(
							array(
								'success' => 0,
								'user_id' => wc_clean( $data['user_id'] ),
							),
							$this->get_formatted_url( $data['return_url'] )
						),
						'redirect_url' => $this->build_url( $data, 'authorize' ),
					)
				); ?>
        <div class="login__close">✖</div>
    </div>
    <div class="login__popup-overlay popup-overlay"></div>
</div>