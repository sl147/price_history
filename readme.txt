=== Price history ===
Contributors: sl147
Tags: Price history omnibus
Requires PHP: 7.4
Requires at least: 3.8
Tested up to: 6.1
Stable tag: 1.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description =
Describe $value_options in the plagin class.
Records price changes on the site. Allows to display the minimum price from the last 30 days.
Example of description of option values.
id_option, label_option, type_option is required.
$value_options = [
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

And then require class php file.
require_once <PLUGIN_DIR_PATH> . 'settings/sl147_class_settings.php';
$options = new Sl147_class_settings(<PLUGIN_DIR_PATH>, <PRICE_HISTORY_TEXT_DOMAIN>, <PAGE SLUG>, $value_options);

PLUGIN_DIR_PATH - plugin_dir_path( __FILE__) path pkugin directory
PRICE_HISTORY_TEXT_DOMAIN - plugin text domain or any text constant
PAGE SLUG - page slug in add_menu_page()

== Changelog ==
= 1.0 =
*  Initial version

== Frequently Asked Questions ==
