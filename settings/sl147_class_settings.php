<?php

/**Class for Wordpress setting options
 * 
 */
class Sl147_class_settings {

    /**
     * @param sl147_plugin_dir_path string constant PRICE_HISTORY_PLUGIN_DIR_PATH
     * @param sl147_text_domain string constant PRICE_HISTORY_TEXT_DOMAIN
     * @param $sl147_page_slug string page slug
     * @param $value_options array description each option element
     * @param $sl147_value_sections array description each section
     * 
     * 
     */ 	
	function __construct( string $sl147_page_slug, array $sl147_value_options, array $sl147_value_sections) {

		$this->sl147_page_slug         = $sl147_page_slug;
		$this->sl147_value_options     = $sl147_value_options;
		$this->sl147_value_sections    = $sl147_value_sections;
		$this->sl147_settings_bd       = 'sl147_bd_'.PRICE_HISTORY_TEXT_DOMAIN;
		$this->sl147_option_group      = 'sl147_option_group'.PRICE_HISTORY_TEXT_DOMAIN;
		$this->sl147_settings_errors   = 'sl147_settings_errors'.PRICE_HISTORY_TEXT_DOMAIN;
		$this->sl147_section_id        = $this->sl147_get_section();
		$this->sl147_fix_value_options = __( 'Fix the $value_options in the file settings/class-price_history_settings.php', 'price_history');
		$this->required_key            = ['id_section', 'label_option', 'id_option', 'type_option'];
		
		add_action( 'admin_menu',            array( $this, 'sl147_add_submenu' ), 25 );
		add_action( 'admin_init',            array( $this, 'sl147_register_options' ) );
		add_action( 'admin_notices',         array( $this, 'sl147_display_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'sl147_color_picker' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'sl147_type_range' ) );
        add_action( 'admin_footer',          array( $this, 'sl147_footer_script'), 99 );
        add_action( 'admin_enqueue_scripts', array( $this, 'sl147_settings_style' ));       
	}

	private function sl147_check_value_options () {
		$tmp = false;
		foreach ($this->sl147_value_options as $key => $option) {
			for ($i=0; $i < count($this->required_key); $i++) {
				if ( !array_key_exists( $this->required_key[$i], $option) ) {
					$label = ( $option['label_option'] ) ? $option['label_option'] : $key;
					$this->sl147_add_error( $this->required_key[$i], 'For ' . $label . ' no key ' . $this->required_key[$i] . '. ' . $this->sl147_fix_value_options);
					$tmp = true;
				}	
			}
		}
		return $tmp;
		
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
	public function sl147_display_section($args ) :void{
		if ( ! empty($this->sl147_value_sections )) {
				echo "<div class='sl147_PH_section'>" . $this->sl147_value_sections[$args['id']] . " </div>";
		}
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
			if ( array_key_exists( 'id_section', $option ) ) {
				if ($option['id_section'] == $section_id) {
					$this->sl147_add_field($page_slug, $section_id, $display_input_options, $option['id_option'], $option['label_option'], $option['type_option']);	
				}
			}else {
				$this->sl147_add_field($page_slug, 'sl147_section', $display_input_options, $option['id_option'], $option['label_option'], $option['type_option']);
			}
		}		
	}
	
 
    /**
     * register options admin
     * 
     * @return void
     */ 
	public function sl147_register_options() :void{

		if ( !empty($this->sl147_section_id) ) {

			if ( $this->sl147_check_value_options() ) return;

			foreach ($this->sl147_section_id as $key => $value){
				$this->sl147_register_settings($this->sl147_option_group, $this->sl147_settings_bd, $key, 'sl147_display_section', $this->sl147_page_slug, $this->sl147_value_options, 'sl147_display_input_options');	
			}
		}else{
			$this->sl147_register_settings($this->sl147_option_group, $this->sl147_settings_bd,'sl147_section', 'sl147_display_section', $this->sl147_page_slug, $this->sl147_value_options, 'sl147_display_input_options');
		}
		
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

    /**
     * display option for input
     * @param $field array options
     * @return void
     * 
     */ 
	public function sl147_display_input_options(array $field) :void {
		$val = get_option($this->sl147_settings_bd);
		$val  = ($val) ? $val : [];
		if( $field['type_field'] == 'select') {
			require_once PRICE_HISTORY_PLUGIN_DIR_PATH_SETTINGS . 'sl147_class_settings_select.php';
			$tmp = new Sl147_class_settings_select();
			$tmp->sl147_input_select($val, $field['name_field'], $this->sl147_settings_bd, $this->sl147_value_options);
		}elseif( $field['type_field'] == 'radio' ) {
			require_once PRICE_HISTORY_PLUGIN_DIR_PATH_SETTINGS . 'sl147_class_settings_radio.php';
			$tmp = new Sl147_class_settings_radio();
			$tmp->sl147_input_radio($val, $field['name_field'], $this->sl147_settings_bd, $this->sl147_value_options);
		}else{
			require_once PRICE_HISTORY_PLUGIN_DIR_PATH_SETTINGS . 'sl147_class_settings_TENCDRT.php';
			$tmp = new Sl147_class_settings_TENCDRT();
			$tmp->sl147_input_TENCDRT($val, $field['name_field'], $field['type_field'], $this->sl147_settings_bd, $this->sl147_value_options);
		}
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
					if ( ! empty( get_settings_errors( $this->sl147_settings_errors  ) ) ) return;
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
					<p><?php echo __( 'Settings updated', 'price_history'  )?></p>
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

    private function sl147_get_section(){
    	$tmp = [];
    	foreach ( $this->sl147_value_options as $key => $option ) {
    		if ( array_key_exists( 'id_section', $option )) {
    			$tmp[$option['id_section']] = 1;	
    		}   		
    	}
    	return (array) $tmp;
    }
    /**
     * validate input data
     * @param $input array 
     * @return array
     */ 
	public function sl147_options_validate( array $input ) :array {
		foreach( $input as $name_option => & $val ){
			foreach ( $this->sl147_value_options as $key => $option ) {		
				if ($name_option == $option['id_option']) {
					if ( $option['type_option'] == 'email') {
						if ( ! is_email($val)) {
							$input[$option['id_option']] = $this->sl147_add_error($name_option,$option['label_option'].' '.__('Invalid email address entered '.$err, 'price_history' ). ": " .$val);
						}
					}
					if ( $option['validate'] ) {
						foreach ( $option['validate'] as $key => $value ) {
							if ( $key == 'check_color') {
								if(!$this->sl147_check_color($val)) {
									$input[$option['id_option']] = $this->sl147_add_error($name_option,$option['label_option'].' '.__('wrong color code'.$err,'price_history' ). ": " .$val);
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