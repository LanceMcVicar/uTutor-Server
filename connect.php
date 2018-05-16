<?php
	//Connection
	$conn = new mysqli(getenv('RDS_HOSTNAME'),getenv('RDS_USERNAME'),getenv('RDS_PASSWORD'),getenv('RDS_DB_NAME'),getenv('RDS_PORT'));
	//$conn = new mysqli($servername,$username,$password,$database);
	
	//Verify
	if($conn->connect_error) {
		die("Connection failed successfully: " . $conn->connect_error);
	}	
?>