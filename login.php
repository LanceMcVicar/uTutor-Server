<?php
	if($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("connect.php");
		//$_POST['email']="lance@lance.lance";
		$email = $_POST['email'];
		$sql = "SELECT `firstName`, `lastName`, `email`, `hashedPassword`, `onlineStatus` FROM `users` WHERE `email` = \"".$email."\"";

		//$sql = "SELECT `firstName`, `lastName`, `email`, `hashedPassword`, `onlineStatus` FROM `users` WHERE `email` = \"lance@lance.lance\"";
		
		if($result = $conn -> query($sql)){
			$rows = array();
			while($row = $result->fetch_assoc()){
				$rows[] = $row;
			}
			echo json_encode($rows);
		}else{
			$err = array('error' => -1);
			echo json_encode($err);
		}
	}
?>