<?php
	require_once("connect.php");
	$sql = "SELECT * FROM `users` WHERE `email` = ?";	
	$email = "test@test.test";
	//$result = $conn->query($sql);
	if($stmt = $conn->prepare($sql)){
		//echo "Ya did it kiddo";
	}else{
		echo "Ya messed up kiddo";
		die;
	}
	$stmt = $conn->prepare($sql);
	if(!$stmt -> bind_param("s", $email)){
		echo "The heck are you doin";
	}
	if($stmt->execute()){
		$result = $stmt->get_result();
		if((mysqli_num_rows($result)) > 0){
			while($row = $result->fetch_assoc()){
				echo json_encode($row);
			}
		}else{
			echo "0 results";
		}
	}else{
		echo "Failed Statement";
	}
?>