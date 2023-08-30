<?php
/**
 * 
 */
class Sl147_price_history_view {

	function __construct()	{
		global $wpdb;
		$this->table     = $wpdb->prefix . 'sl147_price_history';
		$this->arr_th    = [
			__( 'Product',        'price_history' ),
			__( 'Category',       'price_history' ),
			__( 'Price',          'price_history' ),
			__( 'Date of change', 'price_history' ),
			__( 'Price type',     'price_history' ),
			__( 'User',           'price_history' ),
		];
		$this->arr_td            = ['product_name', 'category', 'product_price', 'date_change', 'type_price', 'user'];
		$this->arr_td_style      = ['left', 'center', 'right', 'center', 'center', 'center'];
		$this->arr_class         = ['text-left', 'text-center', 'text-right', 'text-center', 'text-center', 'text-center'];
		$this->product_selected  = false;
		$this->product_ID        = 1;
		$this->product_name      = "";
		$this->arr_price         = [];
		$this->all_products      = __( "All products", "price_history");
		$this->sl147_settings_bd = 'sl147_bd_'.PRICE_HISTORY_TEXT_DOMAIN;
		$this->sl147_category    = '';
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
						? __( 'regular price',     'price_history' )
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

	private function sl147_PH_get_name_product(int $product_ID) : string {

		return (string) strip_tags(wc_get_product( $product_ID )->name);
	}
	/**
	 * form item array to list products.
	 *
	 * @param @id int product ID
	 * @param $name string product name 
	 *
	 * @return array
	 */

	private function sl147_PH_set_new_item(int $id, string $name) :array{

		return (array) [
							"ID"         => $id,
							"post_title" => $name
						];
		}

	/**
	 * form array to list products.
	 *
	 * @param $all_products array all products in the site
	 *
	 * @return array
	 */
	private function sl147_PH_get_all_products($all_products) :array {
		$tmp_arr = [];

		array_push($tmp_arr, $this->sl147_PH_set_new_item(1, $this->all_products));

		foreach ($all_products as $value) {
			array_push($tmp_arr, $this->sl147_PH_set_new_item($value->ID, $value->post_title));
		}
		return (array) $tmp_arr;
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
					'ID_product'    => $value->ID_product,
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
	 * get products from BD .
	 *
	 * @since 1.1
	 *
	 * @return array
	 */
	
	private function sl147_PH_name_product_to_display($product_ID) :string {
		return (string) ($product_ID == 1) 
						? $this->all_products
						: wc_get_product( $product_ID )->get_title();
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

	private function sl147_get_products_ID(int $product_ID) :array {
		global $wpdb;

		$sql = "SELECT * FROM " . $this->table;
		if ($product_ID > 1) $sql .= " WHERE ID_product=".$product_ID;	

		$array_output = $this->sl147_PH_array_prepare($wpdb->get_results($sql));
		$array_output = $this->sl147_PH_data_sort( $array_output, array(
			'product_name' => 'desc',
			'date_change'  => 'asc') );
		//$array_output = $arr;

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

		$sql = $wpdb->prepare("DELETE FROM " . $this->table ." WHERE ID = %s ", $id_post );

		return $wpdb->query($sql);
	}

	/**
	 * get category names for product
	 *
	 * @param int $product_ID id product
	 * 
	 * @return string
	 */
	private function sl147_get_category_names(int $product_ID) :string {
		$terms     = get_the_terms( $product_ID, 'product_cat' );		
		$cat_names = '';
		if ($terms){
			$last_element = $terms[array_key_last($terms)];
			foreach ($terms as $term) {
				$separator  = ($term->term_id == $last_element->term_id) ? '' : ' | ';
				$cat_names .= $term->name . $separator;
			}
		}
		return (string) $cat_names;
	}


function sl147_PH_data_sort( $array, $args ) { //= array('votes' => 'desc') ){
	usort( $array, function( $a, $b ) use ( $args ){
		$res = 0;

		$a = (object) $a;
		$b = (object) $b;

		foreach( $args as $k => $v ){
			if( $a->$k == $b->$k ) continue;

			$res = ( $a->$k < $b->$k ) ? 1 : -1;
			if( $v=='desc' ) $res= -$res;
			break;
		}

		return $res;
	} );

	return $array;
}

	/**
	 * add category to arr_price
	 * 
	 * @param int $product_ID id product or 1 for all products
	 * 
	 * @return void
	 */

	private function sl147_add_category ( int $product_ID) :void {		
		$arr_temp = [];
		foreach ($this->arr_price as $value) {
			$cat_names           = $this->sl147_get_category_names( ( $product_ID == 1) ? $value['ID_product'] : $product_ID );
			$value['id']         = $product_ID;
			$value['category']   = $cat_names;
			$value['cat_select'] = (strpos($cat_names, $this->sl147_category) === false) ? false : true;
			array_push($arr_temp, $value);			
		}
		unset($this->arr_price);
		$this->arr_price = $arr_temp;
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
		$this->product_ID       = $product_ID;
		$this->product_name     = $this->sl147_PH_name_product_to_display($this->product_ID);
		$this->arr_price        = $this->sl147_get_products_ID($this->product_ID);
		$this->product_selected = true;
		$this->sl147_add_category($this->product_ID);
	}

	/**
	 * require form to view price history.
	 *
	 * @since 1.1
	 *
	 * @return void
	 */

	public function sl147_PH_view_history(): void {

		$this->product_selected = false;
		$all_products           = $this->sl147_PH_get_all_products($this->sl147_get_products());
		$delete_button          = __('Delete this price', 'price_history');
		if ( get_option( $this->sl147_settings_bd ) ) {
			foreach (get_option($this->sl147_settings_bd) as $key =>$option) {
				if ($key == 'sl147_option_color') $option_color = $option;
				if ($key == 'sl147_option_font')  $font_size    = $option."px";
				if ($key == 'sl147_option_category') {
					$sl147_cat  = get_term_by( 'id', $option, 'product_cat');
					$this->sl147_category  = $sl147_cat->name;
				}
			}
		}else{
			$option_color = "red";
			$font_size    = "16px";
		}

		if( wp_verify_nonce( $_POST['nonce_PH'], 'nonce_action_PH' )) {
			$this->sl147_set_data(intval($_POST['product_id']));
		}
		
		if( wp_verify_nonce( $_POST['nonce_field_delete'], 'nonce_delete_action' )) {
			$delete_err = 0;
			if ($this->sl147_PH_delete_post(intval($_POST['id_post']))) {
				$this->sl147_set_data(intval($_POST['product_id_del']));
				$price        = floatval($_POST['product_price']);
				$product_name = sanitize_text_field($_POST['product_name']);
				$delete_err   = 2;				
			}else {
				$delete_err = 1;
			}
		}

		require_once (PRICE_HISTORY_PLUGIN_DIR_PATH . 'admin/partials/price_history_view.php');
	}
}