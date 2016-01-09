<?php
/*
    Plugin Name: WooCommerce - Webmoney Payment Gateway
    Plugin URI: https://wordpress.org/plugins/wc-webmoney
    Description: Allows you to use Webmoney payment gateway with the WooCommerce plugin.
    Version: 0.2.2.1
    Author: Mofsy
    Author URI: https://mofsy.ru
    Text Domain: wc-webmoney
    Domain Path: /languages

    Copyright: © 2015-2016 Mofsy.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if(!defined('ABSPATH'))
{
    exit;
}

add_action('plugins_loaded', 'woocommerce_webmoney_init', 0);

function woocommerce_webmoney_init()
{
    /**
     * Main check
     */
    if (!class_exists('WC_Payment_Gateway'))
    {
        return;
    }
    if(class_exists('WC_Webmoney'))
    {
        return;
    }

    /**
     * Define plugin url
     */
    define('WC_WEBMONEY_URL', plugin_dir_url(__FILE__));

    /**
     * Load language
     */
    load_plugin_textdomain( 'wc-webmoney',  false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    /**
     * Check status
     */
    if( class_exists('WooCommerce_Payment_Status') )
    {
        add_filter( 'woocommerce_valid_order_statuses_for_payment', array( 'WC_Webmoney', 'valid_order_statuses_for_payment' ), 52, 2 );
    }

    /**
     * Gateway class load
     */
    include_once dirname(__FILE__) . '/class-wc-webmoney.php';

    /**
     * Add the gateway to WooCommerce
     **/
    function woocommerce_add_webmoney_gateway($methods)
    {
        $methods[] = 'WC_Webmoney';

        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_webmoney_gateway');
}

function woocommerce_webmoney_get_version()
{
    if ( ! function_exists( 'get_plugins' ) )
    {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $plugin_folder = get_plugins( '/' . 'woocommerce' );
    $plugin_file = 'woocommerce.php';

    if(isset( $plugin_folder[$plugin_file]['Version'] ))
    {
        return $plugin_folder[$plugin_file]['Version'];
    }

    return null;
}