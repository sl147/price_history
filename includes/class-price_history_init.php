<?php

/**
 * 
 */
class Sl147_price_history_init {
	
	function __construct()	{
		add_action( 'admin_menu', array( $this, 'sl147_PH_register_menu' ));
	}


	public function sl147_PH_view_history() {
	    require_once PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES . 'class-price_history_view.php';  
	    $sl147_class = new Sl147_price_history_view();
	    $sl147_class->sl147_PH_view_history();
	}

	public function sl147_PH_register_menu(){
	    add_menu_page('Price history', __('Price history', 'price_history' ), 1, 'price_history-slug',  array( $this, 'sl147_PH_view_history') ,"",2.1);
	}

	public function sl147_PH_admin_style(){
	    wp_enqueue_style( 'sl147_PH_admin', plugins_url( 'admin/css/sl147_PH_admin.css', dirname(__FILE__) ) , array());
	}

	public function sl147_price_histor_row_meta( $meta, $plugin_file ){
	    if( false === strpos( $plugin_file, PH_BASENAME) )  return $meta;

	    $meta[] = '<a href="admin.php?page=price_history_page"><span class="dashicons dashicons-admin-settings"></span>'. __( 'Settings', 'sl147_gift'  ) .'</a>';

	    return $meta; 
	}

	public function sl147_price_history_add_settings_link( $links ) {
	    $settings_link = '<a href="admin.php?page=price_history_page">' . __( 'Settings', 'sl147_gift'  ) . '</a>';
	    array_push( $links, $settings_link );
	    return $links;
	}

/**
 * check Woocommerce is active
 */
	public function sl147_PH_is_woocommerce() {
	    ?>
	    <div class="notice notice-warning">
	        <p><?php esc_html_e( 'WooCommerce must be installed and active for Price History plugin.', 'price_history' ); ?></p>
	    </div>
	    <?php
	}

	public function Sl147_PH_init_run() {

		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		    add_action( 'admin_notices', array($this, 'sl147_PH_is_woocommerce'));
		    return;
		}

		add_action( 'admin_enqueue_scripts',  array( $this, 'sl147_PH_admin_style' ));
		add_filter( 'plugin_row_meta', array($this, 'sl147_price_histor_row_meta'), 10, 4 );

		$filter_name = "plugin_action_links_" . PH_PLUGIN_BASENAME; 
		add_filter( $filter_name, array($this, 'sl147_price_history_add_settings_link') );

		require_once PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES . 'class-price_history_settings.php';
		$sl147_options = new Sl147_PH_options();
		$sl147_options->Sl147_PH_options_run();

		require_once PRICE_HISTORY_PLUGIN_DIR_PATH_INCLUDES . 'class-price_history.php';
		$sl147_class  = new Sl147_price_history();
		$sl147_class->sl147_PH_run();
	}

}