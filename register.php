<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		$email = $_POST['email'];
		$password = $_POST['password'];
		$sql= "INSERT INTO users (email,hashedPassword) VALUES('".$email."' ,'".$password. "')";
		$result = $conn-> query($sql);
		//$conn->close();
		$arr = array('success' => "woohoo");
		echo json_encode($arr);
	}
?>