<?php

/**Class for Wordpress setting options
 * 
 * $value_options = [
			'color_option' => array (
				'id_option'    => 'sl147_option_color',
				'label_option' => __( 'Color', 'price_history' ),
				'type_option'  => 'text',
				'validate'     => array(
					'required' => true,
					'check_color' => true
				),
			),
			'text_option' => array (
				'id_option'    => 'sl147_option_text',
				'label_option' => __( 'text ', 'price_history' ),
				'type_option'  => 'text',
				'validate'     => array(
					'required' => true
				),
			),
			'number_option' => array (
				'id_option'    => 'sl147_option_number',
				'label_option' => __( 'number', 'price_history' ),
				'type_option'  => 'number',
				'validate'     => array(
					'required' => true,
					'check_min'   => 1,
					'check_max'   => 10
				),
			),
			'date_option' => array (
				'id_option'    => 'sl147_option_date',
				'label_option' => __( 'date ', 'price_history' ),
				'type_option'  => 'date',
				'validate'     => array(
					'required' => true,
					'check_min'   => '2023-01-01',
					'check_max'   => '2023-06-29'
				),
			),
			'range_option' => array (
				'id_option'    => 'sl147_option_range',
				'label_option' => __( 'range', 'price_history' ),
				'type_option'  => 'range',
				'validate'     => array(
					'required' => true,
					'check_min'   => '10',
					'check_max'   => '100'
				),
			),
			'email_option' => array (
				'id_option'    => 'sl147_option_email',
				'label_option' => __( 'Email', 'price_history' ),
				'type_option'  => 'email',
				'validate'     => array(
					'required' => false
				),
			),
			'tel_option' => array (
				'id_option'    => 'sl147_option_tel',
				'label_option' => __( 'Phone', 'price_history' ),
				'type_option'  => 'tel',
				'validate'     => array(
					'required' => true,
					'pattern' => '[0-9]{3}-[0-9]{3}-[0-9]{3}',
				),
			),
			'select_option' => array (
				'id_option'    => 'sl147_option_select',
				'label_option' => __( 'select', 'price_history' ),
				'type_option'  => 'select',
				'select_options'=> $this->sl147_get_select()
			),
			'checkbox_option' => array (
				'id_option'    => 'sl147_option_checkbox',
				'label_option' => __( 'checkbox', 'price_history' ),
				'type_option'  => 'checkbox',
			),
			'radio_option' => array (
				'id_option'    => 'sl147_option_radio',
				'label_option' => __( 'radio', 'price_history' ),
				'type_option'  => 'radio',
				'radio_options'=> $this->sl147_get_radio()
			),

		];
 */
class Sl147_class_settings {

    /**
     * @param PLUGIN_TB_TEXT_DOMAIN string text domain for translate
     * @param $sl147_page_slug string page slug
     * @param $value_options array description each option element as 
     *         array(id_option => id option, label_option =>label option,  type_option=> type option for input)
     * 
     * 
     */ 	
	function __construct(string $sl147_plugin_dir_path, string $sl147_text_domain, string $sl147_page_slug, array $sl147_value_options_admin, array $sl147_value_options_front) {
		$this->sl147_text_domain     = $sl147_text_domain;
		$this->sl147_plugin_dir_path = $sl147_plugin_dir_path;
		$this->sl147_page_slug       = $sl147_page_slug;
		$this->sl147_value_options_admin   = $sl147_value_options_admin;
		$this->sl147_value_options_front   = $sl147_value_options_front;
		$this->sl147_section_id_admin = 'sl147_section_id_admin_'.$this->sl147_text_domain;
		$this->sl147_section_id_front = 'sl147_section_id_front_'.$this->sl147_text_domain;
		$this->sl147_settings_bd     = 'sl147_bd_'.$this->sl147_text_domain;
		$this->sl147_option_group    = 'sl147_option_group'.$this->sl147_text_domain;
		$this->sl147_settings_errors = 'sl147_settings_errors'.TEXT_DOMAIN;

		add_action( 'admin_menu',            array( $this, 'sl147_add_submenu' ), 25 );
		add_action( 'admin_init',            array( $this, 'sl147_register_options_admin' ) );
		add_action( 'admin_init',            array( $this, 'sl147_register_options_front' ) );
		add_action( 'admin_notices',         array( $this, 'sl147_display_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'sl147_color_picker' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'sl147_type_range' ) );
        add_action( 'admin_footer',          array( $this, 'sl147_footer_script'), 99 );
        add_action( 'admin_enqueue_scripts', array( $this, 'sl147_settings_style' ));
	}

	public function sl147_settings_style(){
	    wp_enqueue_style( 'sl147_settings_style', plugins_url( 'settings/css/sl147_settings.css', dirname(__FILE__) ) , array());
	}

    /**
     * script for color-picker
     * 
     */ 
    public function sl147_footer_script() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($){
                $('input[name*="color"]').wpColorPicker();
                palettes: true
            });
        </script>
        <?php
    }

    /**
     * register script and style for color picker
     * 
     */ 
    public function sl147_color_picker( $hook ) {
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style ( 'wp-color-picker' );
    }
 
    /**
     * display option section
     * 
     * @return void
     */ 
	private function sl147_display_section( string $title) :void{
		echo "<div class='sl147_PH_section'>$title</div>";
	} 
    /**
     * display option section general
     * 
     * @return void
     */ 
	public function sl147_display_section_admin() :void{
		$this->sl147_display_section( __( 'Admin Settings', 'simple-top-bar' ) );
	}

	    /**
     * display option section general
     * 
     * @return void
     */ 
	public function sl147_display_section_front() :void{
		$this->sl147_display_section( __( 'Front Settings', 'simple-top-bar' ) );
	}
	
	/**
     * add setting field
     * @param $page_slug string page slug
	 * @param $section_id string section id
	 * @param $callback string function callback
	 * @param $name_field string name field
     * @param $label string label for input
     * @param $type  string type for input
     * @return void
     * 
     */ 
	private function sl147_add_field(string $page_slug, string $section_id, string $callback, string $name_field, string $label, string $type, string $vals = "") :void {
		add_settings_field(
			$name_field,
			$label,
			array($this, $callback),
			$page_slug,
			$section_id,
			array( 
				'label_for'  => $name_field,
				'class'      => 'sl147_gift-class',
				'name_field' => $name_field,
				'type_field' => $type,
				'vals'       => $vals
			)
		);
	}

    /**
     * register options
     * 
     * @return void
     */ 
	private function sl147_register_settings(string $option_group, string $settings_bd, string $section_id, string $display_section, string $page_slug, array $value_options, string $display_input_options) :void{

		register_setting( $option_group, $settings_bd, 	array($this,'sl147_options_validate') );

		add_settings_section( $section_id, '', array($this, $display_section), $page_slug );

		foreach ($value_options as $option) {
			$this->sl147_add_field($page_slug, $section_id, $display_input_options, $option['id_option'], $option['label_option'], $option['type_option']);
		}		
	}
	
 
    /**
     * register options admin
     * 
     * @return void
     */ 
	public function sl147_register_options_admin() :void{
		$this->sl147_register_settings($this->sl147_option_group, $this->sl147_settings_bd, $this->sl147_section_id_admin, 'sl147_display_section_admin', $this->sl147_page_slug, $this->sl147_value_options_admin, 'sl147_display_input_options_admin');
	}

    /**
     * register options front
     * 
     * @return void
     */ 
	public function sl147_register_options_front() :void{
		$this->sl147_register_settings($this->sl147_option_group, $this->sl147_settings_bd, $this->sl147_section_id_front, 'sl147_display_section_front', $this->sl147_page_slug, $this->sl147_value_options_front, 'sl147_display_input_options_front');
	}

    /**
     * script for color-picker
     * 
     */ 
    public function sl147_type_range() {
		?>
			<script>
				var slider = document.getElementById("sl147_option_range1");
				var output = document.getElementById("sl147_range_value");
				output.innerHTML = slider.value;

				slider.oninput = function() {
				  output.innerHTML = this.value;
				}
			</script>
        <?php
    }

	private function get_data(array $field, string $settings_bd, array $value_options) {
		$val = get_option($settings_bd);
		$val  = ($val) ? $val : [];
		if( $field['type_field'] == 'select') {
			require_once $this->sl147_plugin_dir_path . 'settings/sl147_class_settings_select.php';
			$tmp = new Sl147_class_settings_select();
			$tmp->sl147_input_select($val, $field['name_field'], $settings_bd, $value_options);
		}elseif( $field['type_field'] == 'radio' ) {
			require_once $this->sl147_plugin_dir_path . 'settings/sl147_class_settings_radio.php';
			$tmp = new Sl147_class_settings_radio();
			$tmp->sl147_input_radio($val, $field['name_field'], $settings_bd, $value_options);
		}else{
			require_once $this->sl147_plugin_dir_path . 'settings/sl147_class_settings_TENCDRT.php';
			$tmp = new Sl147_class_settings_TENCDRT();
			$tmp->sl147_input_TENCDRT($val, $field['name_field'], $field['type_field'], $settings_bd, $value_options);
		}
	}

    /**
     * display option for input
     * @param $field array options
     * @return void
     * 
     */ 

	public function sl147_display_input_options_admin(array $field) :void{
		$this->get_data($field, $this->sl147_settings_bd, $this->sl147_value_options_admin);
	}
	
    /**
     * display option for input
     * @param $field array options
     * @return void
     * 
     */ 

	public function sl147_display_input_options_front(array $field) :void{
		$this->get_data($field, $this->sl147_settings_bd, $this->sl147_value_options_front);
	}
    /**
     * display form
     * @return void
     * 
     */ 
	public function sl147_form_display() {
		?>
		<div class="wrap">
			
			<form method="post" action="options.php" class="sl147_class_settings">
				<?php
					settings_errors( $this->sl147_settings_errors );
					settings_fields( $this->sl147_option_group);
					do_settings_sections( $this->sl147_page_slug );
					submit_button(); 
				?>
			</form>
		</div>
		<?php
	}

    /**
     * register submenu
     * @return void
     */ 
	public function sl147_add_submenu() :void{
		add_submenu_page(
			'price_history-slug',
			__( 'Settings', 'price_history' ),
			__( 'Settings', 'price_history' ),
			'manage_options',
			$this->sl147_page_slug,
			array( $this, 'sl147_form_display' )
		);
	}

    /**
     * display notice
     * @return void
     * 
     */ 	
	public function sl147_display_notice() :void {

		if ( ! empty( get_settings_errors( $this->sl147_settings_errors  ) ) ) return;
		
		if(	isset( $_GET[ 'page' ] )
			&& $this->sl147_page_slug == $_GET[ 'page' ]
			&& isset( $_GET[ 'settings-updated' ] )
			&& true == $_GET[ 'settings-updated' ]
		) {
			?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo __( 'Settings updated', 'sl147_Top_Bar'  )?></p>
				</div>
			<?php
		}
	}

    /**
     * Check valid HEX code.
     * @param  $color string HEX code
     * @return bool true if $color is #000 or #000000
     */

    private function sl147_check_color( string $color ) { 
    	return ( ( preg_match( '/^#[a-f0-9]{6}$/i', $color )) || (preg_match( '/^#[a-f0-9]{3}$/i', $color ))  ) ? true : false;
    }

    /**
     * validate input data
     * @param $input array 
     * @return array
     */ 
	public function sl147_options_validate( array $input ) :array {
		foreach( $input as $name_option => & $val ){
			foreach ( $this->sl147_value_options_admin as $key => $option ) {		
				if ($name_option == $option['id_option']) {
					if ( $option['type_option'] == 'email') {
						if ( ! is_email($val)) {
							$input[$option['id_option']] = $this->sl147_add_error($name_option,$option['label_option'].' '.__('Invalid email address entered '.$err, 'sl147_Top_Bar' ). ": " .$val);
						}
					}
					if ( $option['validate'] ) {
						foreach ( $option['validate'] as $key => $value ) {
							if ( $key == 'check_color') {
								if(!$this->sl147_check_color($val)) {
									$input[$option['id_option']] = $this->sl147_add_error($name_option,$option['label_option'].' '.__('wrong color code'.$err,'sl147_Top_Bar' ). ": " .$val);
								}
							}
						}
					}
				}
			}
		}
		return (array) $input;
	}

    /**
     * add eroor message. get and return old field value
     * @param $name_field string 
     * @param $message string error message 
     * @return 
     */ 
	private function sl147_add_error($name_field, $message){
		add_settings_error( $this->sl147_settings_errors, $name_field, $message,'error' );

		foreach (get_option($this->sl147_settings_bd) as $key => $option) {
			if ($key == $name_field) return $option;
		}
	}
}