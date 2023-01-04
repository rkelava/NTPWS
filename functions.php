<?php 
	function pickerDateToMysql($pickerDate){
		$date = DateTime::createFromFormat('Y-m-d H:i:s', $pickerDate);
		return $date->format('d. m. Y H:i:s');
	}  
?>


<?php 
	function pickerSamoDateToMysql($pickerDate){
		$date = DateTime::createFromFormat('Y-m-d', $pickerDate);
		return $date->format('d. m. Y.');
	}  
?>