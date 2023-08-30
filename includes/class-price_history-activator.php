<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Price_History
 * @subpackage Price_History/includes
 * @author     Yaroslav Livchak <sljar147@gmail.com>
 */
class Price_History_Activator {

	public static function activate() {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table_AI = $wpdb->prefix . 'sl147_price_history';
        $sql = "CREATE TABLE IF NOT EXISTS ". $table_AI." (
                            ID BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
                            ID_product BIGINT( 20 ) NOT NULL,
                            price_history FLOAT( 20, 2 ) NOT NULL,
                            type_price tinyint(1) NOT NULL,
                            user_change_price BIGINT( 20 ) NOT NULL,
                            date_history TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY  ( ID )
                        ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";                
        dbDelta( $sql );
	}
}