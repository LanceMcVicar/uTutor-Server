<?php
	$servername = "uTutor";
	$username = "user1";
	$password = "1234";
	
	//Connection
	$conn = new mysqli($servername,$username,$password)
	
	//Verify
	if($conn->connect_error) {
		die("Connection failed successfully: " . $conn->connect_error);
	}
	echo "Connected Successfully"
?>