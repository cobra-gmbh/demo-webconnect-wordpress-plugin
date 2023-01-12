<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.cobra.de
 * @since      1.0.0
 *
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/includes
 * @author     Philipp Kreis <philipp.kreis@cobra.de>
 */
class Cobra_Event_Module_Wp_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
        global $wpdb;
        // if table does not exist yet, create it in the database
        if($wpdb->get_var("SHOW tables like '".$this->wp_cobra_cxm_settings()."'") != $this->wp_cobra_cxm_settings()) {
            $table_query = "CREATE TABLE `".$this->wp_cobra_cxm_settings()."` (`id` int AUTO_INCREMENT,`url` text,`username` varchar(255),`password` varchar(255),`apikey` varchar(255),`token` varchar(255),`toke_lifetime` timestamp, PRIMARY KEY (id));";
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            dbDelta($table_query);
        };

	}

    public function wp_cobra_cxm_settings()
    {
        global $wpdb;
        return $wpdb->prefix."cobra_cxm_settings";
    }

}
