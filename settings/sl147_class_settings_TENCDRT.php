<?php

/**
 * 
 */
class Sl147_class_settings_TENCDRT {

    /**
     * get min max value for type number, date
     * @param $min_max string : min or max
     * @param $value_options array value options
     * @return int for number string for date
     * 
     */ 
	private function sl147_get_attribute (string $min_max, string $type, array $value_options) {
		foreach ( $value_options as $key => $option ) {
			if ($option['id_option'] == $type) {
				foreach ($option as $key1 => $value) {					
					if ($key1 == 'validate'){
						foreach ($value as $key2 => $var) {
							if ($key2 == $min_max) return $var;
						}
					}
				}
			}
		}
		return false;
	}

    /**
     * get atribute min max value
     * @param $type string  type for input
     * @param $value_options array value options
     * @return value atribute min max
     */ 
	private function sl147_get_min_max( string $type, array $value_options) :string{
		$min = intval($this->sl147_get_attribute('check_min', $type, $value_options));
		$max = intval($this->sl147_get_attribute('check_max', $type, $value_options));

		$input  = (($min) || ($min === 0) ) ? " min=" . $min : "";
		$input .= (($max) || ($max === 0) ) ? " max=" . $max : "";
		return $input;
	}

    /**
     * get atribute pattern
     * @param $type for input
     * @param $value_options array value options
     * @return value atribute pattern
     */ 
	private function sl147_get_pattern( string $type, array $value_options) {
		$pattern = $this->sl147_get_attribute('pattern', $type, $value_options);
		return ($pattern) ? " pattern='". $pattern . "'" : "";
	}

    /**
     * get atribute pattern
     * @param $type for input
     * @param $value_options array value options
     * @return value atribute pattern
     */ 
	private function sl147_get_required( string $type, array $value_options) {
		return ($this->sl147_get_attribute('required', $type, $value_options)) ? " required " : ""; 
	}

    /**
     * get atribute class
     * @param $type for input
     * @param $value_options array value options
     * @return value atribute class
     */ 
	private function sl147_get_class( string $type, array $value_options) {
		$class = $this->sl147_get_attribute('class', $type, $value_options);
		return ($class) ? " class='" . $class . "'" : "";
	}
	
     /**
     * input for type TENCDRT (Text Email Number Checkbox Date Range Tel)
     * @param $val array options
     * @param $index string name option, id for input
     * @param $type string input type
     * @param $value_options array value options
     * @return void
     * 
     */ 
	public function sl147_input_TENCDRT(array $val, string $index, string $type, string $bd, array $value_options) :void{
		$input =  "<input type='$type' id='$index' name='".$bd."[$index]' ";
		if ($type == 'checkbox') {
			$input .= "value='1' ".checked( 1, $val[$index], false );	
		}else {
			$input .=  "value='". $val[$index]."' ";	
		}

		if (   ($type == 'number') 
			|| ($type == 'date'  )
			|| ($type == 'range' ) ) $input .= $this->sl147_get_min_max( $index, $value_options );

		$input .= $this->sl147_get_required($index, $value_options);
		$input .= $this->sl147_get_class($index, $value_options);

		echo $input." />";

		if($type == 'range') echo "<p>Value: <span id='sl147_range_value'></span></p>";
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
}