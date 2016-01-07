<?php 
	// Update table based on user selection for temperature unit
	if($_POST['id'] != '') {
		global $wpdb;
		
        $office_id = $_POST["id"];
        $units = $_POST["units"];
        
        $table_name = $wpdb->prefix . "fool_weather"; 
		$charset_collate = $wpdb->get_charset_collate();
   
		$sql = "UPDATE $table_name SET units='$units' WHERE id='$office_id'";
   
		$result = mysql_query($sql);
        
        if($units == 'C') { ?>
        <div class="updated"><p><strong><?php echo('Changed unit to Celsius' ); ?></strong></p></div><?php
	    } if($units == 'F') { ?>
		<div class="updated"><p><strong><?php echo('Changed unit to Fahrenheit' ); ?></strong></p></div><?php    
	    }
    } else {

    }
?>