<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.cobra.de
 * @since      1.0.0
 *
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/includes
 * @author     Philipp Kreis <philipp.kreis@cobra.de>
 */
class Cobra_Event_Module_Wp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cobra-event-module-wp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
