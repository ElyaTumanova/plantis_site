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