<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Plugin Name: Woo order WhatsApp
 * Plugin URI: https://github.com/soyluismunoz
 * Description: WordPress plugin to receive notifications of orders by whatsapp.
 * Version: 1.0
 * Author: Luis Munoz
 * Author URI: https://github.com/soyluismunoz 
 * License: GPLv2
 */
 require_once __DIR__ . '/includes/config.php';
// Start plugin activator.
function wooWhatsAppActicatePlugin()
{
   require_once __DIR__ . '/includes/activator.php';
}
register_activation_hook( __FILE__, 'wooWhatsAppActicatePlugin');
// End plugin acticator

// Add submenu setting to WooCommerce
add_action('admin_menu', 'wooWhatsAppAdminMenu');
function wooWhatsAppAdminMenu(){
   add_submenu_page('woocommerce', 'Woo WhatsApp', 'WooWhatsApp', 'manage_options', 'woo_whatsapp_admin', 'wooWhatsAppAdminPage' );
}
function wooWhatsAppAdminPage()
{
   require_once __DIR__ . '/includes/admin-display.php';
}
// End submenu setting

// Add WA Button after add to cart button start
function wooWhatsAppButtonAfterAddToCart()
{
	require_once __DIR__ . '/includes/public.php';
}
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
   add_action('woocommerce_after_add_to_cart_button', 'wooWhatsAppButtonAfterAddToCart');
}
// Add WA Button after add to cart button end

// Send whatsapp if there are new orders
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	add_action( 'woocommerce_order_status_processing', 'sendWhatsapp');
}
function sendWhatsapp( $order_id ) {
    global $woocommerce;
    $order = new WC_Order( $order_id );
	$vendorId = get_post_meta( $order_id, '_dokan_vendor_id');
	$vendorId = (int) $vendorId[0]; 
	$vendorPhone = get_user_meta ( $vendorId, 'dokan_profile_settings');
	$message = get_option('woo_wa_message');
	
	$msg = "*" . $order->get_billing_first_name(). " " . $order->get_billing_last_name() ."* " .$message. " *" . $order_id . "*";
	$phoneTo = $vendorPhone[0]['phone'];
	$result = SendMessageCurl($phoneTo, $msg);
}
