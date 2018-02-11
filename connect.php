<?php
	$servername = "uTutor";
	$username = "root";
	$password = "Random1*";
	$database = "ututor";
	//Connection
	$conn = new mysqli($servername,$username,$password,$database);
	
	//Verify
	if($conn->connect_error) {
		die("Connection failed successfully: " . $conn->connect_error);
	}	
?>