<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.cobra.de
 * @since      1.0.0
 *
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cobra_Event_Module_Wp
 * @subpackage Cobra_Event_Module_Wp/admin
 * @author     Philipp Kreis <philipp.kreis@cobra.de>
 */
class Cobra_Event_Module_Wp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        add_action( 'admin_init', [$this, 'register_cobra_settings'] );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

        $valid_pages = [
            'cobra-crm',
            'cobra-crm-cxm-data'
        ];

        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : "";
        if(in_array($page, $valid_pages)) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cobra-event-module-wp-admin.css', array(), $this->version, 'all' );
        }


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cobra_Event_Module_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cobra_Event_Module_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cobra-event-module-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

    /***
     * Register our Menu for the settings / admin Page
     *
     * @since   1.0.0
     */
    public function event_management_settings_menu()
    {
        add_menu_page( "cobra EVENTS", "cobra EVENTS", "manage_options", "cobra-crm", [$this, "open_cobra_eventportal"], "dashicons-admin-site", 22);

        add_submenu_page( "cobra-crm", "Event Portal", "Event Portal", "manage_options", "cobra-crm", [$this, "open_cobra_eventportal"] );
        add_submenu_page( "cobra-crm", "CXM API Daten", "CXM API Daten", "manage_options", "cobra-crm-cxm-data", [$this, "open_cobra_cxm_settingspage"] );
    }

    /**
     * Page mit iFrame um eure Applikation einzubinden
     */

    public function open_cobra_eventportal()
    {
        echo "<h1>cobra EVENT Portal</h1><br><iframe width='98%' height='100%' style='height: 100vh' src='https://demo-1.unsere-events.com'></iframe>";
    }

    /**
     * cobra CXM Settings Page
     */
    public function register_cobra_settings()
    {
        register_setting( "cobra_cxm_webconnect", "cobra_cxm_webconnect_url");
        register_setting( "cobra_cxm_webconnect", "cobra_cxm_webconnect_apikey");
        register_setting( "cobra_cxm_webconnect", "cobra_cxm_webconnect_username");
        register_setting( "cobra_cxm_webconnect", "cobra_cxm_webconnect_password");
        register_setting( "cobra_cxm_webconnect", "cobra_cxm_webconnect_token");
        // register_setting( "cobra_cxm_webconnect", "cobra_cxm_webconnect_tokenlifetime_end");

        add_settings_section( "cobra_cxm_webconnect_section", "Verbindungs-Einstellungen", [$this, "cobra_cxm_intro_text"], "cobra_crm_cxm_data");

        add_settings_field( "cobra_cxm_webconnect_url_field", "URL und Port", [$this, "cobra_cxm_webconnect_url_field_html"], "cobra_crm_cxm_data", "cobra_cxm_webconnect_section" );
        add_settings_field( "cobra_cxm_webconnect_apikey_field", "API-Key", [$this, "cobra_cxm_webconnect_apikey_field_html"], "cobra_crm_cxm_data", "cobra_cxm_webconnect_section" );
        add_settings_field( "cobra_cxm_webconnect_username_field", "User", [$this, "cobra_cxm_webconnect_username_field_html"], "cobra_crm_cxm_data", "cobra_cxm_webconnect_section" );
        add_settings_field( "cobra_cxm_webconnect_password_field", "Passwort", [$this, "cobra_cxm_webconnect_password_field_html"], "cobra_crm_cxm_data", "cobra_cxm_webconnect_section" );
    }

    public function open_cobra_cxm_settingspage()
    {
        ?>
        <h1>cobra CXM WEBCONNECT Einstellungen</h1>
        <form action="options.php" method="POST">
            <?php
                settings_fields( "cobra_cxm_webconnect" );
                do_settings_sections( "cobra_crm_cxm_data" );
                submit_button( "Verbindungsdaten speichern" );
                // echo "<button>Verbindung testen</button>";
            ?>
        </form>
        <?php
    }

    public function cobra_cxm_intro_text()
    {
        echo "Machen Sie hier Ihre Einstellungen für CXM WEBCONNECT. Die korrekten Einstellungen sind pro cobra CRM Installation verschieden. <br>Bitte wenden Sie sich an Ihren IT-Administrator. <br><br>Beachten Sie die Portfreigaben in Ihrer Firewall. Weitere Infos finden Sie in der cobra API Dokumentation unter https://api-docs.cobra.de";
    }

    public function cobra_cxm_webconnect_url_field_html()
    {
        ?>
            <input type="url" name="cobra_cxm_webconnect_url" id="cobra_cxm_webconnect_url" value="<?php echo esc_attr( get_option( "cobra_cxm_webconnect_url" ) ); ?>">
        <?php
    }

    public function cobra_cxm_webconnect_apikey_field_html()
    {
        ?>
            <input type="password" name="cobra_cxm_webconnect_apikey" id="cobra_cxm_webconnect_apikey" value="<?php echo esc_attr( get_option( "cobra_cxm_webconnect_apikey" ) ); ?>">
        <?php
    }

    public function cobra_cxm_webconnect_username_field_html()
    {
        ?>
            <input type="text" name="cobra_cxm_webconnect_username" id="cobra_cxm_webconnect_username" value="<?php echo esc_attr( get_option( "cobra_cxm_webconnect_username" ) ); ?>">
        <?php
    }

    public function cobra_cxm_webconnect_password_field_html()
    {
        ?>
            <input type="password" name="cobra_cxm_webconnect_password" id="cobra_cxm_webconnect_password" value="<?php echo esc_attr( get_option( "cobra_cxm_webconnect_password" ) ); ?>">
        <?php
    }

}
