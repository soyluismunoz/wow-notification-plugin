<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Plugin Name: Woo order WhatsApp
 * Plugin URI: https://github.com/soyluismunoz/wow-notification-plugin
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
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	add_action( 'woocommerce_order_status_processing', 'sendWhatsappOwner');
	add_action( 'woocommerce_order_status_processing', 'sendWhatsappCustomer');
	add_action( 'woocommerce_order_status_pending', 'sendWhatsappCustomer',); 
	//add_action( 'woocommerce_thankyou', 'sendWhatsappOwner');
}

// Send whatsapp of new orders to the owners 
function sendWhatsappOwner( $order_id ) {
    global $woocommerce;
    $order = new WC_Order( $order_id ); //get order number
	$orderItem = $order->get_items();
	$product_list = '';
	
	foreach( $orderItem as $product ) {
            $product_details[] = $product['name']." x ".$product['qty'] . " ";
        }
	$product_list = implode( ',', $product_details );
	
	$vendorId = get_post_meta( $order_id, '_dokan_vendor_id'); //get vendor ID
	$vendorId = (int) $vendorId[0]; // change vendor ID to integer
	$vendorPhone = get_user_meta ( $vendorId, 'dokan_profile_settings'); // get vendor phone
	
	$numberFrom = get_option('woo_wa_twilio_phone_number'); //phone number 
	
	$message = get_option('woo_wa_owner_message');
	
	$msg = $message . " *#" . $order_id . "* " . $order->get_billing_first_name() . " " .  $order->get_billing_last_name() . " " . $product_list . " *total* => " . $order->get_total() . "  *Direccion de entrega* " . $order->get_shipping_address_1() . " " . $order->get_shipping_address_2() . " " . $order->get_shipping_city() . " " . $order->get_shipping_postcode() . "*telefono* " . $order->get_billing_phone() ;
	
	$phoneTo = $vendorPhone[0]['phone'];
	$result = SendMessageWsOwner($phoneTo, $numberFrom, $msg);
}
// Send whatsapp of new orders to the owners -- end
//send whatsapp customer with the order status
function sendWhatsappCustomer( $order_id ) {
    global $woocommerce;
    $order = new WC_Order( $order_id ); //get order number
	$orderItem = $order->get_items();
	
	$product_list = '';
	foreach( $orderItem as $product ) {
            $product_details[] = $product['name']." x ".$product['qty'] . " ";
        }
	$product_list = implode( ',', $product_details );// create item list
	
	$numberFrom = get_option('woo_wa_twilio_phone_number'); //phone number 
	
	$message = get_option('woo_wa_customer_message');
	
	$msg = $message . " *" . $product_list . "* con el numero " . $order_id . " tiene el estatus de " . $order->get_status();
	$phoneTo = $order->get_billing_phone();
	$result = SendMessageWsCustomer($phoneTo, $numberFrom, $msg);
}
//send whatsapp customer with the order status -- end