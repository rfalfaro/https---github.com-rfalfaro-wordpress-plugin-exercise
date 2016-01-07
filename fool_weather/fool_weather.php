<?php
	/*
		Plugin Name:	fool-weather
		Description: 	WordPress plugin to demonstrate the weather across Motley Fool office locations around the world using the openweathermap API
		Version: 		0.1
		Author: 		Ricardo Alfaro
		License:     	GPL2
		License URI: 	https://www.gnu.org/licenses/gpl-2.0.html
	*/

/* BEGIN ACTIVATION FUNCTIONS */

// Create table to store offices and selection of temperature units (F or C)

global $fool_weather_db_version;
$fool_weather_db_version = '1.0';

function fool_weather_install () {
   global $wpdb;
   global $fool_weather_db_version;
   
   $table_name = $wpdb->prefix . "fool_weather"; 
   $charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		office text NOT NULL,
		units text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'fool_weather_db_version', $fool_weather_db_version );
}

// Prepopulate data into table

function fool_weather_install_data() {
	
	// Define Motley Fool offices
	// Dev note: may be substituted in the future by another plug-in to define each office
	
	$fool_office = array("Queensland", "London,UK", "Berlin,Germany","Singapore", "Halifax,Canada");
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'fool_weather';
	
	// Prepopulate with F as default temperature unit
	$units = 'F';
			
	foreach($fool_office as $office)
	{
	
		$wpdb->insert( 
			$table_name, 
			array( 
				'office' => $office, 
				'units' => $units, 
				) 
			);
	}
}

register_activation_hook( __FILE__, 'fool_weather_install' );
register_activation_hook( __FILE__, 'fool_weather_install_data' );

/* END ACTIVATION FUNCTIONS */

/* BEGIN DEACTIVATION FUNCTIONS */

// Delete table if plugin is disabled
function fool_weather_deactivation() {
	global $wpdb;
	$table = $wpdb->prefix."fool_weather";
	$wpdb->query("DROP TABLE IF EXISTS $table");
}

register_deactivation_hook( __FILE__, 'fool_weather_deactivation' );

/* END DEACTIVATION FUNCTIONS */

// Create a custom menu item on the administrative side

function add_my_custom_menu() {
    add_menu_page (
        'Motley Fool Weather',
        'Fool Weather',
        'manage_options',
        'fool_weather/admin/fool_weather_admin.php',
        '',
        plugin_dir_url( __FILE__ ).'icons/weather.png'
    );
}

add_action( 'admin_menu', 'add_my_custom_menu' );

// User facing display

function fool_weather() {
	$path = plugin_dir_path( __FILE__ );
	require_once($path.'settings/settings.php');
	require_once($path.'includes/getweather.php');
	$offices = $Constructers->get_office_locations();
	
	if($_POST["office_select"] == "")
	{
		$i = 0;
	}
	else
	{
		$i = $_POST["office_select"]-1;
	}
	
	$weather_current = $Constructers->getWeather($OpenWeatherMap->owmURL,$offices[$i][1],$offices[$i][2],$OpenWeatherMap->APIkey);
	$json = file_get_contents($weather_current);
	$data = json_decode($json,true);
	$html_render = '<p><strong>'.$offices[$i][1].'</strong><br/>';
	$html_render .= '<img src="http://openweathermap.org/img/w/'.$data['weather'][0]["icon"].'.png"><br/>';
	$html_render .= ucwords($data['weather'][0]['description']);
	$html_render .= '<br/>'.$data['main']['temp'].' &deg;'.$offices[$i][2];
	$html_render .= '</p>';
	$html_render .= 'Select alternate location:<br/><form method="post"><select name="office_select" onchange="this.form.submit()">';
	foreach($offices AS $single_office)
	{
		if($offices[$i][0] == $single_office[0])
		{
			$html_render .= '<option value="'.$single_office[0].'" selected>'.$single_office[1].'</option>';
		} else {
			$html_render .= '<option value="'.$single_office[0].'">'.$single_office[1].'</option>';
		}
	}

	$html_render .= '</select></form></br>';

	return $html_render;
}
?>