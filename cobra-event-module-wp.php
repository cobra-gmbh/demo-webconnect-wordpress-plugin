<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.cobra.de
 * @since             1.0.0
 * @package           Cobra_Event_Module_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       cobra Event-Modul WP
 * Plugin URI:        https://api-docs.cobra.de/wordpress-event-modul
 * Description:       The Plugin allows to show Events derived from your cobra CRM Event-Module on your Wordpress site.
 * Version:           1.0.0
 * Author:            Philipp Kreis
 * Author URI:        https://www.cobra.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cobra-event-module-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COBRA_EVENT_MODULE_WP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cobra-event-module-wp-activator.php
 */
function activate_cobra_event_module_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cobra-event-module-wp-activator.php';
    $activator = new Cobra_Event_Module_Wp_Activator();
    $activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cobra-event-module-wp-deactivator.php
 */
function deactivate_cobra_event_module_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cobra-event-module-wp-deactivator.php';
    $deactivator = new Cobra_Event_Module_Wp_Deactivator();
    $deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_cobra_event_module_wp' );
register_deactivation_hook( __FILE__, 'deactivate_cobra_event_module_wp' );

/**
 * Create a new REST Endpoint in the WP API to fetch Events
 * ENDPOINT: <yourdomain>/wp-json/cobra-events/v1/events?limit=10
 * TUTORIAL: https://awhitepixel.com/blog/in-depth-guide-in-creating-and-fetching-custom-wp-rest-api-endpoints/
 */

add_action( 'rest_api_init', function() {
	register_rest_route( 'cobra-events/v1', '/events',
    [
		'methods'   => WP_REST_Server::READABLE,
		'callback' => 'cobra_rest_route_getevents',
	],
    [
        'limit' => [
            'required' => true,
            'type'     => 'number',
        ],
    ]);
} );



require plugin_dir_path( __FILE__ ) . 'includes/class-cobra-cxm-connect.php';

function cobra_rest_route_getevents($request)
{
    $limit = $request->get_param( 'limit' );
    $token = cobra_cxm_connect::getToken();

    if($token === "api-error") {
        return new WP_REST_Response( "Error receiving API Token", 500 );
    }

    $events = cobra_cxm_connect::getEvents($token, $limit);

    if($events === "api-error") {
        return new WP_REST_Response( "Error receiving Events", 500 );
    }

    return new WP_REST_Response($events, 200);
}






    /**
     * cobra Shortcode
     */

     add_shortcode( 'cobra_events', 'cobra_events_shortcode' );
     function cobra_events_shortcode( $atts ) {

         $a = shortcode_atts( ['limit' => '5'], $atts );

         $token = cobra_cxm_connect::getToken();
         $events = cobra_cxm_connect::getEvents($token, $a['limit']);

         if($events == "api-error") {
             return "<div>Technischer Fehler beim abrufen der Events (API Fehler).</div>";
         }

         if(!$events) {
             return "<div>Aktuell keine Events</div>";
         }

         switch_to_locale('de_DE');
         $output = '<div class="cobra-event-box">';
         foreach ($events as $event) {
             $start = date_create($event->{'Datum Beginn'});
             $end = date_create($event->{'Datum Ende'});
             $starttime = date_create($event->{'Uhrzeit Beginn'});
             $endtime = date_create($event->{'Uhrzeit Ende'});
             $day = date_format($start, "d");
             $month = date_format($start, "M");
             $timespan = date_format($start, "D, d.m.y") . ' - ' . date_format($end, "D, d.m.y") . ' um ' . date_format($starttime, "G:i") . ' - ' . date_format($endtime, "G:i");
             $link = $event->{'Direktlink Veranstaltung'};

             $output .= '<div class="cobra-event-box-line">';
                 $output .= '<div class="cobra-event-box-line_date"><div class="cobra-event-box-line_date_content"><div class="cobra-event-box-line_date_content_month">' . $month . '</div><div class="cobra-event-box-line_date_content_day">' . $day . '</div></div></div>';
                 $output .= '<div class="cobra-event-box-line_facts">';
                     $output .= '<div class="cobra-event-box-line_facts_eventtitle"><a href="'. $link .'" target="_blank">' . $event->Caption . '</a></div>';
                     $output .= '<div class="cobra-event-box-line_facts_timespan"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="cobra-event-box-line_facts_icon">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                 </svg>
                 ' . $timespan . '</div>';
                 $output .= '<div class="cobra-event-box-line_facts_location"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="cobra-event-box-line_facts_icon">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                 <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                 </svg>
                 ' . $event->Veranstaltungsort . '</div>';
                 $output .= '</div>';
             $output .= '</div>';
         }
         $output .= '</div>';
         return $output;
     }






/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cobra-event-module-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cobra_event_module_wp() {

	$plugin = new Cobra_Event_Module_Wp();
	$plugin->run();

}
run_cobra_event_module_wp();
