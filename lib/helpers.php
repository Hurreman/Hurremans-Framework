<?php
class Helper
{
	static public function compareDatesToSeconds($cdate)
	{
		
		$cd1 = new DateTime();
		$cd2 = new DateTime($cdate);
		
		$cinterval = date_diff($cd1, $cd2);
		
		$cdays = $cinterval->format('%d');
		$chours = $cinterval->format('%h');
		$cminutes = $cinterval->format('%i');
		$cseconds = $cinterval->format('%s');
		$ctotalSeconds = $cseconds + ($cdays * 60 * 60 * 60) + ($chours * 60 * 60) + ($cminutes * 60);
		
		return $ctotalSeconds;
		
	}
	
	
	static public function validatePostArray($data) {
		
		$validated = true;
		
		foreach($data as $param)
		{
			if((!isset($_POST[$param])) || $_POST[$param] == '')
			{
				$validated = false;
			}
		}
		
		return $validated;
		
	}
	
	
	// Create random password
	static public function create_password($length=8,$use_upper=1,$use_lower=1,$use_number=1,$use_custom="")
	{
		$upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$lower = "abcdefghijklmnopqrstuvwxyz";
		$password = "";
		$number = "0123456789";
		if($use_upper){
			$seed_length += 26;
			$seed .= $upper;
		}
		if($use_lower){
			$seed_length += 26;
			$seed .= $lower;
		}
		if($use_number){
			$seed_length += 10;
			$seed .= $number;
		}
		if($use_custom){
			$seed_length +=strlen($use_custom);
			$seed .= $use_custom;
		}
		for($x=1;$x<=$length;$x++){
			$password .= $seed{rand(0,$seed_length-1)};
		}
		return($password);
	}

	// Convert bytes to a readable format
	static public function convert($size) {
    	$unit = array('b','kb','mb','gb','tb','pb');
	    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}
}
?>