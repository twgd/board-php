<?php
	$DB_Config = array(
		"Username"=>"root",
		"Password"=>"root",
		"Host"=>"localhost",
		"Name"=>"Member"
	);
	$conn = new mysqli(
		$DB_Config["Host"],
		$DB_Config["Username"],
		$DB_Config["Password"],
		$DB_Config["Name"]
	);
	
?>
