<?php

/**
*
* @link              Yaroslav Livchak
* @since             1.0.0
* @package           Price history
*
* @wordpress-plugin
* Plugin Name:       Price history
* Plugin URI:        https://www.vaolab.pl
* Description:       <code><strong>Price history</strong></code>Price history for all products.
* Version:           1.0.1
* Author:            Yaroslav Livchak
* Author URI:        Yaroslav Livchak
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       price_history
* Domain Path:       /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'PRICE_HISTORY_PLUGIN_DIR_PATH' ) ){
    define( 'PRICE_HISTORY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__));    
}

if ( ! defined( 'PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES' ) ){
    define( 'PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES', plugin_dir_path( __FILE__) . 'includes/' );
}

if ( ! defined( 'PRICE_HISTORY_PLUGIN_DIR_PATH_SETTINGS' ) ){
    define( 'PRICE_HISTORY_PLUGIN_DIR_PATH_SETTINGS', plugin_dir_path( __FILE__) . 'settings/' );
}

if ( ! defined( 'PRICE_HISTORY_TEXT_DOMAIN' ) ){
    define( 'PRICE_HISTORY_TEXT_DOMAIN', 'price_history');
}

if ( ! defined( 'PH_BASENAME' ) ){
    define( 'PH_BASENAME', basename(__FILE__) );
}

if ( ! defined( 'PH_PLUGIN_BASENAME' ) ){
    define( 'PH_PLUGIN_BASENAME', plugin_basename(__FILE__) );
}

function sl147_PH_activate() {
    require_once PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES . 'class-price_history-activator.php';
    Price_History_Activator::activate();
}

function sl147_PH_deactivate() {
    require_once PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES . 'class-price_history-deactivator.php';
    Price_History_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'sl147_PH_activate' );
register_deactivation_hook( __FILE__, 'sl147_PH_deactivate' );

function sl147_PH_textdomain() {
    $locale = determine_locale();
    load_plugin_textdomain( 'price_history', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

add_action('plugins_loaded', 'sl147_PH_textdomain');


/**
 * start plugin
 */
if (is_admin()) {
    require_once PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES . 'class-price_history_init.php';
    $sl147_init = new Sl147_price_history_init();
    $sl147_init->Sl147_PH_init_run();
}else{
    require_once PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES . 'class-price_history_min_price.php';
    $sl147_min_price  = new Sl147_price_history_min_price();    
}
