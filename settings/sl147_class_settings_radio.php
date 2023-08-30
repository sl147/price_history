<?php

/**
 * 
 */
class Sl147_class_settings_radio {

    /**
     * input for type radio
     * @param $val array options
     * @param $index string name option, id for input
     * @return void
     * 
     */ 
	public function sl147_input_radio($val, $index, $settings_bd, $value_options){
		foreach ($value_options as $key => $option) {				
			if ($index == $option['id_option']) {
				$vals = $option['radio_options'];
			}
		}
		
		$vals = ($vals) ? $vals : [];

		foreach ($vals as $value) {
			$selected = ($val[$index] == $value['id_radio']) ? "checked " : '';        
			echo "<div style='display:flex; align-items: center;margin-bottom: 10px;'>";
			echo "<input type='radio' id='".$value['id_radio']."' name='" . $settings_bd . "[$index]' value='".$value['id_radio']."' ".$selected.">";
			echo "<label style='margin-right:20px;padding-bottom: 6px;' for=".$value['id_radio'].">".$value['name_radio']."</label>";
			echo "</div>";
		}
	}
}