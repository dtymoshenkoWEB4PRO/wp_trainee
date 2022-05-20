<?php
/**
 * Plugin Name: Additinal attributes for checkout Woocommerce
 * Version: 1.0.0
 * Author: Daria Tymoshenko
 * Requires PHP: 7.3
 */

add_action( 'woocommerce_after_order_notes', 'additional_informations' );
function additional_informations( $checkout ) {

	echo '<div id="additional_informations"><h2>' . _e( 'Additional informations' ) . '</h2>';

	woocommerce_form_field( 'additional_informations', array(
		'type'  => 'text',
		'label' => __( 'Additional informatiions about your order' ),
	), $checkout->get_value( 'additional_informations' ) );

	echo '</div>';
}

add_action( 'woocommerce_checkout_update_order_meta', 'additional_informations_update_order_meta' );
function additional_informations_update_order_meta( $order_id ) {
	if ( ! empty( $_POST['additional_informations'] ) ) {
		update_post_meta( $order_id, 'additional_informations', sanitize_text_field( $_POST['additional_informations'] ) );
	}
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'additional_informations_display_admin_order_meta', 10, 1 );
function additional_informations_display_admin_order_meta( $order ) {
	echo '<p><strong>' . __( 'Additional informatiions about your order' ) . ':</strong> ' . get_post_meta( $order->id, 'additional_informations', true ) . '</p>';
}


add_filter( 'woocommerce_email_order_meta_keys', 'additional_informations_order_meta_keys' );
function additional_informations_order_meta_keys( $keys ) {
	$keys["Additional informations about your order"] = 'additional_informations';

	return $keys;
}