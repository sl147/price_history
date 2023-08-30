<?php
/**
 * 
 */
class Sl147_price_history_view
{
	function __construct()	{
		$this->arr_th    = ['Product', 'Price', 'Date of change', 'Price type', 'User'];
		$this->arr_td    = ['product_name', 'product_price', 'date_change', 'type_price', 'user'];
		$this->arr_class = ['text-left', 'text-right', 'text-center', 'text-center', 'text-center'];
		$this->product_selected = false;
		$this->product_ID       = 1;
		$this->product_name     = "";
		$this->arr_price        = [];
	}

	/**
	 * Get type price.
	 *
	 * @since 1.1
	 *
	 * @param int  $type_price type price: 1 - regular price, 2 - sale price
	 *
	 * @return string
	 */

	private function sl147_PH_get_type_price(int $type_price) : string {

		return (string) ($type_price == 1) 
			? __( 'regular price', 'price_history' )
			: __( 'promotional price', 'price_history' );
	}
/**
 * get WP products object.
 *
 * @since 1.1
 *
 * @return object
 */
	private function sl147_get_products() :object{
		$args = array(
			'post_type'      => 'product',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => -1
		);

		return (object) get_posts($args);
	}

		/**
	 * Get user's login.
	 *
	 * @since 1.1
	 *
	 * @param int  $user_ID user ID who changed the price.
	 *
	 * @return string
	 */

	private function sl147_PH_get_name_user(int $user_ID): string{

		return (string) get_user_by( 'ID', $user_ID )->user_login;
	}
	
	/**
	 * Get product's name.
	 *
	 * @since 1.1
	 *
	 * @param int  $product_ID product ID.
	 *
	 * @return string
	 */

	public function sl147_PH_get_name_product(int $product_ID) : string {

		return (string) strip_tags(wc_get_product( $product_ID )->name);
	}
/**
 * form item array to list products.
 *
 * @since 1.1
 *
 * @return array
 */

	private function sl147_PH_set_new_item(int $id, string $name) :array{

		return (array)[ "ID" => $id, "post_title" => $name	];
	}

	/**
	 * form array to list products.
	 *
	 * @since 1.1
	 *
	 * @return array
	 */
	private function sl147_PH_get_all_products($all_products) :array {
		$tmp_arr = [];

		array_push($tmp_arr, $this->sl147_PH_set_new_item(1, __( "All products", "price_history")));

		foreach ($all_products as $value) {
			array_push($tmp_arr, $this->sl147_PH_set_new_item($value->ID, $value->post_title));
		}
		return (array)$tmp_arr;
	}

	/**
	 * Prepare data for display.
	 *
	 * @since 1.1
	 * @param array  $array_input.
	 * @param array  $array_output.
	 * @return array
	 */
	private function sl147_PH_array_prepare (array $array_input) :array{
		$array_output = [];
		foreach ($array_input as $value) {
			$name = $this->sl147_PH_get_name_product($value->ID_product);
			if ($name) {
				$new_item = [
					'ID'            => $value->ID,
					'product_name'  => $name,
					'product_price' => $value->price_history,
					'date_change'   => date('d-m-Y', strtotime($value->date_history)), 
					'type_price'    => $this->sl147_PH_get_type_price($value->type_price),
					'user'          => $this->sl147_PH_get_name_user($value->user_change_price),
				];
				array_push($array_output, $new_item);
			}
		}
		return (array) $array_output;
	}
	/**
	 * callback function for uasort().
	 *
	 * @since 1.1
	 *
	 * @return int
	 */
	private function sl147_PH_list_sort($a, $b) :int {
		return (int) ($a['product_name'] > $b['product_name']);
	}

	/**
	 * display text in the table.
	 *
	 * @since 1.1
	 * 
	 * @param strung $text text to display
	 * 
	 * @return void
	 */

	private function sl147_PH_display_text(string $text) :void {
		echo __( $text, 'price_history' );
	}

	/**
	 * get products from BD .
	 *
	 * @since 1.1
	 *
	 * @return array
	 */
	
	private function get_name_product($product_ID) :string {
		return (string) ($product_ID == 1) ? __( 'All products', 'price_history' ) : wc_get_product( $product_ID )->get_title();
	}
	/**
	 * get products from BD .
	 *
	 * @since 1.1
	 * @param int $product_ID id product tu select
	 * @param array $array_output products from BD
	 *
	 * @return array
	 */

	private function sl147_get_products(int $product_ID) :array {
		global $wpdb;

		$sql = "SELECT * FROM " . $wpdb->prefix . 'sl147_price_history';
		if ($product_ID > 1) $sql .= " WHERE ID_product=".$product_ID;	

		$array_output = $this->sl147_PH_array_prepare($wpdb->get_results($sql));
		uasort($array_output, array($this, 'sl147_PH_list_sort'));

		return (array) $array_output;		
	}

	/**
	 * delete post from the table.
	 *
	 * @since 1.1
	 * 
	 * @param int $id_post id post to delete
	 * 
	 * @return void
	 */

	private function sl147_PH_delete_post ( int $id_post)  {
		global $wpdb;

		$sql = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "sl147_price_history" ." WHERE ID = %s ", $id_post );

		return $wpdb->query($sql);
	}

	/**
	 * require form to view price history.
	 *
	 * @since 1.1
	 *
	 * @return void
	 */

	public function sl147_PH_view_history(): void {
		global $wpdb;

		$this->product_selected = false;
		$all_products           = $this->sl147_PH_get_all_products($this->sl147_get_products());
		
		if( wp_verify_nonce( $_POST['nonce_PH'], 'nonce_action_PH' )) {
			$this->sl147_set_data($_POST['product_id']);
		}

		if( wp_verify_nonce( $_POST['nonce_field_delete'], 'nonce_delete_action' )) {

			if ($this->sl147_PH_delete_post($_POST['id_post'])) {
				$this->sl147_set_data($_POST['product_id_del']);				
			}else {
				$del_err = true;
			}

			//wp_redirect( home_url( '/wp-admin/admin.php?page=price_history-slug' ));
		}
		require_once (PRICE_HISTORY_PLUGIN_DIR_PATH . 'admin/partials/price_history_view.php');  
	}

	/**
	 * set data to display form
	 *
	 * @since 1.1
	 * 
	 * @param int $product_ID id product
	 * 
	 * @return void
	 */

	private function sl147_set_data(int $product_ID) :void{
		$this->product_ID        = $product_ID;
		$this->product_name      = $this->get_name_product($this->product_ID);
		$this->arr_price         = $this->sl147_get_products($this->product_ID);
		$this->product_selected  = true;
	}

public function sl147_PH_Orders(){
    $args = array(
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC', 
            'return' => 'ids',
            'status' => 'completed',// order status: pending || processing || on-hold || completed || cancelled || refunded || failed
        );
    $WC_Order_Query = new WC_Order_Query($args);
    $ord = $WC_Order_Query->get_orders();
    $arr_grup = [];
    for ($i=0; $i < count($ord); $i++) {
    	$order = wc_get_order( $ord[$i] );
    	$sum_order = $order->get_total();  
		echo "<br>order No: ".$order->get_id().'  user:'.$order->get_payment_method_title().'  total='.$sum_order;//$order->get_total();
		if($sum_order > 300) $new_item_300 += 1;
		elseif($sum_order > 290) $new_item_290 += 1;
		elseif($sum_order > 280) $new_item_280 += 1;
		elseif($sum_order > 270) $new_item_270 += 1;
		elseif($sum_order > 260) $new_item_260 += 1;
		elseif($sum_order > 250) $new_item_250 += 1;
		elseif($sum_order > 240) $new_item_240 += 1;
		elseif($sum_order > 230) $new_item_230 += 1;
		elseif($sum_order > 220) $new_item_220 += 1;
		elseif($sum_order > 210) $new_item_210 += 1;
		elseif($sum_order > 200) $new_item_200 += 1;
		else $new_item_100 += 1;
}
echo "<br>count ord ".count($ord);
echo "<br><200 ".$new_item_100;
echo "<br>>200 ".$new_item_200;
echo "<br>>210 ".$new_item_210;
echo "<br>>220 ".$new_item_220;
echo "<br>>230 ".$new_item_230;
echo "<br>>240 ".$new_item_240;
echo "<br>>250 ".$new_item_250;
echo "<br>>260 ".$new_item_260;
echo "<br>>270 ".$new_item_270;
echo "<br>>280 ".$new_item_280;
echo "<br>>290 ".$new_item_290;
echo "<br>>300 ".$new_item_300;
//print_r($ord);
		//require_once (PRICE_HISTORY_PLUGIN_DIR_PATH . 'admin/partials/orders_view.php'); 
}
}