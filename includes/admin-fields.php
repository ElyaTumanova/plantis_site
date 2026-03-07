<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
Contents
# Additional fields for admin
# delivery_dates filter for admin
--------------------------------------------------------------*/

/*--------------------------------------------------------------
# Additional fields for admin
--------------------------------------------------------------*/
add_action( 'add_meta_boxes', 'plnt_add_custom_fields_meta_box' );
function plnt_add_custom_fields_meta_box() {
    add_meta_box(
        'plnt_custom_order_fields',
        'Дополнительные поля заказа',
        'plnt_render_custom_fields_meta_box',
        'shop_order',
        'side',
        'default'
    );
}

function plnt_render_custom_fields_meta_box( $post ) {
    $fields = [
        'plnt_client_status'      => ['Статус клиента', 'select'],
        'plnt_client_origin'  => ['Откуда пришел клиент', 'select'],
        'plnt_payment_method'      => ['Способ оплаты', 'select'],
        'plnt_paid'     => ['Оплачен?', 'select'],
        'plnt_comment'     => ['Комментарий', 'text'],
        'plnt_messenger'     => ['Где общаемся с клиентом', 'select'],
    ];

    $select_field_options = [
        'plnt_payment_method' => [
            'site'   => 'На сайте',
            'ssylka'   => 'По ссылке',
            'nal'    => 'Наличными',
            'perevod' => 'Переводом',
            'schet' => 'По счету',
            'giftcard' => 'Подарочная карта',
            'other' => 'Иное',
        ],
        'plnt_client_status' => [
            'new' => 'Новый',
            'requring'  => 'Повтор',
            'other' => 'Иное',
        ],
        'plnt_client_origin' => [
            'site' => 'Сайт',
            'street' => 'С улицы',
            'avito' => 'Авито',
            'one-click' => 'Один клик',
            'preorder'  => 'Предзаказ',
            'messenger' => 'Мессенджер',
            'mail' => 'Письмо',
            'call' => 'Звонок',
            'other' => 'Иное',
        ],
        'plnt_paid' => [
            'yes' => 'Да',
            'no'  => 'Нет',
            'other' => 'Иное',
        ],
        'plnt_messenger' => [
            'avito' => 'Авито',
            'tg' => 'TG',
            'max'  => 'MAX',
            'wa' => 'WA',
            'mail' => 'Почта',
            'call' => 'Звонок',
            'other' => 'Иное'
        ],
    ];

    foreach ( $fields as $key => $field ) {
        $label = $field[0];
        $type  = $field[1];
        $value = get_post_meta( $post->ID, '_' . $key, true );

        echo '<p><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label><br />';

        if ( $type === 'select' ) {
            echo '<select name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" style="width:100%">';
            echo '<option value="">— Выберите —</option>';
            if ( isset( $select_field_options[ $key ] ) ) {
                foreach ( $select_field_options[ $key ] as $option_value => $option_label ) {
                    $selected = selected( $value, $option_value, false );
                    echo '<option value="' . esc_attr($option_value) . '" ' . $selected . '>' . esc_html($option_label) . '</option>';
                }
            }
            echo '</select>';
        } else {
            echo '<input type="text" style="width:100%" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
        }

        echo '</p>';
    }
}

function plnt_save_custom_fields_meta_box( $post_id ) {
    $fields = [
        'plnt_payment_method',
        'plnt_client_status',
        'plnt_client_origin',
        'plnt_paid',
        'plnt_comment',
        'plnt_messenger'  
    ];

    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}

add_action( 'save_post_shop_order', 'plnt_save_custom_fields_meta_box', 20, 2 );



/*--------------------------------------------------------------
# delivery_dates filter for admin
--------------------------------------------------------------*/
add_action( 'restrict_manage_posts', 'plantis_filter_orders_by_delivery_date' );
function plantis_filter_orders_by_delivery_date() {
	global $typenow, $wpdb;

	if ( 'shop_order' !== $typenow ) {
		return;
	}

	$selected = isset( $_GET['delivery_dates'] ) ? sanitize_text_field( wp_unslash( $_GET['delivery_dates'] ) ) : '';

	$dates = $wpdb->get_col(
		"
		SELECT DISTINCT pm.meta_value
		FROM {$wpdb->postmeta} pm
		INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		WHERE pm.meta_key = 'delivery_dates'
		  AND pm.meta_value <> ''
		  AND p.post_type = 'shop_order'
		  AND p.post_status = 'wc-processing'
		ORDER BY pm.meta_value ASC
		"
	);

	if ( empty( $dates ) ) {
		return;
	}

	echo '<select name="delivery_dates">';
	echo '<option value="">' . esc_html__( 'Дата доставки', 'plantis' ) . '</option>';

	foreach ( $dates as $date ) {
		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			esc_attr( $date ),
			selected( $selected, $date, false ),
			esc_html( $date )
		);
	}

	echo '</select>';
}

add_action( 'pre_get_posts', 'plantis_filter_orders_query_by_delivery_date' );
function plantis_filter_orders_query_by_delivery_date( $query ) {
	global $pagenow;

	if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() ) {
		return;
	}

	if ( empty( $_GET['post_type'] ) || 'shop_order' !== $_GET['post_type'] ) {
		return;
	}

	if ( empty( $_GET['delivery_dates'] ) ) {
		return;
	}

	$query->set(
		'meta_query',
		[
			[
				'key'   => 'delivery_dates',
				'value' => sanitize_text_field( wp_unslash( $_GET['delivery_dates'] ) ),
			],
		]
	);
}


/**
 * Модалка "Заказы в обработке" для админа
 */

/**
 * Проверка прав.
 */
function plantis_can_view_processing_orders_modal() {
	return current_user_can( 'manage_woocommerce' ) || current_user_can( 'administrator' );
}

/**
 * Получить данные заказов в обработке.
 */
function plantis_get_processing_orders_modal_data() {
	$orders = wc_get_orders(
		[
			'status' => [ 'processing' ],
			'limit'  => -1,
			'orderby'=> 'date',
			'order'  => 'DESC',
			'return' => 'objects',
		]
	);

	$data = [];

	foreach ( $orders as $order ) {
		$order_id   = $order->get_id();
		$first_name = $order->get_billing_first_name();
		$last_name  = $order->get_billing_last_name();
		$name       = trim( $first_name . ' ' . $last_name );

		if ( '' === $name ) {
			$name = $order->get_formatted_billing_full_name();
		}

		if ( '' === trim( wp_strip_all_tags( $name ) ) ) {
			$name = '—';
		}

		$delivery_date     = get_post_meta( $order_id, 'delivery_dates', true );
		$delivery_interval = get_post_meta( $order_id, 'additional_delivery_interval', true );
		$plnt_paid         = get_post_meta( $order_id, 'plnt_paid', true );

		$address_parts = [
			$order->get_shipping_address_1(),
			$order->get_shipping_address_2(),
			$order->get_shipping_city(),
			$order->get_shipping_state(),
			$order->get_shipping_postcode(),
		];

		$address_parts = array_filter(
			array_map(
				static function ( $value ) {
					return is_string( $value ) ? trim( $value ) : '';
				},
				$address_parts
			)
		);

		$address = implode( ', ', $address_parts );

		if ( '' === $address ) {
			$address = $order->get_formatted_shipping_address();

			if ( '' === trim( wp_strip_all_tags( $address ) ) ) {
				$address = $order->get_formatted_billing_address();
			}
		}

		if ( '' === trim( wp_strip_all_tags( $address ) ) ) {
			$address = '—';
		}

		$paid_label = '—';

		if ( '' !== (string) $plnt_paid ) {
			$truthy = [ '1', 'yes', 'true', 'paid', 'оплачен', 'да' ];
			$paid_label = in_array( mb_strtolower( (string) $plnt_paid ), $truthy, true ) ? 'Да' : 'Нет';
		}

		$data[] = [
			'order_id'           => $order_id,
			'order_number'       => $order->get_order_number(),
			'name'               => $name,
      'phone'              => $order->get_billing_phone() ? $order->get_billing_phone() : '—',
			'delivery_date'      => $delivery_date ? $delivery_date : '—',
			'delivery_interval'  => $delivery_interval ? $delivery_interval : '—',
			'address'            => $address,
			'plnt_paid'          => $paid_label,
			'edit_link'          => admin_url( 'post.php?post=' . $order_id . '&action=edit' ),
		];
	}

	return $data;
}

/**
 * AJAX: получить HTML модалки.
 */
add_action( 'wp_ajax_plantis_get_processing_orders_modal', 'plantis_get_processing_orders_modal_ajax' );
function plantis_get_processing_orders_modal_ajax() {
	if ( ! plantis_can_view_processing_orders_modal() ) {
		wp_send_json_error( [ 'message' => 'Недостаточно прав.' ], 403 );
	}

	check_ajax_referer( 'plantis_processing_orders_modal', 'nonce' );

	$orders = plantis_get_processing_orders_modal_data();

	ob_start();

	if ( empty( $orders ) ) {
		echo '<div class="plantis-processing-orders-empty">Нет заказов в статусе «В обработке».</div>';
	} else {
		echo '<div class="plantis-processing-orders-list">';

		foreach ( $orders as $item ) {
			$paid_class = 'Нет' === $item['plnt_paid'] ? 'is-no' : 'is-yes';

			echo '<article class="plantis-processing-order-card">';
				echo '<div class="plantis-processing-order-card__top">';
					echo '<a class="plantis-processing-order-card__number" href="' . esc_url( $item['edit_link'] ) . '" target="_blank">Заказ #' . esc_html( $item['order_number'] ) . '</a>';
					echo '<span class="plantis-processing-order-card__paid ' . esc_attr( $paid_class ) . '">' . esc_html( $item['plnt_paid'] ) . '</span>';
				echo '</div>';

				echo '<div class="plantis-processing-order-card__meta">';
					echo '<div class="plantis-processing-order-card__row">';
						echo '<span class="plantis-processing-order-card__label">Имя</span>';
						echo '<span class="plantis-processing-order-card__value">' . esc_html( $item['name'] ) . '</span>';
					echo '</div>';

					echo '<div class="plantis-processing-order-card__row">';
						echo '<span class="plantis-processing-order-card__label">Телефон</span>';
						echo '<a class="plantis-processing-order-card__value plantis-processing-order-card__phone" href="tel:' . esc_attr( preg_replace( '/[^\d\+]/', '', $item['phone'] ) ) . '">' . esc_html( $item['phone'] ) . '</a>';
					echo '</div>';

					echo '<div class="plantis-processing-order-card__row">';
						echo '<span class="plantis-processing-order-card__label">Дата доставки</span>';
						echo '<span class="plantis-processing-order-card__value">' . esc_html( $item['delivery_date'] ) . '</span>';
					echo '</div>';

					echo '<div class="plantis-processing-order-card__row">';
						echo '<span class="plantis-processing-order-card__label">Интервал</span>';
						echo '<span class="plantis-processing-order-card__value">' . esc_html( $item['delivery_interval'] ) . '</span>';
					echo '</div>';

					echo '<div class="plantis-processing-order-card__row plantis-processing-order-card__row--address">';
						echo '<span class="plantis-processing-order-card__label">Адрес</span>';
						echo '<span class="plantis-processing-order-card__value">' . nl2br( esc_html( wp_strip_all_tags( $item['address'] ) ) ) . '</span>';
					echo '</div>';
				echo '</div>';
			echo '</article>';
		}

		echo '</div>';
	}

	$html = ob_get_clean();

	wp_send_json_success(
		[
			'html'  => $html,
			'count' => count( $orders ),
		]
	);
}

/**
 * Подключение inline CSS/JS и вывод HTML модалки.
 */
add_action( 'admin_footer', 'plantis_render_processing_orders_modal' );
add_action( 'wp_footer', 'plantis_render_processing_orders_modal' );

function plantis_render_processing_orders_modal() {
	if ( ! is_user_logged_in() || ! plantis_can_view_processing_orders_modal() ) {
		return;
	}

	static $printed = false;

	if ( $printed ) {
		return;
	}

	$printed = true;
	$nonce   = wp_create_nonce( 'plantis_processing_orders_modal' );
	?>
	<div class="plantis-processing-orders-widget">
		<button type="button" class="plantis-processing-orders-widget__button" id="plantisOpenProcessingOrdersModal">
			Заказы в обработке
		</button>
	</div>

	<div class="plantis-processing-orders-modal" id="plantisProcessingOrdersModal" aria-hidden="true">
		<div class="plantis-processing-orders-modal__backdrop"></div>

		<div class="plantis-processing-orders-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="plantisProcessingOrdersTitle">
			<div class="plantis-processing-orders-modal__header">
				<h2 id="plantisProcessingOrdersTitle">Заказы в обработке</h2>
				<button type="button" class="plantis-processing-orders-modal__close" id="plantisCloseProcessingOrdersModal" aria-label="Закрыть">×</button>
			</div>

			<div class="plantis-processing-orders-modal__body" id="plantisProcessingOrdersBody">
				<div class="plantis-processing-orders-loading">Загрузка...</div>
			</div>
		</div>
	</div>

	<style>
		.plantis-processing-orders-widget{
			position: fixed;
			top: 24px;
			right: 24px;
			z-index: 99998;
		}

		.plantis-processing-orders-widget__button{
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-height: 40px;
			padding: 10px 14px;
			border: 0;
			border-radius: 12px;
			background: #111;
			color: #fff;
			font-size: 14px;
			font-weight: 600;
			line-height: 1;
			cursor: pointer;
			box-shadow: 0 10px 30px rgba(0,0,0,.22);
		}

		.plantis-processing-orders-widget__button:hover{
			background: #1c1c1c;
		}

		.plantis-processing-orders-modal{
			position: fixed;
			inset: 0;
			z-index: 99999;
			display: none;
		}

		.plantis-processing-orders-modal.is-open{
			display: block;
		}

		.plantis-processing-orders-modal__backdrop{
			position: absolute;
			inset: 0;
			background: transparent;
		}

		.plantis-processing-orders-modal__dialog{
			position: absolute;
			top: 72px;
			right: 24px;
			width: 420px;
			max-width: calc(100vw - 24px);
			max-height: calc(100vh - 96px);
			background: #0f0f10;
			color: #fff;
			border: 1px solid rgba(255,255,255,.08);
			border-radius: 18px;
			overflow: hidden;
			box-shadow: 0 24px 70px rgba(0,0,0,.45);
			display: flex;
			flex-direction: column;
		}

		.plantis-processing-orders-modal__header{
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 14px 16px;
			border-bottom: 1px solid rgba(255,255,255,.08);
			background: #0f0f10;
		}

		.plantis-processing-orders-modal__header h2{
			margin: 0;
			font-size: 15px;
			line-height: 1.2;
			font-weight: 700;
			color: #fff;
		}

		.plantis-processing-orders-modal__close{
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 28px;
			height: 28px;
			padding: 0;
			border: 0;
			border-radius: 8px;
			background: transparent;
			color: #fff;
			font-size: 22px;
			line-height: 1;
			cursor: pointer;
		}

		.plantis-processing-orders-modal__close:hover{
			background: rgba(255,255,255,.08);
		}

		.plantis-processing-orders-modal__body{
			padding: 12px;
			overflow: auto;
			background: #0f0f10;
			color: #fff;
		}

		.plantis-processing-orders-loading,
		.plantis-processing-orders-empty{
			padding: 10px 6px;
			font-size: 14px;
			line-height: 1.45;
			color: rgba(255,255,255,.82);
		}

		.plantis-processing-orders-list{
			display: grid;
			gap: 10px;
		}

		.plantis-processing-order-card{
			padding: 12px;
			border: 1px solid rgba(255,255,255,.08);
			border-radius: 14px;
			background: #171718;
			box-shadow: inset 0 1px 0 rgba(255,255,255,.03);
		}

		.plantis-processing-order-card__top{
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 10px;
			margin-bottom: 10px;
		}

		.plantis-processing-order-card__number{
			color: #fff;
			text-decoration: none;
			font-size: 14px;
			font-weight: 700;
			line-height: 1.2;
		}

		.plantis-processing-order-card__number:hover{
			text-decoration: underline;
		}

		.plantis-processing-order-card__paid{
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 4px 8px;
			border-radius: 999px;
			font-size: 11px;
			font-weight: 700;
			line-height: 1;
			white-space: nowrap;
		}

		.plantis-processing-order-card__paid.is-yes{
			background: rgba(52,199,89,.14);
			color: #7ee2a0;
			border: 1px solid rgba(52,199,89,.2);
		}

		.plantis-processing-order-card__paid.is-no{
			background: rgba(255,69,58,.14);
			color: #ff8b84;
			border: 1px solid rgba(255,69,58,.2);
		}

		.plantis-processing-order-card__meta{
			display: grid;
			gap: 8px;
		}

		.plantis-processing-order-card__row{
			display: grid;
			grid-template-columns: 110px 1fr;
			gap: 8px;
			align-items: start;
		}

		.plantis-processing-order-card__row--address{
			padding-top: 4px;
			border-top: 1px solid rgba(255,255,255,.06);
			margin-top: 2px;
		}

		.plantis-processing-order-card__label{
			font-size: 11px;
			line-height: 1.35;
			font-weight: 600;
			letter-spacing: .02em;
			text-transform: uppercase;
			color: rgba(255,255,255,.48);
		}

		.plantis-processing-order-card__value{
			font-size: 13px;
			line-height: 1.45;
			color: #fff;
			word-break: break-word;
		}

		.plantis-processing-order-card__phone{
			color: #fff;
			text-decoration: none;
		}

		.plantis-processing-order-card__phone:hover{
			text-decoration: underline;
		}

		@media (max-width: 782px){
			.plantis-processing-orders-widget{
				top: 12px;
				right: 12px;
			}

			.plantis-processing-orders-modal__dialog{
				top: 58px;
				right: 12px;
				width: calc(100vw - 24px);
				max-height: calc(100vh - 70px);
			}

			.plantis-processing-order-card__row{
				grid-template-columns: 1fr;
				gap: 3px;
			}

			.plantis-processing-order-card__label{
				font-size: 10px;
			}
		}
	</style>

	<script>
		(function(){
			const modal = document.getElementById('plantisProcessingOrdersModal');
			const openBtn = document.getElementById('plantisOpenProcessingOrdersModal');
			const closeBtn = document.getElementById('plantisCloseProcessingOrdersModal');
			const body = document.getElementById('plantisProcessingOrdersBody');

			if (!modal || !openBtn || !closeBtn || !body) {
				return;
			}

			let loadedOnce = false;

			function openModal() {
				modal.classList.add('is-open');
				modal.setAttribute('aria-hidden', 'false');

				if (!loadedOnce) {
					loadOrders();
				}
			}

			function closeModal() {
				modal.classList.remove('is-open');
				modal.setAttribute('aria-hidden', 'true');
			}

			function loadOrders() {
				body.innerHTML = '<div class="plantis-processing-orders-loading">Загрузка...</div>';

				const formData = new FormData();
				formData.append('action', 'plantis_get_processing_orders_modal');
				formData.append('nonce', '<?php echo esc_js( $nonce ); ?>');

				fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
					method: 'POST',
					credentials: 'same-origin',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					if (!data || !data.success) {
						throw new Error((data && data.data && data.data.message) ? data.data.message : 'Ошибка загрузки.');
					}

					body.innerHTML = data.data.html;
					loadedOnce = true;
				})
				.catch(error => {
					body.innerHTML = '<div class="plantis-processing-orders-empty">' + (error.message || 'Ошибка загрузки.') + '</div>';
				});
			}

			openBtn.addEventListener('click', function(e){
				e.stopPropagation();
				if (modal.classList.contains('is-open')) {
					closeModal();
				} else {
					openModal();
				}
			});

			closeBtn.addEventListener('click', closeModal);

			modal.addEventListener('click', function(e){
				if (!e.target.closest('.plantis-processing-orders-modal__dialog')) {
					closeModal();
				}
			});

			document.addEventListener('keydown', function(e){
				if (e.key === 'Escape' && modal.classList.contains('is-open')) {
					closeModal();
				}
			});
		})();
	</script>
	<?php
}