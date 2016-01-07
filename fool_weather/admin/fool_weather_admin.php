<?php
	$path = plugin_dir_path( __FILE__ );
	require_once($path.'../settings/settings.php');
	require_once($path.'../includes/getweather.php');
	require_once($path.'../includes/change_unit.php');
?>
<div class="wrap">

<h2>Motley Fool Weather</h2>
<?php print('API key: ' . $OpenWeatherMap->APIkey); ?>
<p></p>
<?php if(isset($_POST['submit'])) { 
    echo("change");
} $offices = $Constructers->get_office_locations();
	echo('<table style="width:100%" cellpadding="10"><tr>');
	foreach($offices AS $single_office)
	{
		echo('<td><p><strong>'.$single_office[1].'</strong> (Unit: '.$single_office[2].')<br/>');
		
		$weather_current = $Constructers->getWeather($OpenWeatherMap->owmURL,$single_office[1],$single_office[2],$OpenWeatherMap->APIkey);
				
		$json = file_get_contents($weather_current);
		$data = json_decode($json,true);
		
		echo('<img src="http://openweathermap.org/img/w/'.$data['weather'][0]["icon"].'.png"><br/>');
		echo ucwords($data['weather'][0]['description']);
		echo('<br/>'.$data['main']['temp'].' &deg;'.$single_office[2]);
		echo($Constructers->fool_weather_change_form($single_office[2],$single_office[0]));
		echo('</p></td>');
	}
	echo('</tr></table>');
?>
<p>To use this plugin, please insert the following code to the sidebar.php file of your theme:</p>
<div contenteditable="true">
<?php
highlight_file($path.'../includes/initiate.txt');
?>
</div>
</div>