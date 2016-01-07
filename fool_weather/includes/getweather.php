<?php
class Constructers {
	// Build JSON query based on the provided variables
	public function getWeather ($owmURL,$place,$unit,$API)
	{
		if($unit == "F")
		{
			$unit = "imperial";
		}
		if($unit == "C")
		{
			$unit = "metric";
		}
		return $owmURL.'q='.$place.'&units='.$unit.'&appid='.$API;
	}
	// Get office locations from pre-populated table with fool_weather prefix
	public function get_office_locations () {
		global $wpdb;
		$office_data = array();   
		$table_name = $wpdb->prefix . "fool_weather"; 
		$charset_collate = $wpdb->get_charset_collate();
   
		$sql = "SELECT id,office,units FROM $table_name";
   
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			$office_data[] = $row;
   		}
   		return $office_data;
	}
	// Build HTML form to change temperature unit
	public function fool_weather_change_form($change_units,$office_id)
	{
		$form_change = '<br/><br/>';
		$form_change .= '<form name="fool_weather_change" method="post">';
		$form_change .= '<input type="hidden" name="id" value="'.$office_id.'">';
		switch($change_units)
		{
			case 'F':
			$form_change .= '<input type="hidden" name="units" value="C">';
			$form_change .= '<input type="submit" name="screen-options-apply" id="screen-options-apply" class="button" value="Change to Celsius" />';
			break;
			case 'C':
			$form_change .= '<input type="hidden" name="units" value="F">';
			$form_change .= '<input type="submit" name="screen-options-apply" id="screen-options-apply" class="button" value="Change to Fahrenheit" />';
			break;
			default:
			$form_change .= '<input type="submit" name="screen-options-apply" id="screen-options-apply" class="button" value="Change to Celsius" />';
			break;
		}
		$form_change .= '</form>';
		
		return $form_change;
	}
}
$Constructers = new Constructers();
?>