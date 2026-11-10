<?php
defined( 'ABSPATH' ) || exit;
?>

<div class="cookie-notice" id="cookie-notice" hidden>
	<div class="cookie-notice__inner">
		<p class="cookie-notice__text">
			Мы используем файлы cookie, чтобы сайт работал корректно,
			а также для анализа посещаемости и улучшения его работы.
			Продолжая пользоваться сайтом, вы соглашаетесь с использованием cookie.
			Подробнее — в
			<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">
				Политике конфиденциальности
			</a>.
		</p>

		<button class="button button--green cookie-notice__button" type="button">
			Понятно
		</button>
	</div>
</div>