<?php
/**
 * 
 */
class Sl147_price_history {
	
	function __construct()	{
		global $wpdb;
		$this->table = $wpdb->prefix . 'sl147_price_history';
	}

	/**
	 * Save price to the history.
	 *
	 * @since 1.1
	 *
	 * @param int  $id_product product ID.
	 * @param int  $price price to save.
	 * @param int  $type_price type of price 1 - regular_price, 2 - sale_price
	 * @param int  $user_ID user ID who changed the price.
	 *
	 * @return void
	 */

	private function sl147_PH_save_history(int $id_product, float $price, int $type_price ) : void {
		global $wpdb;

		$data = [ 
			'ID_product'        => $id_product,
			'price_history'     => $price,
			'type_price'        => $type_price,
			'user_change_price' => wp_get_current_user()->ID
		];

		$format = ['%d', '%s', '%d', '%d'];
		$wpdb->insert( $this->table, $data, $format );
	}

	private function sl147_get_last_price(array $last_price_arr, int $type) {
		foreach ($last_price_arr as $value) {
			if ($value->type_price == $type) return (float) $value->price_history;
		}

		return (float) 0;
	}
	/**
	* save price to DB
	* type_price 1 - regular price, 2 - sale price
	* @param $product_id product ID.
	* @return void
	*/
	public function sl147_PH_save_price( int $product_id ): void {
		global $wpdb;

		$regular_price      = get_post_meta( $product_id, '_regular_price', true );
		$sale_price         = get_post_meta( $product_id, '_sale_price', true );
		$last_price_arr     = array_reverse($this->sl147_PH_get_history($product_id));
		$last_regular_price = $this->sl147_get_last_price($last_price_arr, 1);
		$last_sale_price    = $this->sl147_get_last_price($last_price_arr, 2);

		if ($last_regular_price != $regular_price) $this->sl147_PH_save_history($product_id, $regular_price, 1 );

		if ($last_sale_price != $sale_price) $this->sl147_PH_save_history($product_id, $sale_price, 2 );

	}

	/**
	* Get price history of product from DB
	* 
	* @param int $id_product product ID
	* @return array of prices
	*/
	private function sl147_PH_get_history(int $id_product) : array {
		global $wpdb;

		$sql = "SELECT price_history, type_price FROM $this->table WHERE ID_product=$id_product";

		return (array) $wpdb->get_results($sql);
	}

	/**
	 * Register hooks.
	 */
	public function sl147_PH_run() : void {

		add_action( 'woocommerce_new_product',            [ $this, 'sl147_PH_save_price' ] );
		add_action( 'woocommerce_update_product',         [ $this, 'sl147_PH_save_price' ] );
		add_action( 'woocommerce_save_product_variation', [ $this, 'sl147_PH_save_price' ] );
	}
}