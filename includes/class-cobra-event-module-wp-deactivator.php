<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.cobra.de
 * @since      1.0.0
 *
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/includes
 * @author     Philipp Kreis <philipp.kreis@cobra.de>
 */
class Cobra_Event_Module_Wp_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS " . $this->wp_cobra_cxm_settings());
	}

    public function wp_cobra_cxm_settings()
    {
        global $wpdb;
        return $wpdb->prefix."cobra_cxm_settings";
    }

}
