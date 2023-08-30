<?php

/**
 * 
 */
defined( 'ABSPATH' ) || exit;

class Sl147_PH_options{

	public function Sl147_PH_options_run() {

		$value_options = [
			'select_option' => array (
				'id_section'   => 'admin',
				'id_option'    => 'sl147_option_category',
				'label_option' => __( 'Category', 'price_history' ),
				'type_option'  => 'select',
				'select_options'=> $this->sl147_get_select()
			),
			'color_option' => array (
				'id_section'   => 'admin',
				'id_option'    => 'sl147_option_color',
				'label_option' => __( 'Color', 'price_history') ,
				'type_option'  => 'text',
				'validate'     => array(
					'required' => true,
					'check_color' => true
				),
			),
			'number_option' => array (
				'id_section'   => 'admin',
				'id_option'    => 'sl147_option_font',
				'label_option' => __( 'Font size(px)', 'price_history' ),
				'type_option'  => 'number',
				'validate'     => array(
					'required' => true,
					'check_min'   => 8,
					'check_max'   => 30
				),
			),
			'price_font_loop' => array (
				'id_section'   => 'front_loop',
				'id_option'    => 'price_font_loop',
				'label_option' => __( 'Font size price (px)', 'price_history' ),
				'type_option'  => 'number',
				'validate'     => array(
					'required' => true,
					'check_min'   => 8,
					'check_max'   => 30
				),
			),
			'price_color_loop' => array (
				'id_section'   => 'front_loop',
				'id_option'    => 'price_color_loop',
				'label_option' => __('Price color', 'price_history'),
				'type_option'  => 'text',
				'validate'     => array(
					'required' => true,
					'check_color' => true
                ),
            ),
			'price_font_single' => array (
				'id_section'   => 'front_single',
				'id_option'    => 'price_font_single',
				'label_option' => __( 'Font size price (px)', 'price_history' ),
				'type_option'  => 'number',
				'validate'     => array(
					'required' => true,
					'check_min'   => 8,
					'check_max'   => 30
				),
			),
			'price_color_single' => array (
				'id_section'   => 'front_single',
				'id_option'    => 'price_color_single',
				'label_option' => __('Price color', 'price_history'),
				'type_option'  => 'text',
				'validate'     => array(
					'required' => true,
					'check_color' => true
                ),
            ),
		];
		
		$value_sections = [
			'admin'        => __('Customizing of price history output', 'price_history' ),
			'front_single' => __('Output settings minimum price on single product page', 'price_history' ),
			'front_loop'   => __('Output settings minimum price on products page', 'price_history' )
		];
		require_once PRICE_HISTORY_PLUGIN_DIR_PATH . 'settings/sl147_class_settings.php';
		$sl147_options = new Sl147_class_settings( 'price_history_page', $value_options, $value_sections);
	}

	private function sl147_get_select(){
		global $product;
		$args = array(
		    'taxonomy' => 'product_cat',
		    'order'    => 'ASC'
		  );
		$val = [];
			foreach (get_categories($args ) as  $value) {
				$item = [
					'id_select'   => $value->term_id,
					'name_select' => $value->name
				];
				array_push($val, $item);				
			}
		return $val;
	}
}