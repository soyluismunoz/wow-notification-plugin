<?php

/**
 * WooWhatsApp main class.
 *
 * @package   WoW Notifaction
 * @author    Luis Munoz
 * @link      https://github.com/soyluismunoz/wow-notification-plugin
 * @link      https://github.com/soyluismunoz
 */
class WooWhatsApp
{
    /**
     * Default option.
     * 
     * @var string
     */
    public $default = [
		'phone_number' => 'your phone number',
        'content' => 'Hello, I want to buy this product {{link}}',
        'button' => 'Chat via WhatsApp',
        'button_class' => 'single_add_to_cart_button button',
        'button_id' => 'whatsapp_button',
        'button_css' => '',
        'button_show_desktop' => 'yes',
		'sid' => 'Your Account Sid',
		'auth_token' => 'Your Account Sid',
		'owner_message' => 'Tienes un nuevo pedido',
		'twilio_phone_number' => 'Twilio phone number'
    ];

    /**
     * Content getter function.
     * 
     * @param string $format Content Format.
     * @param \WC_Data\WC_Abstract_Legacy_Product\WC_Product $product WooCommerce Product
     * 
     * @return string
     */
    function getContent($format = '', $product)
    {
        if ($format == '') {
            $format = $wooWhatsAppDefault['content'];
        }
        $data = [];
        $data['title'] = $product->get_title();
        $data['link'] = get_permalink($product->get_id());
        foreach ($data as $key => $value) {
            $format = str_replace('{{' . $key . '}}', $value, $format);
        }

        return $format;
    }

    /**
     * Set plugin option to database.
     *
     * @param string $key   Option Key
     * @param string $value Option Value
     * 
     * @return void
     */
    public function setOption($key = '', $value = '')
    {
        if (!get_option($key) && !is_string(get_option($key))) {
            add_option($key, $value);
        } else {
            update_option($key, $value);
        }
    }

    /**
     * Get option from database.
     *
     * @param string $key       Option key.
     * @param string $default   Default value if option not found.
     * @return void
     */
    public function getOption($key = '', $default = '')
    {
        $result = get_option($key);
        if (!$result && !is_string($result)) {
            return $default;
        }
        
        return $result;
    }

    /**
     * Set default value.
     *
     * @return void
     */
    public function setDefault()
    {
        foreach ($this->default as $key => $value) {
            $optionKey = 'woo_wa_' . $key;
            if (!get_option($optionKey)) {
                add_option($optionKey, $value);
            }
        }
    }
}

$wooWhatsAppObject = new WooWhatsApp();