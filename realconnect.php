<?php
	//Connection
	if(!$conn=mysqli_init()){
		die("Real Connect Init Error");
	}
	if(!mysqli_real_connect($conn,getenv('RDS_HOSTNAME'),getenv('RDS_USERNAME'),getenv('RDS_PASSWORD'),getenv('RDS_DB_NAME'),getenv('RDS_PORT'),null,MYSQLI_CLIENT_FOUND_ROWS)){
		die("Real Connect Error: " . mysqli_connect_error());
	}
?>