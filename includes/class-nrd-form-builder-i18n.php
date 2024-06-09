<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/Alucard17th
 * @since      1.0.0
 *
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Nrd_Form_Builder
 * @subpackage Nrd_Form_Builder/includes
 * @author     Noureddine Eddallal <eddallal.noureddine@gmail.com>
 */
class Nrd_Form_Builder_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'nrd-form-builder',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
