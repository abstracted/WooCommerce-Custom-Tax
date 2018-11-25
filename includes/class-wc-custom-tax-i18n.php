<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       cameron.computer
 * @since      1.0.0
 *
 * @package    Wc_Custom_Tax
 * @subpackage Wc_Custom_Tax/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Custom_Tax
 * @subpackage Wc_Custom_Tax/includes
 * @author     Cameron Sanders <csanders@protonmail.com>
 */
class Wc_Custom_Tax_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-custom-tax',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
