<?php

/**
 * class add min price text to the price
 */

class Sl147_price_history_min_price {
	
	function __construct()	{
		add_filter( 'woocommerce_get_price_suffix', array( $this, 'sl147_add_price_suffix'), 99);
	}

	/**
	 * Get min price of product of last_30_days.
	 * @param $product_id product ID.
	 * @return float min price of product or the '_regular_price'('_sale_price') if there is no history
	 */
	private function sl147_get_min_price_last_30_days(int $product_id) : float {
		global $wpdb;
		$ph_table            = $wpdb->prefix . 'sl147_price_history';
		$sql                 = "SELECT price_history FROM $ph_table WHERE (ID_product=$product_id AND DATE(date_history) <= NOW() AND date_history >= DATE_SUB(NOW(), INTERVAL 30 DAY))";
		$array_price         = $wpdb->get_results($sql);
		$delete_last_element = array_pop($array_price);

		return (float) (empty($array_price))
					? get_post_meta( $product_id, '_price', true )
					: min($array_price)->price_history;
	}

/**
 * form string with min price
 * @return string
 */
	private function sl147_show_min_price() :string{
		global $product;

	    $css   = get_option('sl147_bd_' . PRICE_HISTORY_TEXT_DOMAIN);
	    $color = ( is_product() ) ? $css['price_color_single'] : $css['price_color_loop'];
	    $echo  = '<div style="font-size:12px; color:'. $color .'">' . __('Lowest price in the last 30 days', 'price_history' );
	    $echo .= ( is_product() ) ? '<span style="font-size: '. $css['price_font_single']. 'px"> ' : '<div style="font-size:' . $css['price_font_loop'] . 'px" >';
	    $echo .= number_format( $this->sl147_get_min_price_last_30_days($product->get_id()), 2 ) . ' ' . get_woocommerce_currency_symbol();
	    $echo .= ( is_product() ) ? '</span>' : '</div>'; 

	    return (string) $echo . '</div>';
	}

/**
 * add min price text to the price
 * @param $html string price text
 * @return string
 */

	public function sl147_add_price_suffix( string $html) {
		return (string) $html . $this->sl147_show_min_price();
	}
}